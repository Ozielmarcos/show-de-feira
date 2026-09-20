<div class="card">
    <div class="card-header">
        <h2 class="float-start">Lista de Categorias</h2>
        <div class="float-end">
            <a href="categoria" title="Novo Registro" class="btn btn-success">
                <i class="fas fa-file"></i> Novo Registro
            </a>

            <a href="categoria/listar" title="Listar" class="btn btn-success">
                <i class="fas fa-file"></i> Listar
            </a>
        </div>
    </div>
    <div class="card-body">
        <p>Abaixo as categorias cadastradas:</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Categoria</td>
                    <td>Ativo</td>
                    <td>Opções</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $dadosCategoria = $this->categoria->listar();
                    foreach ($dadosCategoria as $dados) {

                        $ativo = "<span class='alert alert-success'>Sim</span>";
                        if ($dados->ativo == 'N') $ativo = "<span class='alert alert-danger'>Não</span>";
                        ?>
                        <tr>
                            <td><?=$dados->id?></td>
                            <td><?=$dados->descricao?></td>
                            <td><?=$ativo?></td>
                            <td width="150px">
                                <a href="javascript:excluir(<?=$dados->id?>, 'categoria')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="categoria/index/<?=$dados->id?>" class="btn btn-info btn-sm">
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