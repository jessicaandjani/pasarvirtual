<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Response;
use File;

class CategoryController extends Controller {
	
	public function imageUpload() {
		return view('image-upload');
	}


    public function getAllCategory() {
    	//retrieve all categories from datbase
    	$categories = Category::all();
    	
    	return Response::json(array(
    		'categories'=>$categories->toArray()),
    		200
    	);
    }

    public function getCategory(Request $request, $id) {
    	$category = Category::find($id);

    	return Response::json(array(
    		'error'=>false,
    		'category'=>$category),
    		200
    	);
    }

    public function addCategory(Request $request) {
        $category = new Category();
        //add image to folder images
        $fileImage = $request->category_image;
        $imageName = time().'_'.$fileImage->getClientOriginalName();
        $fileImage->move(public_path('images/categories'), $imageName);
        //add category to database
        $category->name = $request->category_name;
        $category->file_img = $imageName;
        $category->save();

        return Response::json(array(
            'error'=>false,
            'message'=>"Kategori berhasil ditambahkan"),
            200
        );
    }

    public function updateCategory(Request $request, $id) {
		$category = Category::find($id);
		//delete old image
		$oldImage = $category->file_img;
        $pathFile = public_path('images/categories/') . $oldImage;
        File::delete($pathFile);
		//add image to folder images
		$fileImage = $request->category_image;
		$imageName = time().'_'.$fileImage->getClientOriginalName();        
        $fileImage->move(public_path('images/categories'), $imageName);
        //add category to database
		$category->name = $request->category_name;
		$category->file_img = $imageName;
		$category->save();

		return Response::json(array(
    		'error'=>false,
    		'message'=>"Kategori berhasil diubah"),
    		200
    	);
	}
}
