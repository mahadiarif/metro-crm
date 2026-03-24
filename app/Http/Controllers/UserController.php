<?php

namespace App\Http\Controllers;

use HasinHayder\TyroDashboard\Http\Controllers\UserController as TyroUserController;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Http\Request;

class UserController extends TyroUserController
{
    /**
     * Get the available roles for the current user based on their permissions.
     */
    protected function getAuthorizedRoles()
    {
        $user = auth()->user();
        if (!$user) return collect();

        $currentStr = strtolower($user->role ?? '');
        $tyroRoles = method_exists($user, 'tyroRoleSlugs') ? $user->tyroRoleSlugs() : [];
        
        $isSuperAdmin = in_array($currentStr, ['super admin', 'super_admin', 'super-admin', 'admin']) || in_array('super_admin', $tyroRoles) || in_array('super-admin', $tyroRoles) || in_array('admin', $tyroRoles);
        $isManager    = in_array($currentStr, ['manager']) || in_array('manager', $tyroRoles);
        $isTeamLeader = in_array($currentStr, ['team leader', 'team_leader']) || in_array('team_leader', $tyroRoles);
        $isExecutive  = in_array($currentStr, ['marketing executive', 'marketing_executive']) || in_array('marketing_executive', $tyroRoles);

        if ($isExecutive) {
            abort(403, 'Marketing Executives are not allowed to manage users.');
        }

        $allRoles = Role::all();

        if ($isSuperAdmin) {
            return $allRoles;
        }

        if ($isManager) {
            // Manager can only assign Team Leader or Marketing Executive
            return $allRoles->filter(function($role) {
                return in_array($role->slug, ['team_leader', 'marketing_executive']);
            });
        }

        if ($isTeamLeader) {
            // Team leader can only assign Marketing Executive
            return $allRoles->filter(function($role) {
                return in_array($role->slug, ['marketing_executive']);
            });
        }

        abort(403, 'Unauthorized role assignment.');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Enforce access control immediately
        $this->getAuthorizedRoles();
        
        return parent::index($request);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = $this->getAuthorizedRoles();

        return view('tyro-dashboard::users.create', $this->getViewData([
            'roles' => $roles,
        ]));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $authorizedRoles = $this->getAuthorizedRoles()->pluck('id')->toArray();
        
        // Ensure the roles they are trying to assign are within their authorized scope
        if ($request->has('roles')) {
            foreach ($request->input('roles') as $roleId) {
                if (!in_array((int)$roleId, $authorizedRoles)) {
                    abort(403, 'You do not have permission to assign one or more of these roles.');
                }
            }
        }

        return parent::store($request);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $userModel = $this->getUserModel();
        $user = $userModel::with('roles')->findOrFail($id);
        
        $roles = $this->getAuthorizedRoles();

        return view('tyro-dashboard::users.edit', $this->getViewData([
            'editUser' => $user,
            'roles' => $roles,
        ]));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $authorizedRoles = $this->getAuthorizedRoles()->pluck('id')->toArray();
        
        if ($request->has('roles')) {
            foreach ($request->input('roles') as $roleId) {
                if (!in_array((int)$roleId, $authorizedRoles)) {
                    abort(403, 'You do not have permission to assign one or more of these roles.');
                }
            }
        }

        return parent::update($request, $id);
    }
}
