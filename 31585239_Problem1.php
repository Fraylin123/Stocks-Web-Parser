<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://localhost:27017");
$collection = $client->data->most_active_stocks;

$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'Index'; #Get the category to sort
$sortDirection = isset($_GET['dir']) && $_GET['dir'] == 'desc' ? -1 : 1;

$cursor = $collection->find([], ['sort' => [$sortField => $sortDirection]]);  


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Active Stocks</title>
    <style>
        th, td { border: 1px solid #ddd; }
        th { cursor: pointer; }
    </style>
</head>
<body>
    <h1 style="text-align:center">Most Active Stocks</h1>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=Index&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Index</a></th>
                <th><a href="?sort=Symbol&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Symbol</a></th>
                <th><a href="?sort=Name&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Name</a></th>
                <th><a href="?sort=Price (Introday)&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Price (Introday)</a></th>
                <th><a href="?sort=Change&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Change</a></th>
                <th><a href="?sort=Volume&dir=<?php echo ($sortDirection == 1) ? 'desc' : 'asc'; ?>">Volume</a></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($cursor as $row){
                    echo "<tr>";
                    echo "<td>" . $row['Index'] . "</td>";
                    echo "<td>" . $row['Symbol'] . "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Price (Introday)'] . "</td>";
                    echo "<td>" . $row['Change'] . "</td>";
                    echo "<td>" . $row['Volume'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
