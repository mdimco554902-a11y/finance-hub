@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .header h2 { font-size: 32px; color: #1E293B; }
    .header p { color: #64748B; font-size: 18px; margin-top: 4px; }
    .btn-add { background: #2563EB; color: white; padding: 14px 24px; border-radius: 16px; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; }
    .btn-add:hover { background: #1d4ed8; }

    /* Summary Grid */
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px; }
    .stat-card { background: white; padding: 24px; border-radius: 24px; border: 1px solid #F1F5F9; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .stat-card .label { color: #94A3B8; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
    .stat-card .value { font-size: 24px; font-weight: 800; color: #1E293B; }
    .stat-card .trend { font-size: 12px; font-weight: 700; margin-top: 12px; }
    .trend.up { color: #10B981; }
    .trend.down { color: #F43F5E; }

    /* Budget & Savings Grid Layout */
    .budget-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .budget-card { background: white; padding: 32px; border-radius: 24px; border: 1px solid #F1F5F9; position: relative; }
    .budget-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .progress-container { background: #F1F5F9; height: 12px; border-radius: 10px; margin: 20px 0; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 10px; transition: 0.5s ease-in-out; }

    /* Modern List Styling */
    .card-section { background: white; padding: 32px; border-radius: 24px; border: 1px solid #F1F5F9; margin-bottom: 40px; }
    .transaction-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #F8FAFC; }
    .transaction-item:last-child { border-bottom: none; }
    .icon-box { width: 48px; height: 48px; background: #F1F5F9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    
    /* Search Bar Styling */
    .search-container { display: flex; gap: 12px; margin-bottom: 32px; background: #F8FAFC; padding: 20px; border-radius: 20px; }
    .search-input { flex: 1; padding: 14px 20px; border-radius: 14px; border: 1px solid #E2E8F0; outline: none; font-size: 15px; }
    .btn-search { background: #1E293B; color: white; border: none; padding: 0 24px; border-radius: 14px; font-weight: bold; cursor: pointer; }

    /* Table Section */
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; color: #94A3B8; font-size: 12px; text-transform: uppercase; padding: 12px; border-bottom: 1px solid #F1F5F9; }
    td { padding: 16px 12px; border-bottom: 1px solid #F8FAFC; }
    .amount-income { color: #10B981; font-weight: 800; }
    .amount-expense { color: #F43F5E; font-weight: 800; }

    /* MODAL STYLES */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .modal-content { background: white; padding: 32px; border-radius: 24px; width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .modal-content h3 { margin-bottom: 20px; color: #1E293B; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #64748B; margin-bottom: 5px; }
    .form-input { width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; }
    .btn-save { width: 100%; background: #2563EB; color: white; padding: 12px; border-radius: 12px; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; }
    
    .btn-contribute { background: #F1F5F9; color: #2563EB; border: none; padding: 8px 12px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .btn-contribute:hover { background: #E2E8F0; }

    /* Icon Buttons (Edit/Delete) */
    .action-icons { display: flex; gap: 8px; }
    .btn-icon { background: none; border: none; cursor: pointer; padding: 4px; color: #94A3B8; transition: 0.2s; }
    .btn-icon:hover { color: #1E293B; }
    .btn-icon.delete:hover { color: #F43F5E; }

    .settings-container { max-width: 800px; }
    .settings-card { background: white; padding: 32px; border-radius: 24px; border: 1px solid #F1F5F9; margin-bottom: 24px; }
    .settings-title { font-size: 20px; font-weight: 700; color: #1E293B; margin-bottom: 24px; display: block; }
    .settings-row { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #F8FAFC; }
    .settings-row:last-child { border-bottom: none; }
    .label-text { font-size: 14px; font-weight: 700; color: #1E293B; margin-bottom: 4px; }
    .value-text { font-size: 14px; color: #64748B; font-weight: 500; }
    .btn-action { padding: 8px 16px; border-radius: 10px; border: 1px solid #E2E8F0; background: white; font-size: 13px; font-weight: 600; color: #1E293B; cursor: pointer; transition: 0.2s; }
    .btn-action:hover { background: #F8FAFC; border-color: #CBD5E1; }
</style>

<div class="header">
    @if(request('view') == 'transactions')
        <div>
            <h2>Transactions</h2>
            <p>View and manage all your financial records.</p>
        </div>
        <button class="btn-add" onclick="toggleModal(true)">Add Transaction</button>
    @elseif(request('view') == 'budgets')
        <div>
            <h2>Budgets</h2>
            <p>Track your spending limits and progress.</p>
        </div>
        <button class="btn-add" onclick="toggleBudgetModal(true)">+ Add Budget</button>
    @elseif(request('view') == 'savings')
        <div>
            <h2>Savings Goals</h2>
            <p>Track your progress toward big purchases.</p>
        </div>
        <button class="btn-add" onclick="toggleSavingsModal(true)">+ New Goal</button>
    @elseif(request('view') == 'settings')
        <div>
            <h2>Settings</h2>
            <p>Manage your account preferences and profile.</p>
        </div>
    @else
        <div>
            <h2>Dashboard</h2>
            <p>Welcome back! Here's your financial overview.</p>
        </div>
        <button class="btn-add" onclick="toggleModal(true)">+ Quick Add Transaction</button>
    @endif
</div>

{{-- Transaction Modal --}}
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>New Transaction</h3>
            <button onclick="toggleModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. Starbucks Coffee" required>
            </div>
            <div class="form-group">
                <label>Amount (₱)</label>
                <input type="number" name="amount" class="form-input" step="0.01" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" class="form-input">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <button type="submit" class="btn-save">Save Transaction</button>
        </form>
    </div>
</div>

{{-- Budget Modal --}}
<div id="addBudgetModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>New Budget</h3>
            <button onclick="toggleBudgetModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('budgets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category" class="form-input" placeholder="e.g. McDonald's, Groceries" required>
            </div>
            <div class="form-group">
                <label>Limit Amount (₱)</label>
                <input type="number" name="limit_amount" class="form-input" step="0.01" placeholder="1000.00" required>
            </div>
            <div class="form-group">
                <label>Theme Color</label>
                <input type="color" name="color" class="form-input" style="height: 45px; padding: 5px;" value="#2563EB">
            </div>
            <button type="submit" class="btn-save">Create Budget</button>
        </form>
    </div>
</div>

{{-- Edit Budget Modal --}}
<div id="editBudgetModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>Edit Budget</h3>
            <button onclick="toggleEditBudgetModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form id="editBudgetForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category" id="edit_budget_category" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Limit Amount (₱)</label>
                <input type="number" name="limit_amount" id="edit_budget_limit" class="form-input" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Theme Color</label>
                <input type="color" name="color" id="edit_budget_color" class="form-input" style="height: 45px; padding: 5px;">
            </div>
            <button type="submit" class="btn-save">Update Budget</button>
        </form>
    </div>
</div>

{{-- Savings Modal --}}
<div id="addSavingsModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>New Savings Goal</h3>
            <button onclick="toggleSavingsModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('savings.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Goal Name</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. New Laptop, Travel Fund" required>
            </div>
            <div class="form-group">
                <label>Target Amount (₱)</label>
                <input type="number" name="target_amount" class="form-input" step="0.01" placeholder="50000.00" required>
            </div>
            <div class="form-group">
                <label>Initial Savings (₱)</label>
                <input type="number" name="current_amount" class="form-input" step="0.01" value="0.00">
            </div>
            <div class="form-group">
                <label>Goal Color</label>
                <input type="color" name="color" class="form-input" style="height: 45px; padding: 5px;" value="#10B981">
            </div>
            <button type="submit" class="btn-save">Set Goal</button>
        </form>
    </div>
</div>

{{-- Edit Savings Modal --}}
<div id="editSavingsModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>Edit Savings Goal</h3>
            <button onclick="toggleEditSavingsModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form id="editSavingsForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Goal Name</label>
                <input type="text" name="title" id="edit_saving_title" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Target Amount (₱)</label>
                <input type="number" name="target_amount" id="edit_saving_target" class="form-input" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Current Savings (₱)</label>
                <input type="number" name="current_amount" id="edit_saving_current" class="form-input" step="0.01">
            </div>
            <div class="form-group">
                <label>Goal Color</label>
                <input type="color" name="color" id="edit_saving_color" class="form-input" style="height: 45px; padding: 5px;">
            </div>
            <button type="submit" class="btn-save">Update Goal</button>
        </form>
    </div>
</div>

{{-- Contribution Modal --}}
<div id="contributionModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>Add Savings</h3>
            <button onclick="toggleContributionModal(false)" style="border:none; background:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('savings.contribute') }}" method="POST">
            @csrf
            <input type="hidden" name="saving_id" id="contribution_saving_id">
            <div class="form-group">
                <label>Adding to: <span id="contribution_goal_name" style="color:#2563EB"></span></label>
            </div>
            <div class="form-group">
                <label>Amount to Add (₱)</label>
                <input type="number" name="amount" class="form-input" step="0.01" placeholder="0.00" required autofocus>
            </div>
            <button type="submit" class="btn-save">Confirm Contribution</button>
        </form>
    </div>
</div>

@if(request('view') == 'transactions')
    <div class="card-section">
        <form action="/" method="GET" class="search-container">
            <input type="hidden" name="view" value="transactions">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="search-input" placeholder="Search by description (e.g. Rent, Groceries)...">
            <button type="submit" class="btn-search">Search</button>
            @if($search)
                <a href="/?view=transactions" style="text-decoration:none; padding:14px; color:#64748B; font-weight:bold;">Clear</a>
            @endif
        </form>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Type</th>
                    <th style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td style="font-weight:600; color:#1E293B">{{ $t->title }}</td>
                    <td style="text-transform:capitalize; font-size:14px; color:#64748B">{{ $t->type }}</td>
                    <td style="text-align:right" class="{{ $t->type == 'income' ? 'amount-income' : 'amount-expense' }}">
                        {{ $t->type == 'income' ? '+' : '-' }}₱{{ number_format($t->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding:40px; color:#94A3B8;">No transactions found matching your search.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@elseif(request('view') == 'budgets') 
    <div class="budget-grid"> 
        @forelse($budgets as $b) 
        <div class="budget-card"> 
            <div style="position: absolute; top: 15px; right: 15px;" class="action-icons">
                <button class="btn-icon" onclick="openEditBudgetModal({ id: {{ $b->id }}, category: '{{ addslashes($b->category) }}', limit_amount: {{ $b->limit_amount }}, color: '{{ $b->color }}' })">
                    <i data-lucide="edit-3" style="width:16px;"></i>
                </button>
                <button type="button" class="btn-icon delete" onclick="confirmDelete('budgets', {{ $b->id }})">
                    <i data-lucide="trash-2" style="width:16px;"></i>
                </button>
                <form id="delete-form-budgets-{{ $b->id }}" action="{{ route('budgets.destroy', $b->id) }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>

            <div class="budget-info"> 
                <span style="font-size:20px; font-weight:700; color:#1E293B;">{{ $b->category }}</span> 
                <span style="font-weight:600; color:#64748B;">₱{{ number_format($b->used) }} / ₱{{ number_format($b->limit_amount) }}</span> 
            </div> 
            <div class="progress-container"> 
                <div class="progress-bar" style="width: {{ $b->percent }}%; background: {{ $b->color }};"></div> 
            </div> 
            <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:600;"> 
                <span style="color:#94A3B8;">{{ round($b->percent) }}% used</span> 
                <span style="color: {{ $b->remaining < 0 ? '#F43F5E' : '#10B981' }}"> 
                    ₱{{ number_format(abs($b->remaining)) }} {{ $b->remaining < 0 ? 'over' : 'remaining' }} 
                </span> 
            </div> 
        </div> 
        @empty 
        <div class="stat-card" style="grid-column: span 2; text-align:center; padding:50px;"> 
            <p style="color:#94A3B8;">No budgets found. Add one to start tracking!</p> 
        </div> 
        @endforelse 
    </div> 

@elseif(request('view') == 'savings')
    <div class="budget-grid">
        @forelse($savings as $s)
        <div class="budget-card">
            <div style="position: absolute; top: 15px; right: 15px;" class="action-icons">
                <button class="btn-icon" onclick="openEditSavingsModal({ id: {{ $s->id }}, title: '{{ addslashes($s->title) }}', target_amount: {{ $s->target_amount }}, current_amount: {{ $s->current_amount }}, color: '{{ $s->color }}' })">
                    <i data-lucide="edit-3" style="width:16px;"></i>
                </button>
                <button type="button" class="btn-icon delete" onclick="confirmDelete('savings', {{ $s->id }})">
                    <i data-lucide="trash-2" style="width:16px;"></i>
                </button>
                <form id="delete-form-savings-{{ $s->id }}" action="{{ route('savings.destroy', $s->id) }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>

            <div class="budget-info">
                <div>
                    <span style="font-size:20px; font-weight:700; color:#1E293B;">{{ $s->title }}</span>
                    <br>
                    <button class="btn-contribute" onclick="openContributionModal({{ $s->id }}, '{{ addslashes($s->title) }}')">
                        + Add Money
                    </button>
                </div>
                <span style="font-weight:600; color:#64748B;">₱{{ number_format($s->current_amount) }} / ₱{{ number_format($s->target_amount) }}</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: {{ $s->percentage }}%; background: {{ $s->color }};"></div>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:600;">
                <span style="color:#94A3B8;">{{ $s->percentage }}% completed</span>
                <span style="color:#2563EB;">₱{{ number_format($s->target_amount - $s->current_amount) }} left to save</span>
            </div>
        </div>
        @empty
        <div class="stat-card" style="grid-column: span 2; text-align:center; padding:50px;">
            <p style="color:#94A3B8;">No savings goals yet. Start saving for your future!</p>
        </div>
        @endforelse
    </div>

@elseif(request('view') == 'settings')
    <div class="settings-container">
        <div class="settings-card">
            <span class="settings-title">Profile Settings</span>
            <div class="settings-row">
                <div>
                    <p class="label-text">Full Name</p>
                    <p class="value-text">{{ auth()->user()->name ?? 'Admin User' }}</p>
                </div>
                <button class="btn-action" onclick="toggleSettingsModal('profileModal', true)">Edit Name</button>
            </div>
            <div class="settings-row">
                <div>
                    <p class="label-text">Email Address</p>
                    <p class="value-text">{{ auth()->user()->email ?? 'admin@test.com' }}</p>
                </div>
                <button class="btn-action" onclick="toggleSettingsModal('profileModal', true)">Change Email</button>
            </div>
        </div>

        <div id="profileModal" class="modal-overlay">
            <div class="modal-content">
                <div style="display:flex; justify-content:space-between; margin-bottom: 20px;">
                    <h3 style="margin:0; font-weight:800;">Update Profile</h3>
                    <button onclick="toggleSettingsModal('profileModal', false)" style="border:none; background:none; font-size:24px; cursor:pointer; color:#94A3B8;">&times;</button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom:15px;">
        <label class="label-text">Full Name</label>
        <input type="text" name="name" class="form-input" value="{{ auth()->user()->name }}" required>
    </div>
    
    <div style="margin-bottom:15px;">
        <label class="label-text">Email Address</label>
        <input type="email" name="email" class="form-input" value="{{ auth()->user()->email }}" required>
    </div>
    
    <button type="submit" class="btn-save">Save Changes</button>
</form>
            </div>
        </div>
    </div>

@else
    <div style="display: flex; flex-direction: column; gap: 32px;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            <div class="stat-card" style="border-left: 4px solid #10B981;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="label">Total Income</div>
                        <div class="value">₱{{ number_format($income, 2) }}</div>
                    </div>
                    <div style="font-size: 24px; background: #ECFDF5; padding: 10px; border-radius: 12px;">
                        <i data-lucide="trending-up" style="color: #10B981"></i>
                    </div>
                </div>
                <div class="trend {{ $incomeChange >= 0 ? 'up' : 'down' }}" style="margin-top: 12px;">
                    {{ $incomeChange >= 0 ? '+' : '' }}{{ number_format($incomeChange, 1) }}% vs last month
                </div>
            </div>

            <div class="stat-card" style="border-left: 4px solid #F43F5E;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="label">Total Expenses</div>
                        <div class="value">₱{{ number_format($expense, 2) }}</div>
                    </div>
                    <div style="font-size: 24px; background: #FFF1F2; padding: 10px; border-radius: 12px;">
                        <i data-lucide="trending-down" style="color: #F43F5E"></i>
                    </div>
                </div>
                <div class="trend {{ $expenseChange <= 0 ? 'up' : 'down' }}" style="margin-top: 12px;">
                    {{ $expenseChange >= 0 ? '+' : '' }}{{ number_format($expenseChange, 1) }}% vs last month
                </div>
            </div>

            <div class="stat-card" style="border-left: 4px solid #2563EB; background: #F8FAFC;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="label" style="color: #2563EB;">Net Savings</div>
                        <div class="value" style="color: #2563EB;">₱{{ number_format($balance, 2) }}</div>
                    </div>
                    <div style="font-size: 24px; background: #EFF6FF; padding: 10px; border-radius: 12px;">
                        <i data-lucide="wallet" style="color: #2563EB"></i>
                    </div>
                </div>
                <div class="trend up" style="color: #3B82F6; margin-top: 12px;">Real-time balance</div>
            </div>
        </div>

        <div class="card-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="color: #1E293B; font-weight: 800; font-size: 20px;">Recent Activity</h3>
                <a href="/?view=transactions" style="color: #2563EB; font-weight: 700; text-decoration: none; font-size: 14px;">View Statement →</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($transactions->take(5) as $t)
                <div class="transaction-item" style="background: #F8FAFC; padding: 16px; border-radius: 16px; border: 1px solid transparent; transition: 0.2s;" onmouseover="this.style.borderColor='#E2E8F0'" onmouseout="this.style.borderColor='transparent'">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div class="icon-box" style="background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            @if($t->type == 'income')
                                <i data-lucide="arrow-down-left" style="color: #10B981; width: 20px;"></i>
                            @else
                                <i data-lucide="arrow-up-right" style="color: #F43F5E; width: 20px;"></i>
                            @endif
                        </div>
                        <div>
                            <p style="font-weight: 700; color: #1E293B; margin: 0;">{{ $t->title }}</p>
                            <p style="font-size: 12px; color: #94A3B8; margin: 0;">{{ $t->created_at->format('M d, Y • h:i A') }}</p>
                        </div>
                    </div>
                    <p style="font-weight: 800; font-size: 16px; color: {{ $t->type == 'income' ? '#10B981' : '#1E293B' }}; margin:0;">
                        {{ $t->type == 'income' ? '+' : '-' }}₱{{ number_format($t->amount, 2) }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<script>
    function toggleModal(show) {
        document.getElementById('addModal').style.display = show ? 'flex' : 'none';
    }

    function toggleBudgetModal(show) {
        document.getElementById('addBudgetModal').style.display = show ? 'flex' : 'none';
    }

    function toggleSavingsModal(show) {
        document.getElementById('addSavingsModal').style.display = show ? 'flex' : 'none';
    }

    function toggleSettingsModal(id, show) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = show ? 'flex' : 'none';
        }
    }

    function toggleContributionModal(show) {
        document.getElementById('contributionModal').style.display = show ? 'flex' : 'none';
    }

    function openContributionModal(id, title) {
        document.getElementById('contribution_saving_id').value = id;
        document.getElementById('contribution_goal_name').innerText = title;
        toggleContributionModal(true);
    }

    function toggleEditSavingsModal(show) {
        document.getElementById('editSavingsModal').style.display = show ? 'flex' : 'none';
    }

    function openEditSavingsModal(saving) {
    // This MUST match the route: /savings/{saving}
    document.getElementById('editSavingsForm').action = "/savings/" + saving.id;
    document.getElementById('edit_saving_title').value = saving.title;
    document.getElementById('edit_saving_target').value = saving.target_amount;
    document.getElementById('edit_saving_current').value = saving.current_amount;
    document.getElementById('edit_saving_color').value = saving.color;
    toggleEditSavingsModal(true);
}

    function toggleEditBudgetModal(show) {
        document.getElementById('editBudgetModal').style.display = show ? 'flex' : 'none';
    }

    function openEditBudgetModal(budget) {
    // This MUST match the route: /budgets/{budget}
    document.getElementById('editBudgetForm').action = "/budgets/" + budget.id;
    document.getElementById('edit_budget_category').value = budget.category;
    document.getElementById('edit_budget_limit').value = budget.limit_amount;
    document.getElementById('edit_budget_color').value = budget.color;
    toggleEditBudgetModal(true);
}

    // Modern Delete Confirmation
    function confirmDelete(type, id) {
        Swal.fire({
            title: 'Delete this ' + (type === 'budgets' ? 'budget' : 'goal') + '?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F43F5E',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + type + '-' + id).submit();
            }
        })
    }

    window.onclick = function(event) {
        const modals = ['addModal', 'profileModal', 'addBudgetModal', 'addSavingsModal', 'contributionModal', 'editSavingsModal', 'editBudgetModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && event.target == modal) {
                modal.style.display = 'none';
            }
        });
    }

    if(window.lucide) {
        lucide.createIcons();
    }
</script>
@endsection