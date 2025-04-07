<?php
require 'connection_silent.php';
if (isset($_GET["key"])) {
    ob_start();
    $patkey = $_GET["key"];
    $id2 = "$me:PAT:$patkey";
    $fn = $redis->hGet($id2, 'firstName');
    $ln = $redis->hGet($id2, 'lastName');
    $filename = "".$fn."_".$ln."-blt-" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    $delimiter = ",";
    $fields = array('Blood test ID', 'Blood test description', 'No. of comments', 'Time created');
    $redis_bltinfo = array();
    $output = fopen( 'php://output', 'w' );
    $bltkey = $redis->keys("$me:BLT:$patkey:*");
    foreach($bltkey as $b) {
        $u = str_replace("$me:BLT:$patkey:", "", $b);
        $lc = $redis->hGet($b, 'last_comment');
        $tc = $redis->hGet($b, 'time');
        $ds = $redis->hGet($b, 'desc');
        $redis_hashes = array($u, $ds, $lc, $tc);
        array_push($redis_bltinfo, $redis_hashes);
    }
    ob_end_clean();
    $output = fopen( 'php://output', 'w' );
    fputcsv( $output, $fields, $delimiter );

    foreach( $redis_bltinfo as $r ){
        fputcsv( $output, $r );
    }
    fclose( $output );
    $redis->close();
    die();
};
?>