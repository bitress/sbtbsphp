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
    <title>Bus Schedules</title>
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
        <h2>Bus Schedules</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Bus ID</th>
                    <th>Departure Date</th>
                    <th>Departure Time</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($route = mysqli_fetch_assoc($routeResult)): ?>
                    <tr>
                        <td><?php echo $route['route_cities']; ?></td>
                        <td><?php echo $route['bus_no']; ?></td>
                        <td><?php echo $route['route_dep_date']; ?></td>
                        <td><?php echo $route['route_dep_time']; ?></td>
                        <td><?php echo $route['route_step_cost']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if (!$routeResult) {
    echo "Error fetching bus schedules: " . mysqli_error($conn);
}

mysqli_close($conn);
?>