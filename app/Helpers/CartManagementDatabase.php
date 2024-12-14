<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Product;
use Auth;
use DB;

class CartManagementDatabase
{
    static public function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFromDatabase();

        $existing_item = $cart_items->firstWhere('product_id', $product_id);

        if ($existing_item) {
            // Item already exists in the cart, increment quantity
            $existing_item->quantity++;
            $existing_item->total_amount = $existing_item->quantity * $existing_item->unit_amount;
            $existing_item->save();  // Save updated cart item
        } else {
            // Item doesn't exist, create a new cart item
            $product = Product::find($product_id);

            if ($product) {
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0],
                    'quantity' => 1,
                    'unit_amount' => $product->final_price,
                    'total_amount' => $product->final_price,
                ]);
            }
        }
        // Return the total quantity
        return self::getCartItemsFromDatabase()->sum('quantity');
    }

    static public function addItemToCartWithQty($product_id, $qty = 1)
    {
        $cart_items = self::getCartItemsFromDatabase();

        $existing_item = $cart_items->firstWhere('product_id', $product_id);

        if ($existing_item) {
            // Item already exists in the cart, update quantity
            $existing_item->quantity = $qty;
            $existing_item->total_amount = $existing_item->quantity * $existing_item->unit_amount;
            $existing_item->save();
        } else {
            // Item doesn't exist, create a new cart item
            $product = Product::find($product_id);

            if ($product) {
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0],
                    'quantity' => $qty,
                    'unit_amount' => $product->final_price,
                    'total_amount' => $product->final_price * $qty,
                ]);
            }
        }
        // Return the total quantity
        return self::getCartItemsFromDatabase()->sum('quantity');
    }

    static public function addItemToCartWithExistingQty($product_id, $qty = 1)
    {
        $cart_items = self::getCartItemsFromDatabase();

        $existing_item = $cart_items->firstWhere('product_id', $product_id);

        if ($existing_item) {
            // Item already exists in the cart, update quantity
            $existing_item->quantity += $qty;
            $existing_item->total_amount = $existing_item->quantity * $existing_item->unit_amount;
            $existing_item->save();
        } else {
            // Item doesn't exist, create a new cart item
            $product = Product::find($product_id);

            if ($product) {
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0],
                    'quantity' => $qty,
                    'unit_amount' => $product->final_price,
                    'total_amount' => $product->final_price * $qty,
                ]);
            }
        }
        // Return the total quantity
        return self::getCartItemsFromDatabase()->sum('quantity');
    }

    static public function removeCartItem($product_id)
    {
        $cart_item = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product_id)
            ->first();

        if ($cart_item) {
            $cart_item->delete();
        }
        return self::getCartItemsFromDatabase();
    }

    // This implementation doesn't follow DCodeMania's cart management logic by design (intended), it adds cart items directly into the database
    static public function addCartItemsToDatabase($cart_items)
    {
        foreach ($cart_items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $product->final_price,
                    'total_amount' => $product->final_price * $item['quantity'],
                ]);
            }
        }
    }

    static public function clearCartItems()
    {
        Cart::whereUserId(Auth::id())->delete();
    }

    static public function getCartItemsFromDatabase()
    {
        return Cart::where('user_id', Auth::user()->id)->get();
    }

    static public function incrementQuantityToCartItem($product_id)
    {
        Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product_id)
            ->increment('quantity');
        Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product_id)
            ->update(['total_amount' => DB::raw('quantity * unit_amount')]);
        return self::getCartItemsFromDatabase();
    }

    static public function decrementQuantityToCartItem($product_id)
    {
        Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product_id)
            ->where('quantity', '>', 1)
            ->decrement('quantity');
        Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product_id)
            ->where('quantity', 1)
            ->delete();
        return self::getCartItemsFromDatabase();
    }

    static public function calculateGrandTotal($selected_cart_items = [])
    {
        if (empty($selected_cart_items)) {
            return 0;
        }
        return Cart::where('user_id', Auth::id())
            ->whereIn('product_id', $selected_cart_items)
            ->selectRaw('SUM(total_amount) as total')
            ->value('total');
    }

    static public function mergeCartFromCookieToDatabase()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart_items_from_cookie = CartManagement::getCartItemsFromCookie();
            $cart_items_from_database = self::getCartItemsFromDatabase();
            foreach ($cart_items_from_cookie as $cart_item_from_cookie) {
                $existing_database_item = $cart_items_from_database->firstWhere('product_id', $cart_item_from_cookie['product_id']);
                if ($existing_database_item) {
                    $existing_database_item->quantity += $cart_item_from_cookie['quantity'];
                    $existing_database_item->total_amount = $existing_database_item->quantity * $existing_database_item->unit_amount;
                    $existing_database_item->save();
                } else {
                    $product = Product::find($cart_item_from_cookie['product_id']);
                    if ($product) {
                        Cart::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'quantity' => $cart_item_from_cookie['quantity'],
                            'name' => $product->name,
                            'image' => $product->images[0],
                            'unit_amount' => $product->final_price,
                            'total_amount' => $product->final_price * $cart_item_from_cookie['quantity'],
                        ]);
                    }
                }
            }
            CartManagement::clearCartItems();
        }
    }
}
