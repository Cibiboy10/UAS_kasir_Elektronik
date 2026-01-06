<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'POS System') }}</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
            --background: #f3f4f6;
            --surface: #ffffff;
            --text: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: var(--background); color: var(--text); margin: 0; line-height: 1.5; }
        
        /* Navigation */
        nav { background: var(--surface); box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 0.75rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50; }
        .nav-brand { font-weight: 800; font-size: 1.25rem; color: var(--primary); text-decoration: none; margin-right: 2rem; display: flex; align-items: center; gap: 0.5rem; }
        .nav-links { display: flex; gap: 1.5rem; }
        .nav-links a { color: var(--text-muted); text-decoration: none; font-weight: 500; padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); background: #eef2ff; }
        .nav-right { display: flex; align-items: center; gap: 1rem; }
        .user-pill { background: #f3f4f6; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; color: var(--text); }
        
        /* Layout */
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        
        /* Cards */
        .card { background: var(--surface); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s; border: 1px solid transparent; }
        .card:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .card h2, .card h3 { margin-top: 0; color: var(--text); }
        
        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; gap: 0.5rem; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: var(--success); color: white; }
        
        /* Tables */
        .table-container { overflow-x: auto; border-radius: 0.75rem; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; background: var(--surface); }
        th { background: #f9fafb; font-weight: 600; text-align: left; color: var(--text-muted); padding: 0.75rem 1.5rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        td { padding: 1rem 1.5rem; border-top: 1px solid var(--border); color: var(--text); }
        tr:hover td { background: #f9fafb; }
        
        /* Forms */
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--text); }
        input, select { width: 100%; padding: 0.625rem; border: 1px solid var(--border); border-radius: 0.5rem; box-sizing: border-box; font-size: 1rem; transition: border-color 0.15s, box-shadow 0.15s; }
        input:focus, select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        
        /* Utilities */
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .mb-4 { margin-bottom: 1rem; }
        .text-sm { font-size: 0.875rem; }
        .text-muted { color: var(--text-muted); }
        .font-bold { font-weight: 700; }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        /* Dashboard Stats Grid specifics */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; right: -10px; top: -10px; width: 60px; height: 60px; border-radius: 50%; opacity: 0.1; background: currentColor; }
    </style>
</head>
<body>
    <nav>
        <div style="display: flex; align-items: center;">
            <a href="{{ route('dashboard') }}" class="nav-brand">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Tronik
            </a>
            <div class="nav-links">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
                @endif
                <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.index') ? 'active' : '' }}">Transactions</a>
                <a href="{{ route('transactions.create') }}" class="{{ request()->routeIs('transactions.create') ? 'active' : '' }}">New Sale</a>
            </div>
        </div>

        <div class="nav-right">
            <div class="user-pill">{{ auth()->user()->name }} • {{ ucfirst(auth()->user()->role) }}</div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn" style="background: none; color: var(--text-muted); padding: 0.5rem;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
