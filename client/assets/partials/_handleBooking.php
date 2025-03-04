<?php 
require '_functions.php';
$conn = db_connect();    

if(!$conn) 
    die("Connection Failed");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {
    // Handle booking submission
    $customer_id = isset($_POST["cid"]) ? $_POST["cid"] : null;
    $route_id = isset($_POST["route_id"]) ? $_POST["route_id"] : null;
    $route_source = isset($_POST["sourceSearch"]) ? $_POST["sourceSearch"] : null;
    $route_destination = isset($_POST["destinationSearch"]) ? $_POST["destinationSearch"] : null;
    $booked_seat = isset($_POST["seatInput"]) ? $_POST["seatInput"] : null;
    $amount = isset($_POST["bookAmount"]) ? $_POST["bookAmount"] : null;
    $pnr_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Generate a unique PNR code

    // Insert into database
    $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`, `booking_id`) VALUES ('$customer_id', '$route_id','$route', '$amount', '$booked_seat', current_timestamp(), '$pnr_code')";
    
    if(mysqli_query($conn, $sql)){
        header("location: ../../client/index.php?booking_added=true&pnr=$pnr_code");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>