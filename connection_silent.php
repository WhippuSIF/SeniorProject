<?php
//file: connect.php
error_reporting( E_ALL );

// https://phpredis.github.io/phpredis/Redis.html
$me = 'wgbp3';			// Redis username on EC2 instance


//connect to redis server on the same EC2 instance
$redis = new Redis();
$redis->connect( 'localhost' );
$redis->auth( [$me, 'cheese'] );	// <-- change to your password

//check whether server is running or not
$redis->ping()
?>