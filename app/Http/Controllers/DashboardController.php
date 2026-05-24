<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Plan;
use App\Models\Policy;
use App\Models\Claim;
use App\Models\Transaction;

class DashboardController extends Controller
{
    /**
     * Main Dashboard Router
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    /* =========================================================
       USER DASHBOARD
    ========================================================= */
    private function userDashboard($user)
    {
        // Use native MongoDB ObjectId directly
        $userId = $user->_id;

        /*
        |--------------------------------------------------------------------------
        | Policies
        |--------------------------------------------------------------------------
        */

        $policies = Policy::with('plan')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $activePolicies = $policies->where('status', 'active');

        $activePoliciesCount = $activePolicies->count();

        $coverageTotal = $activePolicies->sum(function ($policy) {
            return (int) optional($policy->plan)->coverage_amount;
        });

        $monthlyPremiumTotal = $activePolicies->sum(function ($policy) {
            return (int) ($policy->premium_paid ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Claims
        |--------------------------------------------------------------------------
        */

        $userClaims = Claim::with(['policy.plan'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $claimsCount = $userClaims->count();

        $pendingClaimsCount = $userClaims
            ->whereIn('status', ['pending', 'under_review'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Next Due Policy
        |--------------------------------------------------------------------------
        */

        $nextDuePolicy = $activePolicies
            ->filter(fn($policy) => !empty($policy->next_due_date))
            ->sortBy('next_due_date')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent Transactions
        |--------------------------------------------------------------------------
        */

        $recentTransactions = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'policies',
            'activePoliciesCount',
            'coverageTotal',
            'monthlyPremiumTotal',
            'claimsCount',
            'pendingClaimsCount',
            'nextDuePolicy',
            'recentTransactions',
            'userClaims'
        ));
    }

    /* =========================================================
       ADMIN DASHBOARD
    ========================================================= */
    private function adminDashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $allUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsersCount = $allUsers->count();

        /*
        |--------------------------------------------------------------------------
        | Plans
        |--------------------------------------------------------------------------
        */

        $plans = Plan::all();

        foreach ($plans as $plan) {
            $plan->policies_count = Policy::where('plan_id', $plan->_id)
                ->where('status', 'active')
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Policies
        |--------------------------------------------------------------------------
        */

        $allPolicies = Policy::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $globalActivePoliciesCount = $allPolicies
            ->where('status', 'active')
            ->count();

        $globalPendingPoliciesCount = $allPolicies
            ->whereIn('status', ['pending', 'pending_approval'])
            ->count();

        $globalApprovedCount = $allPolicies
            ->where('status', 'approved')
            ->count();

        $pendingPolicies = $allPolicies
            ->whereIn('status', ['pending', 'pending_approval'])
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Claims
        |--------------------------------------------------------------------------
        */

        $allClaims = Claim::with(['user', 'policy.plan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingClaims = $allClaims
            ->whereIn('status', ['pending', 'under_review'])
            ->values();

        $approvedClaims = $allClaims
            ->where('status', 'approved')
            ->values();

        $rejectedClaims = $allClaims
            ->where('status', 'rejected')
            ->values();

        $pendingClaimsCount = $pendingClaims->count();

        $allClaimsCount = $allClaims->count();

        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $totalRevenue = (int) Transaction::where('status', 'completed')
            ->sum('amount');

        return view('dashboard', compact(
            'totalUsersCount',
            'allPolicies',
            'pendingPolicies',
            'globalActivePoliciesCount',
            'globalPendingPoliciesCount',
            'globalApprovedCount',
            'allClaims',
            'pendingClaims',
            'pendingClaimsCount',
            'approvedClaims',
            'rejectedClaims',
            'allClaimsCount',
            'allUsers',
            'totalRevenue',
            'plans'
        ));
    }

    /* =========================================================
       POLICY ACTIONS
    ========================================================= */

    public function approvePolicy(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        $policy->status = 'active';
        $policy->approved_at = now();
        $policy->processed_by = Auth::user()->_id;

        $policy->save();

        return redirect()
            ->route('dashboard')
            ->with('success', "Policy {$policy->policy_number} approved successfully.");
    }

    public function rejectPolicy(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $policy = Policy::findOrFail($id);

        $policy->status = 'rejected';
        $policy->rejected_at = now();
        $policy->processed_by = Auth::user()->_id;
        $policy->notes = $request->reason ?? 'Rejected by administrator';

        $policy->save();

        return redirect()
            ->route('dashboard')
            ->with('error', "Policy {$policy->policy_number} rejected.");
    }

    /* =========================================================
       CLAIM ACTIONS
    ========================================================= */

    public function approveClaim(Request $request, $id)
    {
        $request->validate([
            'settlement_amount' => 'nullable|numeric|min:0'
        ]);

        $claim = Claim::findOrFail($id);

        $claim->status = 'approved';
        $claim->approved_at = now();
        $claim->processed_by = Auth::user()->_id;

        if ($request->filled('settlement_amount')) {
            $claim->claim_amount = (int) $request->settlement_amount;
        }

        $claim->save();

        return redirect()
            ->route('dashboard')
            ->with('success', "Claim approved successfully.");
    }

    public function rejectClaim(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $claim = Claim::findOrFail($id);

        $claim->status = 'rejected';
        $claim->approved_at = now();
        $claim->processed_by = Auth::user()->_id;
        $claim->notes = $request->reason ?? 'Rejected by administrator';

        $claim->save();

        return redirect()
            ->route('dashboard')
            ->with('error', "Claim rejected.");
    }

    public function reviewClaim($id)
    {
        $claim = Claim::findOrFail($id);

        $claim->status = 'under_review';

        $claim->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Claim moved to review.');
    }

    /* =========================================================
       USER MANAGEMENT
    ========================================================= */

    public function toggleUser($id)
    {
        $user = User::findOrFail($id);

        $user->status =
            ($user->status ?? 'active') === 'suspended'
            ? 'active'
            : 'suspended';

        $user->save();

        return redirect()
            ->route('dashboard')
            ->with('success', "User {$user->name} status updated.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        Policy::where('user_id', $user->_id)
            ->update([
                'status' => 'cancelled'
            ]);

        Claim::where('user_id', $user->_id)
            ->update([
                'status' => 'cancelled'
            ]);

        $user->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'User deleted successfully.');
    }
}