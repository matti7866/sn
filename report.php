<?php  
include 'header.php';  
include 'connection.php';
?>  
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>  
<link rel="preconnect" href="https://fonts.googleapis.com">  
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>  
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">  
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">  
<title>Statistics</title>  
<?php  
include 'nav.php';  
if (!isset($_SESSION['user_id'])) {  
    header('location:login.php');  
    exit();  
}  

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

$startYear = 2025;  
$endYear = 2025;  
$currentMonth = date('n');  

// Residences query
$query_residences = "SELECT MONTH(datetime) AS month, YEAR(datetime) AS year, COUNT(*) AS number_of_residences  
                     FROM residence  
                     WHERE YEAR(datetime) BETWEEN $startYear AND $endYear  
                     GROUP BY year, month  
                     ORDER BY year, month";  
$result_residences = $conn->query($query_residences);  

$months = [];  
$counts_residences = [];  
$sales_by_month = array_fill(1, 12, 0);  

if ($result_residences) {  
    while ($row = $result_residences->fetch_assoc()) {  
        $month = $row['month'];  
        $numberOfResidences = $row['number_of_residences'];  
        $months[] = date("F", mktime(0, 0, 0, $month, 1));  
        $counts_residences[$month] = $numberOfResidences;  
    }  
} else {  
    error_log("Residences query failed: " . $conn->error);  
    echo "Error fetching residence data.";  
}  

// Tickets query
$query_tickets = "SELECT MONTH(datetime) AS month, COUNT(*) AS number_of_sales  
                  FROM ticket  
                  WHERE YEAR(datetime) = $endYear AND MONTH(datetime) <= $currentMonth  
                  GROUP BY month  
                  ORDER BY month";  
$result_tickets = $conn->query($query_tickets);  

if ($result_tickets) {  
    while ($row = $result_tickets->fetch_assoc()) {  
        $month = $row['month'];  
        $numberOfSales = $row['number_of_sales'];  
        $sales_by_month[$month] = $numberOfSales;  
    }  
} else {  
    error_log("Tickets query failed: " . $conn->error);  
    echo "Error fetching ticket data.";  
}  

// Expenses query
$query_expenses = "SELECT MONTH(time_creation) AS month, SUM(expense_amount) AS total_expenses  
                   FROM expense  
                   WHERE YEAR(time_creation) BETWEEN $startYear AND $endYear  
                   GROUP BY month  
                   ORDER BY month";  
$result_expenses = $conn->query($query_expenses);  

$expenses_by_month = array_fill(1, 12, 0);  

if ($result_expenses) {  
    while ($row = $result_expenses->fetch_assoc()) {  
        $month = $row['month'];  
        $totalExpenses = $row['total_expenses'];  
        $expenses_by_month[$month] = $totalExpenses;  
    }  
} else {  
    error_log("Expenses query failed: " . $conn->error);  
    echo "Error fetching expense data.";  
}  

$conn->close();  
?>  

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  

<div class="container" style="max-width: 800px; margin: auto; padding: 20px;">  
    <label class="switch">  
        <input type="checkbox" id="toggleSwitch" onchange="toggleDisplay()">  
        <span class="slider round"></span>  
    </label>  
    <span id="toggleLabel">Show Charts</span>  
</div>  

<div id="cardsSection">  
    <div class="container" style="max-width: 800px; margin: auto; padding: 20px;">  
        <h2>Monthly Ticket Sales</h2>  
        <div class="row">  
            <?php   
            for ($month = 1; $month <= $currentMonth; $month++) {  
                $sales = isset($sales_by_month[$month]) ? $sales_by_month[$month] : 0;  
                $monthName = date("F", mktime(0, 0, 0, $month, 1));   
                echo "<div class='card' style='margin: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; display: inline-block; width: 150px; text-align: center;'>  
                        <h4>$monthName</h4>  
                        <p>Sales: " . ($sales > 0 ? $sales : '0') . "</p>  
                      </div>";  
            }  
            ?>  
        </div>  
        
        <h2>Monthly Residences</h2>  
        <div class="row">  
            <?php   
            foreach ($counts_residences as $month => $residences) {  
                $monthName = date("F", mktime(0, 0, 0, $month, 1));   
                echo "<div class='card' style='margin: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; display: inline-block; width: 150px; text-align: center;'>  
                        <h4>$monthName</h4>  
                        <p>Residences: " . ($residences > 0 ? $residences : '0') . "</p>  
                      </div>";  
            }  
            ?>  
        </div>  

        <h2>Monthly Expenses</h2>  
        <div class="row">  
            <?php   
            for ($month = 1; $month <= $currentMonth; $month++) {  
                $expenses = isset($expenses_by_month[$month]) ? $expenses_by_month[$month] : 0;  
                $monthName = date("F", mktime(0, 0, 0, $month, 1));   
                echo "<div class='card' style='margin: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; display: inline-block; width: 150px; text-align: center;'>  
                        <h4>$monthName</h4>  
                        <p>Expenses: " . number_format($expenses, 2) . "</p>  
                      </div>";  
            }  
            ?>  
        </div>  
    </div>  
</div>  

<div id="chartsSection" style="display: none;">  
    <div class="container" style="max-width: 800px; margin: auto; padding: 20px;">  
        <h2>Monthly Residences Bar Chart</h2>  
        <canvas id="residencesChart"></canvas>  
    </div>  

    <div class="container" style="max-width: 800px; margin: auto; padding: 20px;">  
        <h2>Monthly Ticket Sales Bar Chart</h2>  
        <canvas id="ticketsChart"></canvas>  
    </div>  

    <div class="container" style="max-width: 800px; margin: auto; padding: 20px;">  
        <h2>Monthly Expenses Bar Chart</h2>  
        <canvas id="expensesChart"></canvas>  
    </div>  
</div>  

<script>  
    let residencesChart, ticketsChart, expensesChart;

    function initializeCharts() {
        const ctx1 = document.getElementById('residencesChart').getContext('2d');  
        residencesChart = new Chart(ctx1, {  
            type: 'bar',  
            data: {  
                labels: <?php echo json_encode($months); ?>,  
                datasets: [{  
                    label: 'Total Residences',  
                    data: <?php echo json_encode(array_values($counts_residences)); ?>,  
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',  
                    borderColor: 'rgba(75, 192, 192, 1)',  
                    borderWidth: 1  
                }]  
            },  
            options: {  
                scales: { y: { beginAtZero: true } }  
            }  
        });  

        const ctx2 = document.getElementById('ticketsChart').getContext('2d');  
        ticketsChart = new Chart(ctx2, {  
            type: 'bar',  
            data: {  
                labels: <?php echo json_encode(array_map(function($m) { return date("F", mktime(0, 0, 0, $m, 1)); }, range(1, $currentMonth))); ?>,  
                datasets: [{  
                    label: 'Total Ticket Sales',  
                    data: <?php echo json_encode(array_slice($sales_by_month, 0, $currentMonth, true)); ?>,  
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',  
                    borderColor: 'rgba(153, 102, 255, 1)',  
                    borderWidth: 1  
                }]  
            },  
            options: {  
                scales: { y: { beginAtZero: true } }  
            }  
        });  

        const ctx3 = document.getElementById('expensesChart').getContext('2d');  
        expensesChart = new Chart(ctx3, {  
            type: 'bar',  
            data: {  
                labels: <?php echo json_encode(array_map(function($m) { return date("F", mktime(0, 0, 0, $m, 1)); }, range(1, $currentMonth))); ?>,  
                datasets: [{  
                    label: 'Total Expenses',  
                    data: <?php echo json_encode(array_slice($expenses_by_month, 0, $currentMonth, true)); ?>,  
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',  
                    borderColor: 'rgba(255, 99, 132, 1)',  
                    borderWidth: 1  
                }]  
            },  
            options: {  
                scales: { y: { beginAtZero: true } }  
            }  
        });  
    }

    function toggleDisplay() {  
        const cardsSection = document.getElementById('cardsSection');  
        const chartsSection = document.getElementById('chartsSection');  
        const toggleSwitch = document.getElementById('toggleSwitch');  
        const toggleLabel = document.getElementById('toggleLabel');  

        if (toggleSwitch.checked) {  
            cardsSection.style.display = 'none';  
            chartsSection.style.display = 'block';  
            toggleLabel.innerText = 'Show Cards';  
            if (!residencesChart) {
                initializeCharts();
            }
        } else {  
            cardsSection.style.display = 'block';  
            chartsSection.style.display = 'none';  
            toggleLabel.innerText = 'Show Charts';  
        }  
    }  
</script>  

<style>  
    .switch { position: relative; display: inline-block; width: 60px; height: 34px; }  
    .switch input { opacity: 0; width: 0; height: 0; }  
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }  
    .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }  
    input:checked + .slider { background-color: #2196F3; }  
    input:checked + .slider:before { transform: translateX(26px); }  
    body { margin: 0; overflow-x: auto; }  
    .container { position: relative; z-index: 1; }  
</style>  