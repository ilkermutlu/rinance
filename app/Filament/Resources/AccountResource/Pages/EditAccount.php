<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Imports\TransactionsImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Facades\Excel;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('importTransactionsFromCSV')
                ->form([
                    FileUpload::make('csv')
                        ->label('CSV')
                        ->storeFiles(false),
                ])
                ->label('CSV')
                ->action(function (array $data) {
                    $file = data_get($data, 'csv');
                    Excel::import(
                        new TransactionsImport($this->record->uuid),
                        $file->getRealPath()
                    );
                })
        ];
    }
}
