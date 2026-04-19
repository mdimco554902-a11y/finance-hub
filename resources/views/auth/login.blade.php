<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FinanceHub</title>
    <style>
        /* Modern Vanilla CSS Reset & Styling */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        
        body { 
            background-color: #F8FAFC; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);
        }

        .login-card {
            background: white;
            padding: 48px;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            background: #2563EB;
            color: white;
            padding: 12px 18px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 24px;
            margin-bottom: 12px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #1E293B;
            letter-spacing: -0.5px;
        }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            outline: none;
            font-size: 16px;
            transition: 0.2s;
            background: #F8FAFC;
        }

        input:focus {
            border-color: #2563EB;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            width: 100%;
            background: #2563EB;
            color: white;
            padding: 16px;
            border-radius: 12px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #1D4ED8;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .error-msg {
            background: #FEF2F2;
            color: #EF4444;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-area">
            <div class="logo-icon">FH</div>
            <div class="logo-text">FinanceHub</div>
        </div>

        <!-- Error Handling -->
        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@company.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Sign In to Dashboard</button>
        </form>

        <p style="text-align: center; color: #94A3B8; font-size: 13px; margin-top: 24px;">
            Secure Finance Tracking System
        </p>
    </div>

</body>
</html>
