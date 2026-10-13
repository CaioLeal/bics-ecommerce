<?php
// Inicia a sessão para manter o cliente logado na loja
session_start();

// Puxa a conexão do banco que está na pasta admin
require_once 'admin/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pega o e-mail e senha digitados na gaveta lateral
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Busca o cliente pelo e-mail
    $sql = "SELECT id, nome, sobrenome, senha FROM clientes WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Se encontrar 1 cliente com esse e-mail
        if ($stmt->num_rows == 1) {
            
            $stmt->bind_result($id, $nome, $sobrenome, $senha_hash);
            $stmt->fetch();

            // Compara a senha digitada (passada no MD5) com a do banco
            if (md5($senha) === $senha_hash) {
                
                // Sucesso! Cria a sessão do cliente
                $_SESSION['cliente_logado'] = true;
                $_SESSION['cliente_id'] = $id;
                $_SESSION['cliente_nome'] = $nome;
                $_SESSION['cliente_sobrenome'] = $sobrenome;

                // Redireciona para a home page
                header("Location: index.html?login=sucesso");
                exit();
                
            } else {
                // Senha errada
                header("Location: index.html?erro=senha");
                exit();
            }
        } else {
            // E-mail não cadastrado
            header("Location: index.html?erro=email");
            exit();
        }
        $stmt->close();
    }
} else {
    header("Location: index.html");
    exit();
}

$conn->close();
?>