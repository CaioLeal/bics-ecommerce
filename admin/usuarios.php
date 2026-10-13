<?php
session_start();

// Verifica segurança
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php?erro=sessao");
    exit();
}

// Conecta ao banco para buscar os usuários
require_once 'conexao.php';

// Busca todos os administradores cadastrados
$sql = "SELECT id, nome, email FROM admins ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS Admin | Usuários</title>
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
                <a href="dashboard.php" class="nav-link"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
                <a href="storefront.php" class="nav-link"><span class="material-symbols-outlined">web</span> Tela Inicial</a>
                
                <!-- Aba Ativa: Usuários -->
                <a href="usuarios.php" class="nav-link active"><span class="material-symbols-outlined">group</span> Usuários</a>
                
                <a href="#" class="nav-link"><span class="material-symbols-outlined">inventory_2</span> Produtos</a>
                <a href="#" class="nav-link"><span class="material-symbols-outlined">shopping_bag</span> Vendas</a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="nav-link logout-link"><span class="material-symbols-outlined">logout</span> Sair</a>
            </div>
        </aside>

        <div id="admin-overlay" class="admin-overlay"></div>

        <main class="admin-main">
            <!-- TOPBAR -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button id="open-admin-menu" class="mobile-open-btn"><span class="material-symbols-outlined">menu</span></button>
                    <!-- Barra de pesquisa da página inteira -->
                    <div class="search-box">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" id="search-users" placeholder="Buscar por nome ou e-mail..." onkeyup="filtrarUsuarios()">
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="admin-profile"><div class="profile-avatar"><?php echo substr($_SESSION['admin_nome'], 0, 1); ?></div></div>
                </div>
            </header>

            <div class="admin-content">
                
                <!-- CABEÇALHO DA PÁGINA COM O BOTÃO PRETO -->
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
                    <div>
                        <h2 style="font-size: 1.8rem; font-weight: 600;">Administradores</h2>
                        <p style="color: #888; margin-top: 5px;">Gerencie os acessos ao painel da BICS.</p>
                    </div>
                    <button onclick="abrirModal()" style="background-color: var(--color-black); color: var(--color-primary); padding: 12px 25px; border-radius: 8px; border: none; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                        Novo Admin
                    </button>
                </div>

                <?php if(isset($_GET['sucesso'])): ?>
                    <p style="color: green; margin-bottom: 15px; font-weight: bold;">Operação realizada com sucesso!</p>
                <?php endif; ?>

                <!-- TABELA DE USUÁRIOS -->
                <div class="admin-table-container">
                    <table class="admin-table" id="tabela-usuarios">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Ações</th>
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
                                    echo "<td class='action-buttons'>
                                            <button class='btn-icon' title='Redefinir Senha'><span class="material-symbols-outlined">key</span></button>
                                            <button class='btn-icon delete' title='Excluir Usuário'><span class="material-symbols-outlined">delete</span></button>
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

            </div>
        </main>
    </div>

    <!-- =========================================
                  MODAL DE NOVO ADMIN
    ========================================= -->
    <div class="modal-admin-overlay" id="modal-novo-admin">
        <div class="modal-admin-card">
            <button class="modal-close-btn" onclick="fecharModal()"><span class="material-symbols-outlined">close</span></button>
            <h3 class="modal-title">Cadastrar Novo Admin</h3>
            
            <form action="cadastrar_admin.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Nome Completo</label>
                    <input type="text" name="nome" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">E-mail (Login)</label>
                    <input type="email" name="email" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 13px; font-weight: 600; color: #555;">Senha Temporária</label>
                    <input type="password" name="senha" required style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none;">
                </div>

                <button type="submit" style="width: 100%; padding: 15px; background-color: var(--color-black); color: var(--color-primary); border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; margin-top: 10px;">Cadastrar Administrador</button>
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

        // Modal de Cadastro
        const modal = document.getElementById('modal-novo-admin');
        function abrirModal() { modal.classList.add('active'); }
        function fecharModal() { modal.classList.remove('active'); }

        // Filtro de Pesquisa (Filtra Nome e Email em tempo real sem recarregar a página)
        function filtrarUsuarios() {
            const input = document.getElementById('search-users').value.toLowerCase();
            const linhas = document.querySelectorAll('#tabela-usuarios tbody tr');

            linhas.forEach(linha => {
                // Pega o texto das colunas Nome e Email
                const nome = linha.querySelector('.user-nome') ? linha.querySelector('.user-nome').textContent.toLowerCase() : '';
                const email = linha.querySelector('.user-email') ? linha.querySelector('.user-email').textContent.toLowerCase() : '';
                
                if (nome.includes(input) || email.includes(input)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>