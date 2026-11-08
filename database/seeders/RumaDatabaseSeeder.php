<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\DailyProgressReport;
use App\Models\DailyReportLink;
use App\Models\FinancialRecord;
use App\Models\Group;
use App\Models\Land;
use App\Models\LearningClass;
use App\Models\LearningMaterial;
use App\Models\MarketingSubmission;
use App\Models\MarketingTask;
use App\Models\MentoringNote;
use App\Models\NotificationLog;
use App\Models\Product;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RumaDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. System Settings
        SystemSetting::set('whatsapp_notifications_enabled', '1');
        SystemSetting::set('email_notifications_enabled', '1');
        SystemSetting::set('whatsapp_sender_number', '+6281122334455');
        SystemSetting::set('whatsapp_provider', 'WhatsApp Business Cloud API (Official)');
        SystemSetting::set('email_sender_address', 'pendampingan@ruma.siyota.org');

        // 1. Programs
        $programPriangan = Program::create([
            'name' => 'Program Inkubasi Komoditas Alam Priangan Timur 2026',
            'slug' => 'program-inkubasi-komoditas-alam-priangan-2026',
            'description' => 'Program pendampingan terpadu SiYota untuk penguatan kelembagaan, digitalisasi kas, dan standardisasi mutu produk hasil bumi di Priangan Timur.',
            'start_date' => Carbon::create(2026, 1, 15),
            'end_date' => Carbon::create(2026, 12, 31),
            'status' => 'aktif',
        ]);

        // 2. Create Groups
        $kwt = Group::create([
            'name' => 'KWT Sumber Rejeki',
            'slug' => 'kwt-sumber-rejeki',
            'category' => 'Kelompok Binaan SiYota',
            'program_id' => $programPriangan->id,
            'regency' => 'Kabupaten Tasikmalaya',
            'district' => 'Singaparna',
            'village' => 'Sukarame',
            'commodity_summary' => 'Cabai, aren, sayuran',
            'status' => 'active',
            'is_public' => true,
            'business_name' => 'Sentra Olahan Cabai & Gula Kawung KWT Sumber Rejeki',
            'business_logo_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80',
            'leader_name' => 'Ibu Siti Khodijah',
            'pic_name' => 'Ibu Siti Khodijah (Ketua Kelompok)',
            'pic_public_visible' => true,
            'contact_phone' => '0812-3456-7890',
            'address' => 'Kampung Pasir Gebang RT 03/RW 04, Desa Sukarame, Kec. Singaparna',
            'public_description' => 'Kelompok Wanita Tani mandiri di Tasikmalaya yang membudidayakan cabai rawit merah petik segar dan mengolah nira aren pegunungan menjadi gula cetak dan gula kristal higienis.',
            'logo_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80',
            'banner_url' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1200&q=80',
            'verification_notes' => 'Kelompok sangat aktif, sudah memverifikasi 18 anggota dan 3 lahan produktif.',
            'verified_at' => Carbon::now()->subMonths(2),
        ]);

        $arenMakmur = Group::create([
            'name' => 'Kelompok Aren Makmur',
            'slug' => 'kelompok-aren-makmur',
            'category' => 'Kelompok Binaan SiYota',
            'program_id' => $programPriangan->id,
            'regency' => 'Kota Banjar',
            'district' => 'Patikrajan',
            'village' => 'Cibeureum',
            'commodity_summary' => 'Aren, gula semut',
            'status' => 'active',
            'is_public' => true,
            'business_name' => 'Gula Semut Aren Kristal Banjar',
            'business_logo_url' => 'https://images.unsplash.com/photo-1544979590-37e9b47eb705?auto=format&fit=crop&w=400&q=80',
            'leader_name' => 'Pak Dede Permana',
            'pic_name' => 'Pak Dede Permana (Ketua Paguyuban)',
            'pic_public_visible' => true,
            'contact_phone' => '0821-9876-5432',
            'address' => 'Desa Cibeureum RT 01/RW 02, Banjar',
            'public_description' => 'Paguyuban petani penderes pohon aren di Kota Banjar yang memproduksi gula semut aren kristal berkualitas ekspor.',
            'logo_url' => 'https://images.unsplash.com/photo-1544979590-37e9b47eb705?auto=format&fit=crop&w=400&q=80',
            'banner_url' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?auto=format&fit=crop&w=1200&q=80',
            'verification_notes' => 'Perlu pendampingan khusus sertifikasi P-IRT dan higienitas produksi nira.',
            'verified_at' => Carbon::now()->subMonth(),
        ]);

        $taniSejahtera = Group::create([
            'name' => 'Kelompok Tani Sejahtera',
            'slug' => 'kelompok-tani-sejahtera',
            'category' => 'Kelompok Binaan SiYota',
            'program_id' => $programPriangan->id,
            'regency' => 'Kabupaten Ciamis',
            'district' => 'Panjalu',
            'village' => 'Sukamantri',
            'commodity_summary' => 'Sayuran organik, tomat, kubis',
            'status' => 'active',
            'is_public' => true,
            'business_name' => 'Sayur Segar Lereng Panjalu',
            'business_logo_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
            'leader_name' => 'Pak Hendra Saputra',
            'pic_name' => 'Pak Hendra Saputra (Koordinator Kebun)',
            'pic_public_visible' => true,
            'contact_phone' => '0813-8899-7766',
            'address' => 'Dusun Sukamantri RT 04/RW 01, Panjalu, Ciamis',
            'public_description' => 'Sentra budidaya aneka sayuran hortikultura organik di ketinggian 850 mdpl kaki Gunung Sawal.',
            'logo_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
            'banner_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=1200&q=80',
            'verification_notes' => 'Lahan lereng gunung subur dengan potensi sayuran premium.',
            'verified_at' => Carbon::now()->subMonths(3),
        ]);

        $hutanMandiri = Group::create([
            'name' => 'Kelompok Tani Hutan Mandiri',
            'slug' => 'kelompok-tani-hutan-mandiri',
            'category' => 'Kelompok Pendaftar Baru',
            'regency' => 'Kabupaten Garut',
            'district' => 'Cikajang',
            'village' => 'Girijaya',
            'commodity_summary' => 'Kopi Arabika & Madu Hutan',
            'status' => 'pending',
            'is_public' => false,
            'leader_name' => 'Pak Asep Ridwan',
            'pic_name' => 'Pak Asep Ridwan',
            'pic_public_visible' => false,
            'contact_phone' => '0852-1122-3344',
            'address' => 'Lereng Gn. Cikuray, Girijaya, Cikajang',
            'public_description' => 'Petani hutan penyangga lereng Gn Cikuray.',
            'verification_notes' => 'Menunggu verifikasi lapangan tim SiYota untuk kelayakan program pendampingan.',
            'verified_at' => null,
        ]);

        // 3. Create Users (3 Role Architecture: USER, FASIL, ADMIN)
        $defaultPassword = Hash::make('password');

        // Role 1: ADMIN (Super Admin)
        $admin = User::create([
            'name' => 'Tim Admin SiYota',
            'email' => 'admin@siyota.org',
            'password' => $defaultPassword,
            'role' => 'admin',
            'position_title' => 'Koordinator Pendampingan Komunitas',
            'phone' => '0811-9988-7766',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
        ]);

        // Role 2: FASIL (Fasilitator SiYota)
        $facilitator = User::create([
            'name' => 'Kang Rian Prasetya',
            'email' => 'rian@siyota.org',
            'password' => $defaultPassword,
            'role' => 'fasil',
            'position_title' => 'Fasilitator Wilayah Priangan Timur',
            'phone' => '0811-2233-4455',
            'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
        ]);

        // Role 3: USER (Admin Kelompok KWT Sumber Rejeki)
        $ibuSiti = User::create([
            'group_id' => $kwt->id,
            'name' => 'Ibu Siti Khodijah',
            'email' => 'siti@ruma.siyota.org',
            'password' => $defaultPassword,
            'role' => 'user',
            'position_title' => 'Ketua Kelompok (Admin Kelompok)',
            'phone' => '0812-3456-7890',
            'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
        ]);

        // Anggota Internal Kelompok (Data pengelola usaha)
        $ibuRina = User::create([
            'group_id' => $kwt->id,
            'name' => 'Ibu Rina Marlina',
            'email' => 'rina@ruma.siyota.org',
            'password' => $defaultPassword,
            'role' => 'user',
            'position_title' => 'Pengelola Keuangan Kas',
            'phone' => '0812-4455-6677',
            'avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80',
        ]);

        $ibuDewi = User::create([
            'group_id' => $kwt->id,
            'name' => 'Ibu Dewi Sartika',
            'email' => 'dewi@ruma.siyota.org',
            'password' => $defaultPassword,
            'role' => 'user',
            'position_title' => 'Pengelola Produk & Olahan',
            'phone' => '0812-7788-9900',
            'avatar_url' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=300&q=80',
        ]);

        // Link facilitator to groups
        $kwt->update(['facilitator_id' => $facilitator->id]);
        $arenMakmur->update(['facilitator_id' => $facilitator->id]);
        $taniSejahtera->update(['facilitator_id' => $facilitator->id]);

        // 4. Lands (Lahan Berbasis Polygon GeoJSON Sesuai Brief)
        $lahanA = Land::create([
            'group_id' => $kwt->id,
            'name' => 'Lahan A - Pasir Gebang',
            'latitude' => -7.348215,
            'longitude' => 108.163210,
            'polygon_geojson' => [
                [-7.3475, 108.1620],
                [-7.3470, 108.1645],
                [-7.3490, 108.1652],
                [-7.3495, 108.1625],
                [-7.3475, 108.1620],
            ],
            'area_m2' => 2800.00,
            'land_status' => 'Kas Desa (Bina Pinjam)',
            'is_public' => true,
            'manager_name' => 'Pak Rustam & Tim Tani KWT',
            'commodities' => 'Cabai Rawit Merah & Sayuran Hijau',
            'photo_url' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80',
            'notes' => 'Kondisi tanah gembur subur, memiliki sistem pengairan pipa gravitasi dari mata air lereng bukit.',
        ]);

        $lahanB = Land::create([
            'group_id' => $kwt->id,
            'name' => 'Lahan B - Blok Pohon Kawung',
            'latitude' => -7.354020,
            'longitude' => 108.172050,
            'polygon_geojson' => [
                [-7.3530, 108.1710],
                [-7.3525, 108.1740],
                [-7.3555, 108.1745],
                [-7.3560, 108.1715],
                [-7.3530, 108.1710],
            ],
            'area_m2' => 4500.00,
            'land_status' => 'Milik Bersama Anggota',
            'is_public' => true,
            'manager_name' => 'Pak Entis & Ibu Dewi',
            'commodities' => 'Pohon Aren (Nira Kawung)',
            'photo_url' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?auto=format&fit=crop&w=800&q=80',
            'notes' => 'Terdapat 42 pohon aren produktif yang disadap setiap pagi dan sore hari.',
        ]);

        $lahanC = Land::create([
            'group_id' => $kwt->id,
            'name' => 'Lahan C - Demplot Sayur Organik',
            'latitude' => -7.345090,
            'longitude' => 108.158140,
            'polygon_geojson' => [
                [-7.3445, 108.1575],
                [-7.3440, 108.1592],
                [-7.3460, 108.1595],
                [-7.3465, 108.1578],
                [-7.3445, 108.1575],
            ],
            'area_m2' => 1200.00,
            'land_status' => 'Pekarangan Anggota',
            'is_public' => false,
            'manager_name' => 'Ibu Siti Khodijah',
            'commodities' => 'Sayuran Daun, Bayam, Kangkung',
            'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=800&q=80',
            'notes' => 'Demplot uji coba pupuk organik hayati pendampingan SiYota.',
        ]);

        // 5. Commodities
        $komoditasCabai = Commodity::create([
            'group_id' => $kwt->id,
            'land_id' => $lahanA->id,
            'name' => 'Cabai Rawit Merah',
            'category' => 'Pertanian Hortikultura',
            'status' => 'Aktif',
            'harvest_estimate' => '150 - 200 kg / bulan',
            'planted_area_m2' => 2000.00,
            'photo_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80',
            'notes' => 'Panen petik merah setiap 4 hari sekali. Kualitas segar terhubung langsung ke produksi sambal botol.',
        ]);

        $komoditasAren = Commodity::create([
            'group_id' => $kwt->id,
            'land_id' => $lahanB->id,
            'name' => 'Aren / Nira Kawung',
            'category' => 'Perkebunan',
            'status' => 'Aktif',
            'harvest_estimate' => '180 liter nira / minggu',
            'planted_area_m2' => 4500.00,
            'photo_url' => 'https://images.unsplash.com/photo-1544979590-37e9b47eb705?auto=format&fit=crop&w=600&q=80',
            'notes' => 'Nira langsung diolah menjadi gula cetak dan gula kristal semut higienis.',
        ]);

        $komoditasSayuran = Commodity::create([
            'group_id' => $kwt->id,
            'land_id' => $lahanC->id,
            'name' => 'Sayuran Hijau Organik',
            'category' => 'Pertanian Sayur',
            'status' => 'Aktif',
            'harvest_estimate' => '80 kg / minggu',
            'planted_area_m2' => 1200.00,
            'photo_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
            'notes' => 'Bayam cabut, pakcoy, dan kangkung segar bebas pestisida kimia sintetis.',
        ]);

        // 6. Products
        Product::create([
            'group_id' => $kwt->id,
            'commodity_id' => $komoditasAren->id,
            'name' => 'Gula Aren Asli Tasikmalaya (Cetak)',
            'description' => '100% nira aren murni tanpa campuran pemanis buatan, aroma khas alami kelapa aren pegunungan.',
            'price' => 32000.00,
            'unit' => 'Pack 500gr',
            'packaging_type' => 'Plastik Sealed dengan Label Kertas Kraft',
            'photo_url' => 'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?auto=format&fit=crop&w=600&q=80',
            'status' => 'perlu_diperbaiki',
            'is_public' => true,
            'qc_label_status' => 'sesuai',
            'qc_packaging_status' => 'perbaikan',
            'qc_info_status' => 'perbaikan',
            'qc_photo_status' => 'sesuai',
            'facilitator_notes' => 'Kemasan luar perlu diganti dengan plastik vacuum atau pouch ber-zipper agar gula tidak menyerap lembap dan meleleh saat pengiriman luar kota.',
        ]);

        Product::create([
            'group_id' => $kwt->id,
            'commodity_id' => $komoditasCabai->id,
            'name' => 'Sambal Rawit Merah Uleg KWT',
            'description' => 'Sambal segar siap saji dengan cabai pilihan petani lokal, rasa pedas mantap alami tanpa pengawet berbahaya.',
            'price' => 25000.00,
            'unit' => 'Jar Kaca 180gr',
            'packaging_type' => 'Jar Kaca Segel Shrink Wrap',
            'photo_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            'status' => 'sesuai_panduan',
            'is_public' => true,
            'qc_label_status' => 'sesuai',
            'qc_packaging_status' => 'sesuai',
            'qc_info_status' => 'sesuai',
            'qc_photo_status' => 'sesuai',
            'facilitator_notes' => 'Sangat baik! Tanggal kedaluwarsa, komposisi, dan nomor izin P-IRT sudah tercantum dengan jelas di bagian belakang toples.',
        ]);

        Product::create([
            'group_id' => $kwt->id,
            'commodity_id' => $komoditasAren->id,
            'name' => 'Gula Semut Aren Kristal Premium',
            'description' => 'Gula aren bubuk kristal berbutir halus, praktis untuk kopi, teh, dan aneka olahan kue modern.',
            'price' => 38000.00,
            'unit' => 'Pouch 250gr',
            'packaging_type' => 'Standing Pouch Alumunium Foil Matte',
            'photo_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=600&q=80',
            'status' => 'dalam_evaluasi',
            'is_public' => true,
            'qc_label_status' => 'sesuai',
            'qc_packaging_status' => 'sesuai',
            'qc_info_status' => 'perbaikan',
            'qc_photo_status' => 'belum_dinilai',
            'facilitator_notes' => 'Perlu menambahkan saran penyajian dan info nilai gizi singkat pada stiker label samping.',
        ]);

        Product::create([
            'group_id' => $kwt->id,
            'commodity_id' => $komoditasSayuran->id,
            'name' => 'Paket Sayur Bayam & Pakcoy Petik Segar',
            'description' => 'Sayuran organik dipetik pagi hari sebelum dikirim, higienis dan dicuci bersih air mengalir.',
            'price' => 15000.00,
            'unit' => 'Pack Ikat (350gr)',
            'packaging_type' => 'Plastik Sayur Berpori Breathable',
            'photo_url' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=600&q=80',
            'status' => 'sesuai_panduan',
            'is_public' => true,
            'qc_label_status' => 'sesuai',
            'qc_packaging_status' => 'sesuai',
            'qc_info_status' => 'sesuai',
            'qc_photo_status' => 'sesuai',
            'facilitator_notes' => 'Sistem kemasan sudah rapi dan sayuran tetap segar hingga sore hari.',
        ]);

        Product::create([
            'group_id' => $kwt->id,
            'commodity_id' => $komoditasCabai->id,
            'name' => 'Cabai Bubuk Kering Murni 100%',
            'description' => 'Cabai rawit dikeringkan menggunakan dehidrator higienis, digiling halus tanpa pewarna buatan.',
            'price' => 20000.00,
            'unit' => 'Botol Bumbu 80gr',
            'packaging_type' => 'Botol Bumbu Plastik Segel Induksi',
            'photo_url' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80',
            'status' => 'dalam_evaluasi',
            'is_public' => true,
            'qc_label_status' => 'sesuai',
            'qc_packaging_status' => 'sesuai',
            'qc_info_status' => 'belum_dinilai',
            'qc_photo_status' => 'belum_dinilai',
            'facilitator_notes' => 'Sedang menunggu hasil uji daya simpan di ruangan terbuka selama 2 minggu.',
        ]);

        // 7. Financial Records
        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'masuk',
            'amount' => 1800000.00,
            'transaction_date' => Carbon::now()->subDays(12),
            'category' => 'Penjualan Gula Aren Cetak',
            'description' => 'Pesanan 60 bungkus dari Koperasi UMKM Kabupaten Tasikmalaya',
            'commodity_id' => $komoditasAren->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'keluar',
            'amount' => 450000.00,
            'transaction_date' => Carbon::now()->subDays(9),
            'category' => 'Pembelian Bibit & Pupuk Organik',
            'description' => 'Bibit cabai rawit unggul 10 bungkus & biofermentasi cair',
            'commodity_id' => $komoditasCabai->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'masuk',
            'amount' => 1200000.00,
            'transaction_date' => Carbon::now()->subDays(5),
            'category' => 'Penjualan Sayuran Segar',
            'description' => 'Langganan rumah makan khas sunda 4 titik pengantaran',
            'commodity_id' => $komoditasSayuran->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'keluar',
            'amount' => 450000.00,
            'transaction_date' => Carbon::now()->subDays(3),
            'category' => 'Pembelian Wadah Kemasan & Label',
            'description' => 'Pouch standing zipper 100 pcs dan stiker label laminasi',
            'commodity_id' => $komoditasAren->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'keluar',
            'amount' => 150000.00,
            'transaction_date' => Carbon::create(2026, 11, 5),
            'category' => 'Pembelian Botol Kemasan Sambal',
            'description' => 'Beli toples jar kaca 20 buah untuk stok sambal rawit',
            'commodity_id' => $komoditasCabai->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        FinancialRecord::create([
            'group_id' => $kwt->id,
            'type' => 'masuk',
            'amount' => 500000.00,
            'transaction_date' => Carbon::create(2026, 11, 6),
            'category' => 'Penjualan Cabai Rawit Segar',
            'description' => 'Penjualan petik cabai 15 kg ke warung grosir pasar',
            'commodity_id' => $komoditasCabai->id,
            'recorded_by_user_id' => $ibuRina->id,
        ]);

        // 8. Learning Classes
        LearningClass::create([
            'title' => 'Pengelolaan Keuangan Kelompok Tani Sederhana',
            'slug' => 'pengelolaan-keuangan-kelompok-tani-sederhana',
            'description' => 'Belajar memisahkan uang kas kelompok dari kas pribadi, mencatat pemasukan dan pengeluaran harian, serta menghitung keuntungan bersih usaha hasil bumi.',
            'instructor_name' => 'Tim Keuangan SiYota & Fasilitator',
            'schedule_text' => 'Kamis, 12 Nov 2026 • 13.30 - 15.00 WIB',
            'zoom_url' => 'https://zoom.us/j/siyota-keuangan-tani',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'category' => 'Pengelolaan Keuangan',
            'cover_image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80',
            'is_published' => true,
        ]);

        LearningClass::create([
            'title' => 'Branding Produk & Desain Kemasan Menarik',
            'slug' => 'branding-produk-desain-kemasan-menarik',
            'description' => 'Teknik memilih warna kemasan, membuat nama merek lokal yang berdaya pikat, serta mencantumkan info izin P-IRT dan tanggal kedaluwarsa secara tepat.',
            'instructor_name' => 'Kang Rian & Mentor Kreatif SiYota',
            'schedule_text' => 'Sabtu, 08 Nov 2026 • 09.00 - 11.00 WIB',
            'zoom_url' => 'https://zoom.us/j/siyota-branding-produk',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'category' => 'Branding Produk',
            'cover_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80',
            'is_published' => true,
        ]);

        LearningClass::create([
            'title' => 'Quality Control (QC) & Standar Higienitas Olahan Pangan',
            'slug' => 'quality-control-standar-higienitas-olahan-pangan',
            'description' => 'Memastikan olahan hasil bumi seperti gula aren, sambal, dan sayuran kering bersih, aman, bebas serangga, dan memiliki masa simpan yang tahan lama.',
            'instructor_name' => 'Dr. Ir. Wahyu - Tim Ahli Pangan SiYota',
            'schedule_text' => 'Senin, 16 Nov 2026 • 10.00 - 12.00 WIB',
            'zoom_url' => 'https://zoom.us/j/siyota-qc-pangan',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'category' => 'Quality Control',
            'cover_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80',
            'is_published' => true,
        ]);

        LearningClass::create([
            'title' => 'Digital Marketing Praktis: Foto HP & Jualan via WhatsApp',
            'slug' => 'digital-marketing-praktis-foto-hp-dan-whatsapp',
            'description' => 'Cara memotret produk tani menggunakan cahaya matahari pagi, menulis pesan promosi broadcast sopan, dan mengelola katalog produk di WhatsApp Business.',
            'instructor_name' => 'Fasilitator Komunikasi SiYota',
            'schedule_text' => 'Rabu, 18 Nov 2026 • 14.00 - 16.00 WIB',
            'zoom_url' => 'https://zoom.us/j/siyota-marketing-wa',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'category' => 'Digital Marketing',
            'cover_image' => 'https://images.unsplash.com/photo-1556742049-0a67e55722ee?auto=format&fit=crop&w=600&q=80',
            'is_published' => true,
        ]);

        LearningClass::create([
            'title' => 'Pengembangan Usaha & Strategi Kemitraan Toko Oleh-Oleh',
            'slug' => 'pengembangan-usaha-kemitraan-toko-oleh-oleh',
            'description' => 'Membangun jejaring penjualan ke toko buah lokal, minimarket desa, toko oleh-oleh khas daerah, dan kerja sama konsinyasi yang adil.',
            'instructor_name' => 'Tim Ekosistem SiYota.org',
            'schedule_text' => 'Jumat, 20 Nov 2026 • 09.30 - 11.30 WIB',
            'zoom_url' => 'https://zoom.us/j/siyota-kemitraan',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'category' => 'Pengembangan Usaha',
            'cover_image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=600&q=80',
            'is_published' => true,
        ]);

        // 9. Schedules
        Schedule::create([
            'group_id' => $kwt->id,
            'title' => 'Pelatihan Branding & Kemasan Produk',
            'event_type' => 'zoom',
            'event_date' => Carbon::create(2026, 11, 8, 9, 0),
            'time_info' => '09.00 WIB • Online Zoom',
            'location_or_link' => 'https://zoom.us/j/siyota-branding-produk',
            'description' => 'Sesi interaktif bersama mentor SiYota untuk bedah contoh kemasan produk cabai dan gula aren kelompok.',
            'badge_month' => 'NOV',
            'badge_day' => '08',
            'status' => 'mendatang',
        ]);

        Schedule::create([
            'group_id' => $kwt->id,
            'title' => 'Deadline Pengumpulan Tugas Foto Produk Cabai',
            'event_type' => 'deadline',
            'event_date' => Carbon::create(2026, 11, 12, 23, 59),
            'time_info' => '23.59 WIB • Unggah di RUMA',
            'location_or_link' => 'Menu Marketing RUMA',
            'description' => 'Kumpulkan 3 foto produk cabai dengan sudut pencahayaan alami dan caption deskripsi.',
            'badge_month' => 'NOV',
            'badge_day' => '12',
            'status' => 'mendatang',
        ]);

        Schedule::create([
            'group_id' => $kwt->id,
            'title' => 'Kunjungan Lapangan & Mentoring Fasilitator',
            'event_type' => 'kunjungan',
            'event_date' => Carbon::create(2026, 11, 14, 10, 0),
            'time_info' => '10.00 WIB • Kebun Pasir Gebang',
            'location_or_link' => 'Lahan A KWT Sumber Rejeki',
            'description' => 'Kang Rian mengunjungi lahan cabai dan demplot sayur untuk evaluasi tanaman dan sistem penampungan air.',
            'badge_month' => 'NOV',
            'badge_day' => '14',
            'status' => 'mendatang',
        ]);

        // 10. Learning Materials
        $materials = [
            [
                'title' => 'Panduan Foto Produk dengan HP & Cahaya Alami.pdf',
                'category' => 'marketing',
                'file_type' => 'pdf',
                'file_url' => '/documents/panduan-foto-produk-hp.pdf',
                'file_size_text' => '2.4 MB',
                'summary' => 'Langkah praktis memotret hasil bumi dan produk olahan menggunakan alas kayu, sinar matahari pagi, dan sudut kemiringan 45 derajat.',
                'download_count' => 142,
            ],
            [
                'title' => 'SOP Quality Control & Higienitas Pengolahan Nira Aren.pdf',
                'category' => 'sop_produksi',
                'file_type' => 'pdf',
                'file_url' => '/documents/sop-qc-nira-aren.pdf',
                'file_size_text' => '1.9 MB',
                'summary' => 'Panduan sanitasi bumbung penampung nira, suhu perebusan ideal, hingga pencetakan gula aren higienis tanpa abu arang.',
                'download_count' => 88,
            ],
            [
                'title' => 'Template Buku Kas Sederhana Pemasukan & Pengeluaran Tani.pdf',
                'category' => 'keuangan',
                'file_type' => 'pdf',
                'file_url' => '/documents/buku-kas-kelompok-tani.pdf',
                'file_size_text' => '1.1 MB',
                'summary' => 'Panduan kolom catatan uang kas masuk, keluar, saldo, dan tip menyatukan kuitansi pembelian kemasan dan pupuk.',
                'download_count' => 210,
            ],
            [
                'title' => 'Panduan Pembuatan Label & Syarat Legalitas P-IRT Kelompok.pdf',
                'category' => 'branding',
                'file_type' => 'pdf',
                'file_url' => '/documents/panduan-label-pirt.pdf',
                'file_size_text' => '3.5 MB',
                'summary' => 'Elemen wajib pada label produk pangan: nama dagang, berat bersih, nomor P-IRT, kode produksi, tanggal kedaluwarsa, dan alamat produsen.',
                'download_count' => 165,
            ],
            [
                'title' => 'Checklist Standar Quality Control Produk Siap Jual.pdf',
                'category' => 'quality_control',
                'file_type' => 'pdf',
                'file_url' => '/documents/qc-checklist-pangan.pdf',
                'file_size_text' => '1.5 MB',
                'summary' => 'Daftar periksa 4 pilar QC SiYota: Label Sesuai, Kemasan Rapat, Informasi Lengkap, dan Foto Terverifikasi.',
                'download_count' => 189,
            ],
        ];

        foreach ($materials as $m) {
            LearningMaterial::create($m);
        }

        // 11. Marketing Tasks
        $taskCabai = MarketingTask::create([
            'facilitator_id' => $facilitator->id,
            'title' => 'Foto Produk Cabai & Sambal dengan Pencahayaan Alami',
            'description' => 'Fasilitator SiYota menginstruksikan kelompok untuk mengambil foto produk olahan cabai rawit dengan kaidah pencahayaan terang dan sudut menarik.',
            'instructions' => [
                'Foto produk cabai dengan pencahayaan yang baik di dekat jendela atau luar ruangan pukul 08.00 - 10.00.',
                'Ambil foto proses produksi pemetikan atau proses uleg sambal bersih.',
                'Buat caption yang menjelaskan keunggulan rasa pedas alami tanpa pengawet.',
                'Unggah hasilnya ke RUMA Marketing Hub sebelum batas waktu.',
            ],
            'reference_pdf_name' => 'Panduan Foto Produk.pdf',
            'reference_pdf_url' => '/documents/panduan-foto-produk-hp.pdf',
            'reference_image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            'deadline' => Carbon::create(2026, 11, 12, 23, 59),
            'priority' => 'tinggi',
            'is_active' => true,
        ]);

        $taskAren = MarketingTask::create([
            'facilitator_id' => $facilitator->id,
            'title' => 'Buat Konten Cerita Pohon Aren & Proses Nira Tradisional',
            'description' => 'Dokumentasikan pohon aren di kebun dan cerita petani penyadap nira pagi untuk membangun narasi produk bernilai tinggi.',
            'instructions' => [
                'Foto pohon aren yang asri dan bumbung bambu penampung nira.',
                'Tulis cerita singkat tentang kearifan lokal petani Tasikmalaya.',
                'Tampilkan kemasan gula semut aren kristal di samping cangkir kopi.',
            ],
            'reference_pdf_name' => 'Storytelling Produk Lokal SiYota.pdf',
            'reference_pdf_url' => '/documents/storytelling-produk-lokal.pdf',
            'reference_image_url' => 'https://images.unsplash.com/photo-1544979590-37e9b47eb705?auto=format&fit=crop&w=600&q=80',
            'deadline' => Carbon::create(2026, 11, 20, 23, 59),
            'priority' => 'normal',
            'is_active' => true,
        ]);

        // 12. Marketing Submissions
        MarketingSubmission::create([
            'task_id' => $taskCabai->id,
            'group_id' => $kwt->id,
            'photo_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=800&q=80',
            'caption' => 'Sambal Rawit Merah Uleg KWT Sumber Rejeki. Dipetik langsung dari kebun Pasir Gebang Tasikmalaya, tanpa bahan pengawet. Pedas segar asli alam!',
            'notes' => 'Kami sudah memotret di meja kayu depan rumah saat pagi hari seperti panduan Kang Rian.',
            'status' => 'perlu_revisi',
            'facilitator_feedback' => 'Foto produk sudah bagus dan segar. Catatan revisi: Tolong tambahkan informasi netto (berat bersih 180 gr) pada caption dan sertakan nomor kontak pemesanan kelompok.',
            'submitted_at' => Carbon::now()->subDays(2),
            'reviewed_at' => Carbon::now()->subDay(),
        ]);

        MarketingSubmission::create([
            'task_id' => $taskAren->id,
            'group_id' => $arenMakmur->id,
            'photo_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=800&q=80',
            'caption' => 'Gula Semut Aren Kristal asli Banjar. Manis lembut alami untuk menemani kopi senja Anda. Pesan sekarang melalui Kelompok Aren Makmur.',
            'notes' => 'Foto diambil saat pengemasan pouch.',
            'status' => 'dikumpulkan',
            'facilitator_feedback' => null,
            'submitted_at' => Carbon::now()->subHours(6),
            'reviewed_at' => null,
        ]);

        // 13. Mentoring Notes
        MentoringNote::create([
            'group_id' => $kwt->id,
            'facilitator_id' => $facilitator->id,
            'date' => Carbon::now()->subDays(7),
            'topic' => 'Review Kualitas Kemasan Gula Aren & Pemisahan Kas',
            'observations' => 'Ibu Siti dan Ibu Rina sangat antusias. Pencatatan keuangan manual sudah mulai rapi, tapi masih ada struk pembelian benih yang belum dicatat ke buku kelompok. Kemasan gula aren masih menggunakan kantong kresek tipis yang mudah bocor udara.',
            'action_items' => '1. Ganti kemasan ke standing pouch zipper bersegel. 2. Ibu Rina input seluruh transaksi ke aplikasi RUMA. 3. Ambil foto produk cabai dengan panduan.',
            'status' => 'tindak_lanjut',
        ]);

        // 14. Notification Logs (WhatsApp & Email)
        NotificationLog::create([
            'group_id' => $kwt->id,
            'user_id' => $ibuSiti->id,
            'channel' => 'whatsapp',
            'event_type' => 'tugas_baru',
            'recipient' => '0812-3456-7890',
            'message_preview' => '🌱 RUMA: Halo KWT Sumber Rejeki! Ada tugas baru dari fasilitator SiYota: Foto Produk Cabai & Sambal. Deadline: 12 Nov 2026.',
            'status' => 'sent',
            'sent_at' => Carbon::now()->subDays(3),
        ]);

        NotificationLog::create([
            'group_id' => $kwt->id,
            'user_id' => $ibuSiti->id,
            'channel' => 'email',
            'event_type' => 'tugas_baru',
            'recipient' => 'siti@ruma.siyota.org',
            'message_preview' => 'Fasilitator SiYota telah menugaskan "Foto Produk Cabai & Sambal". Buka RUMA untuk petunjuk lengkap.',
            'status' => 'sent',
            'sent_at' => Carbon::now()->subDays(3),
        ]);

        NotificationLog::create([
            'group_id' => $kwt->id,
            'user_id' => $ibuSiti->id,
            'channel' => 'whatsapp',
            'event_type' => 'review_konten',
            'recipient' => '0812-3456-7890',
            'message_preview' => '📝 RUMA: Kang Rian telah meninjau tugas Foto Produk Cabai. Status: Perlu Revisi sedikit pada penambahan info berat bersih.',
            'status' => 'sent',
            'sent_at' => Carbon::now()->subDay(),
        ]);

        // 15. Daily Report Links & Sample Reports
        $linkKwt = DailyReportLink::create([
            'group_id' => $kwt->id,
            'title' => 'Laporan Harian — KWT Sumber Rejeki',
            'description' => 'Laporkan aktivitas perawatan kebun cabai rawit, jadwal penyiraman, dan pemupukan organik harian.',
            'token' => 'kwt-sumber-rejeki-harian-2026',
            'daily_limit' => 2,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $linkMukti = DailyReportLink::create([
            'group_id' => $arenMakmur->id,
            'title' => 'Laporan Harian Sadapan Nira Aren — Kelompok Aren Makmur',
            'description' => 'Unggah catatan hasil sadapan nira aren harian, kondisi cuaca, dan proses pemanasan nira.',
            'token' => 'aren-makmur-harian-2026',
            'daily_limit' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        DailyProgressReport::create([
            'daily_report_link_id' => $linkKwt->id,
            'group_id' => $kwt->id,
            'user_id' => $ibuSiti->id,
            'reporter_name' => 'Siti Khodijah (Ketua KWT)',
            'report_date' => Carbon::now('Asia/Jakarta')->toDateString(),
            'title' => 'Penyiraman dan Pengecekan Hama Daun Cabai Blok A',
            'description' => 'Pagi hari pukul 07.30 telah dilakukan penyiraman rutin di petak lahan cabai rawit merah. Daun cabai tampak segar, tunas baru mulai tumbuh seragam, dan tidak ditemukan hama kutu kebul.',
            'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6ef23963?auto=format&fit=crop&w=800&q=80',
            'status' => 'reviewed',
            'admin_notes' => 'Bagus sekali Bu Siti, pastikan drainase bedengan tetap terjaga jika sore ini turun hujan lebat.',
        ]);

        DailyProgressReport::create([
            'daily_report_link_id' => $linkMukti->id,
            'group_id' => $arenMakmur->id,
            'reporter_name' => 'Pak Dede Permana',
            'report_date' => Carbon::now('Asia/Jakarta')->subDay()->toDateString(),
            'title' => 'Penyadapan 12 Pohon Aren Blok Cibeureum',
            'description' => 'Hasil sadapan nira pagi ini mencapai 45 liter dari 12 pohon aren produktif. Nira dalam kondisi segar dan manis, langsung dimasak untuk produksi gula aren cetak dan gula semut.',
            'photo_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
            'status' => 'approved',
            'admin_notes' => 'Kualitas nira sangat baik, rendemen gula mencapai standar prima.',
        ]);
    }
}
