<?php 
$paramId = explode("/", $_GET["param"]);
$id = $paramId[2] ?? null;

$dados = $this->produto->getProdutoDetalhes($id);

if (!$dados) {
    echo "<script>mensagem('Produto não encontrado','produto/listar','error');</script>";
    exit;
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="float-start h4 mb-0">Detalhes do Produto</h2>
    </div>

    <div class="card-body">
      <form action="carrinho/adicionar" method="post" class="m-0 p-0">
        <input type="hidden" name="produto_id" value="<?= $dados->id ?>">
        <input type="hidden" name="quantidade" value="1">

        <div class="row mb-3">
          <div class="col-12">
            <h2><?= htmlspecialchars($dados->nome) ?></h2>
          </div>
        </div>

        <div class="row align-items-center">
          <div class="col-12 col-md-6 text-center">
            <img src="arquivos/<?= htmlspecialchars($dados->imagem) ?>" 
                 alt="<?= htmlspecialchars($dados->nome) ?>" 
                 class="img-fluid rounded" 
                 style="max-height: 350px;"
              >
          </div>
          <div class="col-12 col-md-6">
            <label class="fw-bold" for="descricao">Descrição</label>
            <p><?= $dados->descricao ?></p>
            <p class="float-end fs-4 fw-bold text-success">
              R$ <?= number_format($dados->valor, 2, ',', '.') ?>
            </p>
          </div>
        </div>

        <hr>
       
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success float-end ms-3">
              <i class="fas fa-cart-plus"></i> + Carrinho
            </button>

            <a href="produto" class="btn btn-outline-danger float-end">
              <i class="fas fa-times"></i> Voltar
            </a>
          </div>
        </div>
      </form>
    </div>
</div>