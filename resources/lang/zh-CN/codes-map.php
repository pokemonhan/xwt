<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/7/2019
 * Time: 8:17 PM
 * Normal Exceptions 1  e.g.100001
 * Database Save 2      e.g.200001
 * I0 Exception 3       e.g.300001
 * System Exception 4   e.g.400001
 */

use Illuminate\Support\Facades\Config;

$errorCodeDir = base_path().'/resources/lang/'.Config::get('app.locale').'/codes-and-messages/';
$aErrorCodeFiles = glob($errorCodeDir.'*.php');
$data = [];
foreach ($aErrorCodeFiles as $skey => $sErrcodeFile) {
    ${'arrError'.$skey} = include $sErrcodeFile;
    if (!empty(${'arrError'.$skey})) {
        if (empty($data)) {
            $data = ${'arrError'.$skey};
        } else {
            $data += ${'arrError'.$skey};
        }
    }
}
unset($aErrorCodeFiles,$skey);
return $data;