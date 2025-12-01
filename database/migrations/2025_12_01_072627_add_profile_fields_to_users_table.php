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
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            $table->string('occupation')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('occupation');
            $table->string('address')->nullable()->after('bio');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('zip_code', 20)->nullable()->after('state');
            $table->string('country')->nullable()->after('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'occupation',
                'bio',
                'address',
                'city',
                'state',
                'zip_code',
                'country'
            ]);
        });
    }
};
