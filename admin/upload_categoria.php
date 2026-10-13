<?php
// Inicia a sessão de segurança
session_start();

// Bloqueia quem não estiver logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php");
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipo_categoria'])) {
    
    $tipo = $_POST['tipo_categoria']; // Recebe 'ele' ou 'ela'
    $pasta_destino = "../public/img/categories/";
    
    // Cria a pasta automaticamente se não existir
    if (!file_exists($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    
    // Array com as duas imagens enviadas pelo formulário
    $imagens = [
        'primary' => $_FILES['imagem_primary'] ?? null,
        'secondary' => $_FILES['imagem_secondary'] ?? null
    ];
    
    foreach ($imagens as $chave => $arquivo) {
        if ($arquivo && $arquivo['error'] == 0) {
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            
            // Validação de segurança: apenas JPG ou PNG
            if ($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png") {
                die("ERRO DE SEGURANÇA: Apenas imagens JPG ou PNG são permitidas. <br><a href='dashboard.php'>Voltar</a>");
            }
            
            // Define o nome final padronizado (Ex: capa_ele_primary.jpg ou capa_ela_secondary.jpg)
            $nome_final = "capa_" . $tipo . "_" . $chave . ".jpg";
            $ficheiro_destino = $pasta_destino . $nome_final;
            
            // Move o ficheiro temporário para a pasta pública
            move_uploaded_file($arquivo["tmp_name"], $ficheiro_destino);
        }
    }
    
    // Redireciona de volta para o painel com aviso de sucesso
    header("Location: dashboard.php?sucesso=categoria");
    exit();
    
} else {
    header("Location: dashboard.php");
    exit();
}
?>