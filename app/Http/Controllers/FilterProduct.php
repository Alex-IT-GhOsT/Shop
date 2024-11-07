<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Laptop;
use Inertia\Inertia;
use App\Models\Brands;

class FilterProduct extends Controller
{
    public function index() {

        $product = Laptop::paginate(60);

        $product->withPath('/api/products');

        return Inertia::render('Welcome', [
            'product_list' => $product,
            'brands' => Brands::all(),
        ]);
    }
    public function apiIndex(Request $request) {
        $sort = $request->query('sort');
        $startPrice = $request->query('startPrice');
        $toPrice = $request->query('toPrice');
        $brand_id = $request->query('brand_id','all');
        $query = Laptop::query();

        if ($brand_id !== 'all') {
            $query->where('brand_id', $brand_id);
        }

        if (!empty($startPrice)) {
            $query->where('current_price', '>=', $startPrice);
        }

        if(!empty($toPrice)) {
            $query->where('current_price', '<=', $toPrice);
        }

        if(!empty($sort)){
            if ($sort === 'Дешевые'){
                $query->orderBy('current_price','asc');
            } else if ($sort === 'Дорогие'){
                $query->orderBy('current_price','desc');
            }else {
                $query->orderBy('discount', 'desc');
            }

        }

        $products = $query->paginate(60);

        return response()->json($products);
        
    }

}
