<?php

namespace App\Filament\Resources\IncomeExpenseResource\Pages;

use App\Filament\Resources\IncomeExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomeExpense extends CreateRecord
{
    protected static string $resource = IncomeExpenseResource::class;
}
