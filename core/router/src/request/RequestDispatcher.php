<?php

namespace core\router\request;

use core\di\components\BaseComponent;
use core\router\middleware\MiddlewareHandleException;
use Exception;

class RequestDispatcher extends BaseComponent {
    public function handle(array $headers, array $handler): void
    {
        $acceptHeader = $this->getAcceptHeader($headers);
        try {
            $response = $this->generateResponse($acceptHeader, $headers, $handler);
            $this->sendResponse(body: $response);
        } catch (MiddlewareHandleException $e) {
            $response = ["time" => time()];
            $this->sendResponse(403, $response);
        } catch (Exception $e) {
            $response = ["time" => time(), "message" => $e->getMessage()];
            $this->sendResponse(500, $response);
        }
    }

    private function getResponse(array $handler) {
        if (isset($handler["middleware"])) {
            $middleware = $handler["middleware"];
            if (!$middleware->next(getallheaders())) {
                throw new MiddlewareHandleException("Reqeuest error handling");
            }
        }
        $class = $handler['class'];
        $method = $handler['method'];
        return $class->$method();
    }

    private function getAcceptHeader(array $params): string
    {
        return isset($params['headers']['Accept']) ? $params['headers']['Accept'] : '';
    }

    private function generateResponse(string $acceptHeader, array $params, array $handler): array|null
    {
        // $acceptedTypes = explode(',', $acceptHeader);

        return $this->getResponse($handler);

        // foreach ($acceptedTypes as $type) {

        //     $type = trim($type);
        //     echo $type;

        //     switch ($type) {
        //         case 'application/json':
        //             return $this->generateJsonResponse($responseBody);
        //         // case 'text/html':
        //             // return $this->generateHtmlResponse($responseBody);
        //         default:
        //             return null;
        //     }
        // }
        // return null;
    }

    private function generateJsonResponse(array $data): string
    {
        return json_encode($data);
    }

    private function generateHtmlResponse(array $data): string
    {
        $html = '<ul>';
        foreach ($data as $key => $value) {
            $html .= "<li>$key: $value</li>";
        }
        $html .= '</ul>';
        return $html;
    }

    public function sendResponse($code = 200, mixed $body = '') 
    {
        header("Content-Type: application/json");
        http_response_code($code);
        echo json_encode($body);
    }
}