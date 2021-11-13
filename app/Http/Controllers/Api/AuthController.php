<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{

    /**
     * Users Show
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = User::select('id', 'name', 'email')->orderBy('id', 'DESC')->get();

        return success_response($user, 'Here is all users');
    }


    /**
     * User Create
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);


        if ($validator->fails()) {
            return error_validation($validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return success_response($user->only('name', 'email'), __('message.user.create.success'), '201');
        } catch (Exception $e) {
            return error_response(__('message.user.create.error'));
        }

    }


    /**
     * Show User
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);
        return $user ? success_response($user->only('name', 'email'), 'Find User') : error_response(__('message.user.manage.not_found'));
    }


    /**
     * Update User
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return error_validation($validator->errors());
            }

            try {
                $user->name = $request->name;

                $user->update();

                return success_response($user->only('name', 'email'), __('message.user.update.success'), '201');
            } catch (Exception $e) {
                return error_response(__('message.user.update.error'));
            }
        } else {
            return error_response(__('message.user.manage.not_found'));
        }

    }


    /**
     * Delete User
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return success_response([], __('message.user.manage.deleted'));
        } else {
            return error_response(__('message.user.manage.not_found'));
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return error_validation($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if ($token = auth()->attempt($credentials)) {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'user' => [
                    'user' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ]
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    /**
     * Logged out user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out.']);
    }
}
