<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class registerController extends Controller
{
    /**
     * Register user
     *
     * @bodyParam name string required User name
     * @bodyParam email string required User email
     * @bodyParam password string required User password
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'currency_id' => 13,
            'password' => bcrypt($request->password)
        ]);

        DB::table('transaction_categories')->insert([
            ['category_name' => 'Utilities', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Groceries', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Entertainment', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Healthcare', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Transportation', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Other Expenses', 'category_type' => 'Expense', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Salary', 'category_type' => 'Income', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Freelance Income', 'category_type' => 'Income', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Investment Returns', 'category_type' => 'Income', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Gifts Received', 'category_type' => 'Income', 'created_by' => $user->id, 'updated_by' => $user->id],
            ['category_name' => 'Other Income', 'category_type' => 'Income', 'created_by' => $user->id, 'updated_by' => $user->id],
        ]);
        return response(['data' => 'registration_successful'], 201);
    }
}
