<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->decimal('latitude',20,10)->nullable()->after('ip_address');
            $table->decimal('longitude',20,10)->nullable()->after('latitude');
            $table->string('city')->nullable()->after('longitude');
            $table->string('country_name')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('city');
            $table->dropColumn('country_name');
        });
    }
};
