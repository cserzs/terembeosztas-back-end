<?php
namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class ApiFilter implements FilterInterface {
    
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null) {
        if ($request->getMethod() != "get") {
            $loginManager = new \App\Libraries\LoginManager();
            if ( !$loginManager->isLoggedIn())
            {
                return Services::response()
                    ->setJSON(['error' => 'Access Denied'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}