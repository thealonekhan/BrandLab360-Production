<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'showFilters' => true,
            'showTopCards' => true,
            'showOverview' => true,
            'showEvents' => true,
            'showTrafficChart' => true,
            'showDeviceChart' => true,
            'eventTabs' => json_encode([
                'Location', 'Product', 'Video'
            ])
        ]);
    }
}
