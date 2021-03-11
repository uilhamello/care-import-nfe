<?php
if (isset($data['message'])) {
?>
    <div class="alert alert-<?php echo $data['alert_type'] ?>" role="alert">
        <?php echo $data['message']; ?>
    </div>
<?php } ?>

<?php
if (isset($data['numero'])) {
?>

    <div class="nota">

        <fieldset style="border: 1px solid #000;">
            <legend>Emisor</legend>

            <p>
                <label for="">CNPJ: </label> <?php echo $data['cnpj']; ?>
            </p>
            <p>
                <label for="">Número: </label> <?php echo $data['numero']; ?>
            </p>
            <p>
                <label for="">Data: </label> <?php echo $data['data']; ?>
            </p>
            <p>
                <label for="">Valor: </label> <?php echo $data['valor']; ?>
            </p>

        </fieldset>

        <fieldset style="border: 1px solid #000;">
            <legend>Destino</legend>

            <p>
                <label for="">CNPJ: </label> <?php echo $data['dest']['CNPJ']; ?>
            </p>
            <p>
                <label for="">Nome: </label> <?php echo $data['dest']['xNome']; ?>
            </p>
            <p>
                <label for="">Email: </label> <?php echo $data['dest']['email']; ?>
            </p>
            <p>
                <a href="/home" class="btn btn-primary" role="button">
                    cancelar
                </a>
                <a href="/nfe-save" class="btn btn-primary" role="button">
                    confirmar Importação
                </a>
            </p>
        </fieldset>

    </div>

<?php } else { ?>
    <a href="/home">
        cancelar
    </a>
<?php } ?>