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
        // 1. Add is_active column to users table if not exists
        if (! Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('role');
            });
        }

        // 2. Create daily_report_links table
        Schema::create('daily_report_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->string('title'); // e.g. "Laporan Harian — KWT Sumber Rejeki"
            $table->text('description')->nullable(); // instruksi atau deskripsi
            $table->string('token', 64)->unique(); // token publik unik
            $table->unsignedInteger('daily_limit')->default(1); // 0 = tidak dibatasi, 1, 2, dst.
            $table->boolean('is_active')->default(true); // aktif / nonaktif
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Create daily_progress_reports table
        Schema::create('daily_progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_link_id')->nullable()->constrained('daily_report_links')->nullOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reporter_name');
            $table->date('report_date')->index();
            $table->string('title');
            $table->text('description');
            $table->string('photo_url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('submitted'); // submitted, reviewed, approved
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_progress_reports');
        Schema::dropIfExists('daily_report_links');

        if (Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
