<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_categoria']);
    $parent_id = (int)$_POST['categoria_pai'] > 0 ? (int)$_POST['categoria_pai'] : null;
    
    // Processamento da Imagem de Capa
    $imagem_nome = null;
    if (isset($_FILES['imagem_categoria']) && $_FILES['imagem_categoria']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['imagem_categoria']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $pasta = "../public/img/categories/";
            if (!file_exists($pasta)) { mkdir($pasta, 0777, true); }
            
            $imagem_nome = "cat_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['imagem_categoria']['tmp_name'], $pasta . $imagem_nome);
        }
    }

    $sql = "INSERT INTO categorias (nome, imagem, parent_id) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $nome, $imagem_nome, $parent_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?sucesso=produto");
    exit();
}
?>