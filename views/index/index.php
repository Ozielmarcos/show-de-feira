<div class="login">
    <?php if (!empty($_SESSION['redirect_after_login'])): ?>
        <div class="alert alert-info text-center mb-3 p-2">
            <i class="fas fa-info-circle"></i> Faça login para continuar e finalizar seu pedido!
        </div>
    <?php endif; ?>
    <div class="card shadow-sm">
        <div class="card-header text-center bg-white py-3">
            <img src="images/logo.png" alt="Show de Feira" style="max-height: 50px;">
        </div>
        <div class="card-body p-4">
            <h5 class="card-title text-center mb-3">Acesso ao Sistema</h5>
            <form name="formControle" method="post" action="index/login" data-parsley-validate="">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" name="email" id="email" class="form-control" required 
                    data-parsley-required-message="Digite seu e-mail"
                    data-parsley-type-message="Digite um e-mail válido"
                    placeholder="seu.email@exemplo.com">
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha" required
                        data-parsley-required-message="Digite sua senha"
                        data-parsley-errors-container="#erro">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarSenha()"><i class="fas fa-eye"></i></button>
                    </div>
                    <div id="erro"></div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">
                    <i class="fas fa-sign-in-alt"></i> Fazer Login
                </button>
            </form>
        </div>
        <div class="card-footer text-center bg-light">
            <a href="index" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left"></i> Voltar à Loja</a>
        </div>
    </div>
</div>