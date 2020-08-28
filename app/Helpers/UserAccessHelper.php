<?php

namespace App\Helpers;
use Illuminate\Support\Facades\URL;
use Auth;

use App\Models\ModuleValue;
use App\Models\UserModule;

class UserAccessHelper {

    public static function getAccess()
    {
        try{

            $currentURL = URL::current();
            
            $url = explode("/", $currentURL);
    
            $moduleValue = ModuleValue::where('link', 'like', '%' . end($url) . '%')->first();
    
            $userAccess = UserModule::where('module_value_id', $moduleValue->id)
                                    ->where('user_type_id', Auth::user()->user_type_id)
                                    ->first();

            if($userAccess)
                return true;
            else
                return false;

        }catch(\Exception $e){
            dd($e);
        }
    }

}
