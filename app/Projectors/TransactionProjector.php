<?php

namespace App\Projectors;

use App\Actions\CreateTransactionAction;
use App\Events\MoneyAdded;
use App\Events\MoneySubtracted;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TransactionProjector extends Projector
{
    public function onMoneyAdded(MoneyAdded $event): void
    {
        (new CreateTransactionAction)->execute($event);
    }

    public function onMoneySubtracted(MoneySubtracted $event): void
    {
        (new CreateTransactionAction)->execute($event);
    }
}
