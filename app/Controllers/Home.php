<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;

class Home extends BaseController
{
    public function index()
    {
        return $this->success(message: 'Hello World!');
    }

    public function http404()
    {
        return $this->fail(code: 'NOT_FOUND', message: 'Not Found', httpStatus: Response::HTTP_NOT_FOUND);
    }
}
