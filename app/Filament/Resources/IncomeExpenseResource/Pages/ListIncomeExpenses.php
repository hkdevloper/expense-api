<?php

namespace App\Filament\Resources\IncomeExpenseResource\Pages;

use App\Filament\Resources\IncomeExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomeExpenses extends ListRecords
{
    protected static string $resource = IncomeExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
