<?php

namespace Database\Seeders;

use App\Models\TradeExperience;
use Illuminate\Database\Seeder;

class TradeExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $trade_experiences = [
            ['id' => 1, 'min_trade_year' => 1, 'max_trade_year' => 3],
            ['id' => 2, 'min_trade_year' => 3, 'max_trade_year' => 5],
            ['id' => 3, 'min_trade_year' => 5, 'max_trade_year' => 7],
            ['id' => 4, 'min_trade_year' => 7, 'max_trade_year' => 10],
            ['id' => 5, 'min_trade_year' => 10, 'max_trade_year' => null],
        ];

        TradeExperience::insert($trade_experiences);
    }
}
