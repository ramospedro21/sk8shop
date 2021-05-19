<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factory\WirecardFactory;

use App\Helpers\StockHelper;
use App\Helpers\WirecardHelper;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductStock;
use App\Models\ErrorsLog;
use App\Models\OrderPayment;

use Illuminate\Support\Facades\DB;

use Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['details'])->find(Auth::user()->id);
        return view('checkout', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            DB::beginTransaction();

            // FORMATAÇÃO DO ANIVERSÁRIO
            $birthdate = str_replace('/', '-', $request->input('details.birthdate'));


            // FORMATAÇÃO DO TELEFONE
            $phone = str_replace('(', '', $request->input('details.cellphone'));
            $phone = str_replace(')', '', $phone);
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('-', '', $phone);

            $phone_area_code = substr($phone, 0, 2);
            $phone_number = substr($phone, 2);


            $details = $this->makeUserDetail($phone_area_code, $phone_number, $request, $birthdate);
            $address = $this->makeUserAddress($request);


            $user = Auth::user();
            $user['details'] = $details;
            $user['address'] = $address;
            $user = $user->toArray();

            $shipping_price = floatval($request['cart']['cartShipping']);

            $order = $this->makeOrder($request);

            $order->products = collect();

            foreach($request['cart']['products'] as $product){

                if(!isset($stock)){

                    $stock = ProductStock::with(['stock'])->where([
                        ['variant_id', $product['id']],
                        ['quantity', '>', 0],
                    ])->first();

                    if($stock){

                        $stock = $stock->stock;

                    }else{

                        $error_log = new ErrorsLog;
                        $error_log->description = date("d/m/Y H:i:s") . " Erro: produto sem estoque";
                        $error_log->save();

                        return response()->json('Produto sem estoque', 500);

                    }

                }

                StockHelper::updateStock($stock->id, $product['product_id'], $product['id'], $product['quantity'], 0, 'reserved');

                if($product['stocks'][0]['promote_price'] == '0.00'){
                    $product_price = $product['stocks'][0]['price'];
                } else {
                    $product_price = $product['stocks'][0]['promote_price'];
                }

                # SALVAR PRODUTO DO PEDIDO
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'variant_id' => $product['id'],
                    'title' => $product['product']['title'],
                    'price' => floatval($product_price),
                    'discount' => $product['discount'],
                    'quantity' => $product['quantity'],
                    'amount' => (floatval($product_price) - floatval($product['discount'])) * $product['quantity'],
                ]);

                # INFORMAR SKU PARA UTILIZAR NO PAGAMENTO
                $orderProduct['sku'] = $product['sku'];

                # ADICIONAR NO ARRAY DE PRODUTOS CADASTRADOS PARA O PEDIDO
                $order->products->push($orderProduct);

            }

            try{

                $moip = WirecardFactory::getMoip();

                //inicia o pedido moip
                $orderMoip = $moip->orders()->setOwnId(uniqid());

                foreach ($order->products as $order_product) {

                    $orderMoip->addItem($order_product->title, $order_product->quantity, $order_product['sku'], intval(number_format($order_product->price, 2, '', '')));

                }

                # CONFIGURA O CUSTOMER COM BASE NO ID LOGADO
                $customer = WirecardHelper::setCustomer(Auth::user()->id);

                if(!$customer) {
                    return response()->json([
                        "Erro ao gerar Customer"
                    ], 500);
                }

                $orderMoip = $orderMoip->setShippingAmount(intval(number_format($shipping_price, 2, "", "")))->setCustomer($customer);

                $orderMoip = $orderMoip->create();

                $user = User::with(['details'])->find(Auth::user()->id);

                if ($request['payment']['type'] == "credit-card") {

                    $paymentPhone = str_replace('(', '', $request->input('payment.cardPhoneNumber'));
                    $paymentPhone = str_replace(')', '', $paymentPhone);
                    $paymentPhone = str_replace(' ', '', $paymentPhone);
                    $paymentPhone = str_replace('-', '', $paymentPhone);

                    $explodeBirthdate = explode('/', $request['payment']['cardBirthdate']);

                    $paymentBirthdate = $explodeBirthdate[2].'-'.$explodeBirthdate[1].'-'.$explodeBirthdate[0];

                    $paymentDocument = str_replace('.', '', $request['payment']['cardTaxNumber']);
                    $paymentDocument = str_replace('-', '', $paymentDocument);

                    $holder = $moip->holders()
                        ->setFullname($request['payment']['cardFullName'])
                        ->setTaxDocument($paymentDocument, 'CPF')
                        ->setPhone(substr($paymentPhone, 0, 2), substr($paymentPhone, 2), 55)
                        ->setBirthdate($paymentBirthdate);

                    $payment = $orderMoip->payments()
                        ->setCreditCardHash($request['payment']['hash'], $holder)
                        ->setInstallmentCount($request['payment']['installment'])
                        ->setStatementDescriptor("SK8shop")
                        ->execute();

                    $orderPayment = OrderPayment::create([
                        'order_id' => $order->id,
                        'gateway_id' => $payment->getId(),
                        'gateway_brand' => "credit-card",
                        'gateway_status' => 'WAITING',
                        'installments' => $request['payment']['installment'],
                        'shipping_price' => $shipping_price,
                        'amount' => floatval($request['cart']['cartAmount']),
                    ]);

                } else if ($request['payment']['type'] == "barcode") {

                    $logo_uri = url('/images/logo.jpg');
                    $expiration_date = date('Y-m-d', strtotime('+1 day'));

                    $instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];
                    $payment = $orderMoip->payments()
                        ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
                        ->execute();


                    $orderPayment = OrderPayment::create([
                        'order_id' => $order->id,
                        'gateway_id' => $payment->getId(),
                        'installments' => 1,
                        'gateway_brand' => "boleto",
                        'gateway_status' => 'WAITING',
                        'shipping_price' => $shipping_price,
                        'barcode_url' => $payment->getHrefPrintBoleto(),
                        'barcode_number' => $payment->getLineCodeBoleto(),
                        'barcode_expiration_date' => date('Y-m-d', strtotime('+1 day')),
                        'amount' => $request['cart']['cartAmount'],
                    ]);

                } else {throw new \Exception('Payment Method is not valid (' . $request['payment']['type'] . ')');}

                DB::commit();

                // $user->notify(new OrderStatus($order, $user, 1));

                return response()->json([
                    "order" => $order,
                    "payment" => $orderPayment,
                ], 200);
            }
            catch (\Moip\Exceptions\UnautorizedException $e)
            {
                $error_log = new ErrorsLog;
                $error_log->description = date("d/m/Y H:i:s") . " Erro: " . $e->getMessage();
                $error_log->save();

                return response()->json([
                    'message' => 'Não foi possivel realizar o pagamento',
                    'errors' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine()
                    ]
                ], 500);

            }
            catch (\Moip\Exceptions\ValidationException $e)
            {
                $error_log = new ErrorsLog;
                $error_log->description = date("d/m/Y H:i:s") . " Erro: " . $e->__toString();
                $error_log->save();

                return response()->json([
                    'message' => 'Não foi possivel realizar o pagamento',
                    'errors' => [
                        'message' => $e->__toString(),
                        'line' => $e->getLine()
                    ]
                ], 500);

            }
            catch (\Moip\Exceptions\UnexpectedException $e)
            {
                $error_log = new ErrorsLog;
                $error_log->description = date("d/m/Y H:i:s") . " Erro: " . $e->getMessage();
                $error_log->save();

                return response()->json([
                    'message' => 'Não foi possivel realizar o pagamento',
                    'errors' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine()
                    ]
                ], 500);

            }
            catch (\Exception $e) {

                $error_log = new ErrorsLog;
                $error_log->description = date("d/m/Y H:i:s") . " Erro: " . $e->getMessage();
                $error_log->save();

                return response()->json([
                    'message' => 'Não foi possivel realizar o pagamento',
                    'errors' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine()
                    ]
                ], 500);

            }

        }catch(\Exception $error){
            dd($error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function makeUserDetail($phone_area_code, $phone_number, $request, $birthdate){

        return UserDetail::updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                "birthdate" => date('Y-m-d', strtotime($birthdate)),
                "tax_document_type" => "CPF",
                "tax_document_number" => $request->input('details.tax_document_number'),
                "identity_document_number" => null,
                "identity_document_type" => 'RG',
                "phone_country_code" => '55',
                "phone_area_code" => $phone_area_code,
                "phone_number" => $phone_number,
            ]
        );

    }

    private function makeUserAddress($request){
        return UserAddress::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'zipcode' => $request->input('address.zipcode'),
                'street_number' => $request->input('address.street_number'),
            ],
            [
                "city" => $request->input('address.city'),
                "district" => $request->input('address.district'),
                "state" => $request->input('address.state'),
                "street" => $request->input('address.street'),
                "complement" => $request->input('address.complement'),
                "street_number" => $request->input('address.street_number'),
                "zipcode" => $request->input('address.zipcode'),
                "ibge" => $request->input('address.ibge'),
                'country' => 'BRA',
            ]
        );
    }

    private function makeOrder($request){
        return Order::create([
            'user_id' => Auth::user()->id,
            'stock_id' => 1,
            'name' => Auth::user()->name,
            'tax_document_type' => "CPF",
            'tax_document_number' => $request['details']['tax_document_number'],
            'phone' => $request['details']['cellphone'],
            'ip_address' => $request->ip(),
            'street' => $request['address']['street'],
            'street_number' => $request['address']['street_number'],
            'complement' => isset($request['address']['complement']) ? $request['address']['complement'] : '',
            'district' => $request['address']['district'],
            'city' => $request['address']['city'],
            'state' => $request['address']['state'],
            'zipcode' => $request['address']['zipcode'],
            'agency' => isset($request['cart']['shipping']['agency']) ? $request['cart']['shipping']['agency'] : null,
            'delivery_code' => isset($request['cart']['shipping']['code']) ? $request['cart']['shipping']['code'] : '',
            'delivery_service' => isset($request['cart']['shipping']['title']) ? $request['cart']['shipping']['title'] : '',
            'status' => 1,
        ]);
    }
}
