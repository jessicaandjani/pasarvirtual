<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Unit;
use App\Converter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;
use File;

class ProductController extends Controller {

    public function productAdd() {
        return view('product');
    }

    public function getAllProductByCategory(Request $request, $id) {
    	//retrieve all products based on cateory
    	$products = Category::find($id)->products;
        foreach($products as $product)
            $product->unit_id = $product->unit->unit;

    	return Response::json(array(
    		'error'=> false,
    		'products'=> $products->toArray()),
    		200
    	);
    }

    public function getAllProduct(Request $request) {
        $products = Product::all();

        return Response::json(array(
            'error'=>false,
            'products'=> $products->toArray()),
            200
        );
    }

    public function getProduct(Request $request, $id) {
    	$detail = Product::find($id);
        if($detail != null) {
            $detail->unit_id = $detail->unit->unit;    
        }

    	return Response::json(array(
    		'error'=>false,
    		'detail'=>$detail),
    		200
    	);
    }

    public function getSearchProduct(Request $request, $keyword) {
        $results = Product::where('name', 'LIKE', '%'.$keyword.'%')->get();
        foreach($results as $result)
            $result->unit_id = $result->unit->unit;

        return Response::json(array(
            'error'=>false,
            'products'=>$results->toArray()),
            200
        );
    }

    public function updateProduct(Request $request, $id) {
        //set default value
        $default_quantity = 100;
        // convert price to 100/gram
        $quantity = $request->product_quantity;
        $unit_id = $request->unit_id;
        $unit = Unit::find($unit_id);
        $converterObject = Converter::find($unit_id);
        if ($unit->unit_type == 'common') {
            //search id for unit gram
            $unit_gram = DB::table('unit')
                     ->where('unit', 'gram')
                     ->first();
            $default_unit = $unit_gram->id;
            $converter = $converterObject->in_gram;
            $quantity_in_gram = $quantity * $converter;
            $price = ($quantity_in_gram/$default_quantity) * $request->product_price;
        } else if ($unit->unit_type == 'not common')  {
            $default_quantity = 1;
            $default_unit = $unit_id;
            $price = ($quantity/$default_quantity) * $request->product_price;
        }

        $product = Product::find($id);
        //delete old image
        $oldImage = $product->file_img;
        $pathFile = public_path('images/vegetables/') . $oldImage;
        var_dump($pathFile);
        File::delete($pathFile);
        //add image to folder images
        $fileImage = $request->product_image;
        $imageName = time().'_'.$fileImage->getClientOriginalName();
        $fileImage->move(public_path('images/vegetables/'), $imageName);
        //add product to database
        $product->name = $request->product_name;
        $product->quantity = $default_quantity;
        $product->unit_id = $default_unit;
        //compare price update with price in table for price min and price max
        if ($price < $product->price_min) {
            $product->price_min = $price;
        } else if ($price > $product->price_max) {
            $product->price_max = $price;
        }
        $product->file_img = $imageName;
        $product->category_id = $request->category_id;
        $product->save();

        return Response::json(array(
            'error'=>false,
            'message'=>"Produk berhasil diubah"),
            200
        );
    }

    public function addProduct(Request $request) {
        //set default value
        $default_quantity = 100;
        // convert price to 100/gram
        $quantity = $request->product_quantity;
        $unit_id = $request->unit_id;
        $unit = Unit::find($unit_id);
        $converterObject = Converter::find($unit_id);
        // set default quantity based on unit type
        if ($unit->unit_type == 'common') {
            //search id for unit gram
            $unit_gram = DB::table('unit')
                     ->where('unit', 'gram')
                     ->first();
            $default_unit = $unit_gram->id;
            $converter = $converterObject->in_gram;
            $quantity_in_gram = $quantity * $converter;
            $price = ($quantity_in_gram/$default_quantity) * $request->product_price;
        } else if ($unit->unit_type == 'not common')  {
            $default_quantity = 1;
            $default_unit = $unit_id;
            $price = ($quantity/$default_quantity) * $request->product_price;
        }

        //add image to folder images
        $fileImage = $request->product_image;
        $imageName = time().'_'.$fileImage->getClientOriginalName();
        $fileImage->move(public_path('images/vegetables'), $imageName);

        //add product to database
    	$product = new Product();
    	$product->name = $request->product_name;
    	$product->quantity = $default_quantity;
        $product->unit_id = $default_unit;
    	$product->price_min = $price;
        $product->price_max = $price;    	
    	$product->file_img = $imageName;
    	$product->category_id = $request->category_id;
        $product->save();

        return Response::json(array(
    		'error'=>false,
    		'message'=>"Produk berhasil ditambahkan"),
    		200
    	);
    }
}

