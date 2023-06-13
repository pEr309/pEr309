<?php
$host="localhost";
$bd="proyecto";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
   
} catch (Exception $th) {
    
    echo $ex->getMessage();
}
?>