<?php

namespace core\router\request;

use core\di\components\BaseComponent;
use Exception;

class RequestDispatcher extends BaseComponent {
    public function handle(array $headers, array $handler): mixed
    {
        $acceptHeader = $this->getAcceptHeader($headers);

        return $this->generateResponse($acceptHeader, $headers, $handler);
    }

    private function getResponse(array $handler) {
        if (isset($handler["middleware"])) {
            $middleware = $handler["middleware"];
            if (!$middleware->next(getallheaders())) {
                throw new Exception("Reqeuest error handling");
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
}