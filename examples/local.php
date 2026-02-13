<?php
/**
 * Example demonstrating local KeyValueStore usage (no RPC)
 */

require_once '../vendor/autoload.php';

use Mgleis\PhpSqliteKeyValueStore\KeyValueStore;

echo "=== KeyValueStore Local Example ===\n\n";

try {
    // Initialize the local KeyValueStore
    // This will create a SQLite database file in the current directory
    $store = new KeyValueStore();
    
    echo "1. Setting some key-value pairs...\n";
    $store->set('user:123', 'John Doe');
    $store->set('user:456', 'Jane Smith');
    $store->set('config:theme', 'dark');
    $store->set('config:language', 'en');
    $store->set('product:abc', 'Laptop Computer');
    $store->set('product:def', 'Mobile Phone');
    echo "   Values set successfully!\n\n";

    // Example 2: Get values
    echo "2. Getting values...\n";
    $user123 = $store->get('user:123');
    $theme = $store->get('config:theme');
    $nonExistent = $store->get('non:existent', 'default_value');
    
    echo "   user:123 = $user123\n";
    echo "   config:theme = $theme\n";
    echo "   non:existent = $nonExistent (using default)\n\n";

    // Example 3: Check if keys exist
    echo "3. Checking if keys exist...\n";
    $hasUser123 = $store->has('user:123') ? 'true' : 'false';
    $hasNonExistent = $store->has('non:existent') ? 'true' : 'false';

    echo "   has('user:123') = $hasUser123\n";
    echo "   has('non:existent') = $hasNonExistent\n\n";

    // Example 4: Search operations
    echo "4. Search operations...\n";

    // Search keys containing 'user'
    $userKeys = $store->searchKeyContains('user');
    echo "   Keys containing 'user': " . json_encode($userKeys) . "\n";

    // Search keys starting with 'config'
    $configKeys = $store->searchKeyStartsWith('config');
    echo "   Keys starting with 'config': " . json_encode($configKeys) . "\n";

    // Search values containing 'John'
    $johnValues = $store->searchValueContains('John');
    echo "   Values containing 'John': " . json_encode($johnValues) . "\n";

    // Search values starting with 'Mobile'
    $mobileValues = $store->searchValueStartsWith('Mobile');
    echo "   Values starting with 'Mobile': " . json_encode($mobileValues) . "\n\n";

    // Example 5: Delete a key
    echo "5. Deleting a key...\n";
    echo "   Before deletion - has('user:456'): " . ($store->has('user:456') ? 'true' : 'false') . "\n";
    $store->delete('user:456');
    echo "   After deletion - has('user:456'): " . ($store->has('user:456') ? 'true' : 'false') . "\n\n";

    // Example 6: Working with different data types
    echo "6. Working with different data types...\n";
    $store->set('number:pi', 3.14159);
    $store->set('array:colors', ['red', 'green', 'blue']);
    $store->set('bool:active', true);
    $store->set('object:user', ['name' => 'Alice', 'age' => 30, 'email' => 'alice@example.com']);

    $pi = $store->get('number:pi');
    $colors = $store->get('array:colors');
    $active = $store->get('bool:active');
    $userObject = $store->get('object:user');

    echo "   number:pi = $pi\n";
    echo "   array:colors = " . json_encode($colors) . "\n";
    echo "   bool:active = " . ($active ? 'true' : 'false') . "\n";
    echo "   object:user = " . json_encode($userObject) . "\n\n";

    // Example 7: Performance demonstration
    echo "7. Performance test - storing 1000 items...\n";
    $startTime = microtime(true);

    for ($i = 1; $i <= 1000; $i++) {
        $store->set("perf:item_$i", "Value for item number $i");
    }

    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);
    echo "   Stored 1000 items in {$duration}ms\n";

    // Search within performance test data
    $perfItems = $store->searchKeyStartsWith('perf:');
    echo "   Found " . count($perfItems) . " performance test items\n\n";

    // Example 8: Database persistence
    echo "8. Database persistence...\n";
    echo "   The SQLite database file stores all data persistently.\n";
    echo "   Re-running this script will show the same data is still there.\n";
    echo "   Database location: " . realpath('keyvalue_store.sqlite') . "\n\n";

    echo "=== All local operations completed successfully! ===\n";
    echo "Advantages of local usage:\n";
    echo "- Direct database access (faster)\n";
    echo "- No network overhead\n";
    echo "- No server setup required\n";
    echo "- Simpler deployment\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}