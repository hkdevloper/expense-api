<?php

namespace App\Filament\Resources\IncomeExpenseResource\Pages;

use App\Filament\Resources\IncomeExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomeExpense extends EditRecord
{
    protected static string $resource = IncomeExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
