<?php
/**
 * Example client demonstrating KeyValueStoreRpcClient usage
 */

require_once '../../vendor/autoload.php';

use Mgleis\PhpSqliteKeyValueStore\KeyValueStoreRpcClient;

// Initialize the client pointing to the RPC server
$client = new KeyValueStoreRpcClient('http://localhost:8080/rpc');

echo "=== KeyValueStore RPC Client Example ===\n\n";

try {
    // Example 1: Set some values
    echo "1. Setting some key-value pairs...\n";
    $client->set('user:123', 'John Doe');
    $client->set('user:456', 'Jane Smith');
    $client->set('config:theme', 'dark');
    $client->set('config:language', 'en');
    $client->set('product:abc', 'Laptop Computer');
    $client->set('product:def', 'Mobile Phone');
    echo "   Values set successfully!\n\n";

    // Example 2: Get values
    echo "2. Getting values...\n";
    $user123 = $client->get('user:123');
    $theme = $client->get('config:theme');
    $nonExistent = $client->get('non:existent', 'default_value');

    echo "   user:123 = $user123\n";
    echo "   config:theme = $theme\n";
    echo "   non:existent = $nonExistent (using default)\n\n";

    // Example 3: Check if keys exist
    echo "3. Checking if keys exist...\n";
    $hasUser123 = $client->has('user:123') ? 'true' : 'false';
    $hasNonExistent = $client->has('non:existent') ? 'true' : 'false';

    echo "   has('user:123') = $hasUser123\n";
    echo "   has('non:existent') = $hasNonExistent\n\n";

    // Example 4: Search operations
    echo "4. Search operations...\n";

    // Search keys containing 'user'
    $userKeys = $client->searchKeyContains('user');
    echo "   Keys containing 'user': " . json_encode($userKeys) . "\n";

    // Search keys starting with 'config'
    $configKeys = $client->searchKeyStartsWith('config');
    echo "   Keys starting with 'config': " . json_encode($configKeys) . "\n";

    // Search values containing 'John'
    $johnValues = $client->searchValueContains('John');
    echo "   Values containing 'John': " . json_encode($johnValues) . "\n";

    // Search values starting with 'Mobile'
    $mobileValues = $client->searchValueStartsWith('Mobile');
    echo "   Values starting with 'Mobile': " . json_encode($mobileValues) . "\n\n";

    // Example 5: Delete a key
    echo "5. Deleting a key...\n";
    echo "   Before deletion - has('user:456'): " . ($client->has('user:456') ? 'true' : 'false') . "\n";
    $client->delete('user:456');
    echo "   After deletion - has('user:456'): " . ($client->has('user:456') ? 'true' : 'false') . "\n\n";

    // Example 6: Working with different data types
    echo "6. Working with different data types...\n";
    $client->set('number:pi', 3.14159);
    $client->set('array:colors', ['red', 'green', 'blue']);
    $client->set('bool:active', true);

    $pi = $client->get('number:pi');
    $colors = $client->get('array:colors');
    $active = $client->get('bool:active');

    echo "   number:pi = $pi\n";
    echo "   array:colors = " . json_encode($colors) . "\n";
    echo "   bool:active = " . ($active ? 'true' : 'false') . "\n\n";

    echo "=== All operations completed successfully! ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Make sure the RPC server is running on http://localhost:8080\n";
    echo "You can start it with: php -S localhost:8080 server.php\n";
}