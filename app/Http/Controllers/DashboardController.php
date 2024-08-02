<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function index()
    {

        $monthlyEarnings = DB::table('orders')
            ->whereBetween('orders.created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->join('stocks', 'stock_id', '=', 'stocks.id')
            ->join('products', 'stocks.id', '=', 'stock_id')
            ->select(
                DB::raw('sum(price) as sums'),
                DB::raw("DATE_FORMAT(orders.created_at,'%M') as months")
            )
            ->groupBy('months')
            ->orderBy('orders.id', 'desc')
            ->get();

        $annualEarnings = DB::table('orders')
            ->join('stocks', 'stock_id', '=', 'stocks.id')
            ->join('products', 'stocks.id', '=', 'stock_id')
            ->select(
                DB::raw('sum(price) as sums'),
                DB::raw("DATE_FORMAT(orders.created_at,'%Y') as years")
            )
            ->groupBy('years')
            ->get();

        $ordersCompletedRatio = Order::where('status', 'completed')->count() / Order::count();

        $ordersPending = Order::where('status', 'pending')->count();

        $revenueSources = DB::table('orders')
            ->leftJoin('stocks', 'orders.stock_id', '=', 'stocks.id')
            ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                DB::raw('categories.name as name'),
                DB::raw('COALESCE(sum(orders.quantity),0) total')
            )
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();


        $data = [
            'monthly_earnings' => $monthlyEarnings,
            'annual_earnings' => $annualEarnings,
            'orders_completed_ratio' => $ordersCompletedRatio,
            'orders_pending' => $ordersPending,
            'revenue_sources' => $revenueSources,
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($request->localCartList) {
            $cartList = json_decode($request->localCartList, true);

            foreach ($cartList as $cartArrayList) {
                foreach ($cartArrayList as $cartItem) {
                    $item = $user->cartItems()->where('stock_id', $cartItem['stock_id'])->first();

                    if (!$item) {
                        ShoppingCart::create([
                            'user_id' => $user->id,
                            'stock_id' => $cartItem['stock_id'],
                            'quantity' => $cartItem['quantity']
                        ]);
                    } else {
                        $stock = Stock::findOrFail($request->stockId);

                        if (($item->quantity + $request->quantity) <= $stock->quantity) {
                            $item->increment('quantity', $request->quantity);
                        } else {
                            $item->update(['quantity' => $stock->quantity]);
                        }
                    }
                }
            }
        }

        return $user->cartItems()->count();
    }
    public function guestCart(Request $request)
    {
        $cartList = json_decode($request->input('cartList'), true);
        $data = [];
        $count = 1;

        foreach ($cartList as $cartArrayList) {
            foreach ($cartArrayList as $cartItem) {
                if ($cartItem['stock_id'] != null && $cartItem['quantity'] != null) {
                    $stock = Stock::with('product')->where('id', $cartItem['stock_id'])->first();
                    $data[] = [
                        'id' => $count,
                        'stock_id' => $cartItem['stock_id'],
                        'quantity' => $cartItem['quantity'],
                        'stock' => $stock
                    ];
                    $count++;
                }
            }
        }

        return $data;
    }

    public function update(Request $request, $id)
    {
        $cartItem = ShoppingCart::with("stock")->where('id', $id)->first();
        $stockQty = $cartItem->stock->quantity;

        if ($request->quantity < $stockQty && $request->quantity > 0) {
            ShoppingCart::where('id', $id)->update(['quantity' => $request->quantity]);
        }
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            $cartItem = $user->cartItems()->findOrFail($id);

            if ($cartItem) {
                $cartItem->delete();
            }
        }

        return $cartItem;
    }

    public function cartCount(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->cartItems()->pluck('stock_id')->toArray();
    }
}
