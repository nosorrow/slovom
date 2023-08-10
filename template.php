<?php
/*
 * Include Class NumberToWordClass
 */
include_once "NumberToWord.php";

$num = new NumberToWord('number');
$curr = new NumberToWord('currency');

$chislo = $_GET['number'] ?? 0;

$num->setNumber($chislo);
$curr->setNumber($chislo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Title</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        .container {
            background-color: white;
        }
    </style>
</head>
<body style="background-color: #e2e2e2">
<div class="container p-5 mt-5">
    <div class="container mb-5 mt-5">
        <div class="row">
            <div class="col-md-10">
                <form class="form-inline">
                    <div class="form-group mx-sm-2 mb-2">
                        <input type="text" class="form-control" id="number" name="number" placeholder="Въведете число">
                    </div>
                    <button type="submit" class="btn btn-default mb-2">покажи</button>
                </form>
                <small id="emailHelp" class="form-text text-muted">Макс. число е <?php echo $num->get_max_number(); ?></small>
            </div>
        </div>
    </div>
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td colspan="2"><h4><?php echo $num->toNumber(); ?></h4></td>
                    </tr>
                    <tr>
                        <td><strong>числото с думи :</strong></td>
                        <td><?php echo $num->toWord(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>числото като сума с думи :</strong></td>
                        <td><?php echo $curr->toWord(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

    $('form').on('submit', function () {
        var max = "<?php echo number_format($num->get_max_number(), 0, '.', '');?>";
        var numbermax = Number(max);
        var n = $("#number").val();
        if (n > numbermax) {
            alert('Твърде голямо число!' + "\n" + n);
            location.reload();
        }
    });

</script>
</body>
</html>
