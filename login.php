<?php
session_start();
require_once "includes/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $senha   = trim($_POST["senha"] ?? "");

    if ($usuario !== "" && $senha !== "") {
        $stmt = $conn->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        // Se você ainda usa senha em texto:
        if ($user && $senha === $user["senha"]) {
            $_SESSION["usuario"] = $user["usuario"];
            header("Location: painel.php");
            exit;
        }

        // Se futuramente usar hash:
        // if ($user && password_verify($senha, $user["senha"])) { ... }

        $erro = "Usuário ou senha inválidos.";
    } else {
        $erro = "Preencha usuário e senha.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Empresa</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="center">

<div class="card">
    <h2>Login</h2>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="post">
        <input name="usuario" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
