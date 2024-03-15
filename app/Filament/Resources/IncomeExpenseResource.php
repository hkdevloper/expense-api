<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeExpenseResource\Pages;
use App\Filament\Resources\IncomeExpenseResource\RelationManagers;
use App\Models\IncomeExpense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncomeExpenseResource extends Resource
{
    protected static ?string $model = IncomeExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->native(false)
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('currency_id')
                    ->native(false)
                    ->searchable()
                    ->relationship('currency', 'currency_code'),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('spent_on')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Textarea::make('remarks')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->default(0)
                    ->numeric(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('transaction_type')
                    ->native(false)
                    ->options([
                        'Income' => 'Income',
                        'Expense' => 'Expense',
                    ])
                    ->required(),
                Forms\Components\Select::make('created_by')
                    ->native(false)
                    ->relationship('creator', 'name'),
                Forms\Components\TextInput::make('updated_by')
                    ->hidden()
                    ->default(auth()->id())
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.category_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency.currency_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spent_on')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_type')->label('Type'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomeExpenses::route('/'),
            'create' => Pages\CreateIncomeExpense::route('/create'),
            'edit' => Pages\EditIncomeExpense::route('/{record}/edit'),
        ];
    }
}
