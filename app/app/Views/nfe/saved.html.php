<?php
if (isset($data['message'])) {
?>
    <div class="alert alert-<?php echo $data['alert_type'] ?>" role="alert">
        <?php echo $data['message']; ?>
    </div>
<?php } ?>

<a href="/home" class="btn btn-primary" role="button">
    Voltar a Home
</a>