<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\NotificationLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingGroups = Group::where('status', 'pending')->latest()->get();

        return view('admin.approvals.index', compact('pendingGroups'));
    }

    public function verify(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $action = $request->input('action', $request->input('status', 'active'));

        if ($action === 'active') {
            $group->update([
                'status' => 'active',
                'verified_at' => Carbon::now(),
                'verification_notes' => $request->input('notes', 'Kelompok disetujui bergabung dalam program pendampingan SiYota.'),
            ]);

            // Create group admin account if not existing
            $leaderEmail = 'ketua.'.$group->slug.'@ruma.siyota.org';
            $user = User::firstOrCreate(
                ['email' => $leaderEmail],
                [
                    'name' => $group->leader_name,
                    'group_id' => $group->id,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'position_title' => 'Ketua Kelompok (Admin)',
                    'phone' => $group->contact_phone,
                ]
            );

            // Log WhatsApp & Email notification
            NotificationLog::create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'channel' => 'whatsapp',
                'event_type' => 'akun_terverifikasi',
                'recipient' => $group->contact_phone,
                'message_preview' => "Selamat {$group->name}! Pendaftaran Anda telah disetujui. Akun RUMA Anda telah aktif.",
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return back()->with('success', "Kelompok {$group->name} berhasil diverifikasi dan diaktifkan!");
        }

        $group->update([
            'status' => 'rejected',
            'verification_notes' => $request->input('notes', 'Belum memenuhi kriteria kelompok usaha hasil bumi.'),
        ]);

        return back()->with('info', "Pendaftaran kelompok {$group->name} telah ditolak.");
    }
}
