<?php
// Configurações do banco de dados local (XAMPP)
$host = "localhost";
$usuario = "root"; // O XAMPP usa 'root' como padrão
$senha = ""; // O XAMPP vem sem senha por padrão
$banco = "bics_db"; // O nome do banco que criamos no Passo 1

// Tenta fazer a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu algum erro
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Configura para aceitar acentos corretamente
$conn->set_charset("utf8mb4");
?>