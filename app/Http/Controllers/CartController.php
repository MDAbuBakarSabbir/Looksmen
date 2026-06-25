<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class CartController extends Controller
{
    public function cartView()
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        } else {
            $cart = session()->get('cart', []);
        }
        return view('Frontend.cart.cartView', compact('cart'));
    }


    public function addToCart(Request $request)
    {
        $product = Product::with(['productAttributes.attribute', 'productColors.color'])->findOrFail($request->id);

        $hasAttributes = $product->productAttributes->count() > 0;
        $hasColors = $product->productColors->count() > 0;
        // যদি এট্রিবিউট থাকে এবং ইউজার সিলেক্ট না করে পাঠায়
        if (($hasAttributes || $hasColors) && !$request->has('option_selected')) {
            return response()->json([
                'status' => 'show_options',
                'view' => view('Frontend.cart.partials.attributeModal', compact('product'))->render(),

            ]);
        }
        $attribute = $request->attribute_value ?? '';
        $color = $request->color_name ?? '';
        $qty = (int) ($request->quantity > 0 ? $request->quantity : 1);
        $cleanAttr = preg_replace("/[^A-Za-z0-9]/", "", $attribute);
        $cleanColor = preg_replace("/[^A-Za-z0-9]/", "", $color);
        $cartId = $request->id . $cleanColor . $cleanAttr;

        if (auth()->check()) {
            $userId = auth()->id();

            // আগে থেকে কার্টে আছে কি না চেক করুন
            $dbCart = Cart::where('user_id', $userId)
                ->where('cart_id', $cartId) // মডেলে এই কলামটি যোগ করবেন
                ->first();

            if ($dbCart) {
                $dbCart->quantity += $qty;
                $dbCart->save();
            } else {
                Cart::create([
                    'user_id'    => $userId,
                    'cart_id'    => $cartId,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'attributes'  => $attribute,
                    'color'      => $color,
                    'status'     => 1,
                    'created_at' => now()
                ]);
            }

            $totalCount = Cart::where('user_id', $userId)->count();
        }
        // --- সেশন লজিক (গেস্ট ইউজার হলে) ---
        else {


            $cart = session()->get('cart', []);
            if (isset($cart[$cartId])) {
                $cart[$cartId]['quantity'] += $qty;
            } else {
                // $cartId = $product->id . '-' . md5($request->attribute_id . $request->color_id); // আপনি চাইলে এখানে আইডি+কালার কম্বিনেশন দিয়ে ইউনিক কি বানাতে পারেন

                $cart[$cartId] = [
                    "id"    => (int) $product->id,
                    "name" => $product->title,
                    'code' => $product->code,
                    "quantity" => $qty,
                    'stock' => (int)$product->stock,
                    "price" => (float)$product->new_price,
                    "image" => $product->thumbnail_img,
                    "attribute" => $attribute, // যেমন: Size: M
                    "color" => $color,
                ];
            }
            session()->put('cart', $cart);
            $totalCount = count($cart);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart!',
            'cart_count' => $totalCount
        ]);
    }

    public function showModal()
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        } else {
            $cart = session()->get('cart', []);
        }
        return view('Frontend.cart.partials.cart_details', compact('cart'));
    }

    public function updateCart(Request $request)
    {
        // $cart = session()->get('cart');
        $id = $request->id;
        $newQty = (int)$request->quantity;

        if (auth()->check()) {
            $cart = Cart::with('product')->where('user_id', auth()->id())->where('cart_id', $id)
                        ->first();
            if ($cart) {
            // ২. কোয়ান্টিটি আপডেট এবং সেভ
            $cart->quantity = $newQty;
            $cart->save();

            // ৩. লাইন টোটাল এবং সাবটোটাল হিসাব করা
            // (আপনার প্রোডাক্ট রিলেশন ব্যবহার করে লেটেস্ট প্রাইস নেওয়া)
            $price = (float) str_replace(',', '', $cart->product->new_price);
            $line_total = $price * $newQty;

            // ৪. ওই ইউজারের সব আইটেমের সাবটোটাল বের করা
            $allCartItems = Cart::where('user_id', auth()->id())->get();
            $subtotal = 0;
            foreach ($allCartItems as $item) {
                $itemPrice = (float) str_replace(',', '', $item->product->new_price);
                $subtotal += ($itemPrice * $item->quantity);
            }
            return response()->json([
                'status' => 'success',
                'line_total' => $line_total,
                'subtotal' => $subtotal
            ]);
            }

        } else {
            $cart = session()->get('cart');
            if (isset($cart[$id])) {
            if ($newQty > $cart[$id]['stock']) {
                return response()->json(['status' => 'error', 'message' => 'Stock limit exceeded'], 400);
            }
            $cart[$id]['quantity'] = $newQty;
            session()->put('cart', $cart);

            // নতুন হিসাব
            $price = (float) str_replace(',', '', $cart[$id]['price']);
            $quantity = (int) $cart[$id]['quantity'];
            $line_total = $price * $quantity;

            $subtotal = 0;
            foreach ($cart as $item) {
                $itemPrice = (float) str_replace(',', '', $item['price']);
                $itemQty = (int) $item['quantity'];
                $subtotal += ($itemPrice * $itemQty);
            }

            return response()->json([
                'status' => 'success',
                'line_total' => $line_total,
                'subtotal' => $subtotal
            ]);
            }
        }


        return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
    }

    public function removeFromCart(Request $request)
    {
        if (auth()->check()) {

        } else {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['status' => 'success']);
        }

    }

    // কার্টের আইটেম সংখ্যা রিটার্ন করার জন্য
    public function getCartCount()
    {
        if (auth()->check()) {
            $count = Cart::where('user_id', auth()->id())->count();
        } else {
            $cart = session()->get('cart', []);
            $count = count($cart);
        }
        return response()->json(['count' => $count]);
    }
}
