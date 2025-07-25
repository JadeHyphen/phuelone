<?php

namespace Core\Http;

use Core\Http\Request;
use Core\Http\Response;

class Route
{
    protected string $method;
    protected string $uri;
    protected mixed $action; // Updated to mixed to resolve the fatal error
    protected array $middleware = [];
    protected array $attributes = [];

    public function __construct(string $method, string $uri, mixed $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }

    public function middleware(array $middleware): static
    {
        $this->middleware = $middleware;
        return $this;
    }

    public function attribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function matches(Request $request): bool
    {
        return $request->method() === $this->method && $request->uri() === $this->uri;
    }

    public function run(Request $request): Response
    {
        $handler = function($req) {
            if (is_array($this->action)) {
                [$controller, $method] = $this->action;
                return call_user_func([new $controller, $method], $req);
            }

            return call_user_func($this->action, $req);
        };

        // Apply middleware stack
        foreach (array_reverse($this->middleware) as $middleware) {
            $handler = fn($req) => (new $middleware)->handle($req, $handler);
        }

        return $handler($request);
    }
}

?>