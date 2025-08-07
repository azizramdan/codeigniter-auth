<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Response;

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
            'user' => $user,
        ]);
    }

    public function me()
    {
        return $this->success(auth()->user());
    }

    public function logout()
    {
        $user = auth()->user();
    
        $header = $this->request->getHeaderLine('Authorization');
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }

        if ($token) {
            $user->revokeAccessToken($token);
        }
        
        auth()->logout();

        return $this->success();
    }
}
