<?php

namespace Core\Http;

class Request
{
    protected array $query = [];
    protected array $request = [];
    protected array $server = [];
    protected array $headers = [];
    protected array $cookies = [];
    protected array $files = [];
    protected string $method;
    protected string $uri;
    protected string $rawContent;

    public function __construct()
    {
        $this->query = $_GET ?? [];
        $this->request = $_POST ?? [];
        $this->server = $_SERVER ?? [];
        $this->cookies = $_COOKIE ?? [];
        $this->files = $_FILES ?? [];
        $this->headers = $this->getAllHeaders();
        $this->method = $this->server['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->rawContent = file_get_contents('php://input');
    }

    // Return all HTTP headers as an associative array
    protected function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($this->server as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $headerName = str_replace('_', '-', substr($name, 5));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    // Parse and return the current URI path (without query string)
    protected function parseUri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return strtok($uri, '?');
    }

    // Get HTTP method (GET, POST, PUT, DELETE, etc.)
    public function method(): string
    {
        return $this->method;
    }

    // Get the request URI path
    public function uri(): string
    {
        return $this->uri;
    }

    // Get query parameter or all query parameters
    public function query(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    // Get POST (request) parameter or all POST data
    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->request;
        }
        return $this->request[$key] ?? $default;
    }

    // Unified input method: POST then GET
    public function input(string $key = null, $default = null)
    {
        if ($key === null) {
            return array_merge($this->query, $this->request);
        }

        if (isset($this->request[$key])) {
            return $this->request[$key];
        }
        if (isset($this->query[$key])) {
            return $this->query[$key];
        }
        return $default;
    }

    // Check if input key exists in POST or GET
    public function has(string $key): bool
    {
        return isset($this->request[$key]) || isset($this->query[$key]);
    }

    // Get all cookies
    public function cookies(): array
    {
        return $this->cookies;
    }

    // Get cookie by key
    public function cookie(string $key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }

    // Get all headers
    public function headers(): array
    {
        return $this->headers;
    }

    // Get header by name (case-insensitive)
    public function header(string $name, $default = null)
    {
        $name = strtolower($name);
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }
        return $default;
    }

    // Get uploaded files array
    public function files(): array
    {
        return $this->files;
    }

    // Get uploaded file info by key
    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    // Check if request is ajax
    public function isAjax(): bool
    {
        return strtolower($this->header('X-Requested-With') ?? '') === 'xmlhttprequest';
    }

    // Get raw body content (for JSON, XML, etc.)
    public function getContent(): string
    {
        return $this->rawContent;
    }

    // Get the request path (without query string)
    public function path(): string
    {
        return $this->uri;
    }
}

?>