<?php 
require '_functions.php';
$conn = db_connect();    

if(!$conn) 
    die("Connection Failed");


function generateCustomerID($conn) {
    $sql = "SELECT customer_id FROM customers ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['customer_id']; 
        preg_match('/\d+/', $last_id, $matches);
        $next_number = intval($matches[0]) + 1; 
    } else {
        $next_number = 1; 
    }

    return "CUST-" . str_pad($next_number, 5, '0', STR_PAD_LEFT);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {

    // Handle customer submission
    $customer_id = generateCustomerID($conn);
    $customer_name = isset($_POST["cname"]) ? $_POST["cname"] : null;
    $customer_phone = isset($_POST["cphone"]) ? $_POST["cphone"] : null;

    $sql = "INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_phone`, `customer_created`) VALUES ('$customer_id', '$customer_name', '$customer_phone', current_timestamp())";
    if(mysqli_query($conn, $sql)){
      

         
    // Handle booking submission

    $route_id = isset($_POST["route_id"]) ? $_POST["route_id"] : null;

    list($route, $routes) = explode("|", $route_id);


    $route_source = isset($_POST["sourceSearch"]) ? $_POST["sourceSearch"] : null;
    $route_destination = isset($_POST["destinationSearch"]) ? $_POST["destinationSearch"] : null;
    $booked_seat = isset($_POST["seatInput"]) ? $_POST["seatInput"] : null;
    $amount = isset($_POST["bookAmount"]) ? $_POST["bookAmount"] : null;
    $pnr_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Generate a unique PNR code

    // Insert into database
    $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`, `booking_id`) VALUES ('$customer_id', '$route','$routes', '$amount', '$booked_seat', current_timestamp(), '$pnr_code')";
    
    if(mysqli_query($conn, query: $sql)){
        header("Location: ../../../client/index.php?booking_added=true&pnr=$pnr_code");
    } else {
        echo "Error: " . mysqli_error($conn);
    }


    } else {
        echo "Error: " . mysqli_error($conn);
    }


   
}
?>