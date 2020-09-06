<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Staff;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use TJGazel\Toastr\Facades\Toastr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         $search = $request->search;
         $criterion = $request->criterion;

        if ($search == '') {
            $users = User::select('users.id', 'users.email', 'users.staff_id', User::raw('CONCAT(staff.last_name, " ", staff.first_name) as staff_name'), 'users.created_at')
                ->join('staff', 'staff.id', '=', 'users.staff_id')
                ->orderBy('users.id', 'DESC')
                ->paginate(8);
        }
        else{
            if ($criterion != 'staff_name') {
                $users = User::select('users.id', 'users.email', 'users.staff_id', User::raw('CONCAT(staff.last_name, " ", staff.first_name) as staff_name'), 'users.created_at')
                ->join('staff', 'staff.id', '=', 'users.staff_id')
                ->where('users.'.$criterion, 'like', '%' . $search . '%')
                ->orderBy('users.id', 'DESC')
                ->paginate(8);
            }
            else{
                $users = User::select('users.id', 'users.email', 'users.staff_id', User::raw('CONCAT(staff.last_name, " ", staff.first_name) as staff_name'), 'users.created_at')
                ->join('staff', 'staff.id', '=', 'users.staff_id')
                ->where('staff.first_name', 'like', '%' . $search . '%')
                ->orWhere('staff.last_name', 'like', '%' . $search . '%')
                ->orderBy('users.id', 'DESC')
                ->paginate(8);
            }

        }

        return [
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'users' => $users,
        ];
    }


    public function create(Request $request)
    {
        if($request->role != '0'){
            $id = $request->role;
            $user = User::find($id);
            $role = $user->getRoleNames();
        }
        else{
            $role = ['a'];
        }

        $staffs = Staff::select('id', Staff::raw('CONCAT(last_name, " ", first_name) as staff_name'), 'email', 'dni')->orderBy('last_name', 'ASC')->get();
        $roles = Role::get();
        return [
            'staffs' => $staffs,
            'roles' => $roles,
            'role' => $role
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        $user = new User();
        $user->staff_id = $request->staff_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($request->role != '') {
            $user->assignRole($request->role);
        }
        $user->save();
        return;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function reset($id)
    {
        $user = User::find($id);
        $staff = User::select('staff.dni as staff_dni')
            ->join('staff', 'staff.id', '=', 'users.staff_id')
            ->where('users.id', '=', $id)
            ->take(1)
            ->get();
        $user->password = Hash::make($staff[0]['staff_dni']);
        $user->save();
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->role != 'a') {
            $role = Role::where('name', '=', $request->role)->get();
            $user->roles()->sync($role[0]['id']);
        }
        else{
            $role = $user->getRoleNames();
            $role = Role::where('name', '=', $role)->get();
            $user->removeRole($role[0]['id']);
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
        $user = User::findOrFail($id);
        $user->delete();
    }
}
