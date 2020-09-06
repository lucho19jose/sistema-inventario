<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff;
use App\Http\Requests\StaffRequest;
use App\Http\Requests\StaffUpdateRequest;
use TJGazel\Toastr\Facades\Toastr;

class StaffController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$search = $request->search;
		$criterion = $request->criterion;

		if($search == ''){
			$staffs = Staff::orderBy('id', 'DESC')->paginate(8);
		}
		else{
			$staffs = Staff::where($criterion, 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate(8);
		}

		return [
			'pagination' => [
				'total' => $staffs->total(),
				'current_page' => $staffs->currentPage(),
				'per_page' => $staffs->perPage(),
				'last_page' => $staffs->lastPage(),
				'from' => $staffs->firstItem(),
				'to' => $staffs->lastItem(),
			],
			'staffs' => $staffs
		];
	}
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

	public function store(StaffRequest $request)
	{
	 	Staff::create($request->validated());
	 	return;
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffUpdateRequest $request, $id)
    {
        Staff::find($id)->update($request->validated());
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
    	$staff = Staff::findOrFail($id);
    	$staff->delete();
    }

    public function selectStaff(Request $request){
        $filter = $request->filter;
        $staffs = Staff::select('id', Staff::raw('CONCAT(last_name, " ", first_name) as staff_name'))
            ->where('first_name', 'like', '%' . $filter . '%')
            ->orWhere('last_name', 'like', '%' . $filter . '%')
            ->orderBy('first_name', 'ASC')->get();

        return [
            'staffs' => $staffs
        ];
    }
}
