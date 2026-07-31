<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (GeneralSetting::count() == 0) {
            GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
            ]);
        }
    }
}
