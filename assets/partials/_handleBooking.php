<?php 
require '_functions.php';
$conn = db_connect();    

if(!$conn) 
    die("Connection Failed");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {
    // Handle booking submission
    $customer_name = ucfirst($_POST["firstName"]) . " " . ucfirst($_POST["lastName"]);
    $customer_phone = $_POST["phone"];
    $route_id = $_POST["route_id"];
    $booked_seat = $_POST["seatInput"];
    $amount = $_POST["booked_amount"];
    $pnr_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Generate a unique PNR code

    // Check if the customer already exists
    $id_customer_exists = exist_customers($conn, $customer_name, $customer_phone);
    $customer_added = false;

    if (!$id_customer_exists) {
        // Insert new customer into the database
        $sql = "INSERT INTO `customers` (`customer_name`, `customer_phone`, `customer_created`) VALUES ('$customer_name', '$customer_phone', current_timestamp());";
        $result = mysqli_query($conn, $sql);

        // Get the auto-incremented ID
        $autoInc_id = mysqli_insert_id($conn);
        if ($autoInc_id) {
            // Generate a unique customer_id
            $code = rand(1, 99999);
            $customer_id = "CUST-" . $code . $autoInc_id;

            // Update the customer record with the generated customer_id
            $query = "UPDATE `customers` SET `customer_id` = '$customer_id' WHERE `customers`.`id` = $autoInc_id;";
            mysqli_query($conn, $query);
        }

        if ($result) {
            $customer_added = true;
        }
    } else {
        // If the customer exists, retrieve their ID
        $customer_id = $id_customer_exists;
    }

    // Insert into bookings table with customer_id
    $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`, `booking_id`) 
            VALUES ('$customer_id', '$route_id', '$customer_route', '$amount', '$booked_seat', current_timestamp(), '$pnr_code')";
    
    if(mysqli_query($conn, $sql)){
        header("location: ../../client/index.php?booking_added=true&pnr=$pnr_code");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>