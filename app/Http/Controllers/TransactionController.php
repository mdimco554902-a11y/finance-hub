<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Saving; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        // 1. Handle Search - Limited to current user
        $query = Transaction::where('user_id', $userId);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // 2. Get Transactions (paginated) and recent activity
        $perPage = 2;
        $transactions = $query->latest()->paginate($perPage)->withQueryString();
        $recentTransactions = Cache::remember(
            'finance.recent_transactions.' . $userId,
            now()->addMinutes(2),
            function () use ($userId) {
                return Transaction::where('user_id', $userId)->latest()->take(5)->get();
            }
        );

        // 3. Calculate All-time totals
        $summary = Cache::remember(
            'finance.summary.' . $userId,
            now()->addMinutes(2),
            function () use ($userId) {
                $now = now();
                $lastMonth = now()->subMonth();

                return [
                    'income' => (float) Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount'),
                    'expense' => (float) Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount'),
                    'this_month_income' => (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum('amount'),
                    'this_month_expense' => (float) Transaction::where('user_id', $userId)->where('type', 'expense')->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum('amount'),
                    'last_month_income' => (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->sum('amount'),
                    'last_month_expense' => (float) Transaction::where('user_id', $userId)->where('type', 'expense')->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->sum('amount'),
                ];
            }
        );

        $income = $summary['income'];
        $expense = $summary['expense'];
        $balance = $income - $expense;

        // 4. LIVE PERCENTAGE LOGIC
        $thisMonthIncome = $summary['this_month_income'];
        $thisMonthExpense = $summary['this_month_expense'];
        $lastMonthIncome = $summary['last_month_income'];
        $lastMonthExpense = $summary['last_month_expense'];

        $incomeChange = $lastMonthIncome > 0 ? (($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : ($thisMonthIncome > 0 ? 100 : 0);
        $expenseChange = $lastMonthExpense > 0 ? (($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : ($thisMonthExpense > 0 ? 100 : 0);

        // 5. Fetch Budgets & Categories for the Datalist
        // Categories come from all budgets (not only the current page)
        $categories = Budget::where('user_id', $userId)->pluck('category')->unique();

        $expenseByCategory = Cache::remember(
            'finance.expense_by_category.' . $userId,
            now()->addMinutes(2),
            function () use ($userId) {
                return Transaction::query()
                    ->where('user_id', $userId)
                    ->where('type', 'expense')
                    ->selectRaw('title, COALESCE(SUM(amount), 0) as total')
                    ->groupBy('title')
                    ->pluck('total', 'title');
            }
        );

        // Paginate budgets and compute used/remaining/percent on the current page collection
        $budgetsPerPage = 6;
        $budgetsQuery = Budget::where('user_id', $userId)->orderBy('id', 'asc');
        $budgets = $budgetsQuery->paginate($budgetsPerPage)->withQueryString();
        $budgets->getCollection()->transform(function($budget) use ($expenseByCategory) {
            $used = (float) ($expenseByCategory[$budget->category] ?? 0);

            $budget->used = $used;
            $budget->remaining = $budget->limit_amount - $used;
            $budget->percent = $budget->limit_amount > 0 ? ($used / $budget->limit_amount) * 100 : 0;
            return $budget;
        });

        // 6. Fetch Savings goals (paginated)
        $savingsPerPage = 6;
        $savingsQuery = Saving::where('user_id', $userId)->orderBy('id', 'asc');
        $savings = $savingsQuery->paginate($savingsPerPage)->withQueryString();
        $savings->getCollection()->transform(function($goal) {
            $goal->percentage = $goal->target_amount > 0 ? round(($goal->current_amount / $goal->target_amount) * 100) : 0;
            return $goal;
        });

        // 7. Return the view with all required variables
        return view('finance.index', compact(
            'transactions',
            'recentTransactions',
            'income',
            'expense',
            'balance',
            'search',
            'budgets',
            'categories',
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

        $data['user_id'] = Auth::id();

        Transaction::create($data);
        $this->clearFinanceCache(Auth::id());

        return back()->with('success', 'Transaction saved successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();
        $this->clearFinanceCache(Auth::id());

        return back()->with('success', 'Transaction deleted.');
    }

    private function clearFinanceCache(int $userId): void
    {
        Cache::forget('finance.summary.' . $userId);
        Cache::forget('finance.recent_transactions.' . $userId);
        Cache::forget('finance.expense_by_category.' . $userId);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

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

        if (! $user instanceof User) {
            abort(403);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password successfully updated!');
    }
}