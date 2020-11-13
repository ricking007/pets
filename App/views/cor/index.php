<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-lg-6">
                <a class="btn btn-info" href="cor/form">Novo Cadastro</a>
            </div>
            <div class="dropdown-divider"></div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50"> ID</th>
                        <th class="text-center">Cor</th>
                        <th width="200"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->cores)) {
                        foreach ( $this->cores as $r) {
                    ?>
                            <tr>
                                <th><?php echo $r['id_cor']; ?></th>
                                <td class="text-center"><?php echo $r['no_cor']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group-sm">
                                        <a class="btn btn-success btn-sm" href="cor/form/<?php echo $r['id_cor']; ?>">editar</a>
                                        <a class="btn btn-danger btn-sm" href="cor/del/<?php echo $r['id_cor']; ?>">excluir</a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>