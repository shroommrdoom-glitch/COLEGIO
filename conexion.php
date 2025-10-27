<?php
$host = "mysql-sadboyz.alwaysdata.net"; 
$dbname = "sadboyz_bd_colegio"; 
$username = "sadboyz"; 
$password = "SPKDENJI27/_/"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Error de conexión a AlwaysData: " . $e->getMessage();
    $conn = null;
}
?>