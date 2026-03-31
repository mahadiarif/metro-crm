<aside class="sidebar" id="sidebar">
    @php
        $user = auth()->user();
        $currentStr = strtolower($user->role ?? '');
        $tyroRoles = method_exists($user, 'tyroRoleSlugs') ? $user->tyroRoleSlugs() : [];
        
        $isSuperAdmin = in_array($currentStr, ['super admin', 'super_admin', 'super-admin', 'admin']) || in_array('super_admin', $tyroRoles) || in_array('super-admin', $tyroRoles) || in_array('admin', $tyroRoles);
        $isManager    = in_array($currentStr, ['manager']) || in_array('manager', $tyroRoles);
        $isTeamLeader = in_array($currentStr, ['team leader', 'team_leader']) || in_array('team_leader', $tyroRoles);
        $isExecutive  = in_array($currentStr, ['marketing executive', 'marketing_executive']) || in_array('marketing_executive', $tyroRoles);
    @endphp
    <div class="sidebar-header">
        <a href="{{ route('tyro-dashboard.index') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="sidebar-logo-text">{{ $branding['app_name'] ?? config('tyro-dashboard.branding.app_name', 'MetroNet') }}</span>
        </a>
        @if(config('tyro-dashboard.collapsible_sidebar', false))
        <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" aria-label="Collapse sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        @endif
    </div>
    @if(config('tyro-dashboard.collapsible_sidebar', false))
    <button class="sidebar-expand-btn" onclick="toggleSidebarCollapse()" aria-label="Expand sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    @endif

    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Menu</div>
            <a href="{{ route('tyro-dashboard.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            
            @if(!empty($commonMenuItems))
                @foreach($commonMenuItems as $item)
                    <a href="{{ route($item['route'] ?? '#') }}" class="sidebar-link {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        @if(isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        @endif
                        {{ $item['title'] ?? 'Menu Item' }}
                    </a>
                @endforeach
            @endif

            @if(!empty($userMenuItems))
                @foreach($userMenuItems as $item)
                    <a href="{{ route($item['route'] ?? '#') }}" class="sidebar-link {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        @if(isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        @endif
                        {{ $item['title'] ?? 'Menu Item' }}
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Resources Section -->
        @php
            $resourcesToRender = !empty($allResources) ? $allResources : config('tyro-dashboard.resources', []);
        @endphp
        
        @if(!empty($resourcesToRender))
        <div class="sidebar-section">
            <div class="sidebar-section-title">SALES</div>
            @foreach($resourcesToRender as $key => $resource)
                @php
                    // Check access (logic duplicated from Controller for view)
                    $canAccess = true;
                    if (isset($resource['roles']) && !empty($resource['roles'])) {
                        $canAccess = false;
                        $user = auth()->user();
                        if ($user && method_exists($user, 'tyroRoleSlugs')) {
                            $userRoles = $user->tyroRoleSlugs();
                            // Check allowed roles
                            foreach ($resource['roles'] as $role) {
                                if (in_array($role, $userRoles)) {
                                    $canAccess = true;
                                    break;
                                }
                            }
                            // Check readonly roles (if not already allowed)
                            if (!$canAccess && isset($resource['readonly']) && !empty($resource['readonly'])) {
                                foreach ($resource['readonly'] as $role) {
                                    if (in_array($role, $userRoles)) {
                                        $canAccess = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                @endphp
                
                @if($canAccess)
                @php
                    $route = isset($resource['custom_route']) ? route($resource['custom_route']) : route('tyro-dashboard.resources.index', $key);
                    $isActive = isset($resource['custom_route']) ? request()->routeIs($resource['custom_route']) : request()->is('*resources/'.$key.'*');
                @endphp
                <a href="{{ $route }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                    @if(isset($resource['icon']))
                        {!! $resource['icon'] !!}
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    @endif
                    {{ $resource['title'] }}
                </a>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Reports Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Reports</div>
            <a href="{{ route('tyro-dashboard.reports.sales') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.reports.sales') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Total Sales Report
            </a>
            <a href="{{ route('tyro-dashboard.reports.visits') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.reports.visits') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Sales Visit Report
            </a>
            <a href="{{ route('tyro-dashboard.reports.leads') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.reports.leads') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Sales Lead Report
            </a>
            <a href="{{ route('tyro-dashboard.reports.quarterly') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.reports.quarterly') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Quarterly Report
            </a>
            @if(auth()->check() && ($isTeamLeader || $isManager || $isSuperAdmin))
            <a href="{{ route('tyro-dashboard.reports.team-performance') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.reports.team-performance') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Team Performance
            </a>
            @endif
        </div>

        @if(auth()->check() && ($isSuperAdmin || $isManager))
        <!-- Marketing Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Marketing</div>
            <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.marketing.campaigns.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                Campaigns
            </a>
            <a href="{{ route('tyro-dashboard.marketing.templates.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.marketing.templates.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Templates
            </a>
            <a href="{{ route('tyro-dashboard.marketing.configuration') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.marketing.configuration') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuration
            </a>
        </div>
        @endif

        @if(auth()->check() && $isSuperAdmin)
        <!-- Admin Menu -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Administration</div>
            <a href="{{ route('tyro-dashboard.user-admin.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.user-admin.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>
            <a href="{{ route('tyro-dashboard.roles.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.roles.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Roles
            </a>
            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.privileges.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Privileges
            </a>
            @if(config('tyro-dashboard.features.invitation_system', true))
            <a href="{{ route('tyro-dashboard.invitations.admin.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.invitations.admin.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                Invitation Links
            </a>
            @endif

            @php
                $showAuditLogsMenu = false;
                if (config('tyro-dashboard.features.audit_logs', true) && config('tyro.audit.enabled', true) && class_exists('\HasinHayder\Tyro\Models\AuditLog')) {
                    try {
                        $showAuditLogsMenu = \Illuminate\Support\Facades\Schema::hasTable(config('tyro.tables.audit_logs', 'tyro_audit_logs'));
                    } catch (\Throwable $e) {
                        $showAuditLogsMenu = false;
                    }
                }
            @endphp

            @if($showAuditLogsMenu)
            <a href="{{ route('tyro-dashboard.audits.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.audits.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Audit Logs
            </a>
            @endif
        </div>
        @endif

        @if(!config('tyro-dashboard.disable_examples', false) && !app()->environment('production') && auth()->check() && $isSuperAdmin)
        <div class="sidebar-section">
            <div class="sidebar-section-title">Examples</div>
            <a href="{{ route('tyro-dashboard.components') }}" class="sidebar-link {{ (request()->routeIs('tyro-dashboard.components') || request()->routeIs('tyro-dashboard.examples.components')) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z" />
                </svg>
                Dashboard Components
            </a>

            <a href="{{ route('tyro-dashboard.widgets') }}" class="sidebar-link {{ (request()->routeIs('tyro-dashboard.widgets') || request()->routeIs('tyro-dashboard.examples.widgets')) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h6v6H5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 13h6v6h-6z" />
                </svg>
                Widgets
            </a>

            @if(class_exists('HasinHayder\\TyroDashboardComponents\\TyroDashboardComponentsServiceProvider'))
            <a href="{{ route('tyro-dashboard.x-components') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.x-components') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Form Components
            </a>
            @endif
        </div>
        @endif
    </nav>
</aside>
