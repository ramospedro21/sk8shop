<?php

namespace App\Helpers;

use App\Factory\WirecardFactory;
use App\Models\ErrorsLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WirecardHelper
{

    public static function setCustomer($id)
    {

        try {

            # INICIA A COMUNICAÇÃO COM O MOIP
            $moip = WirecardFactory::getMoip();

            # ENCONTA O USUÁRIO SOLICITADO
            $user = User::with(['details', 'address'])->find($id);

            # DEFINE O ID DELE NA WIRECARD
            $gateway_id = $user->details->gateway_id;

            # SE AINDA NÃO TIVER
            if(!$gateway_id){

                # PEGA O ENDEREÇO
                $user->address = $user->address->first();

                # CONSIGURA O CONTATO
                $customer = $moip->customers()
                                    ->setOwnId(Str::random(10))
                                    ->setFullname($user->name)
                                    ->setEmail($user->email)
                                    ->setBirthDate($user->details->birthdate)
                                    ->setTaxDocument($user->details->tax_document_number)
                                    ->setPhone($user->details->phone_area_code, $user->details->phone_number, 55)
                                    ->addAddress(
                                        "SHIPPING",
                                        $user->address->street,
                                        $user->address->street_number,
                                        $user->address->district,
                                        $user->address->city,
                                        $user->address->state,
                                        $user->address->zipcode,
                                        $user->address->complement
                                    )
                                    ->create();

                # ARMAZENA O GATEWAY RETORNADO
                $gateway_id = $customer->getId();
                $user->details->gateway_id = $gateway_id;
                $user->details->save();

            }

            # RETORNA O USUÁRIO NO MOIP
            return $moip->customers()->get($gateway_id);

        }
        catch (\Moip\Exceptions\UnautorizedException $e)
        {

            $error_log = new ErrorsLog;
            $error_log->description = date("d/m/Y H:i:s") . " User: " . $id . ". Erro: " . $e->getMessage();
            $error_log->save();

        }
        catch (\Moip\Exceptions\ValidationException $e)
        {

            $error_log = new ErrorsLog;
            $error_log->description = date("d/m/Y H:i:s") . " User: " . $id . ". Erro: " . $e->__toString();
            $error_log->save();

        }
        catch (\Moip\Exceptions\UnexpectedException $e)
        {

            $error_log = new ErrorsLog;
            $error_log->description = date("d/m/Y H:i:s") . " User: " . $id . ". Erro: " . $e->getMessage();
            $error_log->save();

        }
        catch (\Exception $e) {

            $error_log = new ErrorsLog;
            $error_log->description = date("d/m/Y H:i:s") . " User: " . $id . ". Erro: " . $e->getMessage();
            $error_log->save();

        }

        return false;

    }

}
