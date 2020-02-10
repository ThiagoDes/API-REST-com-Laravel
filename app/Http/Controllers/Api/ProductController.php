<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\API\ApiError;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product){

        $this->product = $product;

    }

    public function index(){

        $data = ['data' => $this->product::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $product = $this->product->find($id);
            if (! $product) return response()->json(ApiError::errorMessage('Produto não encontrado!', 4040), 404);

            $data = ['data' => $product];
            return response()->json($data);
    }

    public function store(Request $request){

        try {
            $productData = $request->all();
            $this->product->create($productData);

            return ['data' => ['msg' => 'Produto criado com sucesso!']];

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação', 1010));
        }

    }

    public function update(Request $request, $id){

        try {
            $productData = $request->all();
            $product = $this->product->find($id);
            $product->update($productData);

            $return = ['data' => ['msg' => 'Produto atualizado com sucesso!']];
            return response()->json($return, 201);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação atualização', 1011));
        }

    }

    public function delete(Product $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Produto'. $id->name .'Excluidor com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
