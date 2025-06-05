<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems')->where('user_id', Auth::id())->get();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->where('user_id', Auth::id())->findOrFail($id);
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|integer|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'cart',
        ]);

        foreach ($validated['order_items'] as $item) {
            $order->orderItems()->create($item);
        }

        $order->load('orderItems');
        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'status' => 'sometimes|string',
            'order_items' => 'sometimes|array',
            'order_items.*.id' => 'sometimes|integer|exists:order_items,id',
            'order_items.*.product_id' => 'required_with:order_items|integer|exists:products,id',
            'order_items.*.quantity' => 'required_with:order_items|integer|min:1',
            'order_items.*.price' => 'required_with:order_items|numeric|min:0',
        ]);

        if (isset($validated['status'])) {
            $order->status = $validated['status'];
            $order->save();
        }

        if (isset($validated['order_items'])) {
            foreach ($validated['order_items'] as $item) {
                if (isset($item['id'])) {
                    $orderItem = $order->orderItems()->find($item['id']);
                    if ($orderItem) {
                        $orderItem->update($item);
                    }
                } else {
                    $order->orderItems()->create($item);
                }
            }
        }

        $order->load('orderItems');
        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted'], 200);
    }
}
