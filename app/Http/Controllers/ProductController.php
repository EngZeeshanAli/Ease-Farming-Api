<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexProduct()
    {
        $products = Product::paginate(10);
        $users = array();
        foreach ($products as $product) {
            $users[] = $product->getUser;
        }
        return response()->json($products);
    }

    public function myProduct(Request $request)
    {
        $id = $request->id;
        $products = Product::where(Constants::USER_ID, $id)->get();
        $users = array();
        foreach ($products as $product) {
            $users[] = $product->getUser;
        }
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::NAME => Constants::REQUIRED,
            Constants::PRICE => Constants::REQUIRED,
            Constants::QUANTITY => Constants::REQUIRED,
            Constants::USER_ID => Constants::REQUIRED,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            try {
                $product = new Product;
                $product->name = $request->name;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->user_id = $request->user_id;
                $product->save();
                return response()->json(["success" => "Product Added Successfully."]);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::ID => Constants::REQUIRED,
            Constants::NAME => Constants::REQUIRED,
            Constants::PRICE => Constants::REQUIRED,
            Constants::QUANTITY => Constants::REQUIRED,
            Constants::USER_ID => Constants::REQUIRED,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            try {
                $product = Product::find($request->id);
                $product->name = $request->name;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->user_id = $request->user_id;
                $product->save();
                return response()->json(["success" => "Product Updated Successfully."]);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            Product::find($id)->delete();
            return response()->json(["success" => "Product Deleted Successfully."]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}
