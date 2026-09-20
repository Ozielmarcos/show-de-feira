<?php

$nome = $categoria_id = $descricao = $imagem = $valor = $destaque = $ativo = NULL;

if (!empty($id)) {
    $dados = $this->produto->editar($id);

    if (empty($dados)) {
        echo "<script>mensagem('Registro não encontrado!','produto','error');</script>";
        exit;
    }

    $id = $dados->id;
    $nome = $dados->nome;
    $categoria_id = $dados->categoria_id;
    $imagem = $dados->imagem;
    $valor = number_format($dados->valor,2,",",".");
    $destaque = $dados->destaque;
    $descricao = $dados->descricao;
    $ativo = $dados->ativo;
}

?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<div class="card">
    <div class="card-header">
        <h2 class="float-start">Cadastro de Produtos</h2>
        <div class="float-end">
            <a href="produto" title="Novo Registro" class="btn btn-success">
                <i class="fas fa-file"></i> Novo Registro
            </a>

            <a href="produto/listar" title="Listar" class="btn btn-success">
                <i class="fas fa-file"></i> Listar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form name="formCadastro" method="post" action="produto/salvar" data-parsley-validate="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 col-md-1">
                    <label for="id">ID:</label>
                    <input type="text" name="id" id="id" class="form-control" readonly value="<?= $id ?>">
                </div>
                <div class="col-12 col-md-8">
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" name="nome" id="nome" required class="form-control" value="<?= $nome ?>"
                        data-parsley-required-message="Preencha o nome do produto">
                </div>
                <div class="col-12 col-md-3">
                    <label for="categoria_id">Categoria:</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required data-parsley-required-message="Selecione uma Categoria">
                        <option value="">Selecione</option>
                        <?php
                            $categorias = $this->produto->listarCategoria();
                            foreach($categorias as $dados) {
                                echo "<option value='{$dados->id}'>{$dados->descricao}</option>";
                            }
                         ?>
                    </select>
                    <script>
                        $("#categoria_id").val('<?= $categoria_id ?>');
                    </script>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-md-12">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" required data-parsley-required-message="Preencha a descrição" class="form-control"><?=$descricao?></textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-md-4">
                    <label for="imagem">Selecione uma Imagem (JPG):</label>
                    <input type="file" name="imagem" id="imagem" class="form-control" accept=".jpg">
                    <input type="hidden" name="imagem" value="<?= $imagem ?>">
                </div>      
                <div class="col-12 col-md-2">
                    <label for="valor">Valor:</label>
                    <input type="text" name="valor" id="valor" class="form-control" required data-parsley-required-message="Digite o valor" value="<?= $valor ?>">
                </div>      
                <div class="col-12 col-md-2">
                    <label for="destaque">Destaque:</label>
                    <select name="destaque" id="destaque" class="form-select" required data-parsley-required-message="Selecione uma opção">
                        <option value="">Selecione</option>
                        <option value="S">Sim</option>
                        <option value="N">Não</option>
                    </select>

                    <script>
                        $("#destaque").val('<?= $destaque ?>');
                    </script>
                </div>
                <div class="col-12 col-md-2">
                    <label for="ativo">Ativo:</label>
                    <select name="ativo" id="ativo" class="form-select" required data-parsley-required-message="Selecione uma opção">
                        <option value="">Selecione</option>
                        <option value="S">Sim</option>
                        <option value="N">Não</option>
                    </select>

                    <script>
                        $("#ativo").val('<?= $ativo ?>');
                    </script>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-success float-end">
                <i class="fas fa-check"></i> Salvar/Atualizar Registro
            </button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#descricao').summernote({
            height: 200
        });
    });
    $("#valor").maskMoney({thousands:'.', decimal:','});
</script>