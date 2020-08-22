<?php


namespace App\Library\Providers;


interface TodoProviderInterface
{
    public function handle(): void;
    public function getMappedData(): array;
}