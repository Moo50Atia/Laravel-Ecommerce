<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])->latest()->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,canceled,expired',
            'end_date' => 'nullable|date',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully');
    }
}
