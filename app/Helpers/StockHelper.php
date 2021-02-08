<?php

namespace App\Helpers;

use App\Models\ProductStock;

class StockHelper {

        public static function updateStock(
            $stock_id,
            $product_id,
            $variant_id,
            $quantity,
            $factory_price,
            $action
        )
        {

            $productStock = ProductStock::where([
                ['stock_id', $stock_id],
                ['product_id', $product_id],
                ['variant_id', $variant_id],
            ])->get()->first();

            // se o estoque existir
            if($productStock){

                // se o comando for de entrada
                if($action == 'input'){

                    if($factory_price){
                        $productStock->factory_price = $factory_price;
                    }

                    $productStock->quantity = $productStock->quantity + $quantity;

                }

                // se o comando for de reserva
                if($action == 'reserved'){
                    $productStock->quantity = $productStock->quantity - $quantity;
                    $productStock->reserved = $productStock->reserved + $quantity;
                }

                // se o comando for saida de transferencia
                if($action == 'output-transfer'){
                    $productStock->quantity = $productStock->quantity - $quantity;
                }

                // se o comando for de saida
                if($action == 'output'){
                    $productStock->reserved = $productStock->reserved - $quantity;
                }

                return $productStock->save();

            } else {

                try{

                    $productStock = ProductStock::create([
                        "stock_id" => $stock_id,
                        "product_id" => $product_id,
                        "variant_id" => $variant_id,
                        "quantity" => $quantity,
                        "reserved" => 0,
                        "price" => 0,
                        "saleprice" => 0,
                        "factory_price" => $factory_price,
                    ]);

                    return $productStock;

                } catch(\Exception $e) {

                    return response()->json([
                        'message' => "Erro ao alterar estoque!"
                    ], 500);

                }


            }

        }

    }
