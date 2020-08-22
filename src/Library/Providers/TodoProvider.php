<?php


namespace App\Library\Providers;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class TodoProvider
{
    /**
     * @var HttpClientInterface $client
     */
    private $client;

    /**
     * @var string $apiUrl
     */
    private $apiUrl;

    /**
     * @var string $method
     */
    private $httpMethod;

    /**
     * @var array $mappedData
     */
    private $mappedData;

    /**
     * TodoProvider constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * @return void
     */
    abstract public function handle(): void;

    /**
     * @return HttpClientInterface
     */
    private function getClient(): HttpClientInterface
    {

        return $this->client;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    protected function getTodoData(): array
    {
        $client = $this->getClient();
        $response = $client->request($this->getHttpMethod(), $this->getApiUrl());

        if ($response->getStatusCode() != 200) {
            throw new \Exception('This provider has not accessible');
        }

        try {
            $content = $response->toArray();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $content;
    }

    /**
     * @param string $url
     * @return void
     */
    protected function setApiUrl(string $url): void
    {
        $this->apiUrl = $url;
    }

    /**
     * @return string
     */
    protected function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $string
     * @return void
     */
    protected function setHttpMethod(string $string): void
    {
        $this->httpMethod = $string;
    }

    /**
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @param string $name
     * @param int $level
     * @param int $estimatedDuration
     * @return void
     */
    protected function addTodo(string $name, int $level, int $estimatedDuration): void
    {
        $this->mappedData[] = [
            'name' => $name,
            'level' => $level,
            'estimated_duration' => $estimatedDuration
        ];
    }

    /**
     * @return array
     */
    public function getMappedData(): array
    {
        return $this->mappedData;
    }
}