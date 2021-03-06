<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cart;
use Illuminate\Support\Facades\Redirect;
use Response;
use Auth;

class CartController extends Controller
{
    public function AddCart($id){
 
        $product = DB::table('products')->where('id',$id)->first();
       
         $data = array();
        
        if ($product->discount_price == NULL) {
            $data['id'] = $product->id;
            $data['name'] = $product->product_name;
            $data['qty'] = 1;
            $data['price'] = $product->selling_price;
            $data['weight'] = 1;
            $data['options']['image'] = $product->image_one;
            $data['options']['color'] = '';
            $data['options']['size'] = '';

             Cart::add($data);
             return \Response::json(['success' => 'Successfully Added on your Cart']);
        }else{
       
            $data['id'] = $product->id;
            $data['name'] = $product->product_name;
            $data['qty'] = 1;
            $data['price'] = $product->discount_price;
            $data['weight'] = 1;
            $data['options']['image'] = $product->image_one;
            $data['options']['color'] = '';
            $data['options']['size'] = '';
             Cart::add($data);
             return \Response::json(['success' => 'Successfully Added on your Cart']);
       
            } 
        }

        public function Check(){
            $content = Cart::content();
            return response()->json($content);
        }
       
        public function ShowCart(){
            $cart = Cart::content();
            return view('pages.cart',compact('cart'));
        }

        public function RemoveCart($rowId){
            Cart::remove($rowId);
            return Redirect()->back();
        }

        public function UpdateCart(Request $request){

            $rowId = $request->productid;
            $qty = $request->qty;
            Cart::update($rowId,$qty);
            return Redirect()->back();
        }


        public function ViewProduct($id){
            $product = DB::table('products')
                    ->join('categories','products.category_id','categories.id')
                    ->join('brands','products.brand_id','brands.id')
                    ->select('products.*','categories.category_name','brands.brand_name')
                    ->where('products.id',$id)
                    ->first();

        $color = $product->product_color;
        $product_color = explode(',',$color);

        $size = $product->product_size;
        $product_size = explode(',',$size);

    return response::json(array(
        'product' => $product,
        'color' =>$product_color,
        'size' => $product_size,
    ));
        }


        public function InsertCart(Request $request){
            $id = $request->product_id;
            
 
                $product = DB::table('products')->where('id',$id)->first();
                $color = $request->color;
                $size = $request->size;
                $qty = $request->qty;
               
                 $data = array();

                    $data['id'] = $product->id;
                    $data['name'] = $product->product_name;
                    $data['qty'] = $request->qty;
                    $data['price'] = $product->selling_price;
                    $data['weight'] = 1;
                    $data['options']['image'] = $product->image_one;
                    $data['options']['color'] = $request->color;
                    $data['options']['size'] = $request->size;
        
                     Cart::add($data);
                     $notification=array(
                                        'message' => 'Product Added successfully',
                                        'alert-type'=>'success'
                                        );
                                    return Redirect()->back()->with($notification);

                                    
        }

        public function CheckOut(){
            if(Auth::check()){

                $cart = Cart::content();
                $total = Cart::total();
                $setting = DB::table('settings')->first();
                $charge = $setting->shipping_charge;
                $total_price = $total + $charge;
                return view('pages.checkout',compact('cart','total_price'));
            
            }else{
                $notification=array(
                    'message' => 'Product Added successfully',
                    'alert-type'=>'success'
                    );
                return Redirect()->route('login')->with($notification);
            }
        }

        public function Wishlist(){
            $userid = Auth::id();
            $product = DB::table('wishlists')
                    ->join('products','wishlists.product_id','products.id')
                    ->select('products.*','wishlists.user_id')
                    ->where('wishlists.user_id',$userid)
                    ->get();
                    //return response()->json($product);

                    return view('pages.wishlist',compact('product'));
        }



        public function PaymentPage(){

            $cart = Cart::Content();
            $setting = DB::table('settings')->first();
            $charge = $setting->shipping_charge;
            return view('pages.payment',compact('cart','charge'));
        }


}


        