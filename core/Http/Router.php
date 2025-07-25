<?php

namespace Core\Http;

use Core\Http\Request;
use Core\Http\Response;

class Router
{
    protected array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $uri, callable|array $action): Route
    {
        return $this->addRoute('GET', $uri, $action);
        
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, callable|array $action): Route
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Add a route to the route collection.
     */
    protected function addRoute(string $method, string $uri, callable|array $action): Route
    {
        $route = new Route($method, $uri, $action);
        $this->routes[] = $route;
        $this->routes[$method][] = [
            'uri' => $uri,
            'action' => $action,
            'pattern' => $this->getPattern($uri)
        ];
        return $route;
    }

    // Get Pattern
    protected function getPattern(string $uri): string
    {
        return '#^' . preg_replace('#\{(\w+)\}#', '([^/]+)', $uri) . '$#';
    }

    /**
     * Dispatch the incoming request.
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches); // Remove full match
                return $this->callAction($route['action'], $request, $matches);
            }
        }

        return new Response('404 Not Found', 404);
    }


    protected function callAction(array $action, Request $request, array $params): Response
    {
        [$controllerClass, $method] = $action;
        $controller = new $controllerClass;

        return call_user_func_array([$controller, $method], array_merge([$request], $params));
    }

    protected function convertUriToRegex(string $uri): string
    {
        return '#^' . preg_replace('#\{(\w+)\}#', '([^/]+)', $uri) . '$#';
    }
}

?>