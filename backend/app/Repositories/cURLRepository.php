<?php

namespace App\Repositories;


/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class cURLRepository
{
    public function sendRequest($method = 'GET', $url = 'https://api.exemplo.com/items', $body = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($method == 'POST' || $method == 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilitar verificação de SSL para testes
        $response = curl_exec($ch);

        if ($response === false) {
            // Tratar erros de cURL
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL Error: $error");
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function getAllItems($method, $url)
    {
        return $this->sendRequest($method, $url);
    }

    public function createItem($method, $url, $data)
    {
        return $this->sendRequest($method, $url, $data);
    }

    public function updateItem($method, $url, $data)
    {
        return $this->sendRequest($method, $url, $data);
    }

    public function deleteItem($method, $url)
    {
        return $this->sendRequest($method, $url);
    }
}
