<?php
session_start();
require_once "includes/conexao.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

// Buscar empresas
$result = $conn->query("SELECT * FROM empresas ORDER BY id DESC");
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
  <span>Painel Administrativo</span>
  <a class="logout" href="logout.php">Sair</a>
</header>

<main class="container">
<h1>Empresas Cadastradas</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Empresa</th>
            <th>CNPJ/CPF</th>
            <th>E-mail</th>
            <th>Cidade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($e = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $e["id"] ?></td>
                    <td><?= htmlspecialchars($e["nome_empresa"]) ?></td>
                    <td><?= htmlspecialchars($e["cnpj"]) ?></td>
                    <td><?= htmlspecialchars($e["email"]) ?></td>
                    <td><?= htmlspecialchars($e["cidade"]) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $e["id"] ?>">Editar</a> |
                        <a href="excluir.php?id=<?= $e["id"] ?>" onclick="return confirm('Excluir este registro?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum cadastro encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</main>

</body>
</html>
