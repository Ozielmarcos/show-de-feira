<?php if (!empty($_SESSION["feira"]["id"])): ?>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="text-center mb-0">
            Olá, seja bem-vindo(a): <strong><?= htmlspecialchars($_SESSION["feira"]["nome"] ?? "") ?></strong>
        </h4>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">
        <h2 class="h4 mb-0">Atalhos Administrativos</h2>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-3">
                <a href="categoria" title="Categorias" class="btn btn-outline-success w-100 py-3">
                    <i class="fas fa-tags fa-2x mb-2"></i>
                    <br>
                    Categorias
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="produto" title="Produtos" class="btn btn-outline-success w-100 py-3">
                    <i class="fas fa-gift fa-2x mb-2"></i>
                    <br>
                    Produtos
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="usuario" title="Usuários" class="btn btn-outline-success w-100 py-3">
                    <i class="fas fa-user fa-2x mb-2"></i>
                    <br>
                    Usuários
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="index/sair" title="Sair" class="btn btn-outline-danger w-100 py-3">
                    <i class="fas fa-power-off fa-2x mb-2"></i>
                    <br>
                    Sair
                </a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="p-4 mb-4 bg-light rounded-3 text-center border">
    <h1 class="display-6 fw-bold text-success"><i class="fas fa-leaf"></i> Show de Feira</h1>
    <p class="lead mb-0 text-muted">Produtos frescos, selecionados e direto do produtor para a sua casa!</p>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0"><i class="fas fa-star text-warning"></i> Produtos em Destaque</h2>
        <a href="carrinho" class="btn btn-sm btn-outline-success">
            <i class="fas fa-shopping-cart"></i> Ver Carrinho
        </a>
    </div>
    <div class="card-body">
      <div class="row g-4">
        <?php 
        $produtos = $this->produto->listar(); 

        foreach ($produtos as $produto) {
          if ($produto->destaque == 'S' && $produto->ativo == 'S') {
        ?>
            
          <div class="col-12 col-sm-6 col-md-3 text-center">
            <div class="card h-100 p-2 shadow-sm border">
                <div style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img src="arquivos/<?php echo htmlspecialchars($produto->imagem); ?>" alt="<?php echo htmlspecialchars($produto->nome); ?>" class="img-fluid" style="max-height: 170px; object-fit: contain;">
                </div>
                <div class="card-body p-2 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="h6 fw-bold text-dark mt-2 mb-1"><?php echo htmlspecialchars($produto->nome); ?></h5>
                        <p class="text-success fs-5 fw-bold mb-2">R$ <?= number_format($produto->valor, 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <a href="produto/detalhes/<?php echo $produto->id; ?>" class="btn btn-success btn-sm w-100 mb-1">
                            <i class="fas fa-eye"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
          </div>
        <?php
          }
        }
        ?>
      </div>
      <hr class="my-4">
      <div class="text-center">
        <?php if (!empty($_SESSION["feira"]["id"])): ?>
            <a href="produto" class="btn btn-success"><i class="fas fa-search"></i> Ver Todos os Produtos</a>
        <?php endif; ?>
      </div>
    </div>
</div>