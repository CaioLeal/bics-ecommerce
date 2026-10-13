<?php
// Inicia a sessão para poder manipulá-la
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão completamente
session_destroy();

// Redireciona de volta para a tela de login
header("Location: index.php");
exit();
?>