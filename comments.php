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

<?php
require "connection.php";
if (isset($_POST["message"])  and isset($_POST["submit"]) and isset ($_POST["comkey"]) and isset($_POST["comtest"])) {
    $id = $_POST["comkey"];
    $test = $_POST["comtest"];
    $desc = $_POST["message"];
    $time = new DateTime(date("Y-m-d H:i:s"));

    $com = array(
        'comment' => $desc,
        'time' => $time->format(DateTime::ATOM)   # ISO8601 format
    );
    $last_comment = $redis->hIncrBy("$me:BLT:$id:$test",
        'last_comment', 1);
    $key = "$me:COM:$id:$test:$last_comment";
    $redis->hMset($key, $com);
    echo "Added!";
    echo "<br>";
}

if (isset($_GET["key"]) and isset($_GET["test"])) {
    $id = $_GET["key"];
    $test = $_GET["test"];
    $id2 = "$me:COM:$id:$test:*";
    $key = $redis->keys($id2);
    $k = "$me:PAT:$id";
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    echo '<br>';
    echo '<h1 class="text-center">Comments for blood test #'.$test.' of '.$fn.' '.$ln.' </h1>';
    echo '<br>';
    foreach ($key as $i){
        echo '<ul class="list-group d-grid col-6 mx-auto">';
        $c =   str_replace("$me:COM:$id:$test:" , "", $i);
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Comment ID: '. $c.'</li>';
        $tc = $redis->hGet($i,'time');
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Date/time of creation: '. $tc.'</li>';
        $cm = $redis->hGet($i,'comment');
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">Comment: '.$cm.'</li>';
        echo '<li class="list-group-item text-center">
        <a class="btn btn-primary" href="/editcominfo.php?key='.$id.'&test='.$test.'&com='.$c.'" role="button">Edit comment</a>
        <a class="btn btn-danger" href="/delcom.php?key='.$id.'&test='.$test.'&com='.$c.'" role="button">Delete comment</a>
        </li>';

        echo '</ul>';
        echo "<br>";

    }
    $redis->close();
    echo '<h1 class="text-center">Add Comment</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        Description: <input type="text" class="form-control" name="message" /> <br/><br/>
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
        <input type="hidden" name="comkey"  value="'.$_GET["key"].'">
        <input type="hidden" name="comtest"  value="'.$_GET["test"].'">
    </form>';
    echo "<br>";
    echo '<div class="d-grid gap-2 col-6 mx-auto">';
    echo '<a class="btn btn-primary" href="/bloodtests.php?key='. $_GET["key"].'" role="button">Back</a>';
    echo '<a class="btn btn-primary" href="/patient.php?key='. $_GET["key"].'" role="button">Patient info</a>';
    echo '<a class="btn btn-primary" href="/" role="button">Home</a>';
    echo '</div>';
} else {
    $redis->close();
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
