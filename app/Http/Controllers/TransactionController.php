<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Handle Search
        $search = $request->input('search');
        $query = Transaction::query();
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // 2. Get Transactions
        $transactions = $query->latest()->get();

        // 3. Calculate All-time totals
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // 4. LIVE PERCENTAGE LOGIC (Current Month vs Last Month)
        $thisMonthIncome = Transaction::where('type', 'income')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $thisMonthExpense = Transaction::where('type', 'expense')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');

        $lastMonthIncome = Transaction::where('type', 'income')->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');
        $lastMonthExpense = Transaction::where('type', 'expense')->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');

        $incomeChange = $lastMonthIncome > 0 ? (($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : ($thisMonthIncome > 0 ? 100 : 0);
        $expenseChange = $lastMonthExpense > 0 ? (($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : ($thisMonthExpense > 0 ? 100 : 0);

        // 5. Fetch Budgets and calculate real-time progress
        $budgets = Budget::all()->map(function($budget) {
            $used = Transaction::where('type', 'expense')
                ->where('title', 'like', '%' . $budget->category . '%')
                ->sum('amount');
            $budget->used = $used;
            $budget->remaining = $budget->limit_amount - $used;
            $budget->percent = $budget->limit_amount > 0 ? ($used / $budget->limit_amount) * 100 : 0;
            return $budget;
        });

        // 6. Return the view with only the required data (Savings removed)
        return view('finance.index', compact(
            'transactions', 
            'income', 
            'expense', 
            'balance', 
            'search', 
            'budgets',
            'incomeChange',
            'expenseChange'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
        ]);
        Transaction::create($data);
        return back()->with('success', 'Transaction saved successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Transaction deleted.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->update($data);
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password successfully updated in the database!');
    }
}
