@extends('tyro-login::layouts.auth')

@section('content')
<div class="auth-wrapper">
    <div class="auth-bg" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
    <div class="auth-overlay"></div>
    
    <div class="auth-container">
        <div class="glass-card">
            <!-- Left Side: Branding -->
            <div class="auth-branding">
                <img src="{{ asset('images/metronet-logo.png') }}" alt="MetroNet" class="brand-logo">
                <div class="branding-content">
                    <h3>Recover Access</h3>
                    <p>Advanced CRM Integration</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="auth-form-content">
                <div class="form-header">
                    <h2>Reset Password</h2>
                    <p>Enter your email for the recovery link</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('tyro-login.password.email') }}" class="modern-form">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                            @error('email')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">
                        <span>Send Reset Link</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Recall your password? <a href="{{ route('tyro-login.login') }}" class="sign-up-link">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --primary-color: #005F9A;
        --primary-hover: #004a79;
        --accent-color: #F28C00;
        --glass-bg: rgba(255, 255, 255, 0.88);
        --glass-border: rgba(255, 255, 255, 0.4);
        --text-main: #0f172a;
        --text-muted: #475569;
        --form-width: min(92%, 800px);
    }

    html.dark {
        --glass-bg: rgba(15, 23, 42, 0.9);
        --glass-border: rgba(255, 255, 255, 0.12);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 5px 20px;
        font-family: 'Inter', sans-serif;
        background-color: #0f172a;
    }

    .auth-bg {
        position: fixed;
        inset: 0;
        background-size: cover;
        background-position: center;
        z-index: 1;
        opacity: 0.8;
    }

    .auth-overlay {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.6) 100%);
        z-index: 2;
    }

    .auth-container {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: var(--form-width);
        animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 32px;
        display: flex;
        overflow: hidden;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
    }

    .auth-branding {
        flex: 1;
        padding: clamp(2.5rem, 5vw, 3.5rem);
        background: linear-gradient(135deg, rgba(0, 95, 154, 0.1), rgba(242, 140, 0, 0.05));
        border-right: 1px solid var(--glass-border);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: background 0.3s ease;
    }

    html.dark .auth-branding {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.05), rgba(0, 0, 0, 0.2));
    }

    .branding-content h3 {
        margin-top: 1.2rem;
        font-size: clamp(1.1rem, 2.5vw, 1.3rem);
        font-weight: 700;
        color: var(--text-main);
    }

    .branding-content p {
        color: var(--text-muted);
        font-size: clamp(0.7rem, 1.5vw, 0.8rem);
    }

    .auth-form-content {
        flex: 1.2;
        padding: clamp(2rem, 4vw, 3rem);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .brand-logo {
        height: clamp(55px, 10vw, 75px);
        width: auto;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    .form-header {
        margin-bottom: clamp(0.6rem, 2vw, 0.8rem);
    }

    .form-header h2 {
        font-size: clamp(1.3rem, 3vw, 1.6rem);
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.02em;
        margin-bottom: 2px;
    }

    .form-header p {
        color: var(--text-muted);
        font-size: clamp(0.75rem, 1.8vw, 0.85rem);
    }

    .modern-form .form-group {
        margin-bottom: clamp(0.4rem, 1.5vw, 0.6rem);
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .flex-1 { flex: 1; }

    .form-label {
        font-size: clamp(0.75rem, 1.5vw, 0.8rem);
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .form-input {
        width: 100%;
        padding: clamp(8px, 1.5vw, 12px) clamp(12px, 2vw, 16px);
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid transparent;
        border-radius: 12px;
        font-size: clamp(0.9rem, 1.8vw, 1rem);
        color: var(--text-main);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    html.dark .form-input {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    html.dark .form-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .form-input:focus {
        border-color: var(--primary-color);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 95, 154, 0.1);
        transform: translateY(-1px);
    }

    html.dark .form-input:focus {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .btn-primary {
        width: 100%;
        padding: clamp(8px, 1.5vw, 12px);
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: clamp(0.9rem, 1.8vw, 1rem);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 16px -4px rgba(0, 95, 154, 0.4);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -4px rgba(0, 95, 154, 0.5);
    }

    .form-footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--glass-border);
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .sign-up-link {
        color: var(--primary-color);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .sign-up-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 6px;
        display: block;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 0.9rem;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    @media (max-width: 850px) {
        .glass-card {
            flex-direction: column;
            border-radius: 24px;
        }
        .auth-branding {
            padding: 40px;
            border-right: none;
            border-bottom: 1px solid var(--glass-border);
        }
        .auth-form-content {
            padding: 40px;
        }
        --form-width: 500px;
    }

    @media (max-width: 480px) {
        .auth-wrapper { padding: 20px 15px; }
        .auth-branding, .auth-form-content { padding: 30px 20px; }
        .form-header h2 { font-size: 1.5rem; }
    }
</style>
@endsection