<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Saving; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Handle Search - Limited to current user
        $search = $request->input('search');
        $query = Transaction::where('user_id', $userId);
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // 2. Get Transactions
        $transactions = $query->latest()->get();

        // 3. Calculate All-time totals - Limited to current user
        $income = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $expense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // 4. LIVE PERCENTAGE LOGIC - Limited to current user
        $thisMonthIncome = Transaction::where('user_id', $userId)->where('type', 'income')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $thisMonthExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');

        $lastMonthIncome = Transaction::where('user_id', $userId)->where('type', 'income')->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');
        $lastMonthExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');

        $incomeChange = $lastMonthIncome > 0 ? (($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : ($thisMonthIncome > 0 ? 100 : 0);
        $expenseChange = $lastMonthExpense > 0 ? (($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : ($thisMonthExpense > 0 ? 100 : 0);

        // 5. Fetch Budgets - Limited to current user
        $budgets = Budget::where('user_id', $userId)->get()->map(function($budget) use ($userId) {
            $used = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->where('title', 'like', '%' . $budget->category . '%')
                ->sum('amount');
            $budget->used = $used;
            $budget->remaining = $budget->limit_amount - $used;
            $budget->percent = $budget->limit_amount > 0 ? ($used / $budget->limit_amount) * 100 : 0;
            return $budget;
        });

        // 6. Fetch Savings goals - Limited to current user
        $savings = Saving::where('user_id', $userId)->get()->map(function($goal) {
            $goal->percentage = $goal->target_amount > 0 ? round(($goal->current_amount / $goal->target_amount) * 100) : 0;
            return $goal;
        });

        // 7. Return the view
        return view('finance.index', compact(
            'transactions', 
            'income', 
            'expense', 
            'balance', 
            'search', 
            'budgets',
            'incomeChange',
            'expenseChange',
            'savings'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
        ]);

        // FIXED: Added user_id to prevent the SQL "default value" error
        $data['user_id'] = Auth::id();

        Transaction::create($data);
        return back()->with('success', 'Transaction saved successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        // Security Check: Ensure user owns transaction before deleting
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();
        return back()->with('success', 'Transaction deleted.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
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
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password successfully updated!');
    }
}