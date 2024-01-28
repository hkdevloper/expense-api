<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IncomeExpense extends Model
{
    public static array $COLOR_PROFILE = [
        'Income' => '#55dda9',
        'Expense' => '#ffb102'
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class,'category_id');
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
