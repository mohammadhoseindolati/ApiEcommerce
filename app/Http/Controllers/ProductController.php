<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(20);

        return $this->successResponse([
            'products' => ProductResource::collection($products->load('images')) ,
            'links' => ProductResource::collection($products)->response()->getData()->links ,
            'meta' => ProductResource::collection($products)->response()->getData()->meta ,
        ], 200) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|string',
            'brand_id' => 'required|integer',
            'category_id' => 'required|integer',
            'primary_image' => 'required|image',
            'price' => 'integer',
            'quantity' => 'integer',
            'delivery_amount' => 'integer',
            'description' => 'required',
            'images.*' => 'nullable|image'
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages() , 422);
        }

        DB::beginTransaction() ;

        $primaryImageName = Carbon::now()->microsecond . '.'. $request->primary_image->extension();
        $request->primary_image->storeAs('images/products' , $primaryImageName , 'public');


        if ($request->has('images')){
            $fileNameImages = [] ;
            foreach ($request->images as $image) {
                $fileImageName = Carbon::now()->microsecond . '.' . $image->extension();
                $image->storeAs('images/products' , $fileImageName , 'public');
                array_push( $fileNameImages, $fileImageName ) ;
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'primary_image' => $primaryImageName,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'delivery_amount' => $request->delivery_amount,
            'description' => $request->description,
        ]);

        if ($request->has('images')){
            foreach ($fileNameImages as $fileNameImage) {
                ProductImage::create([
                    'product_id' =>  $product->id,
                    'image' => $fileNameImage
                ]);
            }
        }

        DB::commit();

        return $this->successResponse( new ProductResource($product) , 201) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->successResponse(new ProductResource($product->load('images')) , 200) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
