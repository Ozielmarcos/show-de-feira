<div class="card">
    <div class="card-header">
        <h2 class="float-start">Lista de Usuários</h2>
        <div class="float-end">
            <a href="usuario" title="Novo Registro" class="btn btn-success">
                <i class="fas fa-file"></i> Novo Registro
            </a>

            <a href="usuario/listar" title="Listar" class="btn btn-success">
                <i class="fas fa-file"></i> Listar
            </a>
        </div>
    </div>
    <div class="card-body">
        <p>Abaixo os usuários cadastrados:</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Nome do Usuário</td>
                    <td>E-mail</td>
                    <td>Telefone</td>
                    <td>Ativo</td>
                    <td>Opções</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $dadosUsuario = $this->usuario->listar();
                    foreach ($dadosUsuario as $dados) {

                        $ativo = "<span class='alert alert-success'>Sim</span>";
                        if ($dados->ativo == 'N') $ativo = "<span class='alert alert-danger'>Não</span>";
                        ?>
                        <tr>
                            <td><?=$dados->id?></td>
                            <td><?=$dados->nome?></td>
                            <td><?=$dados->email?></td>
                            <td><?=$dados->telefone?></td>
                            <td><?=$ativo?></td>
                            <td width="150px">
                                <a href="javascript:excluir(<?=$dados->id?>, 'usuario')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="usuario/index/<?=$dados->id?>" class="btn btn-info btn-sm">
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