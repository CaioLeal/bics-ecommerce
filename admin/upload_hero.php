<?php
// Inicia a sessão para garantir que apenas administradores podem fazer uploads
session_start();

// Proteção de segurança: Se não estiver logado, bloqueia a execução
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php");
    exit();
}

// Verifica se o formulário foi enviado com um ficheiro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['hero_video'])) {
    
    $video = $_FILES['hero_video'];
    
    // Extrai a extensão do ficheiro
    $extensao = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
    
    // Segurança: Aceitar APENAS ficheiros .mp4
    if ($extensao != "mp4") {
        die("ERRO DE SEGURANÇA: Apenas ficheiros no formato MP4 são permitidos. <br><a href='dashboard.php'>Voltar</a>");
    }
    
    // O caminho onde o vídeo oficial da loja está guardado
    $pasta_destino = "../public/video/hero/";
    
    // Se por acaso a pasta não existir, o PHP cria-a automaticamente
    if (!file_exists($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    
    // O nome fixo que o site principal e a tela de login já puxam
    $ficheiro_destino = $pasta_destino . "herovideo.mp4";
    
    // Tenta mover o ficheiro temporário do upload para o destino final, substituindo o antigo
    if (move_uploaded_file($video["tmp_name"], $ficheiro_destino)) {
        
        // Redireciona de volta para a dashboard com a mensagem verde de sucesso
        header("Location: dashboard.php?sucesso=video");
        exit();
        
    } else {
        echo "Ocorreu um erro inesperado ao guardar o vídeo no servidor. Verifique as permissões da pasta. <br><a href='dashboard.php'>Voltar</a>";
    }
} else {
    // Se alguém tentar aceder a este ficheiro diretamente pela URL, volta para a dashboard
    header("Location: dashboard.php");
    exit();
}
?>