<?php
session_start();
require_once 'conexao.php';

// Segurança
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { exit(); }

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Deleta a categoria pelo ID
    $sql = "DELETE FROM categorias WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Volta para o painel
header("Location: dashboard.php?sucesso=produto");
exit();
?>