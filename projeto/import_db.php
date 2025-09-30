<?php
$host = 'localhost';
$user = 'admin';
$pass = 'admin';
$dbname = 'user_tn';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Import usr.sql
    $sql = file_get_contents('usr.sql');
    $pdo->exec($sql);
    echo "usr.sql imported successfully.<br>";

    // Import grant_permissions.sql
    $sql3 = file_get_contents('grant_permissions.sql');
    $pdo->exec($sql3);
    echo "grant_permissions.sql imported successfully.<br>";

    // Import insert_products.sql
    $sql4 = file_get_contents('insert_products.sql');
    $pdo->exec($sql4);
    echo "insert_products.sql imported successfully.<br>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
