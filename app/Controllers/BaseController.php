<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    protected function success(array|object|null $data = null, string $message = 'Success!', string $code = 'SUCCESS', int $httpStatus = Response::HTTP_OK)
    {
        $response = [
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];

        return $this->response->setJSON($response)->setStatusCode($httpStatus);
    }

    protected function fail(array|object|null $data = null, string $message = 'Failed!', string $code = 'FAIL', int $httpStatus = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];

        return $this->response->setJSON($response)->setStatusCode($httpStatus);
    }

    /**
     * A shortcut to performing validation on Request data.
     *
     * @param array|string $rules
     * @param array        $messages An array of custom error messages
     */
    protected function validateRequest($rules, array $messages = [])
    {
        if (! $this->validate($rules, $messages)) {
            return [
                false,
                $this->fail(
                    $this->validator->getErrors(),
                    'Validation Failed',
                    'VALIDATION_FAILED',
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                ),
            ];
        }

        return [true, $this->validator->getValidated()];
    }
}
