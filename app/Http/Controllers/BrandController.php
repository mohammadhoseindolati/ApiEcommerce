<?php

namespace App\Http\Controllers;

use Execption;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BrandResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'display_name' => 'required|unique:brands,display_name'
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages() , 422);
        }

        DB::beginTransaction() ;

        $brand = Brand::create([
            'name' => $request->name ,
            'display_name' => $request->display_name ,
        ]);

        DB::commit();

        return $this->successResponse( new BrandResource($brand) , 201) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
