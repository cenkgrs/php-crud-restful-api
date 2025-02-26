<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DateTime;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'customer_id' => 'required|integer',
            'products' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Sipariş oluşturulamadı'], 200);
        }

        $total_price = 0;

        // Check requested products
        foreach ($input['products'] as $p) {
            $product = Product::where('id', $p['id'])->first();

            if (!$product) {
                return response()->json(['status' => false, 'message' => 'Sipariş oluşturulamadı, seçilen ürün bulunamadı'], 200);
            }

            if ($product->stock < $p['quantity']) {
                return response()->json(['status' => false, 'message' => "Sipariş oluşturulamadı, $product->name isimli üründe yeterli stok bulunmuyor"], 200);
            }

            $total_price += $product->price * $p['quantity'];
        }

        $order_id = Order::insertGetId([
            'customer_id' => $input['customer_id'],
            'total_price' => $total_price,
            'created_at' => new DateTime
        ]);

        foreach ($input['products'] as $p) {
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $p['id'],
                'quantity' => $p['quantity'],
                'created_at' => new DateTime
            ]);

            // Decrement stock
            $product = Product::where('id', $p['id'])->first();
            $product->stock = $product->stock - $p['quantity'];
            
            $product->save();
        }

        return response()->json(['status' => true, 'message' => 'Sipariş oluşturuldu'], 200);
    }

    public function getOrders()
    {
        $orders = Order::with('order_items')->get();

        return response()->json(['orders' => $orders], 200);
    }

    public function deleteOrder(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Sipariş bulunamadı'], 200);
        }

        $order = Order::where('id', $input['id'])->first();

        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Sipariş bulunamadı'], 200);
        }

        OrderItem::where('order_id', $input['id'])->delete();

        $order->delete();
        
        return response()->json(['status' => true, 'message' => 'Sipariş silindi'], 200);
    }
}
