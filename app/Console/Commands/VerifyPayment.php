<?php

namespace App\Console\Commands;

use App\Models\ErrorsLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Factory\WirecardFactory;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderPaymentLog;
use App\Models\OrderShippingLog;
use GuzzleHttp\Client;

class VerifyPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:verifypayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $order_payments = OrderPayment::query()
			->whereIn('gateway_status', ['WAITING', 'IN_ANALYSIS'])
            ->whereNotNull('gateway_id')
            ->get();

        foreach ($order_payments as $order_payment) {

			DB::beginTransaction();

                $moip = WirecardFactory::getMoip();

                $payment = $moip->payments()->get($order_payment->gateway_id);

                $order_payment->gateway_status = $payment->getStatus();
                $order_payment->save();

                if ($order_payment->gateway_status == 'AUTHORIZED') {

                    $order = Order::findOrFail($order_payment->order_id)->update([
                        'status' => Order::STATUS['APROVED']
                    ]);

                } else if ($order_payment->gateway_status === 'CANCELLED') {

                    $order = Order::findOrFail($order_payment->order_id)->update([
                        'status' => Order::STATUS['CANCELLED']
                    ]);

                }


			DB::commit();

        }

    }

}
