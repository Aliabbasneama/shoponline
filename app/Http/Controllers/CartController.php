<?php

namespace App\Http\Controllers;

use App\Models\Countery;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request){

        $product = Product::with('product_images')->find($request->id);
        
        if($product == null){
          return response()->json([
           'status' => false,
           'message' => 'Product not found'
          ]);
        }

       if(Cart::count() > 0){

        $cartContent = Cart::content();
        $productAlreadyExist = false;
        
        foreach($cartContent as $item){
            if($item->id == $product->id){
             $productAlreadyExist = true;
            }
        }

       if($productAlreadyExist == false){
        Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty
        ($product->product_images)) ? $product->product_images->first() : '']);

        $status = true;
        $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
        session()->flash('success', $message);

       }else{
          $status = false;
          $message = $product->title.' alrady added in cart';
    }
    
       }else{
        Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
          $status = true;
          $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
          session()->flash('success', $message);
    }

    return response()->json([
       'status' => $status,
       'message' => $message
    ]);

    }

    public function cart(){

        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;

      return view('front.cart',$data);
    }

    public function updateCart(Request $request){
     $rowId = $request->rowId;
     $qty = $request->qty;

     $itemInfo = Cart::get($rowId);

     $product = Product::find($itemInfo->id);
     //check qty available in stock

     if($product->track_qty == 'yes'){

       if($qty <= $product->qty){
        Cart::update($rowId, $qty);
         $message = 'Cart updated successfully';
         $status = true;
         session()->flash('success',$message);


       }else{
        $message = 'Request qty('.$qty.') not available in stock.';
        $status = false;
        session()->flash('error',$message);

       }
     }else{
      Cart::update($rowId, $qty);
      $message = 'Cart updated successfully';
      $status = true;
      session()->flash('success',$message);

     }

      

      
      return response()->json([
        'status' => $status,
        'message' => $message
      ]); 

    }

    public function deleteItem(Request $request){

      $itemInfo = Cart::get($request->rowId);

        if($itemInfo == null){
          $errorMessage =  'Item not found in cart';
          session()->flash('error', $errorMessage);
          
          return response()->json([
            'status' => false,
            'message' => $errorMessage
          ]); 
    
        }
      Cart::remove($request->rowId);
      
      $message = 'Item removed from cart successfully.';
      session()->flash('success', $message);

      return response()->json([
        'status' => true,
        'message' => $message
      ]); 
    
    }

    public function checkout(){

      if(Cart::count() == 0){
        //-- if cart empty redirect to cart page
        return redirect()->route('front.cart');
      }

      // if user is not loged in then redirect to login page
      if(Auth::check() == false){

        if(!session()->has('url.intended')){
          session(['url.intended' => url()->current()]);

        }
        return redirect()->route('account.login');
      }

      session()->forget('url.intended');


      $counteris = Countery::orderBy('name','ASC')->get();

       return view('front.checkout',[
        'counteris' => $counteris
       ]);
    }

    public function processCheckout(Request $request){

      $validator = Validator::make($request->all(),[
        'first_name' => 'required',
        
      ]);
      if($validator->fails()){
        return response()->json([
         'message'=>'pless fix errors',
         'status'=>false,
         'errors'=>$validator->errors()

        ]);
      }
    }
}


