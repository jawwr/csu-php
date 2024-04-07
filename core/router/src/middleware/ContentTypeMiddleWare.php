<?php

namespace core\router\middleware;

class ContentTypeMiddleWare implements IMiddleware
{

    public function handle(array $params): bool
    {
        $contentType = $params['Content-Type'] ?? 'application/json';
        return $this->isJsonCorrect($contentType);
    }

    private function isJsonCorrect(mixed $contentType): bool
    {
        // $json = file_get_contents($contentType);
        $json = '{}';//TODO доставать из запроса

        $data = json_decode($json, true);

        if ($data == null && json_last_error() !== JSON_ERROR_NONE) {
            echo "Ошибка парсинга json элемента" . json_last_error_msg();
            return false;
        } else {
            return true;
        }
    }


}