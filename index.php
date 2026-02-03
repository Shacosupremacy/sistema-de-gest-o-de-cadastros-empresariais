<?php
require_once "includes/conexao.php";

$errors = [];
$old = []; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_POST["website"])) {

    $old = $_POST;

 
    if (empty($_POST["nome_empresa"])) {
        $errors['nome_empresa'] = "O nome da empresa é obrigatório.";
    }

  
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido.";
    }

  
    $cpf_cnpj = preg_replace("/\D/", "", $_POST["cnpj"]);
    if (strlen($cpf_cnpj) == 11) {
        // CPF válido: você pode adicionar função de validação real se quiser
    } elseif (strlen($cpf_cnpj) == 14) {
        // CNPJ válido: você pode adicionar função de validação real se quiser
    } else {
        $errors['cnpj'] = "CPF ou CNPJ inválido. Deve ter 11 (CPF) ou 14 (CNPJ) números.";
    }


    $telefone = preg_replace("/\D/", "", $_POST["telefone"]);
    if (!empty($telefone) && strlen($telefone) < 10) {
        $errors['telefone'] = "Telefone inválido.";
    }

  
    $cep = preg_replace("/\D/", "", $_POST["cep"]);
    if (!empty($cep) && strlen($cep) != 8) {
        $errors['cep'] = "CEP inválido. Deve ter 8 números.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO empresas
            (nome_empresa, cnpj, email, telefone, endereco, cep, cidade, estado, segmento, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssss",
            $_POST["nome_empresa"],
            $cpf_cnpj,
            $_POST["email"],
            $telefone,
            $_POST["endereco"],
            $cep,
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["segmento"],
            $_POST["observacoes"]
        );

        $stmt->execute();
        $stmt->close();

        header("Location: confirmacao.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro Empresarial</title>
<style>
*{box-sizing:border-box;font-family:Arial,sans-serif;margin:0;padding:0;}
body{background:#f2f2f2;}
.header{background:#2b2b2b;color:#fff;padding:15px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.header img{height:40px;}
.container{max-width:700px;width:90%;margin:20px auto;background:#fff;padding:20px;border-radius:5px;}
form label{display:block;margin-top:10px;font-weight:bold;}
input,textarea,button{width:100%;padding:10px;margin-top:5px;font-size:1rem;border-radius:4px;border:1px solid #ccc;}
button{background:#000;color:#fff;border:none;cursor:pointer;transition:0.3s;margin-top:15px;}
button:hover{background:#333;}
.footer{text-align:center;padding:15px;background:#2b2b2b;color:#fff;}
.errors{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:10px;margin-bottom:15px;border-radius:5px;}
.errors ul{margin:0;padding-left:20px;}
@media(max-width:768px){.container{padding:15px;width:95%;}input,textarea,button{padding:12px;font-size:1rem;}.header img{height:35px;}}
@media(max-width:480px){.header{flex-direction:column;align-items:flex-start;gap:5px;}button{padding:10px;font-size:0.95rem;}}
</style>
</head>
<body>

<header class="header">
  <img src="assets/logo.jpeg" alt="Logo">
  <span>Cadastro Empresarial</span>
</header>

<main class="container">
<h1>Cadastro de Empresa</h1>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" id="cadastroForm">
<input type="text" name="website" class="honeypot">

<label>Nome da Empresa *</label>
<input required name="nome_empresa" placeholder="Nome da empresa" value="<?= htmlspecialchars($old['nome_empresa'] ?? '') ?>">

<label>CPF/CNPJ *</label>
<input required name="cnpj" placeholder="CPF ou CNPJ" maxlength="18" value="<?= htmlspecialchars($old['cnpj'] ?? '') ?>">

<label>E-mail *</label>
<input required type="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($old['email'] ?? '') ?>">

<label>Telefone</label>
<input type="tel" name="telefone" placeholder="Telefone" maxlength="15" value="<?= htmlspecialchars($old['telefone'] ?? '') ?>">

<label>Endereço</label>
<input name="endereco" placeholder="Endereço" value="<?= htmlspecialchars($old['endereco'] ?? '') ?>">

<label>CEP</label>
<input type="text" name="cep" placeholder="CEP" maxlength="9" value="<?= htmlspecialchars($old['cep'] ?? '') ?>">

<label>Cidade</label>
<input name="cidade" placeholder="Cidade" value="<?= htmlspecialchars($old['cidade'] ?? '') ?>">

<label>Estado</label>
<input name="estado" placeholder="Estado" value="<?= htmlspecialchars($old['estado'] ?? '') ?>">

<label>Segmento</label>
<input name="segmento" placeholder="Segmento" value="<?= htmlspecialchars($old['segmento'] ?? '') ?>">

<label>Observações</label>
<textarea name="observacoes" placeholder="Observações"><?= htmlspecialchars($old['observacoes'] ?? '') ?></textarea>

<button type="submit">Cadastrar</button>
</form>
</main>

<footer class="footer">
Thiago Mecânica Diesel © 2026
</footer>

<script>

const form = document.getElementById("cadastroForm");
const cnpjInput = form.cnpj;
const telInput = form.telefone;
const cepInput = form.cep;

function maskInput(input, cpfMask, cnpjMask) {
    input.addEventListener("input", function() {
        let val = input.value.replace(/\D/g,'');
        if(val.length <= 11){
            
            let newVal = '';
            for(let i=0;i<val.length;i++){
                if(i==3||i==6) newVal+='.';
                if(i==9) newVal+='-';
                newVal+=val[i];
            }
            input.value = newVal;
        } else {
          
            let newVal = '';
            for(let i=0;i<val.length;i++){
                if(i==2||i==5) newVal+='.';
                if(i==8) newVal+='/';
                if(i==12) newVal+='-';
                newVal+=val[i];
            }
            input.value = newVal;
        }
    });
}

maskInput(cnpjInput);
maskInput(telInput);
maskInput(cepInput);


form.addEventListener("submit", function(e){
    let errors = [];
    const email = this.email.value.trim();
    const cpfcnpj = this.cnpj.value.replace(/\D/g,'');
    const telefone = this.telefone.value.replace(/\D/g,'');
    const cep = this.cep.value.replace(/\D/g,'');

    if (!email.match(/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/)) errors.push("E-mail inválido.");
    if (!(cpfcnpj.length === 11 || cpfcnpj.length === 14)) errors.push("CPF ou CNPJ inválido.");
    if (telefone && telefone.length < 10) errors.push("Telefone inválido.");
    if (cep && cep.length != 8) errors.push("CEP inválido.");

    if(errors.length>0){
        e.preventDefault();
        let container = document.querySelector(".errors");
        if(!container){
            container = document.createElement("div");
            container.classList.add("errors");
            form.parentNode.insertBefore(container, form);
        }
        container.innerHTML = "<ul><li>" + errors.join("</li><li>") + "</li></ul>";
        window.scrollTo(0,0);
    }
});
</script>

</body>
</html>
