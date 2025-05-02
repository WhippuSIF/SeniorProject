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
<?php include 'partials/navbar.php'; ?>
<br>
<?php
require 'connection.php';
// Get all info from POST requests
if (isset($_POST['fnm']) and isset($_POST['lnm']) and isset($_POST['country']) and isset($_POST['submit']) and isset($_POST['patkey'])) {
    // First and last name
    $fname = $_POST['fnm'];
    $lname = $_POST['lnm'];
    // Country
    $country = $_POST['country'];
    // Patient ID
    $id = $_POST['patkey'];
    // Path in keystore
    $key = "$me:PAT:$id";
    //Make the changes
    $redis->hSet($key, 'firstName',$fname);
    $redis->hSet($key, 'lastName',$lname);
    $redis->hSet($key, 'country',$country);
    // Close PHP Redis
    $redis->close();
    // Print "Edited!" message
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Edited!</div>";
    // Redirect to patient page
    echo '<meta http-equiv="refresh" content="3;URL=/patient.php?key='.$id.'">';
}
//Get patient ID
if (isset($_GET["key"])) {
    // Patient ID
    $id = $_GET["key"];
    // Path in keystore
    $k = "$me:PAT:$id";
    // First and last name
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    // Country
    $country = $redis->hGet($k,'country');
    // Close PHP Redis
    $redis->close();
    // Print editing form
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
