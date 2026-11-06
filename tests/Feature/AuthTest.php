<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RumaDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RumaDatabaseSeeder::class);
    }

    public function test_login_page_loads_with_three_roles_and_google_button(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
        $response->assertDontSee('Pilih Akses Cepat (3 Role)');
        $response->assertSee('Masuk / Daftar dengan Google');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
    }

    public function test_user_registration_defaults_to_user_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Petani Desa Default',
            'email' => 'petani.default@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/group/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'petani.default@example.com',
            'role' => 'user',
        ]);
    }

    public function test_quick_login_works_for_all_three_roles(): void
    {
        // 1. User
        $responseUser = $this->get('/login/quick/user');
        $responseUser->assertRedirect('/group/dashboard');
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isUser());

        // 2. Fasil
        $responseFasil = $this->get('/login/quick/fasil');
        $responseFasil->assertRedirect('/facilitator/dashboard');
        $this->assertTrue(auth()->user()->isFasil());

        // 3. Admin
        $responseAdmin = $this->get('/login/quick/admin');
        $responseAdmin->assertRedirect('/admin/dashboard');
        $this->assertTrue(auth()->user()->isAdmin());
    }

    public function test_register_page_loads_cleanly(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Akun Baru');
        $response->assertDontSee('Pilih Peran (3 Role)');
        $response->assertSee('Akun Kelompok Tani / Usaha Hasil Bumi');
        $response->assertSee('Daftar Cepat dengan Google');
    }

    public function test_user_can_register_with_email_and_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'user',
            'phone' => '081234567890',
            'group_name' => 'Kelompok Tani Harapan Jaya',
        ]);

        $response->assertRedirect('/group/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@example.com',
            'role' => 'user',
        ]);
    }

    public function test_google_redirect_checks_credentials(): void
    {
        $response = $this->get('/auth/google');
        // If GOOGLE_CLIENT_ID is not configured, it redirects back to /login with flash message
        if (empty(config('services.google.client_id'))) {
            $response->assertRedirect('/login');
            $response->assertSessionHas('error');
        } else {
            $response->assertStatus(302);
        }
    }

    public function test_logout_works(): void
    {
        $user = User::where('email', 'siti@ruma.siyota.org')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
