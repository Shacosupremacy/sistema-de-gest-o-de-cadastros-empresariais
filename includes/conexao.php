<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "*******";
$banco = "*********";
$usuario = "********";
$senha = "********";

$conn = @new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_errno) {
    die("Erro ao conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
