<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Term Life 20 Years',
                'description' => 'Comprehensive term life insurance for 20 years with high coverage.',
                'coverage_amount' => 2000000,
                'premium_amount' => 15000,
                'duration_years' => 20,
                'features' => ['Death Benefit', 'Terminal Illness Cover', 'Accidental Death Benefit'],
            ],
            [
                'name' => 'Endowment Plan',
                'description' => 'Savings-oriented plan with life coverage and maturity benefits.',
                'coverage_amount' => 1500000,
                'premium_amount' => 12000,
                'duration_years' => 25,
                'features' => ['Life Cover', 'Maturity Benefit', 'Bonus Additions'],
            ],
            [
                'name' => 'Money Back Plan',
                'description' => 'Regular income plan with life insurance and periodic payouts.',
                'coverage_amount' => 1500000,
                'premium_amount' => 18000,
                'duration_years' => 20,
                'features' => ['Life Cover', 'Periodic Payouts', 'Survival Benefits'],
            ],
            [
                'name' => 'Whole Life Insurance',
                'description' => 'Lifetime coverage with guaranteed cash value accumulation.',
                'coverage_amount' => 5000000,
                'premium_amount' => 25000,
                'duration_years' => 100,
                'features' => ['Lifetime Cover', 'Cash Value', 'Loan Facility'],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}