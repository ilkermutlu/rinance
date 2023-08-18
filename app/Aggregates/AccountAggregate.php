<?php

namespace App\Aggregates;

use App\Events\AccountCreated;
use App\Events\MoneyAdded;
use App\Events\MoneySubtracted;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class AccountAggregate extends AggregateRoot
{
    private int $balance = 0;

    public function createAccount(array $attributes)
    {
        $this->recordThat(new AccountCreated($attributes));

        return $this;
    }

    public function receiveMoney($accountUuid, int $amount, $date, string $description)
    {
        $this->recordThat(new MoneyAdded($accountUuid, $amount, $date, $description));

        return $this;
    }

    public function spendMoney($accountUuid, int $amount, $date, string $description)
    {
        $this->recordThat(new MoneySubtracted($accountUuid, $amount, $date, $description));

        return $this;
    }

    public function applyMoneyAdded(MoneyAdded $event)
    {
        $this->balance += $event->amount;
    }

    public function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount;
    }
}
