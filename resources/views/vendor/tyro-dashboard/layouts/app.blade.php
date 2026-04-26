<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'Dashboard') - {{ $branding['app_name'] ?? config('tyro-dashboard.branding.app_name', config('app.name', 'Laravel')) }}</title>

    <!-- High-End Elite Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* Global Elite UI Master Kit */
        :root {
            --elite-slate-900: #0f172a;
            --elite-indigo-600: #4f46e5;
            --elite-emerald-600: #059669;
            --elite-rose-600: #e11d48;
            --elite-slate-400: #94a3b8;
            --elite-bg-soft: #f8fafc;
            --elite-border: #f1f5f9;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--background) !important; color: var(--foreground); }
        h1, h2, h3, h4, h5, h6, .page-title, .lead-title { font-family: 'Outfit', sans-serif !important; letter-spacing: -0.02em; }

        /* Master Utility Overrides */
        .main-content { background: var(--background); }
        .page-content { padding: 40px !important; }

        .elite-card-master {
            background: var(--card); border-radius: 20px; border: 1px solid var(--border);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .elite-card-master:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); border-color: #e2e8f0; }

        /* Unified Status Badges */
        .elite-badge {
            display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px;
            font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badg-success { background: var(--success); color: var(--success-foreground); border: 1px solid var(--success); }
        .badg-primary { background: var(--primary); color: var(--primary-foreground); border: 1px solid var(--primary); }
        .badg-danger { background: var(--destructive); color: var(--destructive-foreground); border: 1px solid var(--destructive); }
        .badg-warning { background: var(--warning); color: var(--warning-foreground); border: 1px solid var(--warning); }

        /* Elite Sidebar Enhancements */
        .dashboard-layout .sidebar { border-right: 1px solid var(--sidebar-border) !important; background: var(--sidebar) !important; box-shadow: none !important; }
        .sidebar .nav-link { font-weight: 700; color: var(--sidebar-foreground); font-size: 13px; border-radius: 10px; margin: 4px 12px; }
        .sidebar .nav-link.active { background: var(--sidebar-primary) !important; color: var(--sidebar-primary-foreground) !important; }
        .sidebar .nav-link:hover:not(.active) { background: var(--sidebar-accent); }

        /* Page Transitions */
        .page-content { animation: elitePageFade 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes elitePageFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @include('tyro-dashboard::partials.styles')
    @stack('styles')
</head>

<body>
    @include('tyro-dashboard::partials.admin-bar')
    <div class="dashboard-layout">
        <!-- Sidebar - Conditional based on role -->
        @hasanyrole('admin', 'superadmin')
            @include('tyro-dashboard::partials.admin-sidebar')
        @else
            @include('tyro-dashboard::partials.user-sidebar')
        @endhasanyrole

        <!-- Main Content -->
        <div class="main-content">
            <div class="sticky-topbar-wrapper">
                <!-- Top Bar -->
                @include('tyro-dashboard::partials.topbar')

                <!-- Impersonation Banner -->
                @include('tyro-dashboard::partials.impersonation-banner')
            </div>

            <!-- Page Content -->
            <main class="page-content">
                <!-- Flash Messages -->
                @include('tyro-dashboard::partials.flash-messages')

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Global Modal -->
    @include('tyro-dashboard::partials.modal')

    @include('tyro-dashboard::partials.scripts')
    @stack('scripts')
</body>

</html>
