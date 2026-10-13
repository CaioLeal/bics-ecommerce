<?php
session_start();
require_once 'admin/conexao.php';

$cliente_logado = isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true;
$nome_cliente = $cliente_logado ? $_SESSION['cliente_nome'] : '';
$inicial_cliente = $cliente_logado ? strtoupper(substr($nome_cliente, 0, 1)) : '';

$id_produto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT p.*, c.nome AS categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
        
        // MISTURA FOTOS E VÍDEOS NUMA LISTA ÚNICA
        $midias = [];
        $fotos = json_decode($produto['galeria_fotos'], true);
        if(!empty($fotos)) { foreach($fotos as $f) { $midias[] = ['tipo' => 'img', 'src' => $f]; } }
        
        $videos = json_decode($produto['galeria_videos'], true);
        if(!empty($videos)) { foreach($videos as $v) { $midias[] = ['tipo' => 'video', 'src' => $v]; } }
        
        if(empty($midias)) $midias[] = ['tipo' => 'img', 'src' => 'placeholder.jpg'];
        
        $tem_desconto = !empty($produto['preco_desconto']) && $produto['preco_desconto'] > 0 && $produto['preco_desconto'] < $produto['preco_base'];
        if ($tem_desconto) {
            $preco_atual = "R$ " . number_format($produto['preco_desconto'], 2, ',', '.');
            $preco_antigo = "R$ " . number_format($produto['preco_base'], 2, ',', '.');
            $percentual = round((($produto['preco_base'] - $produto['preco_desconto']) / $produto['preco_base']) * 100);
        } else {
            $preco_atual = "R$ " . number_format($produto['preco_base'], 2, ',', '.');
        }
        
        $cores_array = [];
        if (!empty($produto['cores'])) {
            $pares = explode(',', $produto['cores']);
            foreach($pares as $par) {
                $dados = explode('|', trim($par));
                if(count($dados) >= 2) {
                    $cores_array[] = ['nome' => trim($dados[0]), 'hex' => trim($dados[1]), 'estoque' => isset($dados[2]) ? (int)trim($dados[2]) : 0];
                }
            }
        }

        // Processa os Tamanhos (Ex: "36, 38, 40")
        $tamanhos_array = !empty($produto['tamanhos']) ? array_map('trim', explode(',', $produto['tamanhos'])) : [];

    } else {
        die("<div style='padding: 100px; text-align:center; font-family:sans-serif;'><h2>Produto não encontrado.</h2><a href='index.php'>Voltar</a></div>");
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS — <?php echo htmlspecialchars($produto['nome']); ?></title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
</head>
<body>
    <header id="navbar" class="navbar transparent">
        <div class="navbar-left">
            <button id="menu-btn" class="nav-item"><span class="material-symbols-outlined">menu</span><span class="nav-text">Menu</span></button>
        </div>
        <div class="navbar-center"><a href="index.php" class="logo">BICS</a></div>
        <div class="navbar-right">
            <button id="login-btn" class="nav-item icon-btn" style="padding: 0;">
                <?php if($cliente_logado): ?>
                    <div style="width: 28px; height: 28px; background-color: var(--color-black); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600;"><?php echo $inicial_cliente; ?></div>
                <?php else: ?>
                    <span class="material-symbols-outlined">person</span>
                <?php endif; ?>
            </button>
            <button id="cart-icon" class="nav-item icon-btn"><span class="material-symbols-outlined">shopping_bag</span></button>
        </div>
    </header>

    <main class="product-page">
        <div class="product-container">
            
            <div class="product-gallery">
                <!-- Miniaturas (Lado esquerdo) -->
                <div class="gallery-thumbnails">
                    <?php foreach($midias as $index => $midia): ?>
                        <?php if($midia['tipo'] == 'img'): ?>
                            <img src="public/img/products/<?php echo htmlspecialchars($midia['src']); ?>" class="thumb-img <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <?php else: ?>
                            <!-- Para vídeos, mostramos ele mutado e como miniatura -->
                            <video src="public/video/products/<?php echo htmlspecialchars($midia['src']); ?>" class="thumb-img <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>" muted></video>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Quadro Principal -->
                <div class="gallery-main" style="position: relative; overflow: hidden; background-color: #f5f5f5;">
                    <button class="gallery-arrow arrow-left"><span class="material-symbols-outlined">chevron_left</span></button>
                    
                    <!-- Loop Misto (Cria as camadas empilhadas com CSS absolute) -->
                    <?php foreach($midias as $index => $midia): ?>
                        <?php if($midia['tipo'] == 'img'): ?>
                            <img src="public/img/products/<?php echo htmlspecialchars($midia['src']); ?>" class="main-media <?php echo $index === 0 ? 'active' : ''; ?>">
                        <?php else: ?>
                            <video src="public/video/products/<?php echo htmlspecialchars($midia['src']); ?>" class="main-media <?php echo $index === 0 ? 'active' : ''; ?>" loop muted playsinline></video>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <button class="gallery-arrow arrow-right"><span class="material-symbols-outlined">chevron_right</span></button>

                    <!-- Cores Flutuantes Mobile (Dentro da imagem) -->
                    <?php if(!empty($cores_array)): ?>
                    <div class="mobile-colors">
                        <?php foreach($cores_array as $index => $cor): ?>
                            <div class="color-circle <?php echo $index === 0 ? 'active' : ''; ?>" style="background-color: <?php echo htmlspecialchars($cor['hex']); ?>;" data-color="<?php echo htmlspecialchars($cor['nome']); ?>" data-stock="<?php echo $cor['estoque']; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="gallery-dots">
                        <?php foreach($midias as $index => $midia): ?>
                            <div class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="product-info">
                <span class="product-ref">Ref: <?php echo htmlspecialchars($produto['ref']); ?></span>
                <h1 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h1>
                
                <div class="price-container">
                    <?php if($tem_desconto): ?>
                        <span class="old-price"><?php echo $preco_antigo; ?></span>
                        <span class="current-price"><?php echo $preco_atual; ?></span>
                        <span class="discount-badge"><?php echo $percentual; ?>% OFF</span>
                    <?php else: ?>
                        <span class="current-price"><?php echo $preco_atual; ?></span>
                    <?php endif; ?>
                </div>

                <?php if(!empty($cores_array)): ?>
                <div class="desktop-colors">
                    <p class="color-label">Cor: <span id="selected-color-text"><?php echo htmlspecialchars($cores_array[0]['nome']); ?></span></p>
                    <div class="color-options">
                        <?php foreach($cores_array as $index => $cor): ?>
                            <div class="color-circle <?php echo $index === 0 ? 'active' : ''; ?>" style="background-color: <?php echo htmlspecialchars($cor['hex']); ?>;" data-color="<?php echo htmlspecialchars($cor['nome']); ?>" data-stock="<?php echo $cor['estoque']; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <span id="stock-text" class="stock-info <?php echo $cores_array[0]['estoque'] == 0 ? 'out' : ''; ?>">
                        <?php echo $cores_array[0]['estoque'] > 0 ? "Em estoque: " . $cores_array[0]['estoque'] . " unidades" : "Esgotado"; ?>
                    </span>
                </div>
                <?php endif; ?>

                <div class="purchase-box">
                    <?php if(!empty($tamanhos_array)): ?>
                    <div class="size-selector">
                        <select id="size-select">
                            <option value="" disabled selected>Selecione seu tamanho</option>
                            <?php foreach($tamanhos_array as $tam): ?>
                                <option value="<?php echo htmlspecialchars($tam); ?>"><?php echo htmlspecialchars($tam); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="material-symbols-outlined dropdown-icon">expand_more</span>
                    </div>
                    <a href="#" class="size-guide">Qual é o meu tamanho?</a>
                    <?php endif; ?>
                    
                    <button class="btn-buy" id="buy-button">Comprar</button>
                </div>

                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                </div>

                <div class="product-accordion-details">
                    <div class="acc-item">
                        <button class="acc-header">Cuidados com o Produto <span class="material-symbols-outlined">add</span></button>
                        <div class="acc-content"><p><?php echo !empty($produto['cuidados']) ? nl2br(htmlspecialchars($produto['cuidados'])) : "Lavar a seco. Evitar exposição direta ao sol."; ?></p></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="module" src="src/js/main.js"></script>
</body>
</html>