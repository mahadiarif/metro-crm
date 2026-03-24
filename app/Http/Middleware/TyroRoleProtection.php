<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TyroRoleProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        $currentStr = strtolower($user->role ?? '');
        $tyroRoles = method_exists($user, 'tyroRoleSlugs') ? $user->tyroRoleSlugs() : [];
        
        $isSuperAdmin = in_array($currentStr, ['super admin', 'super_admin', 'super-admin', 'admin']) || in_array('super_admin', $tyroRoles) || in_array('super-admin', $tyroRoles) || in_array('admin', $tyroRoles);
        $isManager    = in_array($currentStr, ['manager']) || in_array('manager', $tyroRoles);
        $isTeamLeader = in_array($currentStr, ['team leader', 'team_leader']) || in_array('team_leader', $tyroRoles);
        $isExecutive  = in_array($currentStr, ['marketing executive', 'marketing_executive']) || in_array('marketing_executive', $tyroRoles);

        $path = $request->path();

        // Admin-only module checks
        $adminPaths = [
            'dashboard/users',
            'dashboard/roles',
            'dashboard/privileges',
            'dashboard/configuration',
            'dashboard/audit-logs'
        ];

        // Marketing module checks
        $marketingPaths = [
            'dashboard/marketing'
        ];
        
        // Team Performance checks
        $teamPerformancePaths = [
            'dashboard/reports/user'
        ];

        // 1. Block access to Admin modules if not Super Admin
        foreach ($adminPaths as $adminPath) {
            if (str_starts_with($path, $adminPath)) {
                if (!$isSuperAdmin) {
                    abort(403, 'Unauthorized access to administration modules.');
                }
            }
        }

        // 2. Block access to Marketing modules if not Super Admin or Manager
        foreach ($marketingPaths as $marketingPath) {
            if (str_starts_with($path, $marketingPath)) {
                if (!$isSuperAdmin && !$isManager) {
                    abort(403, 'Unauthorized access to marketing modules.');
                }
            }
        }
        
        // 3. Block access to Team Performance if Executive
        foreach ($teamPerformancePaths as $teamPath) {
            if (str_starts_with($path, $teamPath)) {
                if ($isExecutive) {
                    abort(403, 'Unauthorized access to team performance.');
                }
            }
        }

        return $next($request);
    }
}
