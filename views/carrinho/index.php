<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h2 class="h4 mb-0"><i class="fas fa-shopping-cart me-2"></i>Meu Carrinho</h2>
    
    <?php if (!empty($_SESSION['carrinho'])): ?>
      <a href="carrinho/limpar" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tem certeza que deseja esvaziar o carrinho?')">
        <i class="fas fa-trash-alt me-1"></i> Esvaziar Carrinho
      </a>
    <?php endif; ?>
  </div>

  <div class="card-body">
    <?php if (empty($_SESSION['carrinho'])): ?>
      
      <div class="text-center py-5">
        <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
        <p class="fs-5 text-secondary">Seu carrinho está vazio no momento.</p>
        <a href="produto" class="btn btn-success mt-2">
          <i class="fas fa-store me-1"></i> Ver Produtos
        </a>
      </div>

    <?php else: ?>

      <form id="form-carrinho" action="carrinho/atualizar" method="post">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col" style="min-width: 250px;">Produto</th>
                <th scope="col">Valor Unitário</th>
                <th scope="col" style="width: 130px;">Quantidade</th>
                <th scope="col">Subtotal</th>
                <th scope="col" class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $totalGeral = 0;
              foreach ($_SESSION['carrinho'] as $id => $item): 
                  $subtotal = $item['quantidade'] * $item['valor'];
                  $totalGeral += $subtotal;
              ?>
                <tr id="linha-<?= $id ?>">
                  <td>
                    <div class="d-flex align-items-center">
                      <img 
                        src="arquivos/<?= htmlspecialchars($item['imagem']) ?>" 
                        alt="<?= htmlspecialchars($item['nome']) ?>" 
                        width="160"  
                        class="img-thumbnail me-3 object-fit-cover"
                      >
                      <div>
                        <strong class="d-block"><?= htmlspecialchars($item['nome']) ?></strong>
                        <?php if (!empty($item['categoria'])): ?>
                          <small class="text-muted"><?= htmlspecialchars($item['categoria']) ?></small>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    R$ <?= number_format($item['valor'], 2, ',', '.') ?>
                  </td>

                  <td>
                    <input type="number" 
                           name="quantidades[<?= $id ?>]" 
                           value="<?= $item['quantidade'] ?>" 
                           min="1" 
                           data-id="<?= $id ?>"
                           data-valor="<?= $item['valor'] ?>"
                           class="form-control text-center input-quantidade">
                  </td>

                  <td>
                    <strong class="text-success subtotal-item" id="subtotal-<?= $id ?>">
                      R$ <?= number_format($subtotal, 2, ',', '.') ?>
                    </strong>
                  </td>

                  <td class="text-end">
                    <a href="carrinho/excluir/<?= $id ?>" class="btn btn-outline-danger btn-sm" title="Remover item">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
          <div class="col-12 col-md-6 mb-3 mb-md-0">
            <a href="produto" class="btn btn-outline-success text-decoration-none ms-2">
              <i class="fas fa-arrow-left me-1"></i> Continuar Comprando
            </a>
          </div>

          <div class="col-12 col-md-6 text-md-end">
            <div class="mb-3">
              <span class="fs-5 text-muted">Total da Compra:</span>
              <span class="fs-3 fw-bold text-success ms-2" id="total-geral">
                R$ <?= number_format($totalGeral, 2, ',', '.') ?>
              </span>
            </div>

            <a href="carrinho/finalizar" class="btn btn-success btn-lg px-4">
              Finalizar Pedido <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </form>

    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputsQuantidade = document.querySelectorAll('.input-quantidade');
    const formCarrinho = document.getElementById('form-carrinho');

    function formatarMoeda(valor) {
        return 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcularTotais() {
        let totalGeral = 0;

        inputsQuantidade.forEach(input => {
            const id = input.getAttribute('data-id');
            const valorUnitario = parseFloat(input.getAttribute('data-valor'));
            let quantidade = parseInt(input.value) || 1;

            if (quantidade < 1) {
                quantidade = 1;
                input.value = 1;
            }

            const subtotal = quantidade * valorUnitario;
            totalGeral += subtotal;

            const elSubtotal = document.getElementById('subtotal-' + id);
            if (elSubtotal) {
                elSubtotal.textContent = formatarMoeda(subtotal);
            }
        });

        const elTotalGeral = document.getElementById('total-geral');
        if (elTotalGeral) {
            elTotalGeral.textContent = formatarMoeda(totalGeral);
        }
    }

    let timerAtualizacao;
    function salvarSessao() {
        clearTimeout(timerAtualizacao);
        timerAtualizacao = setTimeout(() => {
            const formData = new FormData(formCarrinho);

            fetch('carrinho/atualizar', {
                method: 'POST',
                body: formData
            })
            .catch(error => console.error('Erro ao atualizar carrinho na sessão:', error));
        }, 300);
    }

    inputsQuantidade.forEach(input => {
        ['input', 'change'].forEach(evento => {
            input.addEventListener(evento, function() {
                recalcularTotais();
                salvarSessao();
            });
        });
    });
});
</script>