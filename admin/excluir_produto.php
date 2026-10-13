<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { exit(); }

// Se o ficheiro receber um ID, ele apaga esse produto no banco
if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM produtos WHERE id = $id");
}

// Depois de apagar, redireciona o painel de volta para a aba de produtos
header("Location: dashboard.php?sucesso=produto");
exit();
?>