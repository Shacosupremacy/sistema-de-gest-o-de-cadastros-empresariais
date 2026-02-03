<?php include "includes/auth.php"; include "includes/conexao.php";
$conn->query("DELETE FROM empresas WHERE id=".$_GET["id"]);
header("Location:painel.php");
