<?php

namespace App\Models;

use App\Models\User;
use App\Traits\ApifyScopeTrait;
use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    use ApifyScopeTrait;

    public static array $DEFAULT_CATEGORIES = [
        ['category_name' => 'Lent', 'category_type' => 'Expense'],
        ['category_name' => 'Loan Return', 'category_type' => 'Expense'],
        ['category_name' => 'Salary', 'category_type' => 'Income'],
        ['category_name' => 'Loan', 'category_type' => 'Income'],
        ['category_name' => 'Lent Return', 'category_type' => 'Income']
    ];

    public function scopeDeletable($query)
    {
        return $query->doesntHave('income')->doesntHave('expense')->whereNotIn('category_name', collect(self::$DEFAULT_CATEGORIES)->pluck('category_name')->toArray());
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function income(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeExpense::class, 'category_id')->where('transaction_type', 'Income');
    }

    public function expense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IncomeExpense::class, 'category_id')->where('transaction_type', 'Expense');
    }
}
