<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$banco = "u511483757_cadastros";
$usuario = "u511483757_matheus";
$senha = "Mat13012010";

$conn = @new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_errno) {
    die("Erro ao conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
