<?php
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo password_hash($_ENV['ADMIN_PASSWORD'], PASSWORD_DEFAULT);
