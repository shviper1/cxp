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
        Schema::table('countries', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('currency_symbol');
            $table->decimal('amount', 10, 2)->nullable()->after('order');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('post_type');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('post_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['order', 'amount']);
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
