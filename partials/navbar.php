<?php
require 'connection.php';
// Get patient keys
$key = $redis->keys("$me:PAT:*");
// print basic navbar
echo '<nav class="navbar navbar-expand-lg bg-body-tertiary>';
echo '<div class="container-fluid">';
echo '<a class="navbar-brand" href="#">BTWA</a>';
echo '<div class="collapse navbar-collapse" id="navbarNav">';
echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
echo '<li>';
//link to home page
echo '<a class="nav-link" href="../index.php">Home</a>';
echo '</li>';
echo '<div class="nav-item dropdown">';
// link lising patients
echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
echo 'Patients';
echo '</a>';
echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
// Go through each patient key
foreach($key as $k) {
    // Get only the Patient ID number
    $t =  str_replace("$me:PAT:" , "", $k);
    // First/last name
    $fn = $redis->hGet($k,'firstName');
    $ln = $redis->hGet($k,'lastName');
    // print link to patient as a dropdown menu item
    echo '<a class="dropdown-item" href="/patient.php?key='.$t.'">'.$fn.' '.$ln.'</a>';
}
echo '</div>';
echo '</li>';
echo '</ul>';
echo '</div>';
echo '</div>';
echo '</nav>';
?>