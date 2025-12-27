<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sites' => Site::count(),
            'active_trials' => User::whereHas('subscription', function ($q) {
                $q->where('status', 'trialing');
            })->count(),
            'paid_users' => User::whereHas('subscription', function ($q) {
                $q->where('status', 'active');
            })->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List all users with their details.
     */
    public function users(Request $request)
    {
        $search = $request->input('search');

        $users = User::with(['subscription', 'sites'])
            ->withCount('sites')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    /**
     * List all websites with their details.
     */
    public function sites()
    {
        $sites = Site::with(['user', 'trialDomain'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.sites', compact('sites'));
    }

    /**
     * Pause a website (disable monitoring).
     */
    public function pauseSite(Site $site)
    {
        $site->update(['status' => 'paused']);

        return back()->with('success', "Site '{$site->domain}' has been paused.");
    }

    /**
     * Resume a website (enable monitoring).
     */
    public function resumeSite(Site $site)
    {
        $site->update(['status' => 'active']);

        return back()->with('success', "Site '{$site->domain}' has been resumed.");
    }

    /**
     * Show the create user form.
     */
    public function createUser()
    {
        return view('admin.users-create');
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'has_free_access' => 'boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'has_free_access' => $request->boolean('has_free_access'),
        ]);

        return redirect()->route('admin.users')->with('success', "User '{$validated['email']}' created successfully.");
    }

    /**
     * Toggle free access for a user.
     */
    public function toggleFreeAccess(User $user)
    {
        $user->update(['has_free_access' => !$user->has_free_access]);

        $status = $user->has_free_access ? 'granted' : 'revoked';
        return back()->with('success', "Free access {$status} for '{$user->email}'.");
    }

    /**
     * Suspend a user account.
     */
    public function suspendUser(Request $request, User $user)
    {
        if ($user->is_super_admin) {
            return back()->with('error', 'Cannot suspend a super admin account.');
        }

        $user->update([
            'is_suspended' => true,
            'suspension_reason' => $request->input('reason', 'Suspended by admin'),
        ]);

        return back()->with('success', "User '{$user->email}' has been suspended.");
    }

    /**
     * Unsuspend a user account.
     */
    public function unsuspendUser(User $user)
    {
        $user->update([
            'is_suspended' => false,
            'suspension_reason' => null,
        ]);

        return back()->with('success', "User '{$user->email}' has been unsuspended.");
    }

    /**
     * Delete a user account.
     */
    public function deleteUser(User $user)
    {
        if ($user->is_super_admin) {
            return back()->with('error', 'Cannot delete a super admin account.');
        }

        $email = $user->email;
        $user->sites()->delete();
        $user->delete();

        return back()->with('success', "User '{$email}' and all their sites have been deleted.");
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $user->load(['subscription', 'sites', 'trialDomains']);
        return view('admin.users-show', compact('user'));
    }
    /**
     * End user's trial immediately.
     */
    public function endUserTrial(User $user)
    {
        $ended = false;

        // End subscription trial
        $subscription = $user->subscription;
        if ($subscription && $subscription->onTrial()) {
            $subscription->update(['trial_ends_at' => now()]);
            $ended = true;
        }

        // End site trials
        $activeTrialDomains = $user->trialDomains()->where('is_expired', false)->where('trial_ends_at', '>', now())->get();
        foreach ($activeTrialDomains as $trial) {
            $trial->update(['trial_ends_at' => now(), 'is_expired' => true]);
            $ended = true;
        }

        if ($ended) {
            return back()->with('success', "Trial ended for user '{$user->email}'.");
        }

        return back()->with('info', "No active trials found for user '{$user->email}'.");
    }

    /**
     * Update user service permissions.
     */
    public function updateUserServices(Request $request, User $user)
    {
        $validated = $request->validate([
            'service_speed_test' => 'boolean',
            'service_screenshots' => 'boolean',
            'service_google' => 'boolean',
        ]);

        $user->update([
            'service_speed_test' => $request->has('service_speed_test'),
            'service_screenshots' => $request->has('service_screenshots'),
            'service_google' => $request->has('service_google'),
        ]);

        return back()->with('success', "Services updated for user '{$user->email}'.");
    }
}
