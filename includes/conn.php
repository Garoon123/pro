<?php
$conn = mysqli_connect("localhost", "root", "", "garoon");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
