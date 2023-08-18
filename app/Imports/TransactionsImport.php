<?php

namespace App\Imports;

use App\Aggregates\AccountAggregate;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\DecimalMoneyParser;

class TransactionsImport implements ToCollection
{
    public function __construct(private string $accountUuid)
    {}

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $transaction) {
            $currencies = new ISOCurrencies();
            $moneyParser = new DecimalMoneyParser($currencies);

            $amount = $transaction[2];

            if (gettype($amount) === 'string') {
                $amount = Str::replace(',', '', $amount);
            }

            $money = $moneyParser->parse($amount, new Currency('USD'));

            $date = $transaction[0];
            $description = $transaction[1];

            if ($money->getAmount() < 0) {
                AccountAggregate::retrieve($this->accountUuid)
                    ->spendMoney($this->accountUuid, abs((int) $money->getAmount()), $date, $description)
                    ->persist();
            } else {
                AccountAggregate::retrieve($this->accountUuid)
                    ->receiveMoney($this->accountUuid, $money->getAmount(), $date, $description)
                    ->persist();
            }
        }
    }
}
