<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy; // Adjust based on your actual model names
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        // 1. If user is an Admin, we can bypass consumer database queries
        if ($user->isAdmin()) {
            return view('dashboard'); 
        }

        // 2. Original Consumer Data Queries (Keep your existing queries intact)
        $policies = Policy::where('user_id', $user->id)->get(); // or use your MongoDB _id reference
        
        $activePoliciesCount = $policies->where('status', 'active')->count();
        
        $coverageTotal = $policies->where('status', 'active')->sum(function($policy) {
            return $policy->plan->coverage_amount ?? 0;
        });

        $monthlyPremiumTotal = $policies->sum('premium_paid');

        return view('dashboard', compact(
            'policies', 
            'activePoliciesCount', 
            'coverageTotal', 
            'monthlyPremiumTotal'
        ));
    }
}
