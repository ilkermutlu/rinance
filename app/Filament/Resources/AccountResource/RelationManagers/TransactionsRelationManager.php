<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->getStateUsing(function ($record) {
                        $money = new Money((int) $record->amount, new Currency('GBP'));
                        $currencies = new ISOCurrencies();

                        $numberFormatter = new \NumberFormatter('en_GB', \NumberFormatter::CURRENCY);
                        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

                        return ($record->type === 'out' ? '-' : '')
                            . $moneyFormatter->format($money);
                    })
                    ->color(fn ($record) => $record->type === 'in' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'in' => 'Money in',
                        'out' => 'Money out',
                    ])
            ],
            layout: FiltersLayout::AboveContent
            );
    }
}
