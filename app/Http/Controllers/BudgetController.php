<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function store(Request $request)
    {
        // Validates the data from your modal
        $request->validate([
            'category' => 'required|string|max:255',
            'limit_amount' => 'required|numeric',
            'color' => 'required|string',
        ]);

        // Saves the "McDonald's" budget to the database
        Budget::create([
            'category' => $request->category,
            'limit_amount' => $request->limit_amount,
            'color' => $request->color,
            'used' => 0, // Starts at zero used
        ]);

        // Redirects back to the budget view instead of showing a 404
        return redirect('/?view=budgets');
    }
}