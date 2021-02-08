<?php

namespace App\Factory;

use App\User;
use Moip\Moip;
use Moip\Auth\OAuth;
use Moip\Auth\Connect;
use Illuminate\Support\Facades\Auth;

class WirecardFactory
{
    /**
     * @return Moip
     */
    public static function getMoip()
    {
        $access_token = config('app.moip.access_token');

        return new Moip(
            new OAuth($access_token),
            config('app.moip.homologated') ? Moip::ENDPOINT_PRODUCTION : Moip::ENDPOINT_SANDBOX
        );
    }

    public static function getConnect($callbackUrl)
    {
        return new Connect(
            $callbackUrl,
            config('app.moip.app_id'),
            true,
            config('app.moip.homologated') ? Connect::ENDPOINT_PRODUCTION : Connect::ENDPOINT_SANDBOX
        );
    }

}
