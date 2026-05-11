<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome()
    {
        $plans = Plan::take(3)->get();
        $planCount = Plan::count();
        $activeUsers = User::count();
        $policyCount = Policy::count();

        return view('welcome', compact('plans', 'planCount', 'activeUsers', 'policyCount'));
    }

    public function about()
    {
        $planCount = Plan::count();
        $policyCount = Policy::count();
        $activeUsers = User::count();

        return view('about', compact('planCount', 'policyCount', 'activeUsers'));
    }

    public function contact()
    {
        $plans = Plan::count();
        $planCount = $plans;
        $activeUsers = User::count();
        $policyCount = Policy::count();

        return view('contact', compact('planCount', 'activeUsers', 'policyCount'));
    }
}
