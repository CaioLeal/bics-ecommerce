<?php
// Inicia a sessão para manter o usuário logado
session_start();

// Chama o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se os dados vieram do formulário via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pega os dados do formulário e limpa espaços em branco
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // 1. Prepara a consulta (Prepared Statement) para evitar SQL Injection
    // Busca o usuário pelo email
    $sql = "SELECT id, nome, senha FROM admins WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Vincula o parâmetro (s = string)
        $stmt->bind_param("s", $usuario);
        
        // Executa a consulta
        $stmt->execute();
        
        // Guarda o resultado
        $stmt->store_result();

        // Se encontrou algum usuário com esse email (1 linha retornada)
        if ($stmt->num_rows == 1) {
            
            // Vincula o resultado às variáveis
            $stmt->bind_result($id, $nome, $senha_hash);
            $stmt->fetch();

            // 2. Verifica a senha
            // Como criamos a senha com MD5 no banco para o teste, comparamos usando md5(). 
            if (md5($senha) === $senha_hash) {
                
                // Senha correta! Cria as variáveis de sessão para permitir acesso ao painel
                $_SESSION['admin_logado'] = true;
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_nome'] = $nome;

                // Redireciona para o painel (dashboard)
                header("Location: dashboard.php");
                exit();
                
            } else {
                // Senha incorreta
                header("Location: index.php?erro=senha");
                exit();
            }
        } else {
            // Usuário não encontrado no banco
            header("Location: index.php?erro=usuario");
            exit();
        }
        
        $stmt->close();
    }
} else {
    // Se tentarem acessar este arquivo diretamente pela URL sem enviar o formulário, joga de volta pro login
    header("Location: index.php");
    exit();
}

$conn->close();
?>