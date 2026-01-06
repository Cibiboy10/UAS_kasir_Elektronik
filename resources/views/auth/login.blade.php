<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS System</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --text: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --surface: #ffffff;
        }

        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); 
            margin: 0; 
            color: var(--text);
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: var(--primary);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .brand-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .brand-header p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.875rem;
        }

        .login-card { 
            background: var(--surface); 
            padding: 2.5rem; 
            border-radius: 1rem; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
            border: 1px solid white;
        }

        .form-group { margin-bottom: 1.25rem; }
        
        .form-group label { 
            display: block; 
            margin-bottom: 0.5rem; 
            color: #374151; 
            font-size: 0.875rem; 
            font-weight: 500; 
        }

        .form-group input { 
            width: 100%; 
            padding: 0.75rem 1rem; 
            border: 1px solid var(--border); 
            border-radius: 0.5rem; 
            box-sizing: border-box; 
            font-size: 1rem; 
            transition: all 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .btn { 
            width: 100%; 
            padding: 0.75rem; 
            background-color: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 0.5rem; 
            cursor: pointer; 
            font-size: 0.875rem; 
            font-weight: 600; 
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }

        .btn:hover { 
            background-color: var(--primary-hover); 
        }

        .error { 
            color: #dc2626; 
            font-size: 0.875rem; 
            margin-top: 0.375rem; 
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .demo-credentials {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .demo-credentials strong { color: var(--text); }
        .demo-row { display: flex; justify-content: space-between; margin-bottom: 0.25rem; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-header">
            <div class="brand-logo">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1>Tronik</h1>
            <p>Sign in to your account</p>
        </div>

        <div class="login-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    @error('email')
                        <div class="error">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>

            <div class="demo-credentials">
                <div style="text-align: center; margin-bottom: 0.5rem; font-weight: 600;">Demo Accounts</div>
                <div class="demo-row">
                    <span>Admin:</span>
                    <strong>admin@example.com / password</strong>
                </div>
                <div class="demo-row" style="margin-bottom: 0;">
                    <span>Kasir:</span>
                    <strong>kasir@example.com / password</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
