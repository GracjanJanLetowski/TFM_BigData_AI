<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderAdminNotificationMail;

class CartController extends Controller
{

    //El carrito es permanente, se guarda en una cookie durante 30 días, y al comprar se borra.
    //Si cambias de usuario tambien se ve el carrito, ya que la cookie es guarda en el navegador como tal.
    //Y si cambias de navegador ya no se vería el carrito, ya que la cookie se guarda en los navegadores

    //Y para comprar productos tiene que estar mailhog encendido, si no da errores
    //Comento la parte del envio de correos para seguir haciendo cosas sin mailhog encendido

    public function index(Request $request)
    {
        $total = 0;
        $productsInCart = [];
    
        $productsInCookie = json_decode($request->cookie('cart_products', '{}'), true);
    
        if (!empty($productsInCookie)) {
            $productsInCart = Product::findMany(array_keys($productsInCookie));
            $total = Product::sumPricesByQuantities($productsInCart, $productsInCookie);
        }
    
        $viewData = [];
        $viewData["title"] = "Cart - Online Store";
        $viewData["subtitle"] = "Shopping Cart";
        $viewData["total"] = $total;
        $viewData["products"] = $productsInCart;
    
        return view('cart.index')->with("viewData", $viewData);
    }
    

    public function add(Request $request, $id)
    {
        $quantity = $request->input('quantity', 1);
    
        $products = json_decode($request->cookie('cart_products', '{}'), true);
    
        $products[$id] = isset($products[$id]) ? $products[$id] + $quantity : $quantity;
    
        return redirect()->route('cart.index')->withCookie(cookie('cart_products', json_encode($products), 43200));
    }
    
    public function delete(Request $request)
    {
        return back()->withCookie(cookie()->forget('cart_products'));
    }

    public function purchase(Request $request)
    {
        $productsInCookie = json_decode($request->cookie('cart_products', '{}'), true);
    
        if ($productsInCookie) {
            $user = Auth::user();
            $total = 0;
    
            $productsInCart = Product::findMany(array_keys($productsInCookie));
            foreach ($productsInCart as $product) {
                $quantity = $productsInCookie[$product->getId()];
                $total += $product->getPrice() * $quantity;
            }
    
            if ($user->getBalance() < $total) {
                return redirect()->route('cart.index')->with('error', 'No tienes suficiente dinero.');
            }
    
            $order = new Order();
            $order->setUserId($user->getId());
            $order->setTotal($total);
            $order->save();
    
            foreach ($productsInCart as $product) {
                $quantity = $productsInCookie[$product->getId()];
                $item = new Item();
                $item->setQuantity($quantity);
                $item->setPrice($product->getPrice());
                $item->setProductId($product->getId());
                $item->setOrderId($order->getId());
                $item->save();

                // NUEVO: Actualizar stock del producto para la IA de Inventario
                $product->stock = $product->stock - $quantity;
                $product->save();
            }
    
            $user->setBalance($user->getBalance() - $total);
            $user->save();
    
            return redirect()->route('purchase.completed', ['order' => $order])->withCookie(cookie()->forget('cart_products'));
        }
    
        return redirect()->route('cart.index');
    }
    
    public function purchaseCompleted($orderId)
    {
        $order = Order::find($orderId);
    
        if ($order) {
            $viewData = [
                'title' => __('auth.purchase_completed'),
                'subtitle' => __('auth.congratulations_purchase', ['order_id' => $order->id]),
                'order' => $order
            ];
            return view('cart.purchase', compact('viewData'));
        }
    
        return redirect()->route('cart.index')->with('error', 'Order not found.');
    }
    
}
