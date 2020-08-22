<?php


namespace App\Library\Providers;


class ProviderA extends TodoProvider implements TodoProviderInterface
{
    const API_URL = 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa';
    const HTTP_METHOD = 'GET';

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @return void
     */
    public function handle(): void
    {
        $this->setApiUrl(self::API_URL);
        $this->setHttpMethod(self::HTTP_METHOD);

        $todoData = $this->getTodoData();
        foreach ($todoData as $todoDatum) {
            $this->addTodo(
                $todoDatum['id'],
                $todoDatum['zorluk'],
                $todoDatum['sure']
            );
        }
    }
}