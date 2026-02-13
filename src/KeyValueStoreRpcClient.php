<?php
declare(strict_types=1);
namespace Mgleis\PhpSqliteKeyValueStore;
class KeyValueStoreRpcClient implements KeyValueStoreInterface
{

    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Make an HTTP request to the server
     */
    private function makeRequest(string $method, array $data = []): array
    {
        $url = $this->baseUrl . '/' . $method;

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($data, JSON_THROW_ON_ERROR)
            ]
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new \RuntimeException("Failed to connect to server at $url");
        }

        $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($result['success']) || !$result['success']) {
            $error = $result['error'] ?? 'Unknown error';
            throw new \RuntimeException("Server error: $error");
        }

        return $result;
    }

    public function get(string $key, $default = null)
    {
        $result = $this->makeRequest('get', ['key' => $key, 'default' => $default]);
        return $result['data'];
    }

    public function has(string $key): bool
    {
        $result = $this->makeRequest('has', ['key' => $key]);
        return (bool) $result['data'];
    }

    public function delete(string $key): void
    {
        $this->makeRequest('delete', ['key' => $key]);
    }

    public function set(string $key, $value): void
    {
        $this->makeRequest('set', ['key' => $key, 'value' => $value]);
    }

    public function searchValueContains(string $searchText): array
    {
        $result = $this->makeRequest('searchValueContains', ['searchText' => $searchText]);
        return $result['data'] ?? [];
    }

    public function searchValueStartsWith(string $searchText): array
    {
        $result = $this->makeRequest('searchValueStartsWith', ['searchText' => $searchText]);
        return $result['data'] ?? [];
    }

    public function searchKeyContains(string $searchText): array
    {
        $result = $this->makeRequest('searchKeyContains', ['searchText' => $searchText]);
        return $result['data'] ?? [];
    }

    public function searchKeyStartsWith(string $searchText): array
    {
        $result = $this->makeRequest('searchKeyStartsWith', ['searchText' => $searchText]);
        return $result['data'] ?? [];
    }
}