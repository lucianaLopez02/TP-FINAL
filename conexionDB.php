<?php
// Hay que incluir este archivo en todos los Archivos que realizan consultas SQL.
// include 'config.php';

$nombreServidor = "localhost";
$nombreUsuario = "root";
$contraseña = "";
$baseDeDatos = "viajes";
// Hay que cambiar nombre de usuario, servidor, contraseña y nombre de la base de datos, según corresponda.

// Crear conexión
$conn = new mysqli($nombreServidor, $nombreUsuario, $contraseña, $baseDeDatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}