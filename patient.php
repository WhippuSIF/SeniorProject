<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient information</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<br>
<h1  class="text-center">Patient information</h1>
<br>
<?php
require 'connection.php';
if (isset ($_GET["key"])) {
    echo '<ul class="list-group d-grid col-6 mx-auto">';
    $id = $_GET["key"];
    echo '<li class="list-group-item text-center">Patient ID: '. $id.'</li>';
    $k = "$me:PAT:$id";
    $fn = $redis->hGet($k,'firstName');
    echo '<li class="list-group-item text-center">First name: '. $fn.'</li>';
    $ln = $redis->hGet($k,'lastName');
    echo '<li class="list-group-item text-center">Last name: '. $ln.'</li>';
    $c = $redis->hGet($k,'country');
    echo '<li class="list-group-item text-center">Country: '. $c.'</li>';
    $dob = $redis->hGet($k,'birthdtc');
    echo '<li class="list-group-item text-center">Date of birth: '. $dob.'</li>';
    $lbt = $redis->hGet($k,'last_bloodtest');
    echo '<li class="list-group-item text-center">No. of blood tests taken: '. $lbt.'</li>';
    echo "</ul>";
    echo "<br>";
    echo '<div class="d-grid gap-2 col-6 mx-auto">';
    echo '<a class="btn btn-primary" href="/bloodtests.php?key='.$id.'" role="button">Blood Tests</a>';
    echo '<a class="btn btn-primary" href="/editpatinfo.php?key='.$id.'" role="button">Edit patient information</a>';
    echo '<a class="btn btn-danger" href="/delpat.php?key='.$id.'" role="button">Delete patient</a>';
    echo '<a class="btn btn-primary" href="/" role="button">Home</a>';
    echo '</div>';
}
$redis->close();

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>