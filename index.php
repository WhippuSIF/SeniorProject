<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood test patients</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<br>
<h1 class="text-center">Blood test patients</h1>
<br>
<?php
require 'connection.php';

$key = $redis->keys("$me:PAT:*");
echo '<div class="list-group d-grid col-6 mx-auto">';
foreach($key as $k) {
    $t =   str_replace("$me:PAT:" , "", $k);
    echo '<a href="/patient.php?key='. $t.'" class="list-group-item list-group-item-action text-center">'. $t .'</a>';
}
echo '</div>';
$redis->close();
echo '<br>';
echo '<div class="d-grid gap-2 col-6 mx-auto">';
echo '<a class="btn btn-primary" href="/addpatient.php" role="button">Add patient</a>';
echo '</div>';

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>