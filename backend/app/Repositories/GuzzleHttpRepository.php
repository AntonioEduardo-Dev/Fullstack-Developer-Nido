<?php

namespace App\Repositories;

use GuzzleHttp\Client;

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class GuzzleHttpRepository
{
    public function __construct(protected Client $client)
    {}

    public function getAllItems($method = 'GET', $url = 'https://api.exemplo.com/items', $body = [])
    {
        $response = $this->client->request($method, $url, $body);
        return json_decode($response->getBody(), true);
    }

    public function getItemById($id)
    {
        $response = $this->client->request('GET', "https://api.exemplo.com/items/{$id}");
        return json_decode($response->getBody(), true);
    }

    public function createItem($data)
    {
        $response = $this->client->request('POST', 'https://api.exemplo.com/items', $data);
        return json_decode($response->getBody(), true);
    }

    public function deleteItem($id)
    {
        $response = $this->client->request('DELETE', "https://api.exemplo.com/items/{$id}");
        return json_decode($response->getBody(), true);
    }
}
