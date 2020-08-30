<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Branch;
use App\Http\Requests\BranchRequest;
use TJGazel\Toastr\Facades\Toastr;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $criterion = $request->criterion;

        if($search == ''){
            $branches = Branch::orderBy('id', 'DESC')->paginate(8);
        }
        else{
            $branches = Branch::where($criterion, 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $branches->total(),
                'current_page' => $branches->currentPage(),
                'per_page' => $branches->perPage(),
                'last_page' => $branches->lastPage(),
                'from' => $branches->firstItem(),
                'to' => $branches->lastItem(),
            ],
            'branches' => $branches
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        Branch::create($request->validated());
        return;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        Branch::find($id)->update($request->validated());
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
        $branch =  Branch::findOrFail($id);
        $branch->delete();
    }

    public function selectBranch(Request $request){
        $filter = $request->filter;
        $branches = Branch::where('name', 'like', '%' . $filter . '%')
            ->orderBy('name', 'ASC')->get();

        return [
            'branches' => $branches
        ];
    }
}
