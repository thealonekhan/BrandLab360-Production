<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('showFilters')->nullable();
            $table->boolean('showTopCards')->nullable();
            $table->boolean('showOverview')->nullable();
            $table->boolean('showEvents')->nullable();
            $table->boolean('showTrafficChart')->nullable();
            $table->boolean('showDeviceChart')->nullable();
            $table->json('eventTabs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
