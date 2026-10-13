<?php
session_start();

// Proteção: apenas logados podem cadastrar
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    
    // Criptografa a senha antes de salvar
    $senha_hash = md5($senha);
    
    // Prepara a inserção segura (Prepared Statement)
    $sql = "INSERT INTO admins (nome, email, senha) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        
        if ($stmt->execute()) {
            // Sucesso! Volta para a tela de usuários
            header("Location: usuarios.php?sucesso=cadastro");
            exit();
        } else {
            // Se o e-mail já existir, o MySQL vai gerar um erro de duplicidade
            echo "Erro ao cadastrar. Este e-mail já pode estar em uso. <br><a href='usuarios.php'>Voltar</a>";
        }
        $stmt->close();
    }
}
$conn->close();
?>