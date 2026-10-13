<?php
session_start();
require_once 'conexao.php';
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { exit(); }

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM cupons WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: dashboard.php?sucesso=cupom");
exit();
?>