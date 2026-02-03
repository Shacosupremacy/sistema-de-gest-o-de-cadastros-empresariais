<?php
include "includes/auth.php";
include "includes/conexao.php";

$id = $_GET["id"];

// Atualizar dados se o formulário for enviado
if ($_POST) {
    $campos = [];
    $tipos = '';
    $valores = [];

    foreach ($_POST as $col => $valor) {
        $campos[] = "$col=?";
        $tipos .= 's'; // assume todos string; ajustar se necessário
        $valores[] = $valor;
    }

    $tipos .= 'i'; // id
    $valores[] = $id;

    $sql = "UPDATE empresas SET " . implode(', ', $campos) . " WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($tipos, ...$valores);
    $stmt->execute();

    header("Location: painel.php");
    exit;
}

// Buscar dados atuais
$empresa = $conn->query("SELECT * FROM empresas WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Empresa</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
  <img src="assets/logo.jpeg">
  <span>Editar Empresa</span>
</header>

<main class="container">
<h1>Editar Empresa</h1>

<form method="post">
    <?php foreach ($empresa as $col => $valor): ?>
        <?php if ($col === 'id') continue; ?>
        <label><?= ucfirst(str_replace('_',' ',$col)) ?></label>
        <input type="text" name="<?= $col ?>" value="<?= htmlspecialchars($valor) ?>">
    <?php endforeach; ?>
    <button>Salvar</button>
</form>
</main>

<footer class="footer">
Thiago Mecânica Diesel © 2026
</footer>

</body>
</html>
