<?php
require 'connection_silent.php';
// Start buffer
ob_start();
// Filename formatting
$filename = "comments_" . date('Y-m-d') . ".csv";
// Download header
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
// Delimiter
$delimiter = ",";
// Hash Fields
$fields = array('Patient ID', 'First name', 'Last name', 'Comment ID' , 'Blood test ID',  'Comment message', 'Time created');
// Comment information array
$redis_bltinfo = array();
// Get keys for information
$patkey = $redis->keys("$me:PAT:*");
// Open output
$output = fopen( 'php://output', 'w' );
// First Loop: Get all patient information
foreach($patkey as $k) {
    // Only Patient ID number
    $t =  str_replace("$me:PAT:" , "", $k);
    // First and last name
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    // Get blood test keys
    $bltkey = $redis->keys("$me:BLT:$t:*");
    // 2nd loop: Get all blood test information
    foreach($bltkey as $b) {
        // Only blood test ID number
        $u = str_replace("$me:BLT:$t:" , "", $b);
        // Get comment keys
        $comkey = $redis->keys("$me:COM:$t:$u:*");
        // 3rd loop: Get all comment keys
        foreach($comkey as $c) {
            // Only comment ID number
            $v = str_replace("$me:COM:$t:$u:" , "", $c);
            // Comment message
            $ms = $redis->hGet($c, 'comment');
            // Date and time of creation
            $tc = $redis->hGet($c, 'time');
            // Combine in hash array
            $redis_hashes = array($t, $fn, $ln, $v, $u, $ms, $tc);
            // Push to comment info array
            array_push($redis_bltinfo, $redis_hashes);
        }
    }
};
//Clean buffer
ob_end_clean();
//Open output again
$output = fopen( 'php://output', 'w' );
//Add hash fields and delimter to CSV
fputcsv( $output, $fields, $delimiter );
// Add all comments to the CSV
foreach( $redis_bltinfo as $r ){
    fputcsv( $output, $r );
}
//Close output CSV
fclose( $output );
//Close PHP Redis
$redis->close();
die();
?>