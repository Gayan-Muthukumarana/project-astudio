<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::query()->applyFilters($request, User::getAllowedFilters())->get();

        if ($users->count() == 0) {
            $data['responseMessage'] = 'No data found!';
            $data['responseName'] = 'data';
            $data['data'] = [];

            return $this->successResponse($data, 200);
        }

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $users;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $user;

        return $this->successResponse($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $data['responseMessage'] = 'User created successfully!!';
        $data['responseName'] = 'data';
        $data['data'] = $user;

        return $this->successResponse($data, 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        $data['responseMessage'] = 'User updated successfully!';
        $data['responseName'] = 'data';
        $data['data'] = $user;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        User::destroy($id);

        $data['responseMessage'] = 'User deleted successfully!';
        $data['responseName'] = 'Deleted User ID';
        $data['data'] = $id;

        return $this->successResponse($data, 200);
    }
}
