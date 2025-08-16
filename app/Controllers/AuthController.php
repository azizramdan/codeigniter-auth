<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Pegawai;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Shield\Entities\User;

class AuthController extends BaseController
{
    public function login()
    {
        [$isValid, $data] = $this->validateRequest([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!$isValid) {
            return $data;
        }

        if (auth()->loggedIn()) {
            auth()->logout();
        }

        $result = auth()->attempt($data);

         if (! $result->isOK()) {
            return $this->fail(message: 'Username atau password salah', httpStatus: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = auth()->user();

        $token = $user->generateAccessToken('login');

        return $this->success([
            'token' => $token->raw_token,
            'user' => $this->selectUserColumns($user),
        ]);
    }

    public function me()
    {
        return $this->success($this->selectUserColumns(auth()->user()));
    }

    private function selectUserColumns(User $user)
    {
        $pegawai = $user->nip
            ? new Pegawai()
                ->select('nip, nama')
                ->where('nip', $user->nip)
                ->first()
            : null;

        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'nip' => $user->nip,
            'pegawai' => $pegawai,
        ];
    }

    public function logout()
    {
        auth()->user()->revokeAccessToken(auth()->getBearerToken());
        
        auth()->logout();

        return $this->success();
    }
}
