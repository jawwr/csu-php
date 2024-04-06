<?php

namespace csuPhp\Csu2024;

use Exception;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, $reasonPhrase, [], "", "1.1");
    }
}

class CustomRequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface {

    }
}

class Response implements ResponseInterface, RequestInterface
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

    public function getRequestTarget(): string {
        return "";
    }

    public function withRequestTarget(string $requestTarget): RequestInterface {
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

    public function getReasonPhrase(): string {
        return $this->reasonPhrase;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function hasHeader(string $name): bool {
        return count($this->headers);
    }

    public function getHeader(string $name): array {
        return $this->headers[$name];
    }

    public function getHeaderLine(string $name): string {
        return $this->headers[$name];
    }

    public function withHeader(string $name, $value): MessageInterface {
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface {
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface {
        $this->headers = [];
        return $this;
    }

    public function withBody(StreamInterface $body): MessageInterface {
        $this->body = $body;
        return $this;
    }

    public function getProtocolVersion(): string {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface {
         $this->protocolVersion = $version;
         return $this;
    }
}

class TextResponse {
    private $message;
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}

class Router extends BaseComponent {
    private $responseFactory;
    private $requestFactory;
    private $routes = [];
    private $rules = [];

    public function __construct(array $params)
    {
        $this->responseFactory = new CustomResponseFactory();
        $this->requestFactory = $requestFactory;
    }

    public function addRoute($method, $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    public function handleRequest()
    {
        $request = $this->requestFactory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $response = $handler($request, $this->responseFactory);
        } else {
            $response = $this->responseFactory->createResponse(404);
            $response->getBody()->write('Not Found');
        }
    }

    public function run(): string {
        $request = $this->requestFactory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        $path = $_SERVER["path"];
        $result = "";
        foreach($this->rules as $rule => $handler) {
            if (preg_match($rule, $path) != 0) {
                $result = call_user_func($handler, $request);
                break;
            }
        }
        if (empty($result)) {
            throw new Exception();
        }
        return $result;
    }

    protected function init(): void {
        $this->rules = ["index" => fn($request) => new TextResponse("pupupu"),];
    }
}