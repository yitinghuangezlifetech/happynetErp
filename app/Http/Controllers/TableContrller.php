<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Term;
use App\Models\Product;
use App\Models\FuncType;

class TableContrller extends Controller
{
    public function getProductsByFilters(Request $request)
    {
        $filters = [
            'sales_type_id' => $request->sales_type_id,
            'product_type_id' => $request->product_type_id,
        ];

        $row = $request->row;
        $products = app(Product::class)->getDataByFilters($filters);

        $content = view('tables.product', compact(
            'products', 'row'
        ))->render();

        return response()->json([
            'status'=>true,
            'message'=>'取得資料成功',
            'data'=>$content
        ], 200);
    }

    public function getTermsByFilters(Request $request)
    {
        $termType = app(FuncType::class)->find($request->term_type_id);

        switch ($termType->type_name)
        {
            case '共用型條文':
                $filters = [
                    'term_type_id' => $request->term_type_id,
                ];
                break;
            default:
                $filters = [
                    'sales_type_id' => $request->sales_type_id,
                    'product_type_id' => $request->product_type_id,
                ];
                break;
        }

        $row = $request->row;
        $terms = app(Term::class)->getTermsByFilters($filters);
        $content = view('tables.regulation', compact(
            'terms', 'row'
        ))->render();

        return response()->json([
            'status'=>true,
            'message'=>'取得資料成功',
            'data'=>$content
        ], 200);
    }
}
