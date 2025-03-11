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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('ip_id')->nullable()->after('user_id');
            $table->json('changes')->nullable()->after('action');

            $table->foreign('ip_id')->references('id')->on('ip_addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['ip_id']);
            $table->dropColumn(['ip_id', 'changes']);
        });
    }
};
