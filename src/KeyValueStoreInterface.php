<?php
declare(strict_types=1);
namespace Mgleis\PhpSqliteKeyValueStore;
interface KeyValueStoreInterface
{
    /**
     * Get a value by key
     *
     * @param string $key The key to search for
     * @param mixed $default The default value to return if key is not found
     * @return mixed The value associated with the key, or default if not found
     */
    public function get(string $key, $default = null);

    /**
     * Check if a key exists
     *
     * @param string $key The key to check
     * @return bool True if the key exists, false otherwise
     */
    public function has(string $key): bool;

    /**
     * Delete a key-value pair
     *
     * @param string $key The key to delete
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Set a key-value pair
     *
     * @param string $key The key
     * @param mixed $value The value to store
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Search for keys where value contains the given text
     *
     * @param string $searchText The text to search for
     * @return array Array of key-value pairs where value contains the search text
     */
    public function searchValueContains(string $searchText): array;

    /**
     * Search for keys where value starts with the given text
     *
     * @param string $searchText The text to search for
     * @return array Array of key-value pairs where value starts with the search text
     */
    public function searchValueStartsWith(string $searchText): array;

    /**
     * Search for keys where key contains the given text
     *
     * @param string $searchText The text to search for
     * @return array Array of key-value pairs where key contains the search text
     */
    public function searchKeyContains(string $searchText): array;

    /**
     * Search for keys where key starts with the given text
     *
     * @param string $searchText The text to search for
     * @return array Array of key-value pairs where key starts with the search text
     */
    public function searchKeyStartsWith(string $searchText): array;

}