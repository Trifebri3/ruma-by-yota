<?php

namespace Tests\Feature;

use App\Models\DailyProgressReport;
use App\Models\DailyReportLink;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RumaDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyProgressReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected Group $group;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RumaDatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@siyota.org')->first();
        $this->user = User::where('email', 'siti@ruma.siyota.org')->first();
        $this->group = Group::where('slug', 'kwt-sumber-rejeki')->first();
    }

    public function test_admin_can_view_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pengguna');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pengguna');
        $response->assertSee('Siti Khodijah');
    }

    public function test_admin_can_create_user_and_link_to_group(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pengguna', [
            'name' => 'Petani Baru Sukarame',
            'email' => 'petani.sukarame@example.com',
            'password' => 'password123',
            'role' => 'user',
            'group_id' => $this->group->id,
            'phone' => '081234567899',
            'position_title' => 'Bendahara Kebun',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/pengguna');
        $this->assertDatabaseHas('users', [
            'email' => 'petani.sukarame@example.com',
            'group_id' => $this->group->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_fasil_and_group_member_accounts(): void
    {
        // 1. Buat akun Fasilitator
        $respFasil = $this->actingAs($this->admin)->post('/admin/pengguna', [
            'name' => 'Fasilitator Tasikmalaya',
            'email' => 'fasil.tasik@siyota.org',
            'password' => 'password123',
            'role' => 'fasil',
            'group_id' => $this->group->id,
            'phone' => '081299887766',
            'position_title' => 'Pendamping Lapangan Ahli',
            'is_active' => '1',
        ]);
        $respFasil->assertRedirect('/admin/pengguna');
        $this->assertDatabaseHas('users', [
            'email' => 'fasil.tasik@siyota.org',
            'role' => 'fasil',
        ]);

        // 2. Buat akun Anggota Kelompok
        $respMember = $this->actingAs($this->admin)->post('/admin/pengguna', [
            'name' => 'Anggota Tani Mamat',
            'email' => 'mamat.anggota@example.com',
            'password' => 'password123',
            'role' => 'group_member',
            'group_id' => $this->group->id,
            'phone' => '081233445566',
            'position_title' => 'Anggota Poktan Petak Selatan',
            'is_active' => '1',
        ]);
        $respMember->assertRedirect('/admin/pengguna');
        $this->assertDatabaseHas('users', [
            'email' => 'mamat.anggota@example.com',
            'role' => 'group_member',
            'group_id' => $this->group->id,
        ]);
    }

    public function test_pwa_manifest_and_offline_page_are_accessible(): void
    {
        $manifest = $this->get('/manifest.json');
        $manifest->assertStatus(200);
        $manifest->assertJsonFragment(['short_name' => 'RUMA']);

        $offline = $this->get('/offline.html');
        $offline->assertStatus(200);
        $offline->assertSee('Koneksi Internet Terputus');
    }

    public function test_admin_can_toggle_user_active_status_and_inactive_user_cannot_login(): void
    {
        // 1. Admin nonaktifkan akun
        $response = $this->actingAs($this->admin)->post("/admin/pengguna/{$this->user->id}/toggle-status");
        $response->assertRedirect();

        $this->user->refresh();
        $this->assertFalse($this->user->is_active);

        // 2. User mencoba login
        auth()->logout();
        $loginResponse = $this->post('/login', [
            'email' => 'siti@ruma.siyota.org',
            'password' => 'password',
        ]);

        $loginResponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_create_report_link_with_daily_limit(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/tautan-laporan', [
            'group_id' => $this->group->id,
            'title' => 'Laporan Harian Kebun B',
            'description' => 'Aktivitas pemupukan dan penyiangan rumput',
            'daily_limit' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/tautan-laporan');
        $this->assertDatabaseHas('daily_report_links', [
            'group_id' => $this->group->id,
            'title' => 'Laporan Harian Kebun B',
            'daily_limit' => 2,
            'is_active' => true,
        ]);
    }

    public function test_public_user_can_view_report_form_without_login(): void
    {
        $link = DailyReportLink::where('token', 'kwt-sumber-rejeki-harian-2026')->first();

        auth()->logout();
        $response = $this->get("/laporan/{$link->token}");

        $response->assertStatus(200);
        $response->assertSee('KWT Sumber Rejeki');
        $response->assertSee('Laporan Harian — KWT Sumber Rejeki');
        $response->assertSee('Formulir Laporan Aktivitas');
    }

    public function test_public_user_can_submit_report_and_it_records_under_link(): void
    {
        $link = DailyReportLink::where('token', 'kwt-sumber-rejeki-harian-2026')->first();

        auth()->logout();
        $response = $this->post("/laporan/{$link->token}", [
            'reporter_name' => 'Mang Udin Petani',
            'title' => 'Pemupukan NPK Sore Hari',
            'description' => 'Telah dilakukan pemupukan susulan pada 500 pohon cabai rawit dengan dosis sesuai anjuran.',
        ]);

        $response->assertRedirect("/laporan/{$link->token}");
        $this->assertDatabaseHas('daily_progress_reports', [
            'daily_report_link_id' => $link->id,
            'group_id' => $this->group->id,
            'reporter_name' => 'Mang Udin Petani',
            'title' => 'Pemupukan NPK Sore Hari',
        ]);
    }

    public function test_daily_limit_is_strictly_enforced_in_backend(): void
    {
        // Link kwt memiliki daily_limit = 2. Di seeder sudah ada 1 laporan hari ini.
        $link = DailyReportLink::where('token', 'kwt-sumber-rejeki-harian-2026')->first();
        $todayDate = Carbon::now('Asia/Jakarta')->toDateString();

        auth()->logout();

        // Kirim laporan ke-2 (harus berhasil)
        $resp2 = $this->post("/laporan/{$link->token}", [
            'reporter_name' => 'Laporan Kedua',
            'title' => 'Aktivitas Kedua Hari Ini',
            'description' => 'Ini adalah laporan kedua yang diizinkan untuk hari ini.',
        ]);
        $resp2->assertRedirect("/laporan/{$link->token}");
        $this->assertEquals(2, $link->todayReportsCount($todayDate));

        // Kirim laporan ke-3 (harus DITOLAK karena limit = 2)
        $resp3 = $this->post("/laporan/{$link->token}", [
            'reporter_name' => 'Laporan Ketiga (Melebihi Limit)',
            'title' => 'Aktivitas Melebihi Kuota',
            'description' => 'Ini harus ditolak oleh validasi backend.',
        ]);

        $resp3->assertSessionHas('error');
        // Jumlah tetap 2
        $this->assertEquals(2, $link->todayReportsCount($todayDate));
    }

    public function test_admin_can_view_incoming_reports_and_change_status(): void
    {
        $report = DailyProgressReport::first();

        $response = $this->actingAs($this->admin)->get('/admin/laporan-progress');
        $response->assertStatus(200);
        $response->assertSee('Laporan Progress Harian Kelompok');
        $response->assertSee($report->title);

        // Update status
        $updateResp = $this->actingAs($this->admin)->post("/admin/laporan-progress/{$report->id}/status", [
            'status' => 'approved',
            'admin_notes' => 'Catatan verifikasi berhasil.',
        ]);
        $updateResp->assertRedirect();

        $this->assertDatabaseHas('daily_progress_reports', [
            'id' => $report->id,
            'status' => 'approved',
            'admin_notes' => 'Catatan verifikasi berhasil.',
        ]);
    }

    public function test_group_user_can_submit_report_from_group_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/group/progress');
        $response->assertStatus(200);
        $response->assertSee('Laporan Progress Harian');

        $submitResp = $this->actingAs($this->user)->post('/group/progress', [
            'title' => 'Panen Perdana Cabai Rawit Merah',
            'description' => 'Hasil panen perdana petak barat sebanyak 25 kg dengan kualitas grade A.',
        ]);

        $submitResp->assertRedirect('/group/progress');
        $this->assertDatabaseHas('daily_progress_reports', [
            'group_id' => $this->group->id,
            'title' => 'Panen Perdana Cabai Rawit Merah',
            'user_id' => $this->user->id,
        ]);
    }
}
