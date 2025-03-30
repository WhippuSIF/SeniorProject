<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood test entry deleted</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php
require 'connection.php';
if (isset($_GET["key"]) and isset ($_GET["test"])) {
    $id = $_GET["key"];
    $test = $_GET["test"];
    $redis->del("$me:BLT:$id:$test");
    $redis->del("$me:COM:$id:$test:*");
    $redis->hIncrBy("$me:PAT:$id", 'last_bloodtest', -1);
    $redis->close();
    echo "Deleted!";
    echo '<meta http-equiv="refresh" content="3;URL=/bloodtests.php?key='.$id.'">';
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
