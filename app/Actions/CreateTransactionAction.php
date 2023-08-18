<?php

namespace App\Actions;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;

class CreateTransactionAction
{
    public function execute($event)
    {
        $date = Carbon::createFromFormat('d/m/Y', $event->date);

        Transaction::create([
            'date' => $date,
            'amount' => $event->amount,
            'description' => $event->description,
            'account_id' => Account::whereUuid($event->accountUuid)->first()->id,
            'type' => $event->type,
        ]);
    }
}
