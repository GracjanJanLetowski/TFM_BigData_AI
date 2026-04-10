<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Item;
use App\Models\Rating;
use Faker\Factory as Faker;

class OrderAndRatingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $users = User::where('role', 'client')->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Cada usuario hace entre 1 y 4 pedidos
            $numOrders = $faker->numberBetween(1, 4);
            
            for ($o = 0; $o < $numOrders; $o++) {
                $orderTotal = 0;
                $randomDate = $faker->dateTimeBetween('-30 days', 'now');
                
                $order = new Order();
                $order->setTotal(0);
                $order->setUserId($user->getId());
                $order->setCreatedAt($randomDate);
                $order->setUpdatedAt($randomDate);
                $order->save();

                // Cada pedido tiene entre 1 y 3 productos
                $numItems = $faker->numberBetween(1, 3);
                $orderProducts = $products->random($numItems);

                foreach ($orderProducts as $product) {
                    $quantity = $faker->numberBetween(1, 2);
                    $price = $product->getPrice();
                    
                    $item = new Item();
                    $item->setQuantity($quantity);
                    $item->setPrice($price);
                    $item->setOrderId($order->getId());
                    $item->setProductId($product->getId());
                    $item->setCreatedAt($randomDate);
                    $item->setUpdatedAt($randomDate);
                    $item->save();

                    $orderTotal += ($quantity * $price);
                }

                $order->setTotal($orderTotal);
                $order->save();
            }

            // Además de comprar, cada usuario valora entre 2 y 5 productos
            $ratedProducts = $products->random($faker->numberBetween(2, 5));
            foreach ($ratedProducts as $product) {
                Rating::create([
                    'user_id' => $user->getId(),
                    'product_id' => $product->getId(),
                    'rating' => $faker->numberBetween(2, 5),
                ]);

                // NUEVO: Crear comentario para NLP
                $sentimientos = [
                    'Me encanta este producto, es increíble y de gran calidad.',
                    'Excelente compra, muy contento con el resultado.',
                    'Cumple su función, pero podría ser mejor.',
                    'No me gusta, mala calidad y llegó tarde.',
                    'Horrible, no lo recomiendo para nada, una decepción.',
                    'Espectacular, el mejor que he tenido hasta ahora.',
                ];
                
                \App\Models\Comment::create([
                    'user_id' => $user->getId(),
                    'product_id' => $product->getId(),
                    'comment' => $faker->randomElement($sentimientos),
                ]);
            }
        }
        
        $this->command->info('Órdenes, Items y Ratings simulados con éxito para la IA.');
    }
}
