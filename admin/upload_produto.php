<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) { header("Location: index.php"); exit(); }
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. Recebe TODOS os dados do formulário
    $nome_produto = trim($_POST['nome_produto']);
    $ref_produto = trim($_POST['ref_produto']);
    $categoria_id = (int)$_POST['categoria_produto'];
    
    $preco_normal = str_replace(',', '.', trim($_POST['preco_normal']));
    $preco_desconto = isset($_POST['preco_desconto']) && $_POST['preco_desconto'] !== '' ? str_replace(',', '.', trim($_POST['preco_desconto'])) : null;
    
    $cores_produto = trim($_POST['cores_produto']);
    $descricao = trim($_POST['descricao']);
    
    // AQUI ESTÃO OS CAMPOS NOVOS CAPTURADOS:
    $tamanhos = isset($_POST['tamanhos']) ? trim($_POST['tamanhos']) : null;
    $cuidados = isset($_POST['cuidados']) ? trim($_POST['cuidados']) : null;

    $pasta_imagens = "../public/img/products/";
    $pasta_videos = "../public/video/products/";
    if (!file_exists($pasta_imagens)) { mkdir($pasta_imagens, 0777, true); }
    if (!file_exists($pasta_videos)) { mkdir($pasta_videos, 0777, true); }

    // 2. Processa Fotos
    $lista_fotos = [];
    if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
        foreach ($_FILES['fotos']['name'] as $key => $nome_original) {
            if ($_FILES['fotos']['error'][$key] == 0) {
                $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $nome_seguro = $ref_produto . "_foto_" . uniqid() . "." . $ext;
                    if (move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $pasta_imagens . $nome_seguro)) {
                        $lista_fotos[] = $nome_seguro;
                    }
                }
            }
        }
    }
    if (empty($lista_fotos)) { die("Erro: Tem de enviar pelo menos uma foto válida do produto."); }
    $fotos_json = json_encode($lista_fotos);

    // 3. Processa Vídeos
    $lista_videos = [];
    if (isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
        foreach ($_FILES['videos']['name'] as $key => $nome_original) {
            if ($_FILES['videos']['error'][$key] == 0) {
                $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
                if ($ext === 'mp4') {
                    $nome_seguro = $ref_produto . "_video_" . uniqid() . ".mp4";
                    if (move_uploaded_file($_FILES['videos']['tmp_name'][$key], $pasta_videos . $nome_seguro)) {
                        $lista_videos[] = $nome_seguro;
                    }
                }
            }
        }
    }
    $videos_json = !empty($lista_videos) ? json_encode($lista_videos) : null;

    // 4. Salvar no Banco (AGORA COM TAMANHOS E CUIDADOS)
    $sql = "INSERT INTO produtos (ref, nome, categoria_id, preco_base, preco_desconto, cores, tamanhos, cuidados, descricao, galeria_fotos, galeria_videos) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        // ssiddssssss = 11 parâmetros
        $stmt->bind_param("ssiddssssss", 
            $ref_produto, $nome_produto, $categoria_id, 
            $preco_normal, $preco_desconto, $cores_produto, 
            $tamanhos, $cuidados, // Injetando os novos dados aqui!
            $descricao, $fotos_json, $videos_json
        );

        if ($stmt->execute()) {
            header("Location: dashboard.php?sucesso=produto");
            exit();
        } else {
            echo "Erro ao cadastrar. Detalhes: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>