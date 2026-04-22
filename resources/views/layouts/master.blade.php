<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceHub</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; background-color: #F8FAFC; min-height: 100vh; }
        
        /* Sidebar Styles */
        .sidebar { width: 280px; background: white; border-right: 1px solid #E2E8F0; position: fixed; height: 100vh; padding: 32px; display: flex; flex-direction: column; z-index: 100; }
        .logo-area { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
        
        /* UPDATED LOGO ICON: Extra Bold to match Login Page */
        .logo-icon { 
            background: #2563EB; 
            color: white; 
            padding: 8px 12px; 
            border-radius: 12px; 
            font-weight: 900; /* Extra bold */
            font-size: 20px; 
            letter-spacing: -1px; /* Tighter look for professional logo feel */
            text-transform: uppercase;
        }
        .logo-text { font-size: 20px; font-weight: 700; color: #1E293B; }
        
        .nav-menu { flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: #64748B; font-weight: 600; border-radius: 12px; margin-bottom: 8px; transition: 0.3s; }
        
        /* Sidebar Icon Sizing */
        .nav-item i { width: 20px; height: 20px; stroke-width: 2.5px; }

        /* Active State Logic */
        .nav-item.active { background: #2563EB; color: white !important; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .nav-item:hover:not(.active) { background: #F1F5F9; color: #1E293B; }

        /* Main Content Area */
        .main-content { margin-left: 280px; padding: 48px; width: calc(100% - 280px); }

        /* User Profile Section */
        .user-section { margin-top: auto; padding-top: 20px; border-top: 1px solid #F1F5F9; }
        .user-info { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .user-avatar { width: 40px; height: 40px; background: #1E293B; color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .user-details { overflow: hidden; }
        .user-name { display: block; font-size: 14px; font-weight: 700; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-email { display: block; font-size: 12px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        .logout-btn { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #FEE2E2; background: white; color: #EF4444; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .logout-btn:hover { background: #FEF2F2; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-area">
            <div class="logo-icon">FH</div>
            <div class="logo-text">FinanceHub</div>
        </div>

        <nav class="nav-menu">
            <a href="/?view=dashboard" class="nav-item {{ request('view', 'dashboard') == 'dashboard' ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>

            <a href="/?view=transactions" class="nav-item {{ request('view') == 'transactions' ? 'active' : '' }}">
                <i data-lucide="arrow-right-left"></i> Transactions
            </a>

            <a href="/?view=budgets" class="nav-item {{ request('view') == 'budgets' ? 'active' : '' }}">
                <i data-lucide="target"></i> Budgets
            </a>

            <a href="/?view=settings" class="nav-item {{ request('view') == 'settings' ? 'active' : '' }}">
                <i data-lucide="settings"></i> Settings
            </a>
        </nav>

        <div class="user-section">
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name ?? 'Admin User' }}</span>
                    <span class="user-email">{{ auth()->user()->email ?? 'admin@test.com' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
      lucide.createIcons();
    </script>
</body>
</html>