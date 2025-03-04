<?php
// Database connection function
function db_connect() {
    // Database configuration
    $host = 'localhost'; // Change this to your database host
    $user = 'root';      // Change this to your database username
    $password = '';      // Change this to your database password
    $database = 'sbtbsphp'; // Change this to your database name

    // Create a connection
    $conn = mysqli_connect($host, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

// Function to fetch all routes
function getAllRoutes($conn) {
    $sql = "SELECT * FROM routes";
    $result = mysqli_query($conn, $sql);
    $routes = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $routes[] = $row;
    }

    return $routes;
}

// Function to fetch booked seats for a specific bus
function getBookedSeats($conn, $bus_no) {
    $sql = "SELECT seat_booked FROM seats WHERE bus_no = '$bus_no'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['seat_booked'] ? explode(',', $row['seat_booked']) : [];
}

// Function to insert a booking into the database
function insertBooking($conn, $customer_name, $customer_phone, $booked_seat, $route_id, $pnr_code) {
    $sql = "INSERT INTO bookings (customer_name, customer_phone, booked_seat, route_id, booking_created, booking_id) 
            VALUES ('$customer_name', '$customer_phone', '$booked_seat', '$route_id', current_timestamp(), '$pnr_code')";
    
    return mysqli_query($conn, $sql);
}

// Function to generate a unique PNR code
function generatePNR() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Generate a unique PNR code
}

// Function to fetch all bookings (for admin side)
function getAllBookings($conn) {
    $sql = "SELECT * FROM bookings";
    $result = mysqli_query($conn, $sql);
    $bookings = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }

    return $bookings;
}


// Function to close the database connection
function closeConnection($conn) {
    mysqli_close($conn);
}
?>