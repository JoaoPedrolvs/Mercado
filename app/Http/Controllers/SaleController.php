<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductsSale;
use App\Models\Promotion;
use App\Models\Sale;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function index(){
        $sales = Sale::orderBy('id', 'asc')->get();

        $data = [
            'sales' =>$sales
        ];

        return view('sale.show', $data);
    }

    public function create(){

        return $this->form(new Sale());

    }

    public function form(Sale $sale){

        $products = Product::orderBy('id','asc')->get();
        $customers = Customer::get();
        $employees = Employee::get();

        $data = [
            'customers' => $customers,
            'employees' => $employees,
            'sale' => $sale,
            'products' => $products
        ];

        return view('sale.form', $data);
    }

    public function insert(Request $request){

        // dd($request->all());

        $sale = new Sale();

        $this->save($sale,$request);

        return redirect('/sales')->with('msg', 'Venda criada com sucesso');
    }

    public function delete($id){

        $sale = Sale::find($id);

        $qty = ProductsSale::searchQty($sale)->where('sale_id', $sale->id)->get();

        foreach($qty as $productQty){
            $product = Product::find($productQty->product_id);

            $product->increment('current_qty', $productQty->qty_sales);
        }

        $sale->delete();

        return redirect('/sales')->with('msg', 'Venda deletada com sucesso');
    }

    public function show($id){

        $saleId = Sale::find($id);

        $sales = ProductsSale::search($id)->where('ps.sale_id', $saleId->id)->get();

        $data = [
            'sales' => $sales
        ];

        return view('sale.show_products', $data);
    }

    private function save(Sale $sale, Request $request){

        try{
            DB::beginTransaction();

            $sale->customer_id = $request->customer_id;
            $sale->employee_id = $request->employee_id;

            $products = $request->product_id;

            $sale->save();

            foreach($products as $product){


                $price = Promotion::searchPrice($product)->get()->first();


                foreach($request->qty_sales as $qty_sale){


                }

                $sale->products()->attach($product);
            }


            // foreach($products as $k => $product_id){

                //     $productSale->sale_id = $sale->id;

                //     foreach($request->qty_sales as $qty_sales){

            //         if($qty_sales !== null){

            //             $price = Promotion::searchPrice($product_id)->get()->first();
            //             $product = Product::find($product_id);

            //             $productSale->product_id = $request->product_id[$k];
            //             $productSale->qty_sales = $qty_sales;

            //             if($price !== null){

            //                 if($price->is_active == "true"){

            //                     $productSale->total_price = $price->promotion * $productSale->qty_sales;

            //                 }
            //                 else{
            //                     $productSale->total_price = $price->price * $productSale->qty_sales;
            //                 }

            //             }
            //             else{
            //                 $productSale->total_price = $product->price * $productSale->qty_sales;
            //             }

            //             $product->decrement('current_qty', $productSale->qty_sales);

            //             // dd($productSale->total_price);

            //             $sale->increment('total',$productSale->total_price);

            //             $productSale->save();

            //         }

            //     }


            // }


            DB::commit();

        } catch(Exception $e){

            dd($e);

            DB::rollBack();

        }

    }

}
