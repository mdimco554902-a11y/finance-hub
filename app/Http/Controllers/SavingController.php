<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingController extends Controller
{
    /**
     * Create a new savings goal.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'nullable|numeric|min:0',
            'color' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $data['current_amount'] = $data['current_amount'] ?? 0;

        Saving::create($data);

        return redirect()->back()->with('success', 'Savings goal created successfully!');
    }

    /**
     * Add money to an existing goal.
     */
    public function contribute(Request $request)
    {
        $request->validate([
            'saving_id' => 'required|exists:savings,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $saving = Saving::where('user_id', Auth::id())->findOrFail($request->saving_id);
        $saving->current_amount += $request->amount;
        $saving->save();

        return redirect()->back()->with('success', 'Savings updated successfully!');
    }

    /**
     * Update the savings goal details.
     * Note: Variable $saving matches {saving} in web.php
     */
    public function update(Request $request, Saving $saving)
    {
        // Security Check: Ensure the user owns this goal
        if ($saving->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'required|numeric|min:0',
            'color' => 'required|string',
        ]);

        $saving->update($request->all());

        return redirect()->back()->with('success', 'Goal updated successfully!');
    }

    /**
     * Delete a savings goal.
     * Note: Variable $saving matches {saving} in web.php
     */
    public function destroy(Saving $saving)
    {
        // Security Check: Ensure the user owns this goal
        if ($saving->user_id !== Auth::id()) {
            abort(403);
        }

        $saving->delete();

        return redirect()->back()->with('success', 'Goal deleted successfully!');
    }
}