<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $roles = Role::select('id', 'name')
                ->orderBy('id', 'DESC')
                ->paginate(8);
        }
        else{
            $roles = Role::select('id', 'name')
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('id', 'DESC')
                ->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return [
                'permissions' => $permissions,
             ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if ($request->special == 'Administrador') {
            $role = Role::create($request->validated());
            $role->syncPermissions(Permission::all());
        }
        else{
            $role = Role::create($request->validated());
            $role->syncPermissions($request->permissions);
        }

        return;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();

        return [
            'role' => $role,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions
        ];
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        if ($request->special == 'Administrador') {
            $role = Role::find($id);
            $role->permissions()->detach();
            $role->name = $request->name;
            $role->syncPermissions(Permission::all());
            $role->save();
        }
        else{
            $role = Role::find($id);
            $role->permissions()->detach();
            $role->name = $request->name;
            $role->syncPermissions($request->permissions);
            $role->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    }
}
