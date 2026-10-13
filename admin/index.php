<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BICS Admin | Secure Login</title>
    <!-- CSS do Admin -->
    <link rel="stylesheet" href="src/admin.css">
    <!-- Ícones do Google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
    <link rel="icon" href="../public/img/icon.png" type="image/x-icon">
</head>
<body>
    
    <div class="admin-login-wrapper">
        <!-- O caminho agora aponta para dentro da pasta public -->
        <video class="admin-login-video" autoplay loop muted playsinline disablePictureInPicture oncontextmenu="return false;">
            <source src="../public/video/hero/herovideo.mp4" type="video/mp4">
        </video>
        
        <div class="admin-login-overlay"></div>

        <!-- AQUI ESTAVA O PROBLEMA: A div do card central havia desaparecido -->
        <div class="admin-login-card">
            
            <div class="admin-login-header">
                <h1 class="logo">BICS <span>ADMIN</span></h1>
                <p>Acesso Restrito</p>
            </div>

            <!-- Mensagens de Erro -->
            <?php
            if (isset($_GET['erro'])) {
                if ($_GET['erro'] == 'senha') {
                    echo '<p style="color: red; font-size: 13px; margin-bottom: 15px;">Senha incorreta. Tente novamente.</p>';
                } else if ($_GET['erro'] == 'usuario') {
                    echo '<p style="color: red; font-size: 13px; margin-bottom: 15px;">Utilizador não encontrado.</p>';
                } else if ($_GET['erro'] == 'sessao') {
                    echo '<p style="color: red; font-size: 13px; margin-bottom: 15px;">Por favor, faça login para aceder ao painel.</p>';
                }
            }
            ?>

            <!-- O formulário aponta para o valida_login.php -->
            <form class="bics-form" action="valida_login.php" method="POST">
                
                <div class="input-group">
                    <input type="text" id="admin-user" name="usuario" required placeholder=" ">
                    <label for="admin-user">Login ID ou E-mail</label>
                </div>

                <div class="input-group">
                    <input type="password" id="admin-pass" name="senha" required placeholder=" ">
                    <label for="admin-pass">Senha</label>
                    <button type="button" class="toggle-password" onclick="togglePass()">
                        <span class="material-symbols-outlined">visibility_off</span>
                    </button>
                </div>

                <button type="submit" class="btn-solid-black">Entrar</button>
            </form>
            
        </div> <!-- Fim da admin-login-card -->
    </div>

    <!-- Script rápido só para o olho da senha funcionar isoladamente aqui -->
    <script>
        function togglePass() {
            const passInput = document.getElementById('admin-pass');
            const icon = document.querySelector('.toggle-password span');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passInput.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>