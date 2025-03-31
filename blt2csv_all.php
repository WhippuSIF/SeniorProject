<?php
require 'connection_silent.php';
ob_start();
$filename = "bloodtests_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$delimiter = ",";
$fields = array('Patient ID', 'First name', 'Last name', 'Blood test ID', 'Blood test description', 'No. of comments', 'Time created');
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
        $lc = $redis->hGet($b, 'last_comment');
        $tc = $redis->hGet($b, 'time');
        $ds = $redis->hGet($b, 'desc');
        $redis_hashes = array($t, $fn, $ln, $u, $ds, $lc, $tc);
        array_push($redis_bltinfo, $redis_hashes);
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