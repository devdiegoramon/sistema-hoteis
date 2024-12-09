<!-- Modal -->
<div class="modal fade" id="modalLogo" tabindex="-1" aria-labelledby="modalLogo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logo da hospedagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype='multipart/form-data' action='include/gLogo.php' id='m_logo' method='POST'>
                    <?php
                    $logoCaminho = "Selecione uma logo no formato PNG.";
                    if (mysqli_num_rows($resultLogo) > 0) {
                        $logoCaminho = "arquivos/logo/logo.png";
                    }
                    ?>
                    <input name='logo' type="file" class="dropify" data-default-file="<?= $logoCaminho ?>" accept="png" data-allowed-file-extensions="png"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" name='validar' form='m_logo' class="btn btn-primary"><i class="fa fa-save"></i> Gravar</button>
            </div>
        </div>
    </div>
</div>