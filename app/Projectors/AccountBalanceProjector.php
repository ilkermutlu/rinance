<?php

namespace App\Projectors;

use App\Events\AccountCreated;
use App\Events\MoneyAdded;
use App\Events\MoneySubtracted;
use App\Models\Account;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class AccountBalanceProjector extends Projector
{
    public function onAccountCreated(AccountCreated $event): void
    {
        (new Account($event->accountAttributes))->writeable()->save();
    }

    public function onMoneyAdded(MoneyAdded $event): void
    {
        $account = Account::whereUuid($event->accountUuid)->first();

        $account->balance += $event->amount;

        $account->writeable()->save();
    }

    public function onMoneySubtracted(MoneySubtracted $event): void
    {
        $account = Account::whereUuid($event->accountUuid)->first();

        $account->balance -= $event->amount;

        $account->writeable()->save();
    }
}
