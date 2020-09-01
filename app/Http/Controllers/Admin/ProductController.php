<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use TJGazel\Toastr\Facades\Toastr;

class ProductController extends Controller
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

        if ($search == '') {
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->orderBy('products.id', 'DESC')
                ->paginate(8);
        }
        else{
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->where('products.name', 'like', '%' . $search . '%')
                ->orderBy('products.id', 'DESC')
                ->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'products' => $products
        ];
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name', 'ASC')->get();
        return ['categories' => $categories];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        Product::find($id)->update($request->validated());
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
        $product = Product::findOrFail($id);
        $product->delete();
    }

    public function searchProduct(Request $request)
    {
        $filter = $request->filter;
        $products = Product::select('id', 'name')
            ->where('code', '=', $filter)
            ->orderBy('name', 'ASC')
            ->take(1)->get();

        return [
            'products' => $products
        ];
    }

    public function getProduct(Request $request)
    {
        $search = $request->search;
        $criterion = $request->criterion;

        if ($search == '') {
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->orderBy('products.id', 'DESC')
                ->paginate(8);
        }
        else{
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->where('products.'.$criterion, 'like', '%' . $search . '%')
                ->orderBy('products.id', 'DESC')
                ->paginate(8);
        }

        return [
            'products' => $products
        ];
    }

    public function stockReport(Request $request)
    {
        $search = $request->search;
        $criterion = $request->criterion;
        $order = $request->order;

        $or = 'DESC';

        if ($search == '') {
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name', 'products.updated_at')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->orderBy('products.stock', $order)
                ->paginate(8);
        }
        else{
            $products = Product::select('products.id', 'products.code', 'products.name', 'products.description', 'products.stock', 'products.category_id', 'categories.name as category_name', 'products.updated_at')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->where('products.'.$criterion, 'like', '%' . $search . '%')
                ->orderBy('products.stock', $order)
                ->paginate(8);
        }

        return [
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'products' => $products,
            'order' => $order,
            'or' => $or
        ];
    }


}
