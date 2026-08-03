<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MCC-MRF Innovation Park</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --primary-color: #850f0f;
            --bg-color: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 1.5rem;
            box-sizing: border-box;
        }

        .login-card {
            background: white;
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .logo img { height: 150px; width: auto; object-fit: contain; }

        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #1e293b;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.65rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1.15rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.2s;
            background: #fcfdfe;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(133, 15, 15, 0.1);
        }

        .btn-login {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(133, 15, 15, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(133, 15, 15, 0.3);
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 0.85rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            text-align: center;
            border: 1px solid #fecaca;
        }

        @media (max-width: 480px) {
            body { padding: 1rem; }
            .login-card { padding: 2rem 1.5rem; border-radius: 16px; background: white; }
            .logo { font-size: 1.5rem; }
            .form-title { font-size: 1rem; }
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <img src="/assets/logo_transparent.png" alt="MCC-MRF Innovation Park">
        </div>
        <h2 class="form-title">Admin Access</h2>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div style="margin: 2px 0;">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="admin@mccigh.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
            </div>
            <button type="submit" class="btn-login">Login to Dashboard</button>
        </form>
    </div>
</body>
</html>
