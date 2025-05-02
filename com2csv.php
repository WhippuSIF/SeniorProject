<?php
require 'connection_silent.php';
if (isset($_GET["key"]) and isset($_GET["test"])) {
    // Start buffer
    ob_start();
    // Patient ID
    $patkey = $_GET["key"];
    // Blood test ID
    $bltkey = $_GET["test"];
    //Path in keystore
    $id2 = "$me:PAT:$patkey";
    //First and last name
    $fn = $redis->hGet($id2, 'firstName');
    $ln = $redis->hGet($id2, 'lastName');
    // Filename formatting
    $filename = "".$fn."_".$ln."-".$bltkey."-com-" . date('Y-m-d') . ".csv";
    // Download header
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    // Delimiter
    $delimiter = ",";
    // Hash Fields
    $fields = array('Comment ID', 'Message', 'Time created');
    // Comment information array
    $redis_bltinfo = array();
    // Open output
    $output = fopen( 'php://output', 'w' );
    // Get keys for information
    $comkey = $redis->keys("$me:COM:$patkey:$bltkey:*");
    // Go through every key
    foreach($comkey as $c) {
        // Only comment ID number
        $u = str_replace("$me:COM:$patkey:$bltkey:", "", $c);
        // Comment
        $cm = $redis->hGet($c, 'comment');
        // Date and time of creation
        $tm = $redis->hGet($c, 'time');
        // Combine in hash array
        $redis_hashes = array($u, $cm, $tm);
        // Push to comment info array
        array_push($redis_bltinfo, $redis_hashes);
    }
    //clean buffer
    ob_end_clean();
    // Open output again
    $output = fopen( 'php://output', 'w' );
    //Add the hash fields and demlimiter to CSV
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