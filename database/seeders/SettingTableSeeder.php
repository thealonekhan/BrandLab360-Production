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
            'config' => json_encode([
                'filters' => [
                    'active' => 'on',
                    'matrix' => 'on',
                    'quickDate' => 'on',
                    'datepicker' => 'on'
                ],
                'topCards' => [
                    'active' => 'on',
                    'sessions' => 'on',
                    'users' => 'on',
                    'visits' => 'on',
                    'bounceRate' => 'on',
                    'avgSessionTime' => 'on'

                ],
                'overview' => [
                    'active' => 'on',
                    'graph' => 'on',
                    'cards' => [
                        'active' => 'on',
                        'newUsers' => 'on',
                        'sessions' => 'on',
                        'avgSessionDuration' => 'on',
                        'bounceRate' => 'on'
                    ],
                    'pieGraph' => 'on'
                ],
                'graphs' => [
                    'devices' => 'on',
                    'traffic' => 'on'
                ],
                'events' => [
                    'active' => 'on',
                    'eventTabs' => ['Location', 'Product', 'Video']
                ]
            ]),
            'user_id' => 1
        ]);
    }
}
