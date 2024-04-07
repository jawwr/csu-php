<?php

namespace core\di\scanner;

interface IComponentScanner {
    public function scanComponents(): array;
}