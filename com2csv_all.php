<?php
require 'connection_silent.php';
ob_start();
$filename = "comments_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$delimiter = ",";
$fields = array('Patient ID', 'First name', 'Last name', 'Comment ID' , 'Blood test ID',  'Comment message', 'Time created');
$redis_bltinfo = array();
$patkey = $redis->keys("$me:PAT:*");
$output = fopen( 'php://output', 'w' );
foreach($patkey as $k) {
    $t =  str_replace("$me:PAT:" , "", $k);
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    $bltkey = $redis->keys("$me:BLT:$t:*");
    foreach($bltkey as $b) {
        $u = str_replace("$me:BLT:$t:" , "", $b);
        $comkey = $redis->keys("$me:COM:$t:$u:*");
        foreach($comkey as $c) {
            $v = str_replace("$me:COM:$t:$u:" , "", $c);
            $ms = $redis->hGet($c, 'comment');
            $tc = $redis->hGet($c, 'time');
            $redis_hashes = array($t, $fn, $ln, $v, $u, $ms, $tc);
            array_push($redis_bltinfo, $redis_hashes);
        }
    }
};
ob_end_clean();
$output = fopen( 'php://output', 'w' );
fputcsv( $output, $fields, $delimiter );

foreach( $redis_bltinfo as $r ){
    fputcsv( $output, $r );
}
fclose( $output );
$redis->close();
die();
?>