<?php
require 'connection_silent.php';
ob_start();
$filename = "patients_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$delimiter = ",";
$fields = array('Patient ID', 'First name', 'Last name', 'Country', 'Date of birth', 'No. of Blood tests');
$redis_patinfo = array();
$key = $redis->keys("$me:PAT:*");
$output = fopen( 'php://output', 'w' );
foreach($key as $k) {
    $t =  str_replace("$me:PAT:" , "", $k);
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    $cn = $redis->hGet($k,'country');
    $cr = $redis->hGet($k,'birthdtc');
    $cb = $redis->hGet($k,'last_bloodtest');

    $redis_hashes = array($t, $fn, $ln, $cn, $cr, $cb);
    array_push($redis_patinfo, $redis_hashes);
};
ob_end_clean();
$output = fopen( 'php://output', 'w' );
fputcsv( $output, $fields, $delimiter );

foreach( $redis_patinfo as $r ){
    fputcsv( $output, $r );
}
fclose( $output );
$redis->close();
die();
?>