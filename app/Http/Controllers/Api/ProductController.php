<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $validated = $request->validate([
            'keyword' => ['nullable'],
            'store_id' => ['exists:stores,id'],
            'category_id' => ['exists:categories,id'],
            'price' => [Rule::in(['asc', 'desc'])],
            'group_by_store' => ['boolean']
        ]);

        $filters = [
            'keyword' => ($validated['keyword'] ?? false),
            'store_id' => ($validated['store_id'] ?? false),
            'category_id' => ($validated['category_id'] ?? false),
            'price' => ($validated['price'] ?? false),
            'group_by_store' => ($validated['group_by_store'] ?? false),
        ];

        if ($filters['group_by_store']) {
            $response['data'] = ['products' => ($filters['price']) ? Store::productFilter($filters)->get() : Store::productFilter($filters)->inRandomOrder()->get()];
            return response()->json($response, $code);
        }

        $products = Product::filter($filters);
        if ($filters['store_id'] && $filters['price']) {
            $response['data'] = ['products' => $products->orderByRaw("CAST(price AS int) " . $filters['price'])->get()];
            return response()->json($response, $code);
        }
        if ($filters['store_id']) {
            $response['data'] = ['products' => $products->get()];
            return response()->json($response, $code);
        }
        if ($filters['price']) {
            $response['data'] = ['products' => $products->orderByRaw("CAST(price AS int) " . $filters['price'])->get()];
            return response()->json($response, $code);
        }

        $response['data'] += ['products' => $products->inRandomOrder()->get()];
        return response()->json($response, $code);
    }

    public function show(Request $request, $id)
    {
        $code = 200;
        $status = 'OK';
        $response = [
            'code' => $code,
            'status' => $status,
            'data' => []
        ];

        $filters = [];
        if (is_numeric($id)) {
            $filters += ['id' => $id];
        } else {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }
        if ($request->user()->seller) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }

        $product = Product::filter($filters)->first();
        if (!$product) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }
        $response['data'] += ['product' => $product];
        return response()->json($response, $code);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'price' => ['required', 'numeric', 'digits_between:3,6'],
            'image' => ['image', 'file', 'max:10000']
        ]);

        $validated['store_id'] = $request->user()->seller->store->id;

        if ($request->file('image')) {
            $request->file('image')->store('uploads/products/images');
            $validated['image'] = $request->image->hashName();
        }

        $price_rounded = ((int)$validated['price'] < 500) ? 500 : round((int)$validated['price'] / 500) * 500;

        $admin_price = (int)$validated['price'] * 5 / 100;
        $admin_price_rounded = ($price_rounded * 5 / 100 < 500) ? 500 : round($price_rounded * 5 / 100 / 500) * 500;

        $tax_price = (int)$validated['price'] * 10 / 100;
        $tax_price_rounded = ($price_rounded * 10 / 100 < 500) ? 500 : round($price_rounded * 10 / 100 / 500) * 500;

        $user_price = $tax_price + $admin_price + (int)$validated['price'];
        $user_price_rounded = $price_rounded + $admin_price_rounded + $tax_price_rounded;

        $validated['price_rounded'] = $price_rounded;
        $validated['admin_price'] = $admin_price;
        $validated['admin_price_rounded'] = $admin_price_rounded;
        $validated['tax_price'] = $tax_price;
        $validated['tax_price_rounded'] = $tax_price_rounded;
        $validated['user_price'] = $user_price;
        $validated['user_price_rounded'] = $user_price_rounded;

        Product::create($validated);

        $code = 201;
        $response = [
            'code' => $code,
            'status' => 'CREATED',
            'data' => []
        ];
        return response()->json($response, $code);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => ['exists:categories,id'],
            'name' => ['max:255'],
            'description' => ['max:255'],
            'price' => ['numeric', 'digits_between:3,6'],
            'image' => ['image', 'file', 'max:10000']
        ]);
        $validated['store_id'] = $request->user()->seller->store->id;

        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        if (is_numeric($id)) {
            $filters += ['id' => $id];
        } else {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }
        $filters += ['store_id' => $validated['store_id']];

        $product = Product::filter($filters)->first();
        if (!$product) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        if ($request->file('image')) {
            if ($product->image) {
                Storage::delete('uploads/products/images/' . $product->image);
            }
            $request->file('image')->store('uploads/products/images');
            $validated['image'] = $request->image->hashName();
        }

        if ($validated['price'] ?? false) {
            $price_rounded = ((int)$validated['price'] < 500) ? 500 : round((int)$validated['price'] / 500) * 500;

            $admin_price = (int)$validated['price'] * 5 / 100;
            $admin_price_rounded = ($price_rounded * 5 / 100 < 500) ? 500 : round($price_rounded * 5 / 100 / 500) * 500;

            $tax_price = (int)$validated['price'] * 10 / 100;
            $tax_price_rounded = ($price_rounded * 10 / 100 < 500) ? 500 : round($price_rounded * 10 / 100 / 500) * 500;

            $user_price = $tax_price + $admin_price + (int)$validated['price'];
            $user_price_rounded = $price_rounded + $admin_price_rounded + $tax_price_rounded;

            $validated['price_rounded'] = $price_rounded;
            $validated['admin_price'] = $admin_price;
            $validated['admin_price_rounded'] = $admin_price_rounded;
            $validated['tax_price'] = $tax_price;
            $validated['tax_price_rounded'] = $tax_price_rounded;
            $validated['user_price'] = $user_price;
            $validated['user_price_rounded'] = $user_price_rounded;
        }

        Product::where('id', $product->id)->update($validated);

        return response()->json($response, $code);
    }

    public function delete(Request $request, $id)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        if (is_numeric($id)) {
            $filters += ['id' => $id];
        } else {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] += [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }
        $filters += ['store_id' => $request->user()->seller->store->id];

        $product = Product::filter($filters)->first();
        if (!$product) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Produk dari id tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        Product::destroy($product->id);
        return response()->json($response, $code);
    }

    public function image(Request $request, $image_name)
    {
        $filters = [];
        $filters += ['image' => $image_name];
        if ($request->user()->seller) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }

        $product = Product::filter($filters)->first();
        if (!$product && $image_name != 'default.png') {
            $code = 404;
            $response = [
                'code' => $code,
                'status' => 'NOT FOUND',
                'data' => [
                    'message' => 'Gambar produk dengan nama ' . $image_name . ' tidak dapat ditemukan.'
                ]
            ];
            return response()->json($response, $code);
        }

        return response()->file('storage/uploads/products/images/' . $image_name);
    }
}
