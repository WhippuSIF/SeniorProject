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
<?php include 'partials/navbar.php'; ?>
<br>
<?php
require 'connection.php';
// Get all info from POST requests
if (isset($_POST['desc']) and isset($_POST['submit']) and isset($_POST['patkey']) and isset($_POST['pattest']) and isset($_POST['patcom'])) {
    // New message
    $d = $_POST['desc'];
    // Patient ID
    $id = $_POST['patkey'];
    // Blood test ID
    $ts = $_POST['pattest'];
    // Comment ID
    $cm = $_POST['patcom'];
    // Path in keystore
    $key = "$me:COM:$id:$ts:$cm";
    // Edit comment
    $redis->hSet($key, 'comment',$d);
    // Close PHP Redis
    $redis->close();
    // Print "Edited!" message
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Edited!</div>";
    // Redirect to comments page
    echo '<meta http-equiv="refresh" content="3;URL=/comments.php?key='.$id.'&test='.$ts.'">';
}
// Get patient, blood test, and comment keys
if (isset($_GET["key"]) and isset ($_GET["test"]) and isset ($_GET["com"])) {
    // Patient ID
    $id = $_GET["key"];
    // Blood test ID
    $t = $_GET["test"];
    // Comment ID
    $c = $_GET["com"];
    // Path in keystore
    $k = "$me:COM:$id:$t:$c";
    // Get comment
    $desc = $redis->hGet($k,'comment');
    // Close PHP Redis
    $redis->close();
    // Print editing form
    echo '<h1 class="text-center">Editing comment #'.$c.' of blood test #'.$id.'</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        Description: <input type="text" class="form-control" name="desc" value="'.$desc.'" /><br/>
        <input type="hidden" name="patkey"  value="'.$id.'">
        <input type="hidden" name="pattest"  value="'.$t.'">
        <input type="hidden" name="patcom"  value="'.$c.'">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
    </form>';
}



?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
