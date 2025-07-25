<?php

namespace Tests\Middleware;

use PHPUnit\Framework\TestCase;
use App\Http\Middleware\RbacMiddleware;
use Core\Http\Request;
use Core\Http\Response;

class RbacMiddlewareTest extends TestCase
{
    public function testAccessDeniedForUnauthorizedRole()
    {
        $rolesPermissions = [
            'admin' => ['/admin/dashboard'],
            'user' => ['/user/profile']
        ];

        $middleware = new RbacMiddleware();
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
                ->method('getUserRole')
                ->willReturn('guest');

        $request->expects($this->any())
                ->method('getRoute')
                ->willReturn('/admin/dashboard');

        $response = $middleware->handle($request, function ($req) {
            return new Response("OK", 200);
        });

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Access Denied", $response->getContent());
    }

    public function testAccessGrantedForAuthorizedRole()
    {
        $rolesPermissions = [
            'admin' => ['/admin/dashboard'],
            'user' => ['/user/profile']
        ];

        $middleware = new RbacMiddleware();
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
                ->method('getUserRole')
                ->willReturn('admin');

        $request->expects($this->any())
                ->method('getRoute')
                ->willReturn('/admin/dashboard');

        $response = $middleware->handle($request, function ($req) {
            return new Response("OK", 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("OK", $response->getContent());
    }
}
