<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --bg-color: #f8fafc;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        header.header-container {
            background: rgba(255, 255, 255, 0.98) !important;
            height: 100px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0 2.5rem !important;
            border-bottom: 1px solid rgba(0,0,0,0.08) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
            width: 100% !important;
            box-sizing: border-box !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
        }

        .header-logo {
            height: 85px !important;
            width: auto !important;
            object-fit: contain !important;
        }

        body {
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .auth-main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex: 1;
            padding: 40px 2rem;
        }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            font-size: 1.2rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
            color: var(--text-main);
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        }

        .btn-auth {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-auth:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
        }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
                border: none;
                box-shadow: none;
                background: transparent;
            }
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    @include('partials.header', ['headerBackBtn' => ['url' => route('home'), 'label' => 'Back to Home']])

    <main class="auth-main">
        <div class="auth-card" style="margin-top: 40px;">
            <div class="auth-header">
                <div class="sidebar-logo" style="justify-content: center; font-size: 1.8rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; color: var(--text-main);">
                    <i class="ph-fill ph-user-plus" style="color: var(--primary-color);"></i> Guest<span style="color: var(--primary-color);">Registration</span>
                </div>
                <p>Join the MCC IGH community</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div style="background: #fef2f2; color: #991b1b; padding: 0.8rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.875rem; border: 1px solid #fee2e2;">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-wrapper">
                        <i class="ph ph-user"></i>
                        <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <i class="ph ph-envelope"></i>
                        <input type="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Create Password</label>
                    <div class="input-wrapper">
                        <i class="ph ph-lock"></i>
                        <input type="password" name="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="ph ph-lock-key"></i>
                        <input type="password" name="password_confirmation" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" required>
                    </div>
                </div>

                <label class="remember-me">
                    <input type="checkbox" required> I agree to terms & conditions
                </label>

                <button type="submit" class="btn-auth">Create Account</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </div>
    </main>
</body>
</html>
