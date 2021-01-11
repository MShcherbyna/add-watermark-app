<?php

namespace App\Service;

Interface ImageServiceContract
{
    /**
     * @param array $payload
     * @return string
     */
    public function saveImage(array $payload): string;

    /**
     * @param array $payload
     * @return void
     */
    public function removeImage(string $path): void;
}
