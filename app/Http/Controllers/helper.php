<?php

use App\Models\OrderLog;
use App\Models\User;

function defff()
{
    return true;
}


function getExt($file) {
    $file = explode('.', $file);
    $ext = $file[1];
    $val = 'bx-file text-primary ';
    if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' ) {
        $val = 'bx-image text-info ';
    }elseif($ext == 'zip' || $ext == 'rar') {
        $val = 'bx-file-blank text-warning';
    }

    return $val;
}


function generateUserIdentifier($userId)
{
    // You can use the user's ID and a random token or device-specific information to create a unique identifier
    $deviceToken = md5(uniqid(rand(), true));
    return "user_{$userId}_{$deviceToken}";
}

function getCustomerId()
{
    $customer = \Auth::guard('customers')->user();
    return $customer->id;
}



function completeDesign($order_id)
{
    $log = OrderLog::where(['department' => 'designer', 'status' => 'completed', 'order_id' => $order_id])->first();
return $log;
}

function user($id)
{
    return User::find($id);
}