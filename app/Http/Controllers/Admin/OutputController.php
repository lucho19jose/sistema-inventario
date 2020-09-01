<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Output;
use App\OutputProduct;
use App\Product;
use App\Http\Requests\OutputRequest;
use TJGazel\Toastr\Facades\Toastr;
use Carbon\Carbon;

class OutputController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $criterion = $request->criterion;

        if($search == ''){
            $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'users.email as user_email', 'users.id as user_id', 'branches.name as branch_name')
            ->join('staff', 'staff.id', '=', 'outputs.staff_id')
            ->join('users', 'users.id', '=', 'outputs.user_id')
            ->join('branches', 'branches.id', '=', 'outputs.branch_id')
            ->orderBy('outputs.id', 'DESC')
            ->paginate(8);
        }
        else{
            $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'users.email as user_email', 'users.id as user_id', 'branches.name as branch_name')
            ->join('staff', 'staff.id', '=', 'outputs.staff_id')
            ->join('users', 'users.id', '=', 'outputs.user_id')
            ->join('branches', 'branches.id', '=', 'outputs.branch_id')
            ->where('outputs.'.$criterion, 'like', '%' . $search . '%')
            ->orderBy('outputs.id', 'DESC')
            ->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $outputs->total(),
                'current_page' => $outputs->currentPage(),
                'per_page' => $outputs->perPage(),
                'last_page' => $outputs->lastPage(),
                'from' => $outputs->firstItem(),
                'to' => $outputs->lastItem(),
            ],
            'outputs' => $outputs
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OutputRequest $request)
    {
        $output = Output::create($request->validated());
        //Arrar con todos los detalles de los productos
        $details = $request->data;
        foreach ($details as $key => $detail) {
            $outputProduct = new OutputProduct();
            $outputProduct->product_id = $detail['product_id'];
            $outputProduct->output_id = $output->id;
            $outputProduct->quantity = $detail['quantity'];
            $outputProduct->save();

            //Actualizar el stock
            $product = Product::find($detail['product_id']);
            $product->stock = $product->stock - $detail['quantity'];
            $product->save();
        }
        return;
    }

    public function showOutput(Request $request){
        $id = $request->id;

        $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'branches.name as branch_name')
            ->join('staff', 'staff.id', '=', 'outputs.staff_id')
            ->join('branches', 'branches.id', '=', 'outputs.branch_id')
            ->where('outputs.id', '=', $id)
            ->orderBy('outputs.id', 'DESC')
            ->take(1)
            ->get();

        $detailProducts = OutputProduct::select('output_product.quantity', 'products.name as product')
            ->join('products', 'products.id', '=', 'output_product.product_id')
            ->where('output_product.output_id', '=', $id)
            ->orderBy('output_product.id', 'DESC')
            ->get();

        return [
            'outputs' => $outputs,
            'detailProducts' => $detailProducts
        ];
    }


    public function outputReport(Request $request)
    {
        $criterionVoucher = $request->criterionVoucher;
        $initialDate = $request->initialDate;
        $finalDate = $request->finalDate;

        if($initialDate == '' && $finalDate == '' && $criterionVoucher == ''){
            $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'users.email as user_email', 'users.id as user_id', 'branches.name as branch_name')
            ->join('staff', 'staff.id', '=', 'outputs.staff_id')
            ->join('users', 'users.id', '=', 'outputs.user_id')
            ->join('branches', 'branches.id', '=', 'outputs.branch_id')
            ->orderBy('outputs.id', 'DESC')
            ->paginate(8);
        }
        else{
            if ($finalDate == '') {
                $finalDate = Carbon::now()->format('d-m-Y');
            }
            if ($initialDate == '') {
                $minDate = Output::select(Output::raw('MIN(created_at) as min_date'))->take(1)->get();
                $initialDate = $minDate[0]['min_date'];
            }

            if ($criterionVoucher == '') {
                $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'users.email as user_email', 'users.id as user_id', 'branches.name as branch_name')
                ->join('staff', 'staff.id', '=', 'outputs.staff_id')
                ->join('users', 'users.id', '=', 'outputs.user_id')
                ->join('branches', 'branches.id', '=', 'outputs.branch_id')
                ->where('outputs.created_at', '>=', $initialDate)
                ->where('outputs.created_at', '<=', $finalDate)
                ->orderBy('outputs.id', 'DESC')
                ->paginate(8);
             }
             else{
                $outputs = Output::select('outputs.id', 'outputs.voucher_type', 'outputs.voucher_serie', 'outputs.voucher_number', 'outputs.observation', 'outputs.created_at', Output::raw('CONCAT(staff.last_name, ", ", staff.first_name) as staff_name'), 'users.email as user_email', 'users.id as user_id', 'branches.name as branch_name')
                ->join('staff', 'staff.id', '=', 'outputs.staff_id')
                ->join('users', 'users.id', '=', 'outputs.user_id')
                ->join('branches', 'branches.id', '=', 'outputs.branch_id')
                ->where('outputs.voucher_type', '=', $criterionVoucher)
                ->where('outputs.created_at', '>=', $initialDate)
                ->where('outputs.created_at', '<=', $finalDate)
                ->orderBy('outputs.id', 'DESC')
                ->paginate(8);
             }
        }

        return [
            'pagination' => [
                'total' => $outputs->total(),
                'current_page' => $outputs->currentPage(),
                'per_page' => $outputs->perPage(),
                'last_page' => $outputs->lastPage(),
                'from' => $outputs->firstItem(),
                'to' => $outputs->lastItem(),
            ],
            'outputs' => $outputs
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
    public function update(OutputRequest $request, $id)
    {
        //
    }

}
