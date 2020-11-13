<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="header text-center">
                <?php
                echo !empty($this->cor['no_cor']) ? "Editar a cor " . $this->cor['no_cor'] : "Cadastrar nova cor!";
                ?>
            </h3>
            <form action="cor/set" method="POST">
                <input type="hidden" name="id_cor" value="<?php echo !empty($this->cor) ? $this->cor['id_cor'] : ""; ?>" />
                <div class="form-group">
                    <label>Nome da Cor</label>
                    <input type="text" name="no_cor" value="<?php echo !empty($this->cor) ? $this->cor['no_cor'] : ""; ?>" 
                    class="form-control" placeholder="Ex: rosa">
                    <small id="emailHelp" class="form-text text-muted">Você pode usar uma cor para criar uma aquarela.</small>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
    </div>
</div>