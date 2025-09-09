<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function login(LoginRequest $request)
    {
        $request->validated();
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => $user,
            'status' => Response::HTTP_OK,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ], Response::HTTP_OK);
    }

    public function getUsers()
    {
        $users = User::with(['institution:id,name'])->get();

        return response()->json($users, Response::HTTP_OK);
    }

    public function getUsersById($id)
    {
        $user = User::with(['institution:id,name,street,external_number,internal_number,neighborhood,postal_code,country,city,google_maps,type,id_state,id_municipality,id_subsystem,id_academic_period', 'institution.state:id,name', 'institution.municipality:id,name', 'institution.subsystem:id,name', 'institution.academicPeriod:id,name'])->where('id', $id)->firstOrFail();

        return response()->json($user, Response::HTTP_OK);
    }

    public function createUser(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
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

    public function getProfile()
    {
        $user = Auth::user()->load([
            'institution:id,name,id_state,id_municipality,id_subsystem',
            'institution.state:id,name',
            'institution.municipality:id,name',
            'institution.subsystem:id,name',
        ]);

        return response()->json($user, Response::HTTP_OK);
    }
}
