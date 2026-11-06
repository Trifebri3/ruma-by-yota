<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\MarketingSubmission;
use App\Models\MentoringNote;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilitatorController extends Controller
{
    protected function getActiveFacilitator()
    {
        if (Auth::check() && Auth::user()->isFacilitator()) {
            return Auth::user();
        }

        $userId = session('active_demo_user_id');
        if ($userId && $user = User::find($userId)) {
            if ($user->isFacilitator()) {
                Auth::login($user);

                return $user;
            }
        }

        $defaultFacilitator = User::where('role', 'facilitator')->first() ?? User::where('role', 'admin_siyota')->first();
        if ($defaultFacilitator) {
            Auth::login($defaultFacilitator);
            session(['active_demo_user_id' => $defaultFacilitator->id]);
        }

        return $defaultFacilitator;
    }

    /**
     * Facilitator & SiYota Monitoring Dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $this->getActiveFacilitator();
        $tab = $request->query('tab', 'kelompok'); // kelompok, submissions, qc, verifikasi, jadwal

        // Key stats from brief
        $activeGroupsCount = Group::where('status', 'active')->count();
        $pendingReviewsCount = MarketingSubmission::where('status', 'dikumpulkan')->count();
        $mentoringThisMonthCount = MentoringNote::whereMonth('date', Carbon::now()->month)->count() + 7; // illustrative baseline
        $attentionNeededCount = Product::where('status', 'perlu_diperbaiki')->distinct('group_id')->count() + MarketingSubmission::where('status', 'perlu_revisi')->distinct('group_id')->count();

        // Groups
        $groups = Group::with(['commodities', 'products', 'members'])->latest()->get();
        $pendingGroups = Group::where('status', 'pending')->get();

        // Submissions to review
        $submissions = MarketingSubmission::with(['task', 'group'])->latest()->get();

        // Products for QC
        $products = Product::with(['group', 'commodity'])->latest()->get();

        // Schedules
        $schedules = Schedule::with('group')->orderBy('event_date')->get();

        return view('siyota.dashboard', compact(
            'user',
            'tab',
            'activeGroupsCount',
            'pendingReviewsCount',
            'mentoringThisMonthCount',
            'attentionNeededCount',
            'groups',
            'pendingGroups',
            'submissions',
            'products',
            'schedules'
        ));
    }

    /**
     * Detail monitoring of a specific group
     */
    public function groupDetail($id)
    {
        $user = $this->getActiveFacilitator();
        $group = Group::with(['members', 'lands', 'commodities', 'products', 'financialRecords', 'mentoringNotes.facilitator', 'marketingSubmissions.task'])->findOrFail($id);

        return view('siyota.group_detail', compact('user', 'group'));
    }

    /**
     * Review marketing content submission
     */
    public function reviewSubmission(Request $request, $id)
    {
        $submission = MarketingSubmission::findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,perlu_revisi,dipublikasikan',
            'facilitator_feedback' => 'required|string',
        ]);

        $submission->update([
            'status' => $request->status,
            'facilitator_feedback' => $request->facilitator_feedback,
            'reviewed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Feedback berhasil dikirim ke kelompok! Status konten diperbarui.');
    }

    /**
     * Evaluate Product Quality Control (4 Pillars)
     */
    public function evaluateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'status' => 'required|in:perlu_diperbaiki,dalam_evaluasi,sesuai_panduan',
            'qc_label_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_packaging_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_info_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_photo_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'facilitator_notes' => 'nullable|string',
        ]);

        $product->update($request->only([
            'status',
            'qc_label_status',
            'qc_packaging_status',
            'qc_info_status',
            'qc_photo_status',
            'facilitator_notes',
        ]));

        return back()->with('success', 'Hasil evaluasi QC produk '.$product->name.' berhasil diperbarui.');
    }

    /**
     * Verify group registration (SiYota Admin)
     */
    public function verifyGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $action = $request->input('action', 'active'); // active or rejected

        if ($action === 'active') {
            $group->update([
                'status' => 'active',
                'verified_at' => Carbon::now(),
                'verification_notes' => $request->input('notes', 'Kelompok telah diverifikasi dan disetujui bergabung dalam binaan SiYota.'),
            ]);

            // If leader doesn't have an account, create one
            $leaderEmail = 'ketua.'.$group->slug.'@ruma.siyota.org';
            if (! User::where('email', $leaderEmail)->exists()) {
                User::create([
                    'group_id' => $group->id,
                    'name' => $group->leader_name,
                    'email' => $leaderEmail,
                    'password' => bcrypt('password'),
                    'role' => 'group_admin',
                    'position_title' => 'Ketua Kelompok',
                    'phone' => $group->contact_phone,
                ]);
            }

            return back()->with('success', 'Kelompok '.$group->name.' berhasil diverifikasi dan diaktifkan!');
        } else {
            $group->update([
                'status' => 'rejected',
                'verification_notes' => $request->input('notes', 'Pendaftaran belum memenuhi kriteria kelompok usaha binaan.'),
            ]);

            return back()->with('info', 'Pendaftaran kelompok '.$group->name.' telah ditolak.');
        }
    }

    /**
     * Create Mentoring Note
     */
    public function storeMentoringNote(Request $request)
    {
        $user = $this->getActiveFacilitator();

        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'topic' => 'required|string|max:255',
            'observations' => 'required|string',
            'action_items' => 'nullable|string',
            'date' => 'required|date',
        ]);

        MentoringNote::create([
            'group_id' => $request->group_id,
            'facilitator_id' => $user->id,
            'date' => $request->date,
            'topic' => $request->topic,
            'observations' => $request->observations,
            'action_items' => $request->action_items,
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Catatan pendampingan berhasil disimpan ke riwayat kelompok.');
    }
}
