<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Provider;
use App\Http\Requests\ProviderRequest;
use TJGazel\Toastr\Facades\Toastr;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->search;
        if($search == ''){
            $providers = Provider::orderBy('id', 'DESC')->paginate(8);
        }
        else{
            $providers = Provider::where('name', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $providers->total(),
                'current_page' => $providers->currentPage(),
                'per_page' => $providers->perPage(),
                'last_page' => $providers->lastPage(),
                'from' => $providers->firstItem(),
                'to' => $providers->lastItem(),
            ],
            'providers' => $providers
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderRequest $request)
    {
        Provider::create($request->validated());
        return;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderRequest $request, $id)
    {
        Provider::find($id)->update($request->validated());
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
        $provider= Provider::findOrFail($id);
        $provider->delete();
    }
}
