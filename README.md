# PHP SQLite Key Value Store

A PHP library that implements a key/value store with SQLite as the persistence layer.

Use it for small projects / prototypes with at most hundreds or thousands of values.

The library now includes RPC server and client capabilities for remote access over HTTP.

## Install

    composer require mgleis/php-sqlite-key-value-store

## Usage

### Basic Local Usage

    $kv = new KeyValueStore();

### Set a Key

    $kv->set('key', 'value');

### Get a Value by Key

    $value = $kv->get('key');

### Check if a key exists

    $exists = $kv->has('key');

### Delete by Key

    $kv->delete('key');

### Search by Value

    $arr = $kv->searchValueStartsWith('text');
    $arr = $kv->searchValueContains('text');

### Search by Key

    $arr = $kv->searchKeyStartsWith('text');
    $arr = $kv->searchKeyContains('text');

## RPC Server and Client

### Starting an RPC Server

You can expose your KeyValueStore via HTTP using the KeyValueStoreRpcServer:

```php
use Mgleis\PhpSqliteKeyValueStore\KeyValueStoreRpcServer;

$server = new KeyValueStoreRpcServer('/rpc'); // Initialize server for route /rpc/*
$server->start();
```

This creates an HTTP server that accepts JSON-RPC requests at endpoints like:
- `POST /rpc/get` - Get a value
- `POST /rpc/set` - Set a key-value pair
- `POST /rpc/has` - Check if key exists
- `POST /rpc/delete` - Delete a key
- `POST /rpc/searchValueContains` - Search values containing text
- `POST /rpc/searchValueStartsWith` - Search values starting with text
- `POST /rpc/searchKeyContains` - Search keys containing text
- `POST /rpc/searchKeyStartsWith` - Search keys starting with text

### Using the RPC Client

Connect to a remote KeyValueStore server using the client:

```php
use Mgleis\PhpSqliteKeyValueStore\KeyValueStoreRpcClient;

$kv = new KeyValueStoreRpcClient('http://myserver.de:8080/rpc');

// Use exactly like the local KeyValueStore
$kv->set('key1', 'value1');
echo $kv->get('key1');
echo $kv->has('key1');
$kv->delete('key1');

// Search operations work the same way
$arr = $kv->searchValueStartsWith('text');
$arr = $kv->searchValueContains('text');
$arr = $kv->searchKeyStartsWith('text');
$arr = $kv->searchKeyContains('text');
```

The client implements the same interface as the local KeyValueStore, making it a drop-in replacement for remote access.