<?php
// Inicia a sessão de segurança
session_start();

// Bloqueia quem não estiver logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php");
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['midia_universo']) && isset($_POST['seccao_universo'])) {
    
    $seccao = $_POST['seccao_universo']; // 'fashion', 'runway' ou 'magazine'
    $ficheiro = $_FILES['midia_universo'];
    
    // Pasta de destino (A mesma que usamos nos seus caminhos de origem no Vite)
    $pasta_destino = "../public/img/universe/"; 
    $pasta_video = "../public/video/universe/";
    
    // Extrai a extensão
    $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
    
    // Validações e Nomes Padronizados com base na escolha
    if ($seccao == 'fashion') {
        if ($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png") {
            die("ERRO: Para a secção 'Our Fashion', utilize apenas imagens JPG ou PNG. <br><a href='dashboard.php'>Voltar</a>");
        }
        if (!file_exists($pasta_destino)) { mkdir($pasta_destino, 0777, true); }
        $nome_final = "ourfashion.png"; // Padrão PNG
        $destino_final = $pasta_destino . $nome_final;
        
    } else if ($seccao == 'magazine') {
        if ($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png") {
            die("ERRO: Para a secção 'BICS Magazine', utilize apenas imagens JPG ou PNG. <br><a href='dashboard.php'>Voltar</a>");
        }
        if (!file_exists($pasta_destino)) { mkdir($pasta_destino, 0777, true); }
        $nome_final = "bicsmagazine.png"; // Padrão PNG
        $destino_final = $pasta_destino . $nome_final;
        
    } else if ($seccao == 'runway') {
        if ($extensao != "mp4") {
            die("ERRO: Para a secção 'Runway Shows', utilize apenas vídeos MP4. <br><a href='dashboard.php'>Voltar</a>");
        }
        if (!file_exists($pasta_video)) { mkdir($pasta_video, 0777, true); }
        $nome_final = "runwaywalk.mp4"; // Padrão de Vídeo
        $destino_final = $pasta_video . $nome_final;
        
    } else {
        die("Opção inválida. <br><a href='dashboard.php'>Voltar</a>");
    }
    
    // Move o ficheiro para o destino correto
    if (move_uploaded_file($ficheiro["tmp_name"], $destino_final)) {
        header("Location: dashboard.php?sucesso=universo");
        exit();
    } else {
        echo "Ocorreu um erro ao guardar o ficheiro. <br><a href='dashboard.php'>Voltar</a>";
    }
    
} else {
    header("Location: dashboard.php");
    exit();
}
?>