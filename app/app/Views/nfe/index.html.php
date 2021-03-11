<div>
    <center>
        Lista de Notas Fiscais
    </center>
</div>
<?php
$result = $data['result'];

if ($result->total() < 1) { ?>
    <div>
        <br><br>
        <p>
            <b>
                Nenhuma Nota Importada
            </b>
        </p>
    </div>
<?php } else { ?>
    <div class="list">


        <table class='table'>
            <?php
            $tbody = '';
            $header = '';
            $first = true;
            while ($row = $result->next()) {
                $keys = array_keys($row);
                $tbody .= "<tr>";
                for ($i = 0; $i < count($row); $i++) {
                    if ($first) {
                        $header .= "<th scope='col'>" . $keys[$i] . "</th>";
                    }
                    $tbody .= "<td>" . $row[$keys[$i]] . "</td>";
                }
                $tbody .= "</tr>";
                $first = false;
            }
            echo $header = "<tr>" . $header . "</tr>";
            echo $tbody;
            ?>


        </table>
        </br><b> Total: <?php echo $result->total(); ?></b>
    <?php } ?>

    </div>
    <div style="text-align: center;">
        <br><br>
        <a href="/home" class="btn btn-primary" role="button">Voltar</a>
    </div>