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
<?php include 'partials/navbar.php'; ?>
<br>
<?php
require 'connection.php';
// Get info from GET requests
if (isset($_GET["key"]) and isset ($_GET["test"])) {
    // Patient ID
    $id = $_GET["key"];
    // Blood Test ID
    $test = $_GET["test"];
    // Delete the blood test entry
    $redis->del("$me:BLT:$id:$test");
    // Delete all comments
    $redis->del("$me:COM:$id:$test:*");
    // Decrease number of blood tests by 1
    $redis->hIncrBy("$me:PAT:$id", 'last_bloodtest', -1);
    // Close PHP Redis
    $redis->close();
    // Print "Deleted!" message
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Deleted!</div>";
    // Redirect to blood test page
    echo '<meta http-equiv="refresh" content="3;URL=/bloodtests.php?key='.$id.'">';
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
