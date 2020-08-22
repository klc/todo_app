<?php


namespace App\Library\Providers;


class ProviderB extends TodoProvider implements TodoProviderInterface
{

    const API_URL = 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7';
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
            foreach ($todoDatum as $name => $datum) {
                $this->addTodo(
                    $name,
                    $datum['level'],
                    $datum['estimated_duration']
                );
            }
        }
    }
}