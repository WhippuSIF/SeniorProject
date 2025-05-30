<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Patient</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<br>
<?php
require 'connection.php';
// Get info from POST requests
if (isset($_POST['fnm']) and isset($_POST['lnm']) and isset($_POST['country']) and isset($_POST['submit'])) {
    // First/last name
    $fname = $_POST['fnm'];
    $lname = $_POST['lnm'];
    // Country
    $country = $_POST['country'];
    // make array
    $dm = array(
        'firstName' => $fname,
        'lastName' => $lname,
        'birthdtc' => date(DATE_ATOM),  # ISO8601 format
        'country' => $country,
        'last_bloodtest' => 0
    );
    // assign random ID number
    $patientid = rand(10000000,19999999);
    // increase number of patients by 1
    $redis->incr("$me:last_patientid");
    // Path in keystore
    $key = "$me:PAT:$patientid";
    // add array as a patient
    $redis->hMset($key, $dm);
    // Close PHP Redis
    $redis->close();
    // Print "Added!" message
    echo "<div class='alert alert-success d-grid col-6 mx-auto' role='alert'>Added!</div>";
    // Redirect to index page
    echo "<meta http-equiv='refresh' content='3; URL=/'>";
}

echo '<h1 class="text-center">Add Patient</h1>';
echo '<br>';
echo ' <form class="d-grid gap-2 col-6 mx-auto" method="post" action="" >
        First Name: <input type="text" class="form-control" name="fnm" /><br/>
        Last Name: <input type="text" class="form-control" name="lnm" /><br/>
        Country: <input type="text" class="form-control" name="country" /><br/>
        <input type="submit" class="btn btn-primary" value="Submit" name="submit" />
    </form>';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
