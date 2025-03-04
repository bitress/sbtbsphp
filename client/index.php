<?php
require 'assets/partials/_functions.php';
$conn = db_connect();

if(!$conn) 
    die("Connection Failed");

$routeSql = "SELECT * FROM routes";
$routeResult = mysqli_query($conn, $routeSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/styles.css">
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="#" class="nav-item nav-logo">Byahero BusLiner</a>
            </div>
            <ul>
                <li><a href="index.php" class="nav-item">Home</a></li>
                <li><a href="bus_schedules.php" class="nav-item">Bus Schedules</a></li>
                <li><a href="about.php" class="nav-item">About</a></li>
                <li><a href="contact.php" class="nav-item">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="home">
            <h1>Welcome to Byahero BusLiner</h1>
            <button class="btn btn-primary" id="reserveBtn">Reserve Now</button>
        </section>

        <section id="reservationForm" style="display:none;">
            <h2>Reservation Form</h2>
            <form id="bookingForm" action="assets/partials/_handleBooking.php" method="POST">
                <div class="mb-3">
                    <label for="cname" class="form-label">Name</label>
                    <input type="text" class="form-control" id="cname" name="cname" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="cphone" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" id="cphone" name="cphone" required>
                </div>
                <div class="mb-3">
                    <label for="route" class="form-label">Departure Route</label>
                    <select class="form-select" id="route" name="route_id" required>
                        <option value="" disabled selected>Select a route</option>
                        <?php while ($route = mysqli_fetch_assoc($routeResult)): ?>
                            <option value="<?php echo $route['route_id']; ?>|<?php echo $route['route_cities']; ?>" data-busno="<?php echo $route['bus_no']; ?>" data-deptime="<?php echo $route['route_dep_time']; ?>" data-cost="<?php echo $route['route_step_cost']; ?>">
                                <?php echo $route['route_cities']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bus_no" class="form-label">Bus Number</label>
                    <input type="text" class="form-control" id="bus_no" name="bus_no" readonly>
                </div>
                <div class="mb-3">
                    <label for="seatInput" class="form-label">Seat Number</label>
                    <select class="form-select" id="seatInput" name="seatInput" required>
                        <option value="" disabled selected>Select a seat</option>
                        <!-- Populate available seats dynamically based on selected route -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bookAmount" class="form-label">Total Amount(₱)</label>
                    <input type="text" class="form-control" id="bookAmount" name="bookAmount" readonly>
                </div>
                <button type="submit" class="btn btn-primary" name="book">Reserve</button>
            </form>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/main.js"></script>
</body>
</html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/main.js"></script>
    <script>
        document.getElementById('reserveBtn').addEventListener('click', function() {
            document.getElementById('reservationForm').style.display = 'block';
        });

        document.getElementById('route').addEventListener('change', function() {
            const busNo = this.options[this.selectedIndex].dataset.busno;
            const stepCost = this.options[this.selectedIndex].dataset.cost;

            document.getElementById('bus_no').value = busNo;
            document.getElementById('bookAmount').value = '₱' + stepCost;
        });
    </script>
</body>
</html>