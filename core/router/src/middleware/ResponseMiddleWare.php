<?php

namespace core\router\middleware;

class ResponseMiddleWare implements IMiddleware {

    public function handle(array $params): bool
    {
        $acceptHeader = $this->getAcceptHeader($params);

        $responseTuple = $this->generateResponse($acceptHeader, $params);

        if (!$responseTuple[0]) {
            return false;
        }

        $this->sendResponse($responseTuple[1]);

        return true;
    }

    private function getAcceptHeader(array $params): string
    {
        return isset($params['headers']['Accept']) ? $params['headers']['Accept'] : '';
    }

    private function generateResponse(string $acceptHeader, array $params): array
    {
        $acceptedTypes = explode(',', $acceptHeader);

        $response = '';

        foreach ($acceptedTypes as $type) {

            $type = trim($type);

            switch ($type) {
                case 'application/json':
                    $response = $this->generateJsonResponse($params['data']);
                    return [true, $response];
                case 'text/html':
                    $response = $this->generateHtmlResponse($params['data']);
                    return [true, $response];
                default:
                    http_response_code(406);
                    //exit("406 Not Acceptable: $type is not supported");
                    return [false];
            }
            /*if (!empty($response)) {
                break;
            }*/
        }
        //return $response;
        return[];
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

    private function sendResponse(string $response): void
    {
        echo $response;
    }
}