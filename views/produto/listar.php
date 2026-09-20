<div class="card">
    <div class="card-header">
        <h2 class="float-start">Lista de Produtos</h2>
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
        <p>Abaixo os produtos cadastrados:</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Imagem</td>
                    <td>Nome do Produto</td>
                    <td>Valor</td>
                    <td>Ativo</td>
                    <td>Opções</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $dadosProduto = $this->produto->listar();
                    
                    foreach ($dadosProduto as $dados) {

                        $ativo = "<span class='alert alert-success'>Sim</span>";
                        if ($dados->ativo == 'N') $ativo = "<span class='alert alert-danger'>Não</span>";
                        ?>

                        <tr>
                            <td><?=$dados->id?></td>
                            <td><img src="arquivos/<?=$dados->imagem?>" width="70px"></td>
                            <td><?=$dados->nome?></td>
                            <td><?=number_format($dados->valor,2,",",".")?></td>
                            <td><?=$ativo?></td>
                            <td width="150px">
                                <a href="javascript:excluir(<?=$dados->id?>, 'produto')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="produto/index/<?=$dados->id?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>