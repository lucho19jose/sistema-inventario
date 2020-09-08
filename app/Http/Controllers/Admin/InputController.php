<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Input;
use App\InputProduct;
use App\Product;
use App\Http\Requests\InputRequest;
use TJGazel\Toastr\Facades\Toastr;
use Carbon\Carbon;

class InputController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $criterion = $request->criterion;

        if($search == ''){
            $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name', 'users.email as user_email', 'users.id as user_id')
            ->join('providers', 'providers.id', '=', 'inputs.provider_id')
            ->join('users', 'users.id', '=', 'inputs.user_id')
            ->orderBy('inputs.id', 'DESC')
            ->paginate(8);
        }
        else{
            $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name', 'users.email as user_email')
            ->join('providers', 'providers.id', '=', 'inputs.provider_id')
            ->join('users', 'users.id', '=', 'inputs.user_id')
            ->where('inputs.'.$criterion, 'like', '%' . $search . '%')
            ->orderBy('inputs.id', 'DESC')
            ->paginate(8);
        }

        $user_id_log = auth()->user()->id;

        return [
            'pagination' => [
                'total' => $inputs->total(),
                'current_page' => $inputs->currentPage(),
                'per_page' => $inputs->perPage(),
                'last_page' => $inputs->lastPage(),
                'from' => $inputs->firstItem(),
                'to' => $inputs->lastItem(),
            ],
            'inputs' => $inputs,
            'user_id_log' => $user_id_log
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InputRequest $request)
    {
        $input = Input::create($request->validated());
        //Arrar con todos los detalles de los productos
        $details = $request->data;
        foreach ($details as $key => $detail) {
            $inputProduct = new InputProduct();
            $inputProduct->product_id = $detail['product_id'];
            $inputProduct->input_id = $input->id;
            $inputProduct->quantity = $detail['quantity'];
            $inputProduct->price = $detail['price'];
            $inputProduct->save();

            //Actualizar el stock
            $product = Product::find($detail['product_id']);
            $product->stock = $product->stock + $detail['quantity'];
            $product->save();
        }
        return;
    }

    public function showInput(Request $request){
        $id = $request->id;

        $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name',)
            ->join('providers', 'providers.id', '=', 'inputs.provider_id')
            ->where('inputs.id', '=', $id)
            ->orderBy('inputs.id', 'DESC')
            ->take(1)
            ->get();

        $detailProducts = InputProduct::select('input_product.price', 'input_product.quantity', 'products.name as product')
            ->join('products', 'products.id', '=', 'input_product.product_id')
            ->where('input_product.input_id', '=', $id)
            ->orderBy('input_product.id', 'DESC')
            ->get();

        return [
            'inputs' => $inputs,
            'detailProducts' => $detailProducts
        ];
    }

    public function inputReport(Request $request)
    {
        $criterionVoucher = $request->criterionVoucher;
        $initialDate = $request->initialDate;
        $finalDate = $request->finalDate;

        if($initialDate == '' && $finalDate == '' && $criterionVoucher == ''){
            $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name', 'users.email as user_email', 'users.id as user_id')
            ->join('providers', 'providers.id', '=', 'inputs.provider_id')
            ->join('users', 'users.id', '=', 'inputs.user_id')
            ->orderBy('inputs.id', 'DESC')
            ->paginate(8);
        }
        else{
            if ($finalDate == '') {
                $finalDate = Carbon::tomorrow()->format('Y-m-d');
            }
            if ($initialDate == '') {
                $minDate = Input::select(Input::raw('MIN(created_at) as min_date'))->take(1)->get();
                $initialDate = $minDate[0]['min_date'];
            }

            if ($criterionVoucher == '') {
                $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name', 'users.email as user_email')
                ->join('providers', 'providers.id', '=', 'inputs.provider_id')
                ->join('users', 'users.id', '=', 'inputs.user_id')
                ->where('inputs.created_at', '>=', $initialDate)
                ->where('inputs.created_at', '<=', $finalDate)
                ->orderBy('inputs.id', 'DESC')
                ->paginate(8);
             }
             else{
                $inputs = Input::select('inputs.id', 'inputs.voucher_type', 'inputs.voucher_serie', 'inputs.voucher_number', 'inputs.observation', 'inputs.total', 'inputs.created_at', 'providers.name as provider_name', 'users.email as user_email')
                ->join('providers', 'providers.id', '=', 'inputs.provider_id')
                ->join('users', 'users.id', '=', 'inputs.user_id')
                ->where('inputs.voucher_type', '=', $criterionVoucher)
                ->where('inputs.created_at', '>=', $initialDate)
                ->where('inputs.created_at', '<=', $finalDate)
                ->orderBy('inputs.id', 'DESC')
                ->paginate(8);
             }
        }

        return [
            'pagination' => [
                'total' => $inputs->total(),
                'current_page' => $inputs->currentPage(),
                'per_page' => $inputs->perPage(),
                'last_page' => $inputs->lastPage(),
                'from' => $inputs->firstItem(),
                'to' => $inputs->lastItem(),
            ],
            'inputs' => $inputs
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
