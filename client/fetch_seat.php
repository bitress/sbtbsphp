<?php
require '../assets/partials/_functions.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $bus_no = $data->bus_no;

    $sql = "SELECT seat_booked FROM seats WHERE bus_no = '$bus_no'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $bookedSeats = $row['seat_booked'] ? explode(',', $row['seat_booked']) : [];

    echo json_encode($bookedSeats);
}
?>