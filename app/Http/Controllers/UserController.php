<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Get users
     *
     * @url /api/v1/user
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Show a user
     *
     * @urlParam id required User id to show Example: 1
     *
     * @url /api/v1/user/{id}
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        if (Auth::user()->is_admin || Auth::id() == $id) {
            return response()->json(User::with('currency')->find($id));
        }

        return response()->json(['error' => 'unauthorised'], 403);
    }

    /**
     * Update user
     *
     * @urlParam id required User id to update Example: 1
     * @bodyParam name string required Username Example: Cir
     * @bodyParam email string required User email Example: cir@email.com
     * @bodyParam currency_id int required User currency id Example: 1
     *
     * @url /api/v1/user/{id}
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($id)
            ],
            'currency_id' => 'required',
        ]);

        $userById = User::find($id);
        $userById->name = $request->name;
        $userById->email = $request->email;
        $userById->currency_id = $request->currency_id;
        $userById->save();

        return response()->json(['data' => 'user_updated', 'request' => $request->all()]);
    }

    /**
     * Update logged in user
     *
     * @bodyParam name string required Username Example: Tries
     * @bodyParam email string required User email Example: tiss@email.com
     * @bodyParam currency_id int required User currency id Example: 13
     *
     * @url /api/v1/user/update
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore(Auth::id())
            ],
            'currency_id' => 'required',
        ]);

        $userById = User::find(Auth::id());
        $userById->name = $request->name;
        $userById->email = $request->email;
        $userById->currency_id = $request->currency_id;
        $userById->save();

        return response()->json(['data' => 'user_updated', 'request' => $request->all()]);
    }

    /**
     * Update password
     *
     * @bodyParam old_password string required Old password Example: 123456
     * @bodyParam new_password string required New password Example: 234567
     * @bodyParam confirm_password string required Confirm password Example: 234567
     *
     * @url /api/v1/user/password
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|min:6|max:100',
            'new_password' => 'required|min:6|max:100|same:confirm_password',
            'confirm_password' => 'required|min:6|max:100',
        ]);

        $old_password = Auth::user()->password;

        if (Hash::check($request->old_password, $old_password)) {
            if (Hash::check($request->new_password, $old_password)) {
                return response()->json(['data' => 'old_password'], 422);
            } else {
                $authUser = Auth::user();
                $authUser->password = Hash::make($request->new_password);
                $authUser->save();

                return response()->json(['data' => 'password_changed']);
            }
        } else {
            return response()->json(['data' => 'password_mismatch'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        if (Auth::user()->is_admin) {
            $userById = User::find($id);

            if ($userById->delete()) {
                return response()->json(['data' => 'user_deleted']);
            }
        }

        return response()->json(['error' => 'unauthorised'], 403);
    }
}
