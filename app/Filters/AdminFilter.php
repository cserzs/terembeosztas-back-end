<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface {
    
    public function before(RequestInterface $request, $arguments = null) {
        $loginManager = new \App\Libraries\LoginManager();
        if (!$loginManager->isLoggedIn()) {
            return redirect()->to(site_url('/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}