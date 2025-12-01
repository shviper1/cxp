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
        Schema::table('posts', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['user_id']);

            // Make the column nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Add the foreign key constraint back with nullable support
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['user_id']);

            // Make it not nullable again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Add back the original constraint
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
