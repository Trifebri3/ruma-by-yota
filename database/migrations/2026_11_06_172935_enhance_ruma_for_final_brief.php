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
        // 1. Groups table additions for public visibility
        Schema::table('groups', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('status');
            $table->string('business_name')->nullable()->after('name'); // e.g. "Sentra Cabai & Olahan Aren Pasir Gebang"
            $table->string('business_logo_url')->nullable()->after('logo_url');
            $table->string('pic_name')->nullable()->after('leader_name'); // PIC Publik yang disetujui
            $table->boolean('pic_public_visible')->default(true)->after('pic_name');
            $table->text('public_description')->nullable()->after('address');
            $table->unsignedBigInteger('program_id')->nullable()->after('facilitator_id');
        });

        // 2. Lands table additions for Polygon GeoJSON mapping
        Schema::table('lands', function (Blueprint $table) {
            $table->json('polygon_geojson')->nullable()->after('longitude');
            $table->boolean('is_public')->default(false)->after('land_status');
        });

        // 3. Products table additions for public catalog
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('status');
        });

        // 4. Programs table
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('aktif'); // aktif, selesai, arsip
            $table->timestamps();
        });

        // 5. Notification Logs table
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'email']);
            $table->string('event_type'); // akun_terverifikasi, tugas_baru, deadline_tugas, review_konten, kelas_baru, mentoring
            $table->string('recipient'); // no wa or email
            $table->text('message_preview');
            $table->enum('status', ['queued', 'sent', 'failed'])->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // 6. System settings table
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('programs');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });

        Schema::table('lands', function (Blueprint $table) {
            $table->dropColumn(['polygon_geojson', 'is_public']);
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'is_public',
                'business_name',
                'business_logo_url',
                'pic_name',
                'pic_public_visible',
                'public_description',
                'program_id',
            ]);
        });
    }
};
