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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])
                  ->default('unverified')
                  ->after('country');
            $table->string('id_document_path')->nullable()->after('verification_status');
            $table->string('selfie_path')->nullable()->after('id_document_path');
            $table->text('verification_notes')->nullable()->after('selfie_path');
            $table->timestamp('verified_at')->nullable()->after('verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'id_document_path',
                'selfie_path',
                'verification_notes',
                'verified_at'
            ]);
        });
    }
};
