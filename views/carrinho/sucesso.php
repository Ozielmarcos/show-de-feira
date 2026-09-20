<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 text-center">
            <div class="card shadow border-0 p-4">
                <div class="card-body">
                    <!-- Ícone de Sucesso -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success display-1"></i>
                    </div>

                    <h1 class="h3 text-success fw-bold mb-2">Pedido Confirmado!</h1>
                    <p class="text-secondary fs-5 mb-4">
                        Obrigado pela compra! Seu pagamento foi processado com sucesso.
                    </p>

                    <?php if (!empty($_GET['pedido_id'])): ?>
                        <div class="alert alert-light border mb-4">
                            <strong>Número do Pedido:</strong> #<?= htmlspecialchars($_GET['pedido_id']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="index" class="btn btn-success btn-lg px-4 gap-3">
                            <i class="fas fa-store me-1"></i> Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>