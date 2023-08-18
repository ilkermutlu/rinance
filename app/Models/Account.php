<?php

namespace App\Models;

use App\Events\AccountCreated;
use App\Events\MoneyAdded;
use App\Events\MoneySubtracted;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Projections\Projection;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class Account extends Projection
{
    protected $guarded = [];

    public static function createWithAttributes(array $attributes): Account
    {
        $attributes['uuid'] = (string) Uuid::uuid4();

        event(new AccountCreated($attributes));

        return static::uuid($attributes['uuid']);
    }

    public function addMoney(int $amount)
    {
        event(new MoneyAdded($this->uuid, $amount));
    }

    public function subtractMoney(int $amount)
    {
        event(new MoneySubtracted($this->uuid, $amount));
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function storedEvents()
    {
        return $this->hasMany(EloquentStoredEvent::class, 'aggregate_uuid');
    }
}
