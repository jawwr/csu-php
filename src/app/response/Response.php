<?php

namespace csuPhp\Csu2024\response;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private $code;
    private $reasonPhrase;
    private $headers;
    private $body;
    private $protocolVersion;

    public function __construct($code, $reasonPhrase, $headers, $body, $protocolVersion)
    {
        $this->code = $code;
        $this->reasonPhrase = $reasonPhrase;
        $this->headers = $headers;
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
    }

    public function getRequestTarget(): string
    {
        return "";
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->code = $code;
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return count($this->headers);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name];
    }

    public function getHeaderLine(string $name): string
    {
        return $this->headers[$name];
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $this->headers = [];
        return $this;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->protocolVersion = $version;
        return $this;
    }
}