<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function login(): void
    {
        echo 'Hello World';
    }

    public function getUsers()
    {
        $users = User::with(['institution:id,name'])->get();
        return response()->json($users, Response::HTTP_OK);
    }

    public function getUsersById($id)
    {
        $user = User::with(['institution:id,name'])->where('id', $id)->firstOrFail();
        return response()->json($user, Response::HTTP_OK);
    }

    public function createUser(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make('Prueba123$');
        $data['type'] = 1;
        User::create($data);

        return response(status: Response::HTTP_CREATED);
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response(status: Response::HTTP_NOT_FOUND);
        }

        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if (isset($data['type'])) {
            $data['type'] = 1;
        } else {
            unset($data['type']);
        }

        $user->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response(status: Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
