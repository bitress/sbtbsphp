<?php
require 'assets/partials/_functions.php';
$conn = db_connect();

if(!$conn) 
    die("Connection Failed");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {
    // Handle booking submission
    $customer_name = $_POST["cname"];
    $customer_phone = $_POST["cphone"];
    $route_id = $_POST["route_id"];
    $booked_seat = $_POST["seatInput"];
    $amount = $_POST["bookAmount"];
    $pnr_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Generate a unique PNR code

    // Insert into database
    $sql = "INSERT INTO bookings (customer_name, customer_phone, booked_seat, route_id, booking_created, booking_id) 
            VALUES ('$customer_name', '$customer_phone', '$booked_seat', '$route_id', current_timestamp(), '$pnr_code')";
    
    if(mysqli_query($conn, $sql)){
        echo "Booking successful! Your PNR is: " . $pnr_code;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Bus Reservation Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="mb-3">
                <label for="cname" class="form-label">Name</label>
                <input type="text" class="form-control" id="cname" name="cname" required>
            </div>
            <div class="mb-3">
                <label for="cphone" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="cphone" name="cphone" required>
            </div>
            <div class="mb-3">
                <label for="route" class="form-label">Departure Route</label>
                <select class="form-select" id="route" name="route_id" required>
                    <option value="" disabled selected>Select a route</option>
                    <?php 
                    $routeSql = "SELECT * FROM routes";
                    $routeResult = mysqli_query($conn, $routeSql);
                    while ($route = mysqli_fetch_assoc($routeResult)): ?>
                        <option value="<?php echo $route['route_id']; ?>" data-busno="<?php echo $route['bus_no']; ?>" data-deptime="<?php echo $route['route_dep_time']; ?>" data-cost="<?php echo $route['route_step_cost']; ?>">
                            <?php echo $route['route_cities']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="seatInput" class="form-label">Seat Number</label>
                <select class="form-select" id="seatInput" name="seatInput" required>
                    <option value="" disabled selected>Select a seat</option>
                    <!-- Populate available seats dynamically based on selected route -->
                </select>
            </div>
            <div class="mb-3">
                <label for="bookAmount" class="form-label">Total Amount</label>
                <input type="text" class="form-control" id="bookAmount" name="bookAmount" readonly>
            </div>
            <button type="submit" class="btn btn-primary" name="book">Book Now</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('route').addEventListener('change', function() {
            const busNo = this.options[this.selectedIndex].dataset.busno;
            const routeId = this.value;
            const stepCost = this.options[this.selectedIndex].dataset.cost;

            // Fetch available seats for the selected route
            fetch('fetch_seat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ bus_no: busNo })
            })
            .then(response => response.json())
            .then(data => {
                const seatInput = document.getElementById('seatInput');
                seatInput.innerHTML = ''; // Clear previous options
                for (let i = 1; i <= 38; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = i;
                    if (data.includes(i.toString())) {
                        option.disabled = true; // Disable booked seats
                    }
                    seatInput.appendChild(option);
                }
                // Set the amount
                document.getElementById('bookAmount').value = stepCost;
            });
        });
    </script>
</body>
</html>