<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood tests taken</title>
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
if (isset($_POST["message"])  and isset($_POST["submit"]) and isset ($_POST["bltkey"])) {
    $id = $_POST["bltkey"];
    $desc = $_POST["message"];
    $time = new DateTime(date("Y-m-d H:i:s"));

    $blt = array(
        'last_comment' => 0,
        'desc' => $desc,
        'time' => $time->format(DateTime::ATOM)   # ISO8601 format
    );
    $blt_id = rand(10000000,19999999);
    $redis->hIncrBy("$me:PAT:$id",
        'last_bloodtest', 1);
    $key = "$me:BLT:$id:$blt_id";
    $redis->hMset($key, $blt);
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Added!</div>";
    echo "<br>";
}

if (isset($_GET["key"])) {
    $id = $_GET["key"];
    $id2 = "$me:BLT:$id:*";
    $key = $redis->keys($id2);
    $k = "$me:PAT:$id";
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    echo '<br>';
    echo '<h1 class="text-center">Blood tests of '.$fn.' '.$ln.' </h1>';
    echo '<br>';
    foreach ($key as $k){
        echo '<ul class="list-group d-grid col-6 mx-auto">';
        $t =   str_replace("$me:BLT:$id:" , "", $k);
        echo '<li class="list-group-item text-center">Blood Test ID: '. $t.'</li>';
        $lc = $redis->hGet($k, 'last_comment');
        echo '<li class="list-group-item text-center">Number of comments: '. $lc.'</li>';
        $tc = $redis->hGet($k, 'time');
        echo '<li class="list-group-item text-center">Date/time of creation: '. $tc.'</li>';
        $ds = $redis->hGet($k, 'desc');
        echo '<li class="list-group-item text-center">Description: '. $ds.'</li>';
        echo '<li class="list-group-item text-center">
        <a class="btn btn-primary" href="/comments.php?key='.$id.'&test='.$t.'" role="button">Comments</a>
        <a class="btn btn-primary" href="/editbltinfo.php?key='.$id.'&test='.$t.'" role="button">Edit blood test entry</a>
        <a class="btn btn-danger" href="/delblt.php?key='.$id.'&test='.$t.'" role="button">Delete blood test entry</a>
        </li>';

        echo '</ul>';
        echo "<br>";
    }
    $redis->close();
    echo '<h1 class="text-center">Add blood tests</h1>';
    echo '<br>';
    echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        Description: <input type="text" class="form-control" name="message" /> <br/><br/>
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
        <input type="hidden" name="bltkey"  value="'.$id.'">
    </form>';
    echo "<br>";
    echo '<div class="d-grid gap-2 col-6 mx-auto">';
    echo '<a class="btn btn-primary" href="/blt2csv.php?key='.$id.'" role="button">Export to CSV</a>';
    echo '<a class="btn btn-primary" href="/patient.php?key='.$id.'" role="button">Back</a>';
    echo '<a class="btn btn-primary" href="/" role="button">Home</a>';
    echo '</div>';

}else{
    $redis->close();
}

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
