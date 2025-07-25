<?php

namespace Core\Http;

/**
 * Class Response
 *
 * Handles HTTP responses including status codes, headers, and body output.
 */
class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected string $content = '';

    /**
     * Constructor to initialize the response.
     */
    public function __construct(string $content = '', int $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * Set the content of the response.
     */
    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set a header.
     */
    public function header(string $key, string $value): static
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Set the HTTP status code.
     */
    public function setStatusCode(int $code): static
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Send the response to the browser.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        echo $this->content;
    }

    /**
     * Return a JSON response.
     */
    public function json(array $data, int $status = 200): void
    {
        $this->setStatusCode($status)
             ->header('Content-Type', 'application/json')
             ->setContent(json_encode($data))
             ->send();
    }

    /**
     * Redirect to a given URL.
     */
    public function redirect(string $url, int $status = 302): void
    {
        $this->setStatusCode($status)
             ->header('Location', $url)
             ->send();
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the content of the response.
     */
    public function getContent(): string
    {
        return $this->content;
    }
}

?>