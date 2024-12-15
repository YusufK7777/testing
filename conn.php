<?php

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

// Create a PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

?>