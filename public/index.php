<?php
session_start();
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$baseUrl = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';

// Parseamento de rotas amigáveis
$param = !empty($_GET["param"]) ? explode("/", trim($_GET["param"], "/")) : [];
$controller = !empty($param[0]) ? strtolower($param[0]) : "index";
$acao = !empty($param[1]) ? strtolower($param[1]) : "index";
$id = $param[2] ?? NULL;

$isLogged = isset($_SESSION["feira"]["id"]);

// Contagem dinâmica de itens do carrinho
$totalItensCarrinho = 0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $totalItensCarrinho += (int)($item['quantidade'] ?? 0);
    }
}

// Processa tentativa de login via POST
if (!$isLogged && $_POST && isset($_POST["email"]) && isset($_POST["senha"])) {
    $email = trim($_POST["email"] ?? NULL);
    $senha = trim($_POST["senha"] ?? NULL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>mensagem('E-mail inválido','index/login','error');</script>";
        exit;
    } else if (empty($senha)) {
        echo "<script>mensagem('Preencha a senha','index/login','error');</script>";
        exit;
    }

    require_once "../controllers/IndexController.php";
    $loginCtrl = new IndexController();
    $loginCtrl->verificar($email, $senha);
    exit;
}

// Verificação de rotas protegidas (que exigem autenticação)
$rotasProtegidas = [
    'categoria' => true,
    'usuario' => true,
];

$isProtected = false;
if (isset($rotasProtegidas[$controller])) {
    $isProtected = true;
} else if ($controller === 'produto' && $acao !== 'detalhes') {
    $isProtected = true; // Gerenciamento do catálogo exige login
} else if ($controller === 'carrinho' && in_array($acao, ['finalizar', 'sucesso', 'falha', 'pendente'])) {
    $isProtected = true; // Fechamento de pedido exige login
}

// Se tentar acessar rota restrita sem login, guarda retorno e envia para login
if ($isProtected && !$isLogged) {
    $_SESSION['redirect_after_login'] = $_GET["param"] ?? 'index';
    $controller = 'index';
    $acao = 'login';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show de Feira - Loja & Painel</title>
    <base href="<?= $baseUrl ?>">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.5.1.min.js"></script>

    <script src="js/jquery.inputmask.min.js"></script>
    <script src="js/bindings/inputmask.binding.js"></script>

    <script src="js/sweetalert2.js"></script>
    <script src="js/parsley.min.js"></script>

    <script>
        mensagem = function(msg, tabela, icone) {
            Swal.fire({
                icon: icone || 'info',
                title: msg,
                confirmButtonText: "OK",
            }).then((result) => {
                location.href = tabela || 'index';
            });
        }

        excluir = function(id, tabela) {
            Swal.fire({
                icon: "question",
                title: "Deseja realmente excluir este registro?",
                showCancelButton: true,
                confirmButtonText: "Excluir",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = tabela + "/excluir/" + id;
                }
            });
        }

        mostrarSenha = function() {
            const campo = document.getElementById('senha');
            if (campo.type === 'password') {
                campo.type = 'text';
            } else {
                campo.type = 'password';
            }
        }
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index">
                <img src="images/logo.png" alt="Show de Feira" style="max-height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <?php if ($isLogged): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="categoria"><i class="fas fa-tags"></i> Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produto"><i class="fas fa-boxes"></i> Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario"><i class="fas fa-users"></i> Usuários</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="carrinho" class="btn btn-outline-success me-3 position-relative" title="Meu Carrinho">
                        <i class="fas fa-shopping-cart"></i> Carrinho
                        <?php if ($totalItensCarrinho > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $totalItensCarrinho ?>
                                <span class="visually-hidden">itens no carrinho</span>
                            </span>
                        <?php endif; ?>
                    </a>

                    <?php if ($isLogged): ?>
                        <span class="navbar-text me-3 d-none d-md-inline">
                            Olá, <strong><?= htmlspecialchars($_SESSION["feira"]["nome"] ?? "") ?></strong>
                        </span>
                        <a href="index/sair" title="Sair do Sistema" class="btn btn-sm btn-danger">
                            <i class="fas fa-power-off"></i> Sair
                        </a>
                    <?php else: ?>
                        <a href="index/login" class="btn btn-sm btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-fill">
        <?php
        $controllerClass = ucfirst($controller)."Controller";
        $page = "../controllers/{$controllerClass}.php";

        if (file_exists($page)) {
            require_once $page;
            if (class_exists($controllerClass)) {
                $control = new $controllerClass();
                if (method_exists($control, $acao)) {
                    $control->$acao($id);
                } else {
                    include "../views/index/erro.php";
                }
            } else {
                include "../views/index/erro.php";
            }
        } else {
            include "../views/index/erro.php";
        }
        ?>
    </main>

    <footer class="footer mt-auto py-3 bg-white border-top">
        <div class="container text-center">
            <p class="text-muted mb-0">Desenvolvido por Oziel Marcos - <?= date("Y") ?></p>
        </div>
    </footer>
</body>

</html>