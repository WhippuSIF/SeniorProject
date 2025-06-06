<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Test Comments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<br>
<?php
require "connection.php";
// Adding comments
if (isset($_POST["message"])  and isset($_POST["submit"]) and isset ($_POST["comkey"]) and isset($_POST["comtest"])) {
    // Patient ID
    $id = $_POST["comkey"];
    // Blood Test ID
    $test = $_POST["comtest"];
    // Comment Message
    $desc = $_POST["message"];
    // Time / date of creation
    $time = new DateTime(date("Y-m-d H:i:s"));
    // Make array
    $com = array(
        'comment' => $desc,
        'time' => $time->format(DateTime::ATOM)   # ISO8601 format
    );
    // random ID
    $com_id = rand(10000000,19999999);
    // increase number of comments by 1
    $redis->hIncrBy("$me:BLT:$id:$test",
        'last_comment', 1);
    // path to comment in keystore
    $key = "$me:COM:$id:$test:$com_id";
    // append
    $redis->hMset($key, $com);
    // Print added meesage
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Added!</div>";
    echo "<br>";
}
// check for Patient ID and Blood test ID
if (isset($_GET["key"]) and isset($_GET["test"])) {
    // Patient ID
    $id = $_GET["key"];
    // Blood test ID
    $test = $_GET["test"];
    // Path to comments in keystore
    $id2 = "$me:COM:$id:$test:*";
    // Find comment paths
    $key = $redis->keys($id2);
    // Path for patient info
    $k = "$me:PAT:$id";
    // First and last name
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    //Print information
    echo '<br>';
    echo '<h1 class="text-center">Comments for blood test #'.$test.' of '.$fn.' '.$ln.' </h1>';
    echo '<br>';
    foreach ($key as $i){

        echo '<ul class="list-group d-grid col-6 mx-auto">';
        // Comment ID
        $c =   str_replace("$me:COM:$id:$test:" , "", $i);
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Comment ID: '. $c.'</li>';
        // Date and time
        $tc = $redis->hGet($i,'time');
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Date/time of creation: '. $tc.'</li>';
        // Comment message
        $cm = $redis->hGet($i,'comment');
        //Buttons
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Comment: '.$cm.'</li>';
        echo '<li class="list-group-item text-center">
        <a class="btn btn-primary" href="/editcominfo.php?key='.$id.'&test='.$test.'&com='.$c.'" role="button">Edit comment</a>
        <a class="btn btn-danger" href="/delcom.php?key='.$id.'&test='.$test.'&com='.$c.'" role="button">Delete comment</a>
        </li>';


        echo '</ul>';
        echo "<br>";

    }
    // Close PHP Redis
    $redis->close();
    // Form for adding comments
    echo '<h1 class="text-center">Add Comment</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        Description: <input type="text" class="form-control" name="message" /> <br/><br/>
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
        <input type="hidden" name="comkey"  value="'.$id.'">
        <input type="hidden" name="comtest"  value="'.$test.'">
    </form>';
    echo "<br>";
    echo '<div class="d-grid gap-2 col-6 mx-auto">';
    echo '<a class="btn btn-primary" href="/com2csv.php?key='.$id.'&test='.$test.'" role="button">Export as CSV</a>';
    echo '<a class="btn btn-primary" href="/bloodtests.php?key='.$id.'" role="button">Back</a>';
    echo '<a class="btn btn-primary" href="/patient.php?key='.$id.'" role="button">Patient info</a>';
    echo '<a class="btn btn-primary" href="/" role="button">Home</a>';
    echo '</div>';
} else {
    // Close PHP Redis
    $redis->close();
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
