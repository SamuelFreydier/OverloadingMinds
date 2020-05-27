<?php

namespace App\Src\Response;

class Response
{
    private $content;
    private $statusCode;
    private $headers;

    public function __construct(string $content, int $statusCode = 200, Array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = array_merge(['Content-Type' => 'text/html'], $headers);
    }

    public function getStatusCode() : int {
        return $this->statusCode;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function sendHeaders() : void {
        http_response_code($this->statusCode);

        foreach($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
    }

    public function send() : void {
        $this->sendHeaders();

        echo $this->content;
    }
}