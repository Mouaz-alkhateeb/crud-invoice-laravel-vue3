<?php

namespace App\Http\Controllers;

use App\Models\Product1;
use Illuminate\Http\Request;
// use Intervention\Image\Facades\Image;
use Image;

class ProductController extends Controller
{
    public function get_all_products()
    {
        $products = Product1::all();
        return response()->json([
            "products" => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $product = new Product1();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($request->photo != "") {
            $strpos = strpos($request->photo, ';');
            $substr = substr($request->photo, 0, $strpos);
            $ex = explode('/', $substr)[1];
            $name = time() . "." . $ex;
            $img = Image::make($request->photo)->resize(200, 200);
            $upload_file = public_path() . "/upload/";
            $img->save($upload_file . $name);
            $product->photo = $name;
        } else {
            $product->photo = "image.png";
        }
        $product->photo = $name;
        $product->save();
    }

    public function show($id)
    {
        $product = Product1::find($id);
        return response()->json([
            "product" => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product1::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($product->photo != $request->photo) {
            $strpos = strpos($request->photo, ';');
            $substr = substr($request->photo, 0, $strpos);
            $ex = explode('/', $substr)[1];
            $name = time() . "." . $ex;
            $img = Image::make($request->photo)->resize(200, 200);
            $upload_file = public_path() . "/upload/";
            $image = $upload_file . $product->photo;
            $img->save($upload_file . $name);

            if (file_exists($image)) {
                unlink($image);
            }
        } else {
            $name = $product->photo;
        }
        $product->photo = $name;
        $product->save();
    }

    public function delete($id)
    {
        $product = Product1::findOrFail($id);
        $image_path = public_path() . "/upload/";
        $image = $image_path . $product->photo;
        if (file_exists($image)) {
            unlink($image);
        }
        $product->delete();
    }
}
