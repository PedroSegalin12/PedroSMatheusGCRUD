<?php
require __DIR__ . '/vendor/autoload.php';
use App\Core\Database;

try {
    $pdo = Database::getConnection();
    echo "Conexão OK com o banco!";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
