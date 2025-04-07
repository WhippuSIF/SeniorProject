<?php
require 'connection_silent.php';
if (isset($_GET["key"]) and isset($_GET["test"])) {
    ob_start();
    $patkey = $_GET["key"];
    $bltkey = $_GET["test"];
    $id2 = "$me:PAT:$patkey";
    $fn = $redis->hGet($id2, 'firstName');
    $ln = $redis->hGet($id2, 'lastName');
    $filename = "".$fn."_".$ln."-".$bltkey."-com-" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    $delimiter = ",";
    $fields = array('Comment ID', 'Message', 'Time created');
    $redis_bltinfo = array();
    $output = fopen( 'php://output', 'w' );
    $comkey = $redis->keys("$me:COM:$patkey:$bltkey:*");
    foreach($comkey as $c) {
        $u = str_replace("$me:COM:$patkey:$bltkey:", "", $c);
        $cm = $redis->hGet($c, 'comment');
        $tm = $redis->hGet($c, 'time');
        $redis_hashes = array($u, $cm, $tm);
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