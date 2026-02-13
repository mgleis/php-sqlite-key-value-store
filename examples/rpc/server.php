<?php
/**
 * Simple HTTP server script for KeyValueStoreServer
 * 
 * Usage: php -S localhost:8080 server.php
 * Then access endpoints like: POST http://localhost:8080/rpc/get
 */
require_once '../../vendor/autoload.php';

use Mgleis\PhpSqliteKeyValueStore\KeyValueStoreRpcServer;

// Initialize the server with base path '/rpc'
$server = new KeyValueStoreRpcServer('/rpc');

// Handle the request
$server->start();
