<?php
session_start();

// Verifica se existe um cliente logado e guarda a inicial dele
$cliente_logado = isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true;
$nome_cliente = $cliente_logado ? $_SESSION['cliente_nome'] : '';
$inicial_cliente = $cliente_logado ? strtoupper(substr($nome_cliente, 0, 1)) : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS — Beyond the Ordinary</title>

    <link rel="stylesheet" href="src/css/style.css">
    
    <!-- Ícones do Google com traço fino (estilo luxo) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
    <link rel="icon" href="public/img/icon.png" type="image/x-icon">   
</head>
<body>
    <!-- =========================================
                       NAVBAR
    ========================================= -->
    <header id="navbar" class="navbar transparent">
        <div class="navbar-left">
            <button id="menu-btn" class="nav-item">
                <span class="material-symbols-outlined">menu</span>
                <span class="nav-text">Menu</span>
            </button>
            <button class="nav-item search-desktop">
                <span class="material-symbols-outlined">search</span>
                <span class="nav-text">Pesquisar</span>
            </button>
        </div>
        
        <div class="navbar-center">
            <a href="#" class="logo">BICS</a>
        </div>
        
        <div class="navbar-right">
            <a href="#" class="nav-item hidden-mobile">Fale conosco</a>
            <button class="nav-item icon-btn"><span class="material-symbols-outlined">favorite</span></button>
            
            <!-- Ícone de Login Dinâmico -->
            <button id="login-btn" class="nav-item icon-btn" style="padding: 0;">
                <?php if($cliente_logado): ?>
                    <!-- Bolinha preta com a inicial do cliente -->
                    <div style="width: 28px; height: 28px; background-color: var(--color-black); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; font-family: var(--font-main);">
                        <?php echo $inicial_cliente; ?>
                    </div>
                <?php else: ?>
                    <!-- Ícone de pessoa padrão se não estiver logado -->
                    <span class="material-symbols-outlined">person</span>
                <?php endif; ?>
            </button>

            <button id="cart-icon" class="nav-item icon-btn"><span class="material-symbols-outlined">shopping_bag</span></button>
        </div>
    </header>

    <!-- =========================================
                    SIDEBAR MENU
    ========================================= -->
    <aside id="sidebar-menu" class="sidebar">
        <div class="sidebar-header">
            <button id="close-btn" class="nav-item">
                <span class="material-symbols-outlined">close</span>
                <span class="nav-text">Close</span>
            </button>
            <button class="nav-item">
                <span class="material-symbols-outlined">search</span>
                <span class="nav-text">Pesquisar</span>
            </button>
        </div>
        
        <nav class="sidebar-categories">
            <ul>
                <li><a href="categoria.php?id=1">Masculino</a></li>
                <li><a href="categoria.php?id=2">Feminino</a></li>
                <li><a href="categoria.php?id=3">Bolsas</a></li>
                <li><a href="categoria.php?id=4">Acessórios</a></li>
                <li><a href="#">BICS Magazine</a></li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <ul>
                <li><a href="#" id="left-register-btn"><span class="material-symbols-outlined">person</span> Entrar ou Registrar-se</a></li>
                <li><a href="#"><span class="material-symbols-outlined">shopping_bag</span> Carrinho</a></li>
                <li><a href="#"><span class="material-symbols-outlined">favorite</span> Lista de desejos</a></li>
                <li><a href="#"><span class="material-symbols-outlined">call</span> Fale conosco</a></li>
                <li><a href="#"><span class="material-symbols-outlined">language</span> Brasil / Portuguese</a></li>
            </ul>
        </div>
    </aside>

    <!-- =========================================
                 SIDEBAR LOGIN / CADASTRO
    ========================================= -->
    <aside id="login-sidebar" class="sidebar sidebar-right">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Identificação</h2>
            <button id="close-login-btn" class="nav-item icon-btn">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="login-content">
            <?php if($cliente_logado): ?>
                <!-- Visão do Cliente Logado -->
                <div class="login-section" style="text-align: center; margin-top: 40px;">
                    <div style="width: 80px; height: 80px; background-color: var(--color-black); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 600; margin: 0 auto 20px;">
                        <?php echo $inicial_cliente; ?>
                    </div>
                    <h3 style="font-size: 20px;">Olá, <?php echo htmlspecialchars($nome_cliente); ?>!</h3>
                    <p style="color: #666; margin-bottom: 40px;">Bem-vindo(a) ao universo BICS.</p>
                    
                    <a href="logout_cliente.php" class="btn-outline-black" style="display: block; text-align: center; text-decoration: none;">Sair da Conta</a>
                </div>
            <?php else: ?>
                <!-- Visão Normal (Para quem não está logado) -->
                <div class="login-section">
                    <h3>Já possuo uma conta</h3>
                    <div class="social-login-buttons">
                        <button class="social-btn google-btn"><img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">Fazer Login com Google</button>
                        <button class="social-btn apple-btn"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple">Iniciar Sessão com a Apple</button>
                    </div>
                    <div class="login-divider"><span>ou</span></div>
                    
                    <!-- Apontando para o index.php agora -->
                    <form class="bics-form" action="login_cliente.php" method="POST">
                        <p class="form-hint">Campos Obrigatórios*</p>
                        <div class="input-group">
                            <input type="email" id="login-email" name="email" required placeholder=" ">
                            <label for="login-email">Login (E-mail)*</label>
                        </div>
                        <div class="input-group">
                            <input type="password" id="login-senha" name="senha" required placeholder=" ">
                            <label for="login-senha">Senha*</label>
                            <button type="button" class="toggle-password"><span class="material-symbols-outlined">visibility_off</span></button>
                        </div>
                        <a href="#" class="forgot-password">Esqueceu sua senha?</a>
                        <p class="magic-link-text">Ou use o link de uso único para login: <a href="#">Envie-me por e-mail o link</a></p>
                        <button type="submit" class="btn-solid-black">Entrar</button>
                    </form>
                </div>

                <hr class="section-divider">

                <div class="register-section">
                    <h3>Ainda não possuo uma conta</h3>
                    <p>Personalize sua experiência criando uma conta BICS.</p>
                    <button id="open-register-btn" class="btn-outline-black">Crie sua Conta MyBics</button>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Fundo escuro atrás do menu lateral quando aberto -->
    <div id="sidebar-overlay" class="overlay"></div>

    <!-- =========================================
                  MODAL DE CADASTRO (MYBICS)
    ========================================= -->
    <div id="register-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Criar Conta MyBics</h2>
                <button id="close-modal-btn" class="nav-item icon-btn">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <p class="form-hint">Personalize sua experiência BICS.</p> -->
                <!-- O action agora aponta para o PHP e removemos o block do Javascript -->
                <form class="bics-form" action="cadastrar_cliente.php" method="POST">
                    <div class="input-row">
                        <div class="input-group">
                            <input type="text" id="reg-nome" name="nome" required placeholder=" ">
                            <label for="reg-nome">Nome*</label>
                        </div>
                        <div class="input-group">
                            <input type="text" id="reg-sobrenome" name="sobrenome" required placeholder=" ">
                            <label for="reg-sobrenome">Sobrenome*</label>
                        </div>
                    </div>

                    <div class="input-group" style="margin-bottom: 15px;">
                        <input type="email" id="reg-email" name="email" required placeholder=" ">
                        <label for="reg-email">E-mail*</label>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <input type="password" id="reg-senha" name="senha" required placeholder=" ">
                            <label for="reg-senha">Senha*</label>
                            <button type="button" class="toggle-password">
                                <span class="material-symbols-outlined">visibility_off</span>
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="password" id="reg-confirma-senha" name="confirma_senha" required placeholder=" ">
                            <label for="reg-confirma-senha">Confirmação de Senha*</label>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <input type="tel" id="reg-telefone" name="telefone" required placeholder=" " maxlength="19">
                            <label for="reg-telefone">Telefone*</label>
                        </div>
                        <div class="input-group">
                            <input type="date" id="reg-nascimento" name="nascimento" required placeholder=" ">
                            <label for="reg-nascimento">Nascimento*</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-solid-black mt-20">Criar Minha Conta</button>
                </form>
            </div>
        </div>
    </div>

    <!-- =========================================
                       HERO SECTION
    ========================================= -->
    <section class="hero">
        <!-- Wrapper do Vídeo de Fundo (Fixo para efeito Parallax) -->
        <div class="hero-video-wrapper">
            <video class="hero-video" autoplay loop muted playsinline disablePictureInPicture oncontextmenu="return false;">
                <source src="public/video/hero/herovideo.mp4" type="video/mp4">
            </video>
        </div>

        <!-- Gradiente que funde o vídeo com o fundo branco da página -->
        <div class="hero-overlay"></div>

        <button id="scroll-indicator" class="scroll-indicator" title="Rolar para baixo">
            <span class="material-symbols-outlined">stat_minus_1</span>
        </button>
    </section>

   <!-- =========================================
                    CONTEÚDO DA LOJA
    ========================================= -->
    <main class="main-content">
        
        <!-- =========================================
                      CATEGORIAS (ELE / ELA)
        ========================================= -->
        <section class="categories-showcase">
            
            <!-- Categoria Masculina -->
            <div class="category-card">
                <a href="categoria.php?id=1" class="category-link">
                    <div class="category-images">
                        <!-- Imagem 1: Pose 1 (Fica cinza pelo CSS) -->
                        <img src="public/img/categories/capa_ele_primary.jpg" class="img-primary" alt="Moda Masculina">
                        <!-- Imagem 2: Pose 2 (Colorida, aparece no hover) -->
                        <img src="public/img/categories/capa_ele_secondary.jpg" class="img-secondary" alt="Moda Masculina Detalhe">
                    </div>
                    
                    <div class="category-content">
                        <h3 class="category-title">Para Ele</h3>
                        <span class="category-action">
                            Descubra <div class="line"></div>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Categoria Feminina -->
            <div class="category-card">
                <a href="categoria.php?id=2" class="category-link">
                    <div class="category-images">
                        <!-- Imagem 1: Pose 1 (Fica cinza pelo CSS) -->
                        <img src="public/img/categories/capa_ela_primary.jpg" class="img-primary" alt="Moda Feminina">
                        <!-- Imagem 2: Pose 2 (Colorida, aparece no hover) -->
                        <img src="public/img/categories/capa_ela_secondary.jpg" class="img-secondary" alt="Moda Feminina Detalhe">
                    </div>
                    
                    <div class="category-content">
                        <h3 class="category-title">Para Ela</h3>
                        <span class="category-action">
                            Descubra <div class="line"></div>
                        </span>
                    </div>
                </a>
            </div>

        </section>

        <!-- =========================================
                      MAIS INSPIRAÇÕES
        ========================================= -->
        <section class="inspirations-section">
            <h2 class="section-title" data-aos="fade-right">Mais Inspirações</h2>
            
            <div class="inspirations-grid">
                
                <!-- Card 1 -->
                <div class="category-card inspiration-card" data-aos="fade-up" data-aos-delay="100">
                    <a href="#" class="category-link">
                        <div class="category-images">
                            <img src="public/img/inspirations/beyondbics1.png" alt="Beyond the ordinary" class="img-primary">
                            <img src="public/img/inspirations/beyondbics2.png" alt="Detalhe" class="img-secondary">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">Beyond the ordinary</h3>
                            <span class="category-action">Descubra <div class="line"></div></span>
                        </div>
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="category-card inspiration-card" data-aos="fade-up" data-aos-delay="200">
                    <a href="#" class="category-link">
                        <div class="category-images">
                            <img src="public/img/inspirations/causbics1.png" alt="Bics x Cauã Style" class="img-primary">
                            <img src="public/img/inspirations/causbics2.png" alt="Detalhe" class="img-secondary">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">Bics x Cauã's Style</h3>
                            <span class="category-action">Descubra <div class="line"></div></span>
                        </div>
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="category-card inspiration-card" data-aos="fade-up" data-aos-delay="300">
                    <a href="#" class="category-link">
                        <div class="category-images">
                            <img src="img/inspirations/performancebics1.png" alt="Performance" class="img-primary">
                            <img src="img/inspirations/performancebics2.png" alt="Detalhe" class="img-secondary">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">Performance</h3>
                            <span class="category-action">Descubra <div class="line"></div></span>
                        </div>
                    </a>
                </div>

                <!-- Card 4 -->
                <div class="category-card inspiration-card" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="category-link">
                        <div class="category-images">
                            <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?q=80&w=800&auto=format&fit=crop" alt="Acessórios" class="img-primary">
                            <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop" alt="Detalhe" class="img-secondary">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">Acessórios</h3>
                            <span class="category-action">Descubra <div class="line"></div></span>
                        </div>
                    </a>
                </div>

                <!-- Card 5 -->
                <div class="category-card inspiration-card" data-aos="fade-up" data-aos-delay="500">
                    <a href="#" class="category-link">
                        <div class="category-images">
                            <img src="public/img/inspirations/sexybics1.png" alt="City x Bics" class="img-primary">
                            <img src="public/img/inspirations/sexybics2.png" alt="Detalhe" class="img-secondary">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">Bics x Sex and the City</h3>
                            <span class="category-action">Descubra <div class="line"></div></span>
                        </div>
                    </a>
                </div>

            </div>
        </section>

        <!-- =========================================
                      MENSWEAR PRODUCTS
        ========================================= -->
        <section class="products-section">
            <h2 class="section-title" data-aos="fade-right">Menswear Collection</h2>
            
            <div class="products-grid">
                
                <!-- Product 1 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                            
                        </div>
                        <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop" alt="Jaqueta Utilitária" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1520975954732-57dd22299614?q=80&w=800&auto=format&fit=crop" alt="Detalhe Jaqueta" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Jaqueta Jeans Utilitária com Assinatura</h3>
                        <span class="product-price">R$ 35.000,00</span>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>
                            
                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="public/img/colections/2.jpg" alt="Calça Jeans" class="product-img-primary">
                        <img src="public/img/colections/2-1.jpg" alt="Detalhe Calça" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Calça Jeans de Pernas Amplas</h3>
                        <span class="product-price">R$ 19.150,00</span>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="public/img/colections/3.jpg" alt="Perfume" class="product-img-primary">
                        <img src="public/img/colections/3.jpg" alt="Perfume Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Perfume L'Immensité 100ml</h3>
                        <span class="product-price">R$ 7.260,00</span>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop" alt="Bolsa Cargo" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop" alt="Bolsa Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Neverfull Cargo Exclusive</h3>
                        <span class="product-price">R$ 46.900,00</span>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="public/img/colections/5.jpg" alt="Tênis" class="product-img-primary">
                        <img src="public/img/colections/5.jpg" alt="Tênis Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Tênis Trainer Monogram</h3>
                        <span class="product-price">R$ 28.500,00</span>
                    </div>
                </div>

            </div>

            <!-- Botão Centralizado -->
            <div class="products-action" data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="btn-explore">Explore a coleção</a>
            </div>
        </section>

        <!-- =========================================
                      WOMENSWEAR PRODUCTS
        ========================================= -->
        <section class="products-section">
            <h2 class="section-title" data-aos="fade-right">Womenswear Collection</h2>
            
            <div class="products-grid">
                
                <!-- Product 1 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                            
                        </div>
                        <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop" alt="Jaqueta Utilitária" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1520975954732-57dd22299614?q=80&w=800&auto=format&fit=crop" alt="Detalhe Jaqueta" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Jaqueta Jeans Utilitária com Assinatura</h3>
                        <span class="product-price">R$ 35.000,00</span>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>
                            
                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?q=80&w=800&auto=format&fit=crop" alt="Calça Jeans" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?q=80&w=800&auto=format&fit=crop" alt="Detalhe Calça" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Calça Jeans de Pernas Amplas</h3>
                        <span class="product-price">R$ 19.150,00</span>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1590845947698-8924d7409b56?q=80&w=800&auto=format&fit=crop" alt="Perfume" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800&auto=format&fit=crop" alt="Perfume Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Perfume L'Immensité 100ml</h3>
                        <span class="product-price">R$ 3.260,00</span>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop" alt="Bolsa Cargo" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop" alt="Bolsa Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Neverfull Cargo Exclusive</h3>
                        <span class="product-price">R$ 46.900,00</span>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>
                            
                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop" alt="Tênis" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=800&auto=format&fit=crop" alt="Tênis Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Tênis Trainer Monogram</h3>
                        <span class="product-price">R$ 28.500,00</span>
                    </div>
                </div>

            </div>

            <!-- Botão Centralizado -->
            <div class="products-action" data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="btn-explore">Explore a coleção</a>
            </div>
        </section>

        <!-- =========================================
                      EVENINGWEAR PRODUCTS
        ========================================= -->
        <section class="products-section">
            <h2 class="section-title" data-aos="fade-right">Eveningwear Collection</h2>
            
            <div class="products-grid">
                
                <!-- Product 1 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                            
                        </div>
                        <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop" alt="Jaqueta Utilitária" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1520975954732-57dd22299614?q=80&w=800&auto=format&fit=crop" alt="Detalhe Jaqueta" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Jaqueta Jeans Utilitária com Assinatura</h3>
                        <span class="product-price">R$ 35.000,00</span>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>
                            
                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?q=80&w=800&auto=format&fit=crop" alt="Calça Jeans" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?q=80&w=800&auto=format&fit=crop" alt="Detalhe Calça" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Calça Jeans de Pernas Amplas</h3>
                        <span class="product-price">R$ 19.150,00</span>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1590845947698-8924d7409b56?q=80&w=800&auto=format&fit=crop" alt="Perfume" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800&auto=format&fit=crop" alt="Perfume Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Perfume L'Immensité 100ml</h3>
                        <span class="product-price">R$ 3.260,00</span>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>

                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop" alt="Bolsa Cargo" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop" alt="Bolsa Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Neverfull Cargo Exclusive</h3>
                        <span class="product-price">R$ 46.900,00</span>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="product-media">
                        <div class="product-actions">
                            <button class="btn-cart" title="Add to Bag">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </button>
                            
                            <button class="btn-favorite" title="Add to Wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop" alt="Tênis" class="product-img-primary">
                        <img src="https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=800&auto=format&fit=crop" alt="Tênis Detalhe" class="product-img-secondary">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Tênis Trainer Monogram</h3>
                        <span class="product-price">R$ 28.500,00</span>
                    </div>
                </div>

            </div>

            <!-- Botão Centralizado -->
            <div class="products-action" data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="btn-explore">Explore a coleção</a>
            </div>
        </section>

        <!-- =========================================
                      BICS UNIVERSE (ACCORDION)
        ========================================= -->
        <section class="universe-section">
            
            <!-- Cabeçalho da Seção -->
            <div class="universe-header" data-aos="fade-up">
                <h2 class="universe-title">The BICS Society</h2>
                <!-- NOVO: Container para dividir os textos -->
                <div class="universe-desc-container">
                    <p class="universe-desc">
                        Descubra a essência da <strong>Beyond The Ordinary</strong>. 
                        Onde o luxo de alto padrão se funde perfeitamente com a energia autêntica do streetwear.
                        Uma nova era de artesanato visionário, redefinindo a elegância moderna para aqueles que ousam se destacar.
                    </p>
                </div>
            </div>

            <!-- Container Sanfona (Accordion) -->
            <div class="universe-accordion">
                
                <!-- Card 1 (Vídeo) -->
                <div class="accordion-card">
                    <div class="accordion-card-inner" data-aos="fade-up" data-aos-delay="100">
                        <div class="accordion-media">
                            <!-- Vídeo silenciado e em loop -->
                            <div class="accordion-media">
                            <!-- Vídeo silenciado e em loop -->
                            <img src="public/img/universe/ourfashion.png" class="accordion-img">
                        </div>
                        </div>
                        <div class="accordion-footer">
                            <h3>Our Fashion</h3>
                        </div>
                    </div>
                </div>

                <!-- Card 2 (Vídeo Centro) -->
                <div class="accordion-card">
                    <div class="accordion-card-inner" data-aos="fade-up" data-aos-delay="100">
                        <div class="accordion-media">
                            <!-- Vídeo silenciado e em loop -->
                            <video class="accordion-video" autoplay loop muted playsinline disablePictureInPicture>
                                <source src="public/video/universe/runwaywalk.mp4" type="video/mp4">
                            </video>
                        </div>
                        <div class="accordion-footer">
                            <h3>Runway Shows</h3>
                        </div>
                    </div>
                </div>

                <!-- Card 3 (Imagem Direita) -->
                <div class="accordion-card">
                    <div class="accordion-card-inner" data-aos="fade-up" data-aos-delay="100">
                        <div class="accordion-media">
                            <!-- Vídeo silenciado e em loop -->
                            <img src="public/img/universe/bicsmagazine.png" class="accordion-img">
                        </div>
                        <div class="accordion-footer">
                            <h3>BICS Magazine</h3>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- =========================================
                      FOOTER
        ========================================= -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-col">
                <h4>Ajuda</h4>
                <ul>
                    <li><a href="#">Fale Conosco</a></li>
                    <li><a href="#">Perguntas Frequentes</a></li>
                    <li><a href="#">Cuidados com o Produto</a></li>
                    <li><a href="#">Lojas</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Serviços</h4>
                <ul>
                    <li><a href="#">Reparos</a></li>
                    <li><a href="#">Personalização</a></li>
                    <li><a href="#">A Arte de Presentear</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Sobre a BICS</h4>
                <ul>
                    <li><a href="#">Desfiles</a></li>
                    <li><a href="#">Arte e Cultura</a></li>
                    <li><a href="#">Sustentabilidade</a></li>
                    <li><a href="#">Carreiras</a></li>
                </ul>
            </div>
            
            <div class="footer-col newsletter-col">
                <h4>Newsletter</h4>
                <p>Inscreva-se para receber as últimas notícias da Maison, incluindo pré-lançamentos exclusivos.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault();">
                    <input type="email" placeholder="Seu endereço de e-mail" required>
                    <button type="submit"><span class="material-symbols-outlined">arrow_forward</span></button>
                </form>
            </div>
        </div>

        <div class="footer-middle">
            <div class="payment-methods">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Visa_Inc._logo_%282021%E2%80%93present%29.svg" alt="Visa" class="pay-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard_2019_logo.svg" alt="Mastercard" class="pay-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="pay-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/Logotipo_da_Elo.svg" alt="Elo" class="pay-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Pix_%28Brazil%29_logo.svg" alt="Pix" class="pay-icon">
            </div>
            <div class="footer-cnpj">
                <p>CNPJ: 00.000.000/0001-00 | BICS Brasil Comércio de Luxo Ltda.</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-location">
                <span class="material-symbols-outlined">language</span> Brasil
            </div>
            
            <!-- NOVO: Container central com o nome da marca grande e o copyright embaixo -->
            <div class="footer-brand-container">
                <h2 class="footer-brand">BICS</h2>
                <div class="footer-copyright">
                    &copy; 2003 - <span id="current-year"></span> BICS. Todos os direitos reservados.
                </div>
            </div>
            
            <div class="footer-agency">
                <a href="https://www.cosmusagency.com/" class="cosmus-badge" title="Desenvolvido por Cosmus Agency">
                    <!-- Substitua pelo caminho correto do seu GIF se precisar -->
                    <img src="public/img/Cosmus-Buttom-Transparent.gif" alt="Cosmus Agency Logo" class="agency-logo">
                </a>
            </div>
        </div>
    </footer>

    <!-- =========================================
                      BOTÃO FLUTUANTE (TOP)
    ========================================= -->
    <button id="backToTop" class="btn-top" title="Voltar ao topo">
        <span class="material-symbols-outlined">keyboard_arrow_up</span>
    </button>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- O Vite injeta os scripts como module nativamente -->
    <script type="module" src="src/js/main.js"></script>
    
    
</body>
</html>