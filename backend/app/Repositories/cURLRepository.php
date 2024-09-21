<?php

namespace App\Repositories;


/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class cURLRepository
{
    public function getAllItems($method = 'GET', $url = 'https://api.exemplo.com/items', $body = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            "Accept: application/json",
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // checa o ssl
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        # Print response.
        return json_decode($response);
    }
}
