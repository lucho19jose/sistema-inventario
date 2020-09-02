<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Staff;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
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
            'users' => $users
        ];
    }


    public function create()
    {
        $staffs = Staff::select('id', Staff::raw('CONCAT(last_name, " ", first_name) as staff_name'))->orderBy('last_name', 'ASC')->get();
        return ['staffs' => $staffs];
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
    public function update(UserUpdateRequest $request, $id)
    {
        $request->validated();
        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        return;
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
