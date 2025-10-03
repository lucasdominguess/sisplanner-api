<?php
namespace App\Interface;

interface SanitizerInterface
{
    public function clean(string|null $value): string|null;
    public function cleanArray(array $data): array;
}
