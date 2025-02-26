<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DateTime;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DiscountRules;

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

    public function createDiscountRule(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'price' => 'required_without:category_id|numeric',
            'count' => 'integer',
            'category_id' => 'required_without:price|integer',
            'discount_type' => 'required|in:giveaway,percentange,fixed',
            'discount_amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Kural oluşturulamadı'], 200);
        }

        DiscountRules::insert([
            'name' => $input['name'],
            'price' => $input['price'] ?? 0.0,
            'count' => $input['count'] ?? 1,
            'category_id' => $input['category_id'] ?? null,
            'discount_type' => $input['discount_type'],
            'discount_amount' => $input['discount_amount'],
            'created_at' => new DateTime
        ]);

        return response()->json(['status' => true, 'message' => 'Kural oluşturuldu'], 200);
    }

    // Gets order id and return discount informations
    public function checkDiscount(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Sipariş bulunamadı'], 200);
        }

        $order = Order::with('order_items')->where('id', $input['id'])->first();

        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Sipariş bulunamadı'], 200);
        }

        $discounts = [];
        $total_discount_amount = 0;

        $sub_total = $order->total_price;    

        // Check Price rules first
        $price_rules = DiscountRules::where('price', '<', $order->total_price)->get();

        if ($price_rules) {
            $result = $this->checkRules($price_rules, $order, $discounts, $total_discount_amount, $sub_total);
        
            $discounts = $result['discounts'];
            $total_discount_amount = $result['total_discount_amount'];
            $sub_total = $result['sub_total'];
        }

        foreach ($order->order_items as $item) {
            $product = Product::where('id', $item->product_id)->first();

            $category_rules = DiscountRules::where('category_id', $product->category_id)->where('count', '<=', $item->quantity)->get(); 
        
            if ($category_rules) {
                $result = $this->checkRules($category_rules, $order, $discounts, $total_discount_amount, $sub_total);
        
                $discounts = $result['discounts'];
                $total_discount_amount = $result['total_discount_amount'];
                $sub_total = $result['sub_total'];
            }
        }

        $response = [
            'order_id' => $order->id,
            'discounts' => $discounts,
            'total_discount' => $total_discount_amount,
            'discounted_total_price' => $sub_total
        ];

        return response()->json(['status' => true, 'response' => $response], 200);

    }

    private function checkRules($rules, $order, $discounts, $total_discount_amount, $sub_total)
    {
        foreach ($rules as $rule) {

            switch ($rule->discount_type) {
                case 'giveaway':
                    
                    $result = $this->getGiveAway($order);

                    if ($result) {

                        $total_discount_amount += $result;
                        $sub_total = $sub_total - $result;

                          // Add discount
                          $discounts[] = [
                            'discountReason' => $rule->name,
                            'discountAmount' => $result,
                            'subTotal' => $sub_total,
                        ];
                    }

                    break;

                case 'percentange':

                    $discount_amount = ($sub_total * $rule->discount_amount) / 100; 

                    $sub_total -= $discount_amount;
                    $total_discount_amount += $discount_amount;

                    // Add discount
                    $discounts[] = [
                        'discountReason' => $rule->name,
                        'discountAmount' => $discount_amount,
                        'subTotal' => $sub_total,
                    ];

                    break;
                
                case 'fixed':

                    $sub_total -= $rule->discount_amount;
                    $total_discount_amount += $rule->discount_amount;
                    
                    // Add discount
                    $discounts[] = [
                        'discountReason' => $rule->name,
                        'discountAmount' => $rule->discount_amount,
                        'subTotal' => $sub_total,
                    ];
            }
        }

        return ['discounts' => $discounts, 'total_discount_amount' => $total_discount_amount, 'sub_total' => $sub_total];
    }
    
    private function getGiveAway($order)
    {
        $min = PHP_INT_MAX;

        foreach ($order->order_items as $item) {

            $product = Product::where('id', $item->product_id)->first();

            if (!$product) {
                continue;
            }

            if ($product->price < $min) {
                $min = (float) $product->price;
            }
        }

        if ($min !== PHP_INT_MAX) {
            return $min;
        }

        // Min priced item can not be found
        return false;
    }
}
