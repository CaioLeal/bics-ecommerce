<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { header("Location: index.php"); exit(); }
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $codigo = strtoupper(trim($_POST['codigo']));
    $tipo_desconto = $_POST['tipo_desconto'];
    $valor_desconto = str_replace(',', '.', trim($_POST['valor_desconto']));
    
    // Trata os campos opcionais (se estiverem vazios, envia NULL para o banco)
    $data_validade = !empty($_POST['data_validade']) ? $_POST['data_validade'] : null;
    $limite_total = !empty($_POST['limite_total']) ? (int)$_POST['limite_total'] : null;
    $limite_cliente = !empty($_POST['limite_cliente']) ? (int)$_POST['limite_cliente'] : 1;
    $categoria_id = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
    $apenas_novos = (int)$_POST['apenas_novos'];

    $sql = "INSERT INTO cupons (codigo, tipo_desconto, valor_desconto, limite_uso_total, limite_uso_cliente, apenas_novos, data_validade, categoria_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        // s = string, d = double, i = int
        $stmt->bind_param("ssdiiisi", $codigo, $tipo_desconto, $valor_desconto, $limite_total, $limite_cliente, $apenas_novos, $data_validade, $categoria_id);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?sucesso=cupom");
            exit();
        } else {
            echo "Erro: Este código de cupom já existe. <br><br>" . $stmt->error;
        }
        $stmt->close();
    }
}
?>