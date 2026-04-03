<?php

namespace ShadrackMballah\ZenoPay\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use ShadrackMballah\ZenoPay\Exceptions\AuthenticationException;
use ShadrackMballah\ZenoPay\Exceptions\ValidationException;
use ShadrackMballah\ZenoPay\Exceptions\ZenoPayException;

class ZenoPayClient
{
    protected Client $client;
    protected string $apiKey;

    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30, bool $verifySsl = true)
    {
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'timeout'  => $timeout,
            'verify'   => $verifySsl,
            'headers'  => [
                'x-api-key'    => $apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ]);
    }

    /**
     * @throws ZenoPayException
     */
    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->post($endpoint, ['json' => $data]);

            return $this->decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ZenoPayException('ZenoPay request failed: ' . $e->getMessage(), null, null, 0, $e);
        }
    }

    /**
     * @throws ZenoPayException
     */
    public function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);

            return $this->decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ZenoPayException('ZenoPay request failed: ' . $e->getMessage(), null, null, 0, $e);
        }
    }

    protected function decode(string $body): array
    {
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ZenoPayException('Invalid JSON response from ZenoPay.');
        }

        return $data;
    }

    /**
     * @throws ZenoPayException
     */
    protected function handleClientException(ClientException $e): never
    {
        $statusCode = $e->getResponse()->getStatusCode();
        $body = json_decode($e->getResponse()->getBody()->getContents(), true) ?? [];
        $message = $body['message'] ?? $e->getMessage();

        if ($statusCode === 401) {
            throw new AuthenticationException($message, $e);
        }

        if ($statusCode === 400) {
            throw new ValidationException($message, $body, $e);
        }

        throw new ZenoPayException($message, (string) $statusCode, $body, $statusCode, $e);
    }
}
