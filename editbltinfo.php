<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit blood test information</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php
require 'connection.php';
if (isset($_POST['desc']) and isset($_POST['submit']) and isset($_POST['patkey']) and isset($_POST['pattest'])) {
    $d = $_POST['desc'];
    $id = $_POST['patkey'];
    $ts = $_POST['pattest'];
    $key = "$me:BLT:$id:$ts";
    $redis->hSet($key, 'desc',$d);
    $redis->close();
    echo "Edited!";
    echo '<meta http-equiv="refresh" content="3;URL=/bloodtests.php?key='.$id.'">';
}
if (isset($_GET["key"]) and isset ($_GET["test"])) {
    $id = $_GET["key"];
    $t = $_GET["test"];
    $k = "$me:BLT:$id:$t";
    $desc = $redis->hGet($k,'desc');
    $redis->close();
    echo '<h1 class="text-center">Editing description of blood test #'.$desc.'</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        Description: <input type="text" class="form-control" name="desc" value="'.$desc.'" /><br/>
        <input type="hidden" name="patkey"  value="'.$id.'">
        <input type="hidden" name="pattest"  value="'.$t.'">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
    </form>';
}



?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
