<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-receipt me-2"></i>Resumo do Pedido #<?= htmlspecialchars($pedidoId ?? '') ?>
                </h2>
            </div>
            <div class="card-body">
                <p class="text-muted small">Confira os itens selecionados antes de prosseguir para o pagamento.</p>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['carrinho'] as $item): 
                                $subtotal = $item['quantidade'] * $item['valor'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="arquivos/<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" width="45" height="45" class="img-thumbnail me-2 object-fit-cover">
                                            <div>
                                                <strong class="d-block text-truncate" style="max-width: 180px;"><?= htmlspecialchars($item['nome']) ?></strong>
                                                <small class="text-muted">R$ <?= number_format($item['valor'], 2, ',', '.') ?> un</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold"><?= $item['quantidade'] ?></td>
                                    <td class="text-end fw-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <span class="fs-5 text-muted">Total a Pagar:</span>
                    <span class="fs-3 fw-bold text-success">R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-lock me-2"></i>Pagamento Seguro
                </h2>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Dados do Comprador</h6>
                        <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['feira']['nome'] ?? 'Cliente') ?></p>
                        <p class="mb-1"><strong>E-mail:</strong> <?= htmlspecialchars($_SESSION['feira']['email'] ?? 'Não informado') ?></p>
                        <?php if (!empty($_SESSION['feira']['cpf'])): ?>
                            <p class="mb-1"><strong>CPF:</strong> <?= htmlspecialchars($_SESSION['feira']['cpf']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-cc-visa fa-2x me-2"></i>
                            <i class="fab fa-cc-mastercard fa-2x me-2"></i>
                            <i class="fas fa-barcode fa-2x me-2"></i>
                            <i class="fas fa-pix fa-2x"></i>
                        </div>
                        <small class="d-block mt-2">Aceitamos Cartão de Crédito, PIX e Boleto Bancário via Mercado Pago.</small>
                    </div>
                </div>

                <div>
                    <?php if (!empty($preferencia['init_point'])): ?>
                        <a href="<?= $preferencia['init_point'] ?>" class="btn btn-success btn-lg w-100 py-3 shadow fs-5">
                            <i class="fas fa-shield-alt me-2"></i> Pagar Agora
                        </a>
                    <?php else: ?>
                        <div class="alert alert-danger mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i> Não foi possível carregar a integração de pagamento. Tente novamente.
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="carrinho" class="btn btn-link text-muted text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Voltar para o Carrinho
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>