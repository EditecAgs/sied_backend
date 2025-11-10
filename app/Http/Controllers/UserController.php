<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

use App\Models\BitacoraAcceso;

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

        BitacoraAcceso::create([
        'user_id' => $user->id,
        'accion' => 'login',
        'ip' => $request->ip(),
        'navegador' => $request->header('User-Agent'),
        'fecha_hora' => now(),
     ]);

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => $user,
            'status' => Response::HTTP_OK,
        ]);
    }

        public function logout()
    {
        $user = Auth::user();

        if ($user) {
            \App\Models\BitacoraAcceso::create([
                'user_id' => $user->id,
                'accion' => 'logout',
                'ip' => request()->ip(),
                'navegador' => request()->header('User-Agent'),
            ]);

            $user->tokens()->delete();
        }

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
        $user = User::with([
            'institution:id,name,type,id_state,id_municipality,id_subsystem,id_academic_period',
            'institution.state:id,name',
            'institution.municipality:id,name',
            'institution.subsystem:id,name',
            'institution.academicPeriod:id,name'
        ])
            ->select('id', 'name', 'lastname', 'id_institution', 'type', 'email')
            ->where('id', $id)
            ->firstOrFail();

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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $user->load([
            'institution:id,name,id_state,id_municipality,id_subsystem',
            'institution.state:id,name',
            'institution.municipality:id,name',
            'institution.subsystem:id,name',
        ]);

        return response()->json($user, Response::HTTP_OK);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->validated();

        // Solo actualiza campos permitidos
        $allowedFields = ['name', 'lastname', 'email', 'password', 'id_institution'];
        $data = array_intersect_key($data, array_flip($allowedFields));

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->load(['institution:id,name'])
        ], Response::HTTP_OK);
    }

}
