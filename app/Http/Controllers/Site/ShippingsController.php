<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Box;

class ShippingsController extends Controller
{
    public function calculate(Request $request){

        try{

            $shippings = collect();
            $packagingUse = collect();

            $volume = 0;
            $weight_amount = 0;
            $additional_days_delivery = 0;

            // recebe os produtos enviados
            $products = $request->products;

            // passa em cada produto
            foreach ($products as $product) {

                $quantity = $product['quantity'];

                $product['crossdocking'] = 0;

                if ($product['crossdocking'] > $additional_days_delivery) $additional_days_delivery = $product['crossdocking'];

                $volume += $this->float_convert($product['product']['height']) * $this->float_convert($product['product']['width']) * $this->float_convert($product['product']['depth']) * $quantity;

                $weight_amount += $this->float_convert($product['weight']) * $quantity;

            }

            $volumeTotal = round($volume, 2);

            // captura as embalagens disponíveis
            $packaging = $this->getPackaging();

            // seleciona a maior embalagem
            $larger_packing = $packaging->first();

            if($larger_packing){

                // enquanto o volume total for maior que o volume da maior caixa
                while($volumeTotal > $larger_packing->volume){

                    // adiciona o uso da maior embalagem
                    $packagingUse->push([
                        'depth' => $larger_packing->depth,
                        'height' => $larger_packing->height,
                        'width' => $larger_packing->width,
                    ]);

                    // subtrai a quantidade alocada
                    $volumeTotal = $volumeTotal - $larger_packing->volume;

                }

                // executa o loop de embalagens da menor para a maior
                foreach($packaging->reverse() as $packing){

                    // se o volume couber na embalagem
                    if($volumeTotal <= $packing->volume && $volumeTotal > 0){

                        // adiciona o uso da embalagem
                        $packagingUse->push([
                            'depth' => $packing->depth,
                            'height' => $packing->height,
                            'width' => $packing->width,
                        ]);

                        // subtrai a quantidade alocada
                        $volumeTotal = $volumeTotal - $packing->volume;

                    }
                }
            }else{
                $packagingUse->push([
                    'depth' => 22,
                    'height' => 22,
                    'width' => 22
                ]);
            }

            $weight = ($weight_amount <= 0) ? 0.1 : $weight_amount;
            $height = $packagingUse->sum('height');
            $width = $packagingUse->sortBy('width')->first()['width'];
            $depth = $packagingUse->sortBy('depth')->first()['depth'];
            $sum = $height + $width + $depth;
            $declared_value = 0;

            // Regras do correios
            $minDepth = 15;
            $maxHeight = 100;
            $maxSum = 200;

            $depth = $minDepth <= $depth ? $depth : $minDepth;

            if($height > $maxHeight || $sum > $maxSum){

                $shippings = $this->getShippings($request, strval($weight), strval($maxHeight), strval($depth), strval($width), $shippings, $additional_days_delivery);

            } else {

                $shippings = $this->getShippings($request, strval($weight), strval($height), strval($depth), strval($width), $shippings, $additional_days_delivery);

            }

            if($shippings->count() > 0){
                return response()->json([
                    "shippings" => $shippings
                ], 200);
            } else{
                return response()->json([
                    'error' => "Não encontramos condições de frete."
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'erro' => $e->getMessage(),
                'line' => $e->getLine()
            ], 200);
        }
    }

    private function getShippings($request, $weight, $height, $depth, $width, $shippings, $additional_days_delivery)
    {

        $services = collect([
            ['code' => 4553, 'title' => 'SEDEX'],
            ['code' => 40215, 'title' => 'SEDEX 10'],
            ['code' => 41106, 'title' => 'PAC'],
        ]);

        foreach($services as $service){


            $cod_nCdEmpresa =  "08082650";
            $cod_sDsSenha = "564321";
            $cod_servico = $service['code'];
            $zipcode_from = "12243360";
            $zipcode_to = $request->zipcode_to;

            $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=".$cod_nCdEmpresa."&sDsSenha=".$cod_sDsSenha."&sCepOrigem=".$zipcode_from."&sCepDestino=".$zipcode_to."&nVlPeso=".$weight."&nCdFormato=1&nVlComprimento=".$depth."&nVlAltura=".$height."&nVlLargura=".$width."&sCdMaoPropria=n&nVlValorDeclarado=0&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";

            $xml = simplexml_load_file($correios);

            $price = number_format(floatval(str_replace(',', '.', str_replace('.', '', $xml->cServico->Valor))), 2);

            if($xml->cServico->Erro == '0'){

                $shippings->push([
                    'title' => ''.$service['title'].'',
                    'code' => ''.$xml->cServico->Codigo.'',
                    'price' => $price,
                    'deadline' => ($xml->cServico->PrazoEntrega + 3 + $additional_days_delivery) . ' dias úteis',
                ]);

            } else if (($xml->cServico->Erro == '011') || ($xml->cServico->Erro == '010')) {
                $shippings->push([
                    'nome' => $service['nome'] ." - Área com restrição de entrega.",
                    'code' => ''.$xml->cServico->Codigo.'',
                    'price' => $price,
                    'deadline' => ($xml->cServico->PrazoEntrega + 1 + $additional_days_delivery) . ' dias úteis',
                    'owner_id' => $owner ? $owner['owner']['id'] : null
                ]);
            }

        }

        return $shippings;

    }

    private function float_convert($string)
    {

        $pos = strpos($string, 'R$');

        if ($pos === false) {
          return $string;
        } else {
          $numeros = str_replace('R$', '', $string);
          $numeros = str_replace('.', '', $numeros);
          return floatval(str_replace(',', '.', $numeros));
        }

    }

    private function getPackaging () {

        $packaging = Box::get();

        $packaging->map(function($package, $i){

            $package->volume = $package->width * $package->height * $package->depth;
            return $package;

        });

        return $packaging->sortByDesc('volume');

    }
}
