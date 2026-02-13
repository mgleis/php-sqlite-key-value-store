<?php
declare(strict_types=1);
namespace Mgleis\PhpSqliteKeyValueStore;
class KeyValueStoreRpcServer implements KeyValueStoreInterface
{

    private KeyValueStoreInterface $store;
    private string $basePath;

    public function __construct(string $basePath, ?KeyValueStoreInterface $store = null)
    {
        $this->basePath = rtrim($basePath, '/');
        $this->store = $store ?? new KeyValueStore();
    }

    /**
     * Start the RPC server and handle incoming requests
     */
    public function start(): void
    {
        // Get the request URI and remove the base path
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $parsedUrl = parse_url($requestUri);
        $path = $parsedUrl['path'] ?? '';

        // Check if the request is for our base path
        if (strpos($path, $this->basePath) !== 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            return;
        }

        // Remove base path from the request path
        $method = substr($path, strlen($this->basePath) + 1);

        // Set JSON content type
        header('Content-Type: application/json');

        try {
            // Get request data
            $input = file_get_contents('php://input');
            $data = $input ? json_decode($input, true, 512, JSON_THROW_ON_ERROR) : [];

            // Route to appropriate method
            $result = $this->handleMethod($method, $data);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handleMethod(string $method, array $data)
    {
        switch ($method) {
            case 'get':
                $key = $data['key'] ?? '';
                $default = $data['default'] ?? null;
                return $this->store->get($key, $default);

            case 'has':
                $key = $data['key'] ?? '';
                return $this->store->has($key);

            case 'set':
                $key = $data['key'] ?? '';
                $value = $data['value'] ?? null;
                $this->store->set($key, $value);
                return null;

            case 'delete':
                $key = $data['key'] ?? '';
                $this->store->delete($key);
                return null;

            case 'searchValueContains':
                $searchText = $data['searchText'] ?? '';
                return $this->store->searchValueContains($searchText);

            case 'searchValueStartsWith':
                $searchText = $data['searchText'] ?? '';
                return $this->store->searchValueStartsWith($searchText);

            case 'searchKeyContains':
                $searchText = $data['searchText'] ?? '';
                return $this->store->searchKeyContains($searchText);

            case 'searchKeyStartsWith':
                $searchText = $data['searchText'] ?? '';
                return $this->store->searchKeyStartsWith($searchText);

            default:
                throw new \InvalidArgumentException("Unknown method: $method");
        }
    }

    // Implement KeyValueStoreInterface methods for local access
    public function get(string $key, $default = null)
    {
        return $this->store->get($key, $default);
    }

    public function has(string $key): bool
    {
        return $this->store->has($key);
    }

    public function delete(string $key): void
    {
        $this->store->delete($key);
    }

    public function set(string $key, $value): void
    {
        $this->store->set($key, $value);
    }

    public function searchValueContains(string $searchText): array
    {
        return $this->store->searchValueContains($searchText);
    }

    public function searchValueStartsWith(string $searchText): array
    {
        return $this->store->searchValueStartsWith($searchText);
    }

    public function searchKeyContains(string $searchText): array
    {
        return $this->store->searchKeyContains($searchText);
    }

    public function searchKeyStartsWith(string $searchText): array
    {
        return $this->store->searchKeyStartsWith($searchText);
    }
}