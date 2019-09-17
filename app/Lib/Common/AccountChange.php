<?php
namespace App\Lib\Common;

class AccountChange
{
    public function addData($eloq, $user, $amount, $before_balance, $balance, $typeEloq)
    {
        $insertData = [
            'sign' => $user['sign'],
            'user_id' => $user['id'],
            'top_id' => $user['top_id'],
            'parent_id' => $user['parent_id'],
            'rid' => $user['rid'],
            'username' => $user['username'],
            'type_sign' => $typeEloq->sign,
            'type_name' => $typeEloq->name,
            'amount' => $amount,
            'before_balance' => $before_balance,
            'balance' => $balance,
        ];
        $eloq->fill($insertData);
        $eloq->save();
    }
}
