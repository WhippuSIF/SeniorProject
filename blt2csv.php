<?php
require 'connection_silent.php';
// Get Patient ID
if (isset($_GET["key"])) {
    //Enable buffer
    ob_start();
    // Patient ID from GET request
    $patkey = $_GET["key"];
    //Path in keystore
    $id2 = "$me:PAT:$patkey";
    //First and last name
    $fn = $redis->hGet($id2, 'firstName');
    $ln = $redis->hGet($id2, 'lastName');
    // Filename formatting
    $filename = "".$fn."_".$ln."-blt-" . date('Y-m-d') . ".csv";
    // Download header
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    // Delimiter
    $delimiter = ",";
    // Hash Fields
    $fields = array('Blood test ID', 'Blood test description', 'No. of comments', 'Time created');
    // Blood test information array
    $redis_bltinfo = array();
    // Open output
    $output = fopen( 'php://output', 'w' );
    // Get keys for information
    $bltkey = $redis->keys("$me:BLT:$patkey:*");
    // Go through every key
    foreach($bltkey as $b) {
        // Take only blood test ID number
        $u = str_replace("$me:BLT:$patkey:", "", $b);
        // No. of comments
        $lc = $redis->hGet($b, 'last_comment');
        // Date and time of creation
        $tc = $redis->hGet($b, 'time');
        // Description
        $ds = $redis->hGet($b, 'desc');
        // Combine in hash array
        $redis_hashes = array($u, $ds, $lc, $tc);
        // Push to blood test info array
        array_push($redis_bltinfo, $redis_hashes);
    }
    //clean buffer
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
    //Close output
    fclose( $output );
    //Close PHP Redis
    $redis->close();
    die();
};
?>