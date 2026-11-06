<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Groups (Kelompok Binaan)
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('Kelompok Binaan SiYota');
            $table->string('regency'); // e.g. Kabupaten Tasikmalaya
            $table->string('district')->nullable(); // e.g. Singaparna
            $table->string('village')->nullable(); // e.g. Sukarame
            $table->string('commodity_summary')->nullable(); // e.g. "Cabai, Aren, Sayuran"
            $table->enum('status', ['pending', 'active', 'rejected'])->default('active');
            $table->string('leader_name');
            $table->string('contact_phone');
            $table->text('address')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->unsignedBigInteger('facilitator_id')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 2. Add columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('id')->constrained('groups')->nullOnDelete();
            $table->string('role')->default('group_member')->after('email'); // super_admin, admin_siyota, facilitator, group_admin, group_member
            $table->string('position_title')->nullable()->after('role'); // Ketua Kelompok, Pengelola Keuangan, dll.
            $table->string('phone')->nullable()->after('position_title');
            $table->string('avatar_url')->nullable()->after('phone');
        });

        // 3. Learning Classes
        Schema::create('learning_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('instructor_name')->default('Tim Fasilitator SiYota');
            $table->string('schedule_text')->nullable();
            $table->string('zoom_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('category')->default('Branding Produk');
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 4. Schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('groups')->cascadeOnDelete();
            $table->string('title');
            $table->enum('event_type', ['pelatihan', 'zoom', 'mentoring', 'kunjungan', 'deadline', 'evaluasi'])->default('pelatihan');
            $table->dateTime('event_date');
            $table->string('time_info')->default('09:00 WIB');
            $table->string('location_or_link')->nullable();
            $table->text('description')->nullable();
            $table->string('badge_month')->nullable(); // e.g. "NOV"
            $table->string('badge_day')->nullable(); // e.g. "08"
            $table->string('status')->default('mendatang');
            $table->timestamps();
        });

        // 5. Learning Materials / Documents
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // keuangan, sop_produksi, quality_control, branding, kemasan, marketing, administrasi
            $table->string('file_type')->default('pdf'); // pdf, video, doc, image
            $table->string('file_url')->nullable();
            $table->string('file_size_text')->default('1.8 MB');
            $table->text('summary')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });

        // 6. Lands (Lahan)
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('latitude', 10, 7)->default(-7.351234);
            $table->decimal('longitude', 10, 7)->default(108.152345);
            $table->decimal('area_m2', 12, 2)->default(1500);
            $table->string('land_status')->default('Milik Kelompok'); // Milik Kelompok, Sewa, Kas Desa, Milik Anggota
            $table->string('manager_name')->nullable();
            $table->string('commodities')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Commodities (Komoditas)
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('land_id')->nullable()->constrained('lands')->nullOnDelete();
            $table->string('name');
            $table->string('category')->default('Pertanian'); // Pertanian, Perkebunan, Hortikultura
            $table->string('status')->default('Aktif'); // Aktif, Panen, Perawatan
            $table->string('harvest_estimate')->nullable(); // e.g. "250 kg / siklus"
            $table->decimal('planted_area_m2', 10, 2)->nullable();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. Products & QC
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('commodity_id')->nullable()->constrained('commodities')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('unit')->default('Pcs');
            $table->string('packaging_type')->nullable(); // e.g. Standing Pouch, Botol Kaca, Plastik Vacuum
            $table->string('photo_url')->nullable();
            $table->enum('status', ['perlu_diperbaiki', 'dalam_evaluasi', 'sesuai_panduan'])->default('dalam_evaluasi');
            // QC Items
            $table->enum('qc_label_status', ['sesuai', 'perbaikan', 'belum_dinilai'])->default('belum_dinilai');
            $table->enum('qc_packaging_status', ['sesuai', 'perbaikan', 'belum_dinilai'])->default('belum_dinilai');
            $table->enum('qc_info_status', ['sesuai', 'perbaikan', 'belum_dinilai'])->default('belum_dinilai');
            $table->enum('qc_photo_status', ['sesuai', 'perbaikan', 'belum_dinilai'])->default('belum_dinilai');
            $table->text('facilitator_notes')->nullable();
            $table->timestamps();
        });

        // 9. Financial Records (Pencatatan Keuangan Sederhana)
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->enum('type', ['masuk', 'keluar']);
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('category'); // Penjualan Cabai, Pembelian Kemasan, Bibit, dsb
            $table->string('description');
            $table->foreignId('commodity_id')->nullable()->constrained('commodities')->nullOnDelete();
            $table->string('receipt_url')->nullable();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. Marketing Tasks
        Schema::create('marketing_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilitator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('instructions')->nullable();
            $table->string('reference_pdf_name')->nullable();
            $table->string('reference_pdf_url')->nullable();
            $table->string('reference_image_url')->nullable();
            $table->dateTime('deadline');
            $table->string('priority')->default('normal');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 11. Marketing Submissions
        Schema::create('marketing_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('marketing_tasks')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->string('photo_url')->nullable();
            $table->text('caption')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['belum_dikerjakan', 'dikumpulkan', 'perlu_revisi', 'disetujui', 'dipublikasikan'])->default('dikumpulkan');
            $table->text('facilitator_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // 12. Mentoring Notes
        Schema::create('mentoring_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('facilitator_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->string('topic');
            $table->text('observations');
            $table->text('action_items')->nullable();
            $table->enum('status', ['selesai', 'tindak_lanjut'])->default('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_notes');
        Schema::dropIfExists('marketing_submissions');
        Schema::dropIfExists('marketing_tasks');
        Schema::dropIfExists('financial_records');
        Schema::dropIfExists('products');
        Schema::dropIfExists('commodities');
        Schema::dropIfExists('lands');
        Schema::dropIfExists('learning_materials');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('learning_classes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'role', 'position_title', 'phone', 'avatar_url']);
        });

        Schema::dropIfExists('groups');
    }
};
