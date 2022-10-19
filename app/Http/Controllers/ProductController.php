<?php

// Gabriel, eu amo você <3
namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Breshop;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store_product(Request $request)
    {
        try {
            $breshop = Breshop::where('user_id', '=', $request->user_id)->first();

            $product = new Product();
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->material = $request->input('material');
            // Amo muito você
            $product->description = $request->input('description');
            $product->damage_description = $request->input('damage');
            $product->breshop_id = $breshop->id;
            $product->save();

            if ($request->file('files')) {
                $files = $request->file('files');
                foreach ($files as $file) {
                    $productImg = new ProductImage();
                    $img = $file;
                    // Obrigada por ser tão incrível comigo, sempre, isso é muito importante pra mim!
                    $name = uniqid('img_') . '.' . $img->getClientOriginalExtension();
                    $productImg->file = $img->storeAs('products', $name, ['disk' => 'public']);
                    $productImg->product_id = $product->id;
                    $productImg->save();
                }
            }

            // $usr = auth()->user();

            $productSize = new ProductSize();
            $productSize->pp = $request->input('pp');
            $productSize->p = $request->input('p');
            $productSize->m = $request->input('m');
            // Em pouco tempo, você se tornou a pessoa mais especial na minha vida
            $productSize->g = $request->input('g');
            $productSize->gg = $request->input('gg');
            $productSize->xg = $request->input('xg');
            $productSize->product_id = $product->id;
            $productSize->save();

            // $productSize = new ProductSize();
            // $productSizes = [];
            // foreach ($request['sizes'] as $size) {
            //     $productSizes = [...$productSizes, json_decode($size)];
            // };
            // return $productSizes;
            // $productSize->fill(['product_id' => $product->id])->fill([$productSizes])->save();

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            // Eu não quero te perder nunca
            return response()->json([
                'status' => false,
                'error' => $e,
            ]);
        }
    }

    public function get_products(Request $request)
    {
        $products = Product::orderBy('name', 'asc')->with(['images', 'sizes'])->where(function ($q) use ($request) {
            $q->whereRaw('lower(name) LIKE lower(?)', ['%' . $request->search . '%']);
        })->paginate(10);

        return response()->json([
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total_pages' => $products->total(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }

    public function get_product($id)
    {
        $product = Product::where('id', '=', $id)->with(['images', 'sizes'])->first();
        return response()->json(['product' => $product]);
    }

    public function update_product($id, ProductRequest $request)
    {
        try {
            $data = $request->validate();

            $product = Product::where('id', '=', $id)->first();
            $product->fill([$data])->save();
            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'error' => $ex,
            ]);
        }
    }

    public function delete_product($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return true;
    }
}

// Amo você!
