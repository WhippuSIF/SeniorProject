<?php
require 'connection_silent.php';
//Enable buffer
ob_start();
//Filename formatting
$filename = "bloodtests_" . date('Y-m-d') . ".csv";
// Download header
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
// Delimiter
$delimiter = ",";
// Hash Fields
$fields = array('Patient ID', 'First name', 'Last name', 'Blood test ID', 'Blood test description', 'No. of comments', 'Time created');
// Blood test information array
$redis_bltinfo = array();
// Get keys for information
$patkey = $redis->keys("$me:PAT:*");
// open output
$output = fopen( 'php://output', 'w' );
// Go through every key
foreach($patkey as $k) {
    // Take only patient ID number
    $t =  str_replace("$me:PAT:" , "", $k);
    // First and last name
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    // Get blood test keys
    $bltkey = $redis->keys("$me:BLT:$t:*");
    // Go through every blood test key
    foreach($bltkey as $b) {
        // Take only the blood test ID number
        $u = str_replace("$me:BLT:$t:" , "", $b);
        // No. of comments
        $lc = $redis->hGet($b, 'last_comment');
        // Date and time of creation
        $tc = $redis->hGet($b, 'time');
        // Description
        $ds = $redis->hGet($b, 'desc');
        // Combine in hash array
        $redis_hashes = array($t, $fn, $ln, $u, $ds, $lc, $tc);
        // Push to blood test info array
        array_push($redis_bltinfo, $redis_hashes);
    }
};
// Clean buffer
ob_end_clean();
// Open output again
$output = fopen( 'php://output', 'w' );
//Add the hash fields and delimiter to CSV
fputcsv( $output, $fields, $delimiter );
// Add all info from array
foreach( $redis_bltinfo as $r ){
    //Append to CSV file
    fputcsv( $output, $r );
}
// Close output
fclose( $output );
// Close PHP Redis
$redis->close();
die();
?>