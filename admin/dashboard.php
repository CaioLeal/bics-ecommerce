<?php
// Inicia a sessão
session_start();

// Verifica segurança
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php?erro=sessao");
    exit();
}

$nome_admin = $_SESSION['admin_nome'];

require_once 'conexao.php';
$sql = "SELECT id, nome, email, senha FROM admins ORDER BY id DESC";
$resultado = $conn->query($sql);

$sql_clientes = "SELECT id, nome, sobrenome, email, metodo_login, data_cadastro FROM clientes ORDER BY id DESC";
$resultado_clientes = $conn->query($sql_clientes);

$sql_produtos = "SELECT p.id, p.ref, p.nome, p.preco_base, p.galeria_fotos, c.nome AS categoria_nome 
                 FROM produtos p 
                 LEFT JOIN categorias c ON p.categoria_id = c.id 
                 ORDER BY p.id DESC";
$resultado_produtos = $conn->query($sql_produtos);

$sql_categorias = "SELECT id, nome FROM categorias ORDER BY nome ASC";
$resultado_categorias = $conn->query($sql_categorias);

// NOVO: Busca os cupons de desconto
$sql_cupons = "SELECT * FROM cupons ORDER BY id DESC";
$resultado_cupons = $conn->query($sql_cupons);

// NOVO: Busca o histórico de Vendas
$sql_vendas = "SELECT * FROM vendas ORDER BY data_venda DESC";
$resultado_vendas = $conn->query($sql_vendas);

$aba_ativa = 'dashboard'; 
if (isset($_GET['sucesso'])) {
    if ($_GET['sucesso'] == 'cadastro') $aba_ativa = 'usuarios';
    elseif ($_GET['sucesso'] == 'produto') $aba_ativa = 'produtos'; 
    elseif ($_GET['sucesso'] == 'cupom') $aba_ativa = 'cupons'; // NOVO!
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS Admin | Dashboard</title>
    <link rel="stylesheet" href="src/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
</head>
<body class="admin-body">

    <div class="admin-layout">
        
        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-header">
                <a href="#" class="admin-logo">BICS <span>ADMIN</span></a>
                <button id="close-admin-menu" class="mobile-close-btn"><span class="material-symbols-outlined">close</span></button>
            </div>

            <nav class="sidebar-nav">
                <a href="#" onclick="mudarAba('dashboard')" class="nav-link active" id="menu-dashboard">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="#" onclick="mudarAba('usuarios')" class="nav-link" id="menu-usuarios">
                    <span class="material-symbols-outlined">group</span> Usuários
                </a>
                <!-- NOVA ABA PRODUTOS -->
                <a href="#" onclick="mudarAba('produtos')" class="nav-link" id="menu-produtos">
                    <span class="material-symbols-outlined">inventory_2</span> Produtos / Categorias
                </a>
                <!-- NOVA ABA CUPONS -->
                <a href="#" onclick="mudarAba('cupons')" class="nav-link" id="menu-cupons">
                    <span class="material-symbols-outlined">local_offer</span> Cupons
                </a>
                <a href="#" onclick="mudarAba('vendas')" class="nav-link" id="menu-vendas">
                    <span class="material-symbols-outlined">shopping_bag</span> Vendas
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="nav-link logout-link"><span class="material-symbols-outlined">logout</span> Sair</a>
            </div>
        </aside>

        <div id="admin-overlay" class="admin-overlay"></div>

        <main class="admin-main">
            
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button id="open-admin-menu" class="mobile-open-btn"><span class="material-symbols-outlined">menu</span></button>
                    <div class="search-box">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" id="search-users" placeholder="Pesquisar..." onkeyup="filtrarUsuarios()">
                    </div>
                </div>
                <div class="topbar-right">
                    <button class="icon-btn"><span class="material-symbols-outlined">notifications</span></button>
                    <div class="admin-profile"><div class="profile-avatar"><?php echo substr(htmlspecialchars($nome_admin), 0, 1); ?></div></div>
                </div>
            </header>

            <div class="admin-content">
                
                <!-- =========================================
                              SEÇÃO 1: DASHBOARD
                ========================================= -->
                <div id="secao-dashboard" class="conteudo-aba">
                    <div class="welcome-banner">
                        <div class="banner-overlay"></div>
                        <div class="banner-text">
                            <h2>Welcome back, <?php echo htmlspecialchars($nome_admin); ?></h2>
                            <p>Aqui está o resumo do desempenho da BICS hoje.</p>
                        </div>
                    </div>
                    <!-- (Omiti os stats e o upload da tela inicial aqui visualmente para não alongar, MAS MANTENHA-OS NO SEU CÓDIGO FINAL COMO ESTAVAM ANTES) -->
                    <!-- NOTA: Para o código rodar perfeitamente, cole o resto da aba dashboard que você já tinha aqui -->
                     <div class="dashboard-stats-grid" style="margin-bottom: 50px;">
                        <div class="stat-card">
                            <div class="stat-icon"><span class="material-symbols-outlined">group</span></div>
                            <div class="stat-info"><h3>Total Usuários</h3><p>1.240</p></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><span class="material-symbols-outlined">inventory_2</span></div>
                            <div class="stat-info"><h3>Produtos Ativos</h3><p>86</p></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><span class="material-symbols-outlined">shopping_bag</span></div>
                            <div class="stat-info"><h3>Vendas Hoje</h3><p>12</p></div>
                        </div>
                    </div>

                    <!-- GERENCIAMENTO DA TELA INICIAL -->
                    <div class="section-header" style="margin-bottom: 30px;">
                        <h2 style="font-size: 1.5rem; font-weight: 600;">Gerenciamento da Tela Inicial</h2>
                        <p style="color: #888; margin-top: 5px;">Altere as mídias da página principal da loja pública.</p>
                    </div>

                    <div class="dashboard-stats-grid">
                        
                        <!-- CARD 1: VÍDEO HERO -->
                        <div class="stat-card" style="flex-direction: column; align-items: flex-start; gap: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="stat-icon" style="width: 40px; height: 40px;"><span class="material-symbols-outlined" style="font-size: 20px;">movie</span></div>
                                <h3 style="font-size: 1.1rem; font-weight: 600;">Vídeo Principal (Hero)</h3>
                            </div>
                            <form action="upload_hero.php" method="POST" enctype="multipart/form-data" style="width: 100%; margin-top: 10px; display: flex; flex-direction: column; gap: 10px;">
                                <input type="file" name="hero_video" id="hero-upload" accept="video/mp4" style="display: none;" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                <label for="hero-upload" class="admin-btn-outline" style="display: block; text-align: center; cursor: pointer;">1. Escolher Vídeo (.mp4)</label>
                                <span id="file-name" style="font-size: 12px; color: #888; text-align: center;">Nenhum arquivo selecionado</span>
                                <button type="submit" style="width: 100%; padding: 12px; background-color: var(--color-black); color: var(--color-primary); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none;">2. Salvar Alteração</button>
                            </form>
                        </div>

                        <!-- CARD 2: CATEGORIAS -->
                        <div class="stat-card" style="flex-direction: column; align-items: flex-start; gap: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="stat-icon" style="width: 40px; height: 40px;"><span class="material-symbols-outlined" style="font-size: 20px;">style</span></div>
                                <h3 style="font-size: 1.1rem; font-weight: 600;">Categorias (Ele / Ela)</h3>
                            </div>
                            <form action="upload_categoria.php" method="POST" enctype="multipart/form-data" style="width: 100%; margin-top: 5px; display: flex; flex-direction: column; gap: 12px;">
                                <select name="tipo_categoria" required style="padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 13px; outline: none; background: transparent; cursor: pointer;">
                                    <option value="" disabled selected>Selecione a coleção...</option>
                                    <option value="ele">Coleção: Para Ele</option>
                                    <option value="ela">Coleção: Para Ela</option>
                                </select>
                                <div style="width: 100%;">
                                    <label style="font-size: 12px; font-weight: 600; color: #555;">Imagem Principal (Padrão):</label>
                                    <input type="file" name="imagem_primary" accept="image/jpeg, image/png" required style="font-size: 12px; width: 100%;">
                                </div>
                                <div style="width: 100%;">
                                    <label style="font-size: 12px; font-weight: 600; color: #555;">Imagem Secundária (Hover):</label>
                                    <input type="file" name="imagem_secondary" accept="image/jpeg, image/png" required style="font-size: 12px; width: 100%;">
                                </div>
                                <button type="submit" style="width: 100%; padding: 12px; background-color: var(--color-black); color: var(--color-primary); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none;">Salvar Alterações</button>
                            </form>
                        </div>

                        <!-- CARD 3: UNIVERSO BICS -->
                        <div class="stat-card" style="flex-direction: column; align-items: flex-start; gap: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="stat-icon" style="width: 40px; height: 40px;"><span class="material-symbols-outlined" style="font-size: 20px;">view_carousel</span></div>
                                <h3 style="font-size: 1.1rem; font-weight: 600;">Universo BICS</h3>
                            </div>
                            <form action="upload_universo.php" method="POST" enctype="multipart/form-data" style="width: 100%; margin-top: 5px; display: flex; flex-direction: column; gap: 12px;">
                                <select name="seccao_universo" required style="padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 13px; outline: none; background: transparent; cursor: pointer;">
                                    <option value="" disabled selected>Selecione a secção...</option>
                                    <option value="fashion">Our Fashion (.jpg/.png)</option>
                                    <option value="runway">Runway Shows (.mp4)</option>
                                    <option value="magazine">BICS Magazine (.jpg/.png)</option>
                                </select>
                                <div style="width: 100%;">
                                    <input type="file" name="midia_universo" required style="font-size: 12px; width: 100%;">
                                </div>
                                <button type="submit" style="width: 100%; padding: 12px; background-color: var(--color-black); color: var(--color-primary); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none;">Editar Universo</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- =========================================
                              SEÇÃO 2: USUÁRIOS
                ========================================= -->
                <div id="secao-usuarios" class="conteudo-aba" style="display: none;">
                    
                    <!-- PARTE 1: ADMINISTRADORES -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 1.8rem; font-weight: 600;">Administradores</h2>
                            <p style="color: #888; margin-top: 5px;">Equipe com acesso ao painel de controle BICS.</p>
                        </div>
                        <button onclick="abrirModalAdmin()" class="btn-solid-black" style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">add</span> Novo Admin
                        </button>
                    </div>

                    <?php if(isset($_GET['sucesso']) && $_GET['sucesso'] == 'cadastro'): ?>
                        <p style="color: green; margin-bottom: 15px; font-weight: bold;">Novo administrador cadastrado com sucesso!</p>
                    <?php endif; ?>

                    <div class="admin-table-container" style="margin-bottom: 50px;">
                        <table class="admin-table" id="tabela-admins">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Senha Criptografada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($resultado->num_rows > 0) {
                                    while($row = $resultado->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='color: #888; font-size: 12px;'>#" . str_pad($row["id"], 4, "0", STR_PAD_LEFT) . "</td>";
                                        echo "<td class='user-nome'><strong>" . htmlspecialchars($row["nome"]) . "</strong></td>";
                                        echo "<td class='user-email'>" . htmlspecialchars($row["email"]) . "</td>";
                                        echo "<td>
                                                <div style='display: flex; align-items: center; gap: 10px;'>
                                                    <span id='senha-text-".$row['id']."' style='color: #888; font-family: monospace; letter-spacing: 2px;'>••••••••••••</span>
                                                    <button onclick='toggleSenha(".$row['id'].", \"".$row['senha']."\")' class='btn-icon' title='Ver Hash MD5'>
                                                        <span class='material-symbols-outlined' id='senha-icon-".$row['id']."'>visibility</span>
                                                    </button>
                                                </div>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' style='text-align: center; color: #888;'>Nenhum administrador encontrado.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PARTE 2: CLIENTES DA LOJA -->
                    <div style="margin-bottom: 20px;">
                        <h2 style="font-size: 1.8rem; font-weight: 600;">Clientes da Loja</h2>
                        <p style="color: #888; margin-top: 5px;">Usuários registrados através do site oficial (Apple, Google ou E-mail).</p>
                    </div>

                    <div class="admin-table-container">
                        <table class="admin-table" id="tabela-clientes">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Cliente</th>
                                    <th>E-mail</th>
                                    <th>Método de Login</th>
                                    <th>Data de Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($resultado_clientes->num_rows > 0) {
                                    while($row_cli = $resultado_clientes->fetch_assoc()) {
                                        $data_formatada = date('d/m/Y H:i', strtotime($row_cli['data_cadastro']));
                                        $nome_completo = htmlspecialchars($row_cli["nome"] . ' ' . $row_cli["sobrenome"]);
                                        
                                        echo "<tr>";
                                        echo "<td style='color: #888; font-size: 12px;'>#" . str_pad($row_cli["id"], 4, "0", STR_PAD_LEFT) . "</td>";
                                        echo "<td><strong>" . $nome_completo . "</strong></td>";
                                        echo "<td class='user-email'>" . htmlspecialchars($row_cli["email"]) . "</td>";
                                        
                                        $metodo = $row_cli["metodo_login"];
                                        $cor = $metodo == 'email' ? 'var(--color-black)' : '#888';
                                        echo "<td><span style='background-color: {$cor}; color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 11px; text-transform: uppercase;'>{$metodo}</span></td>";
                                        
                                        echo "<td style='color: #888; font-size: 13px;'>" . $data_formatada . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' style='text-align: center; color: #888; padding: 30px;'>Nenhum cliente cadastrado ainda.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- =========================================
                     SEÇÃO 3: PRODUTOS E CATEGORIAS (NOVA)
                ========================================= -->
                <div id="secao-produtos" class="conteudo-aba" style="display: none;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h2 style="font-size: 1.8rem; font-weight: 600;">Catálogo e Coleções</h2>
                            <p style="color: #888; margin-top: 5px;">Gerencie as categorias e os produtos da loja.</p>
                        </div>
                        <div class="produtos-header-actions">
                            <button onclick="abrirModalCategoria()" class="btn-outline-black"><span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">category</span> Nova Categoria</button>
                            <button onclick="abrirModalProduto()" class="btn-solid-black"><span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">add</span> Novo Produto</button>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 2.5fr; gap: 30px;">
                        
                        <!-- LADO ESQUERDO: ÁRVORE DE CATEGORIAS -->
                        <div>
                            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 10px;">Categorias</h3>
                            <p style="font-size: 12px; color: #888;">Organização do menu da loja.</p>
                            
                            <ul class="category-list">
                                <?php
                                // Busca as categorias no banco de dados
                                $sql_lista_cat = "SELECT * FROM categorias ORDER BY nome ASC";
                                $res_lista_cat = $conn->query($sql_lista_cat);
                                
                                if ($res_lista_cat && $res_lista_cat->num_rows > 0) {
                                    while($cat = $res_lista_cat->fetch_assoc()) {
                                        echo '<li class="category-item" style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">';
                                        echo '  <div class="category-title" style="font-size: 14px; font-weight: 600; color: var(--color-black);"><span class="material-symbols-outlined" style="color: #888;">folder</span> ' . htmlspecialchars($cat['nome']) . '</div>';
                                        echo '  <div style="display: flex; gap: 5px;">';
                                        echo '      <a href="../categoria.php?id=' . $cat['id'] . '" target="_blank" class="btn-icon" title="Ver na Loja"><span class="material-symbols-outlined" style="font-size: 18px;">visibility</span></a>';
                                        echo '      <a href="excluir_categoria.php?id=' . $cat['id'] . '" class="btn-icon delete" title="Excluir Categoria" onclick="return confirm(\'Tem a certeza que deseja apagar esta categoria e remover todos os produtos associados a ela da loja?\');"><span class="material-symbols-outlined" style="font-size: 18px;">delete</span></a>';
                                        echo '  </div>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo '<li style="padding: 20px; text-align: center; color: #888; font-size: 13px;">Nenhuma categoria criada.</li>';
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- LADO DIREITO: TABELA DE PRODUTOS -->
                        <div>
                            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 10px;">Produtos Ativos</h3>
                            <div class="admin-table-container">
                                <table class="admin-table" id="tabela-produtos">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Ref</th>
                                            <th>Nome do Produto</th>
                                            <th>Categoria</th>
                                            <th>Preço</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if ($resultado_produtos && $resultado_produtos->num_rows > 0) {
                                            while($row_prod = $resultado_produtos->fetch_assoc()) {
                                                // Como as fotos estão numa "lista" (JSON) no banco, nós decodificamos
                                                $fotos = json_decode($row_prod['galeria_fotos'], true);
                                                // Pegamos a primeira foto para ser a capa da tabela
                                                $foto_capa = (!empty($fotos) && isset($fotos[0])) ? "../public/img/products/" . $fotos[0] : "https://via.placeholder.com/40";
                                                
                                                $cat_nome = $row_prod['categoria_nome'] ? htmlspecialchars($row_prod['categoria_nome']) : 'Sem Categoria';
                                                
                                                // Formata o preço para o padrão Brasileiro
                                                $preco_formatado = "R$ " . number_format($row_prod['preco_base'], 2, ',', '.');
                                                
                                                echo "<tr>";
                                                echo "<td><img src='{$foto_capa}' style='width: 40px; height: 40px; object-fit: cover; border-radius: 8px;'></td>";
                                                echo "<td style='color: #888; font-size: 12px;'>" . htmlspecialchars($row_prod['ref']) . "</td>";
                                                echo "<td><strong>" . htmlspecialchars($row_prod['nome']) . "</strong></td>";
                                                echo "<td>" . $cat_nome . "</td>";
                                                echo "<td>" . $preco_formatado . "</td>";
                                                echo "<td class='action-buttons'>
                                                        <a href='../product.php?id={$row_prod['id']}' target='_blank' class='btn-icon' title='Ver na Loja'><span class='material-symbols-outlined'>visibility</span></a>
                                                        <button class='btn-icon' title='Editar (Em breve)'><span class='material-symbols-outlined'>edit</span></button>
                                                        <a href='excluir_produto.php?id={$row_prod['id']}' class='btn-icon delete' title='Excluir' onclick=\"return confirm('Apagar este produto permanentemente?');\"><span class='material-symbols-outlined'>delete</span></a>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' style='text-align: center; color: #888; padding: 30px;'>Nenhum produto cadastrado ainda no catálogo.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- =========================================
                              SEÇÃO 4: CUPONS 
                ========================================= -->
                <div id="secao-cupons" class="conteudo-aba" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
                        <div>
                            <h2 style="font-size: 1.8rem; font-weight: 600;">Cupons de Desconto</h2>
                            <p style="color: #888; margin-top: 5px;">Crie e gerencie campanhas promocionais e regras de uso.</p>
                        </div>
                        <button onclick="abrirModal('modal-novo-cupom')" class="btn-solid-black" style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">add</span> Novo Cupom
                        </button>
                    </div>

                    <div class="admin-table-container">
                        <table class="admin-table" id="tabela-cupons">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Desconto</th>
                                    <th>Validade</th>
                                    <th>Usos (Total)</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($resultado_cupons && $resultado_cupons->num_rows > 0) {
                                    while($row_cupom = $resultado_cupons->fetch_assoc()) {
                                        $desconto = $row_cupom['tipo_desconto'] == 'porcentagem' ? $row_cupom['valor_desconto'] . '%' : 'R$ ' . number_format($row_cupom['valor_desconto'], 2, ',', '.');
                                        
                                        $validade = !empty($row_cupom['data_validade']) ? date('d/m/Y', strtotime($row_cupom['data_validade'])) : 'Sem limite';
                                        
                                        $limite_total = !empty($row_cupom['limite_uso_total']) ? $row_cupom['limite_uso_total'] : '∞';
                                        $usos = $row_cupom['usos_atuais'] . ' / ' . $limite_total;
                                        
                                        $status = $row_cupom['status'] ? '<span style="color: #00a650; font-weight: 600;">Ativo</span>' : '<span style="color: #d32f2f; font-weight: 600;">Inativo</span>';
                                        
                                        echo "<tr>";
                                        echo "<td><span style='background: #f0f0f0; padding: 5px 10px; border-radius: 4px; font-family: monospace; font-weight: bold; font-size: 14px;'>" . htmlspecialchars($row_cupom['codigo']) . "</span></td>";
                                        echo "<td><strong>" . $desconto . "</strong></td>";
                                        echo "<td style='color: #888; font-size: 13px;'>" . $validade . "</td>";
                                        echo "<td style='color: #888; font-size: 13px;'>" . $usos . "</td>";
                                        echo "<td>" . $status . "</td>";
                                        echo "<td class='action-buttons'>
                                                <a href='excluir_cupom.php?id={$row_cupom['id']}' class='btn-icon delete' title='Excluir' onclick=\"return confirm('Apagar este cupom?');\"><span class='material-symbols-outlined'>delete</span></a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' style='text-align: center; color: #888; padding: 30px;'>Nenhum cupom ativo no momento.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- =========================================
                              SEÇÃO 5: VENDAS (NOVA)
                ========================================= -->
                <div id="secao-vendas" class="conteudo-aba" style="display: none;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h2 style="font-size: 1.8rem; font-weight: 600;">Histórico de Vendas</h2>
                            <p style="color: #888; margin-top: 5px;">Acompanhe os pedidos, pagamentos e status de envio.</p>
                        </div>
                        <button class="btn-outline-black" style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">download</span> Exportar Relatório
                        </button>
                    </div>

                    <!-- Usamos a mesma class que garante o scroll horizontal em tablets/mobile -->
                    <div class="admin-table-container" style="overflow-x: auto;">
                        <table class="admin-table" id="tabela-vendas" style="min-width: 900px;">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Data</th>
                                    <th>Itens</th>
                                    <th>Total (R$)</th>
                                    <th>Pagamento</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($resultado_vendas && $resultado_vendas->num_rows > 0) {
                                    while($venda = $resultado_vendas->fetch_assoc()) {
                                        // Formatações
                                        $data_venda = date('d/m/Y H:i', strtotime($venda['data_venda']));
                                        $valor = number_format($venda['valor_total'], 2, ',', '.');
                                        
                                        // Inteligência de Cores para o Status
                                        $status_color = '#888'; // Cinza para Pendente
                                        if($venda['status'] == 'Aprovado' || $venda['status'] == 'Enviado') $status_color = '#00a650'; // Verde
                                        if($venda['status'] == 'Cancelado') $status_color = '#d32f2f'; // Vermelho

                                        // Inteligência de Ícones para o Pagamento
                                        $icon_pag = 'credit_card'; // Padrão Cartão
                                        if($venda['metodo_pagamento'] == 'PIX') $icon_pag = 'qr_code';
                                        if($venda['metodo_pagamento'] == 'Boleto') $icon_pag = 'receipt_long';

                                        echo "<tr>";
                                        
                                        // ID do Pedido
                                        echo "<td><span style='font-family: monospace; font-weight: 800; font-size: 14px;'>#" . str_pad($venda['id'], 6, "0", STR_PAD_LEFT) . "</span></td>";
                                        
                                        // Cliente + Email
                                        echo "<td><strong>" . htmlspecialchars($venda['cliente_nome']) . "</strong><br><span style='font-size: 12px; color: #888;'>" . htmlspecialchars($venda['cliente_email']) . "</span></td>";
                                        
                                        // Data
                                        echo "<td style='color: #888; font-size: 13px;'>" . $data_venda . "</td>";
                                        
                                        // Quantidade
                                        echo "<td style='text-align: center; font-weight: 500;'>" . $venda['quantidade_produtos'] . "</td>";
                                        
                                        // Valor
                                        echo "<td><strong>R$ " . $valor . "</strong></td>";
                                        
                                        // Pagamento com Ícone Dinâmico
                                        echo "<td><div style='display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500;'><span class='material-symbols-outlined' style='font-size: 18px; color: #555;'>" . $icon_pag . "</span> " . $venda['metodo_pagamento'] . "</div></td>";
                                        
                                        // Status (Badge Colorida)
                                        echo "<td><span style='background-color: " . $status_color . "15; color: " . $status_color . "; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid ".$status_color."30;'>" . $venda['status'] . "</span></td>";
                                        
                                        // Ações (Ver Detalhes do Pedido)
                                        echo "<td class='action-buttons'>
                                                <button class='btn-icon' title='Ver Detalhes'><span class='material-symbols-outlined'>visibility</span></button>
                                              </td>";
                                              
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' style='text-align: center; color: #888; padding: 40px;'>Nenhuma venda registrada até o momento.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- =========================================
            MODAL 1: NOVO ADMIN (Existente)
    ========================================= -->
    <div class="modal-admin-overlay" id="modal-novo-admin">
        <div class="modal-admin-card">
            <!-- (Mantenha o formulário de cadastro de Admin que já existia) -->
            <button class="modal-close-btn" onclick="fecharModal('modal-novo-admin')"><span class="material-symbols-outlined">close</span></button>
            <h3 class="modal-title">Cadastrar Novo Admin</h3>
            <p>Formulário mantido...</p> 
        </div>
    </div>

    <!-- =========================================
            MODAL 2: NOVA CATEGORIA
    ========================================= -->
    <div class="modal-admin-overlay" id="modal-nova-categoria">
        <div class="modal-admin-card">
            <button class="modal-close-btn" onclick="fecharModal('modal-nova-categoria')"><span class="material-symbols-outlined">close</span></button>
            <h3 class="modal-title">Criar Categoria</h3>
            <form action="cadastrar_categoria.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Nome da Categoria</label>
                    <input type="text" name="nome_categoria" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; outline: none;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Imagem de Capa (Banner da página)</label>
                    <input type="file" name="imagem_categoria" accept="image/*" required style="font-size: 12px; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Categoria Pai (Para subcategorias)</label>
                    <select name="categoria_pai" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; outline: none; background: transparent;">
                        <option value="0">Nenhuma (Categoria Principal)</option>
                        <?php
                        // Reinicia o ponteiro do banco e preenche as opções reais
                        $resultado_categorias->data_seek(0);
                        while($cat = $resultado_categorias->fetch_assoc()) {
                            echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn-solid-black mt-20">Salvar Categoria</button>
            </form>
        </div>
    </div>

    <!-- =========================================
            MODAL 3: NOVO PRODUTO (GIGANTE)
    ========================================= -->
    <div class="modal-admin-overlay" id="modal-novo-produto">
        <div class="modal-admin-card large">
            <button class="modal-close-btn" onclick="fecharModal('modal-novo-produto')"><span class="material-symbols-outlined">close</span></button>
            <h3 class="modal-title">Cadastrar Novo Produto</h3>
            
            <form action="upload_produto.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Informações Básicas -->
                <div class="form-grid-2">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Nome do Produto*</label>
                        <input type="text" name="nome_produto" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Código Ref.*</label>
                        <input type="text" name="ref_produto" required placeholder="Ex: 1AGIWK" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                </div>

                <!-- Preços e Categoria -->
                <div class="form-grid-2">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Categoria*</label>
                        <select name="categoria_produto" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; background: transparent;">
                            <option value="" disabled selected>Selecione...</option>
                            <?php
                            // Preenche o dropdown com as categorias reais do banco de dados
                            if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
                                while($cat = $resultado_categorias->fetch_assoc()) {
                                    echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['nome']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 13px; font-weight: 600; color: #555;">Preço Base*</label>
                            <input type="number" step="0.01" name="preco_normal" required placeholder="R$" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 13px; font-weight: 600; color: #555;">Preço com Desconto</label>
                            <input type="number" step="0.01" name="preco_desconto" placeholder="Opcional" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                        </div>
                    </div>
                </div>

                <!-- Variações -->
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Cores Disponíveis (Nomes ou Hexadecimais separados por vírgula)</label>
                    <input type="text" name="cores_produto" placeholder="Ex: Azul Claro, #1a1a1a, Branco" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                </div>

                <!-- Mídias -->
                <div class="form-grid-2" style="background: #f9f9f9; padding: 15px; border-radius: 12px; border: 1px dashed #ccc;">
                    <div style="display: flex; flex-direction: column; gap: 5px; grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Galeria de Fotos* (Pode selecionar várias imagens ao mesmo tempo)</label>
                        <!-- O atributo "multiple" permite escolher vários ficheiros, e o "[]" no name cria uma lista para o PHP -->
                        <input type="file" name="fotos[]" accept="image/*" multiple required style="font-size: 12px; padding: 10px; background: #fff; border: 1px solid #ddd; border-radius: 8px;">
                        <p style="font-size: 11px; color: #888;">Segure a tecla CTRL (ou CMD) para selecionar várias fotos na pasta.</p>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 5px; grid-column: span 2; margin-top: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Galeria de Vídeos (Opcional, pode selecionar vários .mp4)</label>
                        <input type="file" name="videos[]" accept="video/mp4" multiple style="font-size: 12px; padding: 10px; background: #fff; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <!-- Descrição -->
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Descrição do Produto</label>
                    <textarea name="descricao" rows="4" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Tamanhos (Separados por vírgula, Ex: 36, 38, 40)</label>
                    <input type="text" name="tamanhos" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Cuidados (Dicas de lavagem)</label>
                    <textarea name="cuidados" rows="2" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn-solid-black mt-20">Criar Produto no Catálogo</button>
            </form>
        </div>
    </div>

    <!-- =========================================
            MODAL 4: NOVO CUPOM
    ========================================= -->
    <div class="modal-admin-overlay" id="modal-novo-cupom">
        <div class="modal-admin-card large">
            <button class="modal-close-btn" onclick="fecharModal('modal-novo-cupom')"><span class="material-symbols-outlined">close</span></button>
            <h3 class="modal-title">Criar Cupom de Desconto</h3>
            
            <form action="cadastrar_cupom.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="form-grid-2">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Código do Cupom* (Ex: BICS20)</label>
                        <input type="text" name="codigo" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; text-transform: uppercase;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Data de Validade (Opcional)</label>
                        <input type="date" name="data_validade" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Tipo de Desconto*</label>
                        <select name="tipo_desconto" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; background: transparent;">
                            <option value="porcentagem">Porcentagem (%)</option>
                            <option value="fixo">Valor Fixo (R$)</option>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Valor do Desconto*</label>
                        <input type="number" step="0.01" name="valor_desconto" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                </div>

                <div class="form-grid-2" style="background: #f9f9f9; padding: 15px; border-radius: 12px; border: 1px dashed #ccc;">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Limite Total de Usos (Ex: 200)</label>
                        <input type="number" name="limite_total" placeholder="Deixe em branco para ilimitado" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Usos por Cliente (Ex: 1)</label>
                        <input type="number" name="limite_cliente" value="1" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Restringir à Categoria (Opcional)</label>
                        <select name="categoria_id" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; background: transparent;">
                            <option value="">Todas as Categorias</option>
                            <?php
                            $resultado_categorias->data_seek(0); // Reinicia o ponteiro do banco
                            while($cat = $resultado_categorias->fetch_assoc()) {
                                echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['nome']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 13px; font-weight: 600; color: #555;">Apenas para Novos Usuários?</label>
                        <select name="apenas_novos" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; background: transparent;">
                            <option value="0">Não (Todos os Clientes)</option>
                            <option value="1">Sim (Apenas Primeira Compra)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-solid-black mt-20">Criar Cupom de Desconto</button>
            </form>
        </div>
    </div>
    
    <script>
        // Menu Lateral
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        function toggleMenu() { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); }
        document.getElementById('open-admin-menu').addEventListener('click', toggleMenu);
        document.getElementById('close-admin-menu').addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        // Sistema SPA
        function mudarAba(abaId) {
            document.querySelectorAll('.conteudo-aba').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.sidebar-nav .nav-link').forEach(el => el.classList.remove('active'));
            document.getElementById('secao-' + abaId).style.display = 'block';
            document.getElementById('menu-' + abaId).classList.add('active');
            if (window.innerWidth <= 1024) toggleMenu();
        }

        window.onload = function() { mudarAba('<?php echo $aba_ativa; ?>'); };

        // Modais Universais
        function abrirModal(id) { document.getElementById(id).classList.add('active'); }
        function fecharModal(id) { document.getElementById(id).classList.remove('active'); }

        // Atalhos antigos (para não quebrar o botão de Novo Admin)
        function abrirModalAdmin() { abrirModal('modal-novo-admin'); }
        function abrirModalCategoria() { abrirModal('modal-nova-categoria'); }
        function abrirModalProduto() { abrirModal('modal-novo-produto'); }

        // Abrir/Fechar Subcategorias (Accordion Simples)
        function toggleSubcategory(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('active');
        }

        // Esconder/Mostrar Senha e Busca
        // (O script do olhinho e de filtro continuam iguais, apenas omiti aqui para poupar espaço)
    </script>
</body>
</html>