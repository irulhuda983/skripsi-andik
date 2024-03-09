<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    
    public function issueToken(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Username dan sandi salah'], 403);
        }

        $token = $user->createToken($request->username);
        return response()->json(['token' => $token->plainTextToken, 'userInfo' => new UserResource($user)]);
    }

    public function getMe(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();
        $rules = [
            'username' => 'required',
            'nama' => 'required',
            'gender' => 'required|in:l,p',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:10240'
        ];

        $request->validate($rules);

        try{
            DB::beginTransaction();

            $foto = $request->foto ? $request->file('foto')->storeAs('users-foto', Str::random(40). '.'.$request->file('foto')->extension()) : $user->foto;

            $updatedData = [
                'nama' => $request->nama,
                'username' => $request->username,
                'gender' => $request->gender,
                'foto' => $foto,
            ];

            if($request->password) {
                $updatedData['password'] = Hash::make($request->password);
            }

            $user->update($updatedData);

            $token = $user->createToken($request->username);
            
            DB::commit();
            return response()->json(['token' => $token->plainTextToken, 'userInfo' => new UserResource($user)]);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function revokeToken(Request $request)
    {
        $user = $request->user();

        if (!$user->currentAccessToken()->delete())
            return response()->json(['error' => 'Terjadi kesalahan'], 500);

        return response()->json(['success' => true]);
    }
}
