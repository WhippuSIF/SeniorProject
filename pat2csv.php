<?php
require 'connection_silent.php';
//Enable buffer
ob_start();
//File name formatting
$filename = "patients_" . date('Y-m-d') . ".csv";
//HTTP header
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
//Delimiter
$delimiter = ",";
//Data fields
$fields = array('Patient ID', 'First name', 'Last name', 'Country', 'Date of birth', 'No. of Blood tests');
//Array for patient info
$redis_patinfo = array();
// Get patient data
$key = $redis->keys("$me:PAT:*");
//Output to CSV
$output = fopen( 'php://output', 'w' );
// Add every patient
foreach($key as $k) {
    // Leave only the ID number
    $t =  str_replace("$me:PAT:" , "", $k);
    //First name
    $fn = $redis->hGet($k,'firstName');
    //Last name
    $ln = $redis->hGet($k,'lastName');
    //Country
    $cn = $redis->hGet($k,'country');
    //Date of birth
    $cr = $redis->hGet($k,'birthdtc');
    //No. of blood tests taken
    $cb = $redis->hGet($k,'last_bloodtest');
    //Combine
    $redis_hashes = array($t, $fn, $ln, $cn, $cr, $cb);
    //Merge w/ labels
    array_push($redis_patinfo, $redis_hashes);
};
//Clean buffer
ob_end_clean();
//End output
$output = fopen( 'php://output', 'w' );
//Add output, fields, and delimtier
fputcsv( $output, $fields, $delimiter );
//Add patient information
foreach( $redis_patinfo as $r ){
    fputcsv( $output, $r );
}
//Close output
fclose( $output );
//Close PHP Redis
$redis->close();
die();
?>