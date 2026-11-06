<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Database\Seeders\RumaDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RumaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RumaDatabaseSeeder::class);
    }

    public function test_public_home_loads_public_groups_and_lands_safely(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ruang Usaha');
        $response->assertSee('Masyarakat Alam');
        $response->assertSee('KWT Sumber Rejeki');
        // Ensure private internal notes / internal ledger are not exposed
        $response->assertDontSee('Saldo tercatat internal');
    }

    public function test_public_group_show_page_loads(): void
    {
        $group = Group::where('slug', 'kwt-sumber-rejeki')->first();
        $response = $this->get('/kelompok-binaan/'.$group->slug);

        $response->assertStatus(200);
        $response->assertSee('KWT Sumber Rejeki');
        $response->assertSee('Kelompok Binaan SiYota');
    }

    public function test_public_business_show_page_loads(): void
    {
        $group = Group::where('slug', 'kwt-sumber-rejeki')->first();
        $response = $this->get('/usaha-binaan/'.$group->slug);

        $response->assertStatus(200);
        $response->assertSee('Produk Unggulan');
    }

    public function test_group_mobile_app_dashboard_loads(): void
    {
        $user = User::where('email', 'siti@ruma.siyota.org')->first();
        $response = $this->actingAs($user)->get('/group/dashboard');

        $response->assertStatus(200);
        $response->assertSee('AKUN KELOMPOK');
        $response->assertSee('Tugas Berikutnya');
        $response->assertSee('Perkembangan Kelompok');
    }

    public function test_group_modules_load(): void
    {
        $user = User::where('email', 'siti@ruma.siyota.org')->first();

        // 1. Belajar
        $this->actingAs($user)->get('/group/belajar')->assertStatus(200)->assertSee('Pusat Belajar');

        // 2. Lahan
        $this->actingAs($user)->get('/group/lahan')->assertStatus(200)->assertSee('Pemetaan Lahan');

        // 3. Komoditas
        $this->actingAs($user)->get('/group/komoditas')->assertStatus(200)->assertSee('Komoditas Kelompok');

        // 4. Keuangan
        $this->actingAs($user)->get('/group/keuangan')->assertStatus(200)->assertSee('Buku Kas');

        // 5. Produk & QC
        $this->actingAs($user)->get('/group/produk')->assertStatus(200)->assertSee('Standar Mutu Produk');

        // 6. Marketing
        $this->actingAs($user)->get('/group/marketing')->assertStatus(200)->assertSee('Tugas Pemasaran');

        // 7. Akun
        $this->actingAs($user)->get('/group/akun')->assertStatus(200)->assertSee('Profil Kelompok');
    }

    public function test_facilitator_flow_and_qc_review(): void
    {
        $fasil = User::where('email', 'rian@siyota.org')->first();

        $this->actingAs($fasil)->get('/facilitator/dashboard')->assertStatus(200)->assertSee('FASILITATOR SIYOTA');
        $this->actingAs($fasil)->get('/facilitator/kelompok')->assertStatus(200)->assertSee('Kelompok Binaan');
        $this->actingAs($fasil)->get('/facilitator/tugas')->assertStatus(200)->assertSee('Tugas Pemasaran');
        $this->actingAs($fasil)->get('/facilitator/reviews')->assertStatus(200)->assertSee('Review Kiriman');
        $this->actingAs($fasil)->get('/facilitator/mentoring')->assertStatus(200)->assertSee('Catatan Mentoring');
    }

    public function test_admin_command_center_modules(): void
    {
        $admin = User::where('email', 'admin@siyota.org')->first();

        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200)->assertSee('SUPER ADMIN PLATFORM');
        $this->actingAs($admin)->get('/admin/kelompok')->assertStatus(200)->assertSee('Kelompok Tani');
        $this->actingAs($admin)->get('/admin/persetujuan')->assertStatus(200)->assertSee('Approval Queue');
        $this->actingAs($admin)->get('/admin/program')->assertStatus(200)->assertSee('Program Pendampingan');
        $this->actingAs($admin)->get('/admin/notifikasi')->assertStatus(200)->assertSee('Pusat Komunikasi');
    }

    public function test_group_registration_works(): void
    {
        $response = $this->post('/daftar-kelompok', [
            'name' => 'Kelompok Tani Berkah Alam',
            'regency' => 'Kabupaten Pangandaran',
            'district' => 'Parigi',
            'village' => 'Karangbenda',
            'commodity_summary' => 'Kelapa & Gula Kelapa',
            'leader_name' => 'Pak Diding',
            'contact_phone' => '0812-9999-8888',
            'address' => 'Desa Karangbenda RT 02/03',
            'notes' => 'Kelompok pengolah gula kelapa murni.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'name' => 'Kelompok Tani Berkah Alam',
            'status' => 'pending',
        ]);
    }
}
