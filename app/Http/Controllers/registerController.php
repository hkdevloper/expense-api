<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class registerController extends Controller
{
    /**
     * Register user
     *
     * @bodyParam name string required User name
     * @bodyParam email string required User email
     * @bodyParam password string required User password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
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

        DB::table('transaction_categories')->insert(
            collect(TransactionCategory::$DEFAULT_CATEGORIES)->map(function ($item) use ($user) {
                return array_merge($item, ['created_by' => $user->id]);
            })->toArray()
        );
        return response(['data' => 'registration_successful'], 201);
    }
}
