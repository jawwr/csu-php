<?php

interface Container {
    public function hasString(string $key): bool;
    public function getString(string $key): mixed;
}