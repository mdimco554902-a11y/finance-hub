<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Store a new budget.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'limit_amount' => 'required|numeric|min:1',
            'color' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        Budget::create($data);

        return redirect()->back()->with('success', 'Budget created successfully!');
    }

    /**
     * Update an existing budget.
     * Note: Variable $budget matches {budget} in web.php
     */
    public function update(Request $request, Budget $budget)
    {
        // Security Check: Ensure the user owns this budget
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category' => 'required|string|max:255',
            'limit_amount' => 'required|numeric|min:1',
            'color' => 'required|string',
        ]);

        $budget->update([
            'category' => $request->category,
            'limit_amount' => $request->limit_amount,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Budget updated successfully!');
    }

    /**
     * Remove a budget.
     * Note: Variable $budget matches {budget} in web.php
     */
    public function destroy(Budget $budget)
    {
        // Security Check: Ensure the user owns this budget
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()->back()->with('success', 'Budget deleted successfully!');
    }
}