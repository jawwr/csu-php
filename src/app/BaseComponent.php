<?php

namespace Mag310\Csu2024;

abstract class BaseComponent implements Component {
    public function __construct(array $params) {
        $this-> init();
    }

    protected abstract function init(): void;
}