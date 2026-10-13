<?php
// Inicia a sessão para podermos usar variáveis globais se necessário
session_start();

// Como este ficheiro está na pasta raiz, chamamos a conexão que está dentro da pasta admin
require_once 'admin/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recebe e limpa os espaços vazios dos dados
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $telefone = trim($_POST['telefone']);
    $nascimento = trim($_POST['nascimento']);
    
    // 1. Validação de Segurança: As senhas coincidem?
    if ($senha !== $confirma_senha) {
        die("ERRO: As senhas não coincidem. <br><br><a href='index.html'>Voltar à loja</a>");
    }
    
    // 2. Criptografa a senha (usando MD5 para manter a consistência com os admins)
    $senha_hash = md5($senha);
    
    // 3. Prepara a query SQL para inserir na tabela "clientes" que acabou de criar
    $sql = "INSERT INTO clientes (nome, sobrenome, email, senha, telefone, nascimento, metodo_login) VALUES (?, ?, ?, ?, ?, ?, 'email')";
    
    if ($stmt = $conn->prepare($sql)) {
        // "ssssss" significa que estamos a passar 6 Strings
        $stmt->bind_param("ssssss", $nome, $sobrenome, $email, $senha_hash, $telefone, $nascimento);
        
        if ($stmt->execute()) {
            // Sucesso! A conta foi criada. Volta para o site com um aviso na URL.
            header("Location: index.html?conta=sucesso");
            exit();
        } else {
            // Erro comum: o utilizador tentar registar um e-mail que já existe
            echo "ERRO: Não foi possível criar a conta. Este e-mail já está registado na BICS. <br><br><a href='index.html'>Tentar novamente</a>";
        }
        $stmt->close();
    } else {
        echo "Erro de conexão com o banco de dados da loja.";
    }
} else {
    // Se alguém tentar aceder ao ficheiro pelo link direto sem enviar o formulário
    header("Location: index.html");
    exit();
}

$conn->close();
?>