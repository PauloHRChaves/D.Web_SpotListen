<?php
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        [$name, $value] = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        $value = trim($value, "\"'");

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

require_once __DIR__ . '/2025_10_24_create_database.php';
echo "   [SUCESSO] Banco de Dados e conexão estabelecidos.\n";

require __DIR__ . '/../src/Config/Database.php';

require_once __DIR__ . '/2025_10_24_create_table_users.php';
echo "   [SUCESSO] Tabela USERS criada ou já existente.\n";

echo "✅ BUILD CONCLUÍDA.\n";
?>