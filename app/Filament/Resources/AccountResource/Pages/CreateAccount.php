<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Aggregates\AccountAggregate;
use App\Filament\Resources\AccountResource;
use App\Models\Account;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $newUuid = Str::uuid()->toString();

        $data['uuid'] = $newUuid;
        $aggregate = AccountAggregate::retrieve($newUuid)
            ->createAccount($data)
            ->persist();

        return Account::whereUuid(collect($aggregate->getAppliedEvents())->last()->accountAttributes['uuid'])
            ->first();
    }
}
