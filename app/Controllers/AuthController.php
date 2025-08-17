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

        // username-password login need session authenticator
        $authService = auth('session');

        if ($authService->loggedIn()) {
            $authService->logout();
        }

        $result = $authService->attempt($data);

         if (! $result->isOK()) {
            return $this->fail(message: 'Username atau password salah', httpStatus: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $authService->user();

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

        $userGroups = $user->getGroups();
        $matrix = setting('AuthGroups.matrix');
        $groupLevelPermissions = [];

        foreach ($userGroups as $group) {
            $groupLevelPermissions = array_merge($groupLevelPermissions, $matrix[$group] ?? []);
        }

        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'nip' => $user->nip,
            'groups' => $userGroups,
            'permissions' => array_merge($groupLevelPermissions, $user->getPermissions()),
            'pegawai' => $pegawai,
        ];
    }

    public function changePassword()
    {
        [$isValid, $data] = $this->validateRequest([
            'password_old' => ['required', 'string'],
            'password_new' => ['required', 'string'],
        ]);

        if (!$isValid) {
            return $data;
        }

        $user = auth()->user();

        $authService = auth('session');
        $isOldPasswordValid = $authService->attempt([
            'username' => $user->username,
            'password' => $data['password_old']
        ]);

        if (! $isOldPasswordValid->isOK()) {
            return $this->fail(message: 'Old password is not valid', httpStatus: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->setPassword($data['password_new']);
        $user->saveEmailIdentity();

        return $this->success();
    }

    public function logout()
    {
        auth()->user()->revokeAccessToken(auth()->getBearerToken());
        
        auth()->logout();

        return $this->success();
    }
}
