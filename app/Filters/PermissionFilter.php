<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Shield\Filters\PermissionFilter as FiltersPermissionFilter;

class PermissionFilter extends FiltersPermissionFilter
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (empty($arguments)) {
            return;
        }

        if (! auth()->loggedIn()) {
            return response()
                ->setJSON([
                    'code'    => 'UNAUTHORIZED',
                    'message' => 'Unauthorized',
                    'data'    => null,
                ])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if ($this->isAuthorized($arguments)) {
            return;
        }

        return response()
            ->setJSON([
                'code'    => 'FORBIDDEN',
                'message' => 'Forbidden',
                'data'    => null,
            ])
            ->setStatusCode(Response::HTTP_FORBIDDEN);
    }
}
