<?php
session_start();
require_once 'admin/conexao.php';

$cliente_logado = isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true;
$nome_cliente = $cliente_logado ? $_SESSION['cliente_nome'] : '';
$inicial_cliente = $cliente_logado ? strtoupper(substr($nome_cliente, 0, 1)) : '';

$id_categoria = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Busca os dados da categoria
$sql_cat = "SELECT * FROM categorias WHERE id = ?";
if ($stmt = $conn->prepare($sql_cat)) {
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $resultado_cat = $stmt->get_result();
    if ($resultado_cat->num_rows > 0) {
        $categoria = $resultado_cat->fetch_assoc();
    } else {
        die("<div style='padding: 100px; text-align:center; font-family:sans-serif;'><h2>Categoria não encontrada.</h2><a href='index.php'>Voltar</a></div>");
    }
    $stmt->close();
}

// 2. Busca os produtos desta categoria
$produtos = [];
$sql_prod = "SELECT * FROM produtos WHERE categoria_id = ? ORDER BY id DESC";
if ($stmt = $conn->prepare($sql_prod)) {
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result_prod = $stmt->get_result();
    while($row = $result_prod->fetch_assoc()) {
        $produtos[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS — <?php echo htmlspecialchars($categoria['nome']); ?></title>
    <link rel="stylesheet" href="src/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
    <style>
        .category-hero {
            position: relative; width: 100%; height: 50vh;
            display: flex; align-items: center; justify-content: center;
            background-color: var(--color-black); overflow: hidden;
            margin-bottom: 50px;
        }
        .category-hero img {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.6;
        }
        .category-hero h1 {
            position: relative; z-index: 2; color: #fff; font-size: 3rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
        }
        @media (max-width: 768px) { .category-hero h1 { font-size: 2rem; } }
    </style>
</head>
<body>
    <!-- NAVBAR (Igualzinha à home) -->
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

    <main class="main-content" style="padding-top: 0;">
        
        <!-- BANNER DA CATEGORIA -->
        <section class="category-hero">
            <?php if(!empty($categoria['imagem'])): ?>
                <img src="public/img/categories/<?php echo htmlspecialchars($categoria['imagem']); ?>" alt="<?php echo htmlspecialchars($categoria['nome']); ?>">
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($categoria['nome']); ?></h1>
        </section>

        <!-- GRADE DE PRODUTOS -->
        <section class="products-section" style="padding-top: 20px;">
            <div class="products-grid">
                
                <?php if(empty($produtos)): ?>
                    <p style="grid-column: span 5; text-align: center; color: #888; font-size: 18px; padding: 50px;">Ainda não há produtos nesta categoria.</p>
                <?php else: ?>
                    <?php foreach($produtos as $prod): 
                        // Formatação e extração das imagens JSON
                        $fotos = json_decode($prod['galeria_fotos'], true);
                        $foto_primary = !empty($fotos) && isset($fotos[0]) ? $fotos[0] : 'placeholder.jpg';
                        $foto_secondary = !empty($fotos) && isset($fotos[1]) ? $fotos[1] : $foto_primary;
                        
                        $preco_atual = "R$ " . number_format($prod['preco_base'], 2, ',', '.');
                    ?>
                    
                    <div class="product-card" data-aos="fade-up">
                        <a href="product.php?id=<?php echo $prod['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="product-media">
                                <div class="product-actions" onclick="event.preventDefault();"> <!-- Impede clique de navegar na tag A -->
                                    <button class="btn-favorite" title="Add to Wishlist"><span class="material-symbols-outlined">favorite</span></button>
                                </div>
                                <img src="public/img/products/<?php echo htmlspecialchars($foto_primary); ?>" class="product-img-primary">
                                <img src="public/img/products/<?php echo htmlspecialchars($foto_secondary); ?>" class="product-img-secondary">
                            </div>
                            <div class="product-details">
                                <h3 class="product-name"><?php echo htmlspecialchars($prod['nome']); ?></h3>
                                <span class="product-price"><?php echo $preco_atual; ?></span>
                            </div>
                        </a>
                    </div>

                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script type="module" src="src/js/main.js"></script>
    <script>AOS.init({ duration: 1000, once: true });</script>
</body>
</html>