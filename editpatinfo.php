<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit patient information</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php
require 'connection.php';
if (isset($_POST['fnm']) and isset($_POST['lnm']) and isset($_POST['country']) and isset($_POST['submit']) and isset($_POST['patkey'])) {
    $fname = $_POST['fnm'];
    $lname = $_POST['lnm'];
    $country = $_POST['country'];
    $id = $_POST['patkey'];
    $key = "$me:PAT:$id";
    $redis->hSet($key, 'firstName',$fname);
    $redis->hSet($key, 'lastName',$lname);
    $redis->hSet($key, 'country',$country);
    $redis->close();
    echo "Edited!";
    echo '<meta http-equiv="refresh" content="3;URL=/patient.php?key='.$id.'">';
}
if (isset($_GET["key"])) {
    $id = $_GET["key"];
    $k = "$me:PAT:$id";
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    $country = $redis->hGet($k,'country');
    $redis->close();
    echo '<h1 class="text-center">Editing patient information of: '.$fn.' '.$ln.'</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        First Name: <input type="text" class="form-control" name="fnm" value="'.$fn.'" /><br/>
        Last Name: <input type="text" class="form-control" name="lnm" value="'.$ln.'" /><br/>
        Country: <input type="text" class="form-control" name="country" value="'.$country.'" /><br/>
        <input type="hidden" name="patkey"  value="'.$id.'">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
    </form>';
}



?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
