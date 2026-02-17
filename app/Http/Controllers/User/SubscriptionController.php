<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscription = Auth::user()->subscription;
        $availablePlans = Plan::where('is_active', true)
            ->where('type', Auth::user()->role === 'vendor' ? 'vendor' : 'user')
            ->get();

        return view('user.subscription.show', compact('subscription', 'availablePlans'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        Subscription::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'plan_id' => $plan->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($plan->duration_days),
            ]
        );

        return redirect()->route('user.subscription.index')->with('success', 'Subscribed successfully!');
    }

    public function cancel()
    {
        $subscription = Auth::user()->subscription;
        if ($subscription) {
            $subscription->update(['status' => 'canceled']);
        }

        return redirect()->route('user.subscription.index')->with('success', 'Subscription canceled.');
    }
}
