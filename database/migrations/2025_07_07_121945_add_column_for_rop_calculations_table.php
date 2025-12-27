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
        Schema::table('eoq_calculations', function (Blueprint $table) {
            $table->double('lead_time');
            $table->integer('working_days');
            $table->double('max_daily_demand');
            $table->double('daily_demand');
            $table->double('safety_stock');
            $table->double('rop_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eoq_calculations', function (Blueprint $table) {
            $table->dropColumn('lead_time');
            $table->dropColumn('working_days');
            $table->dropColumn('max_daily_demand');
            $table->dropColumn('daily_demand');
            $table->dropColumn('safety_stock');
            $table->dropColumn('rop_value');
        });
    }
};

