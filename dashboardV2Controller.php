<?php
// dashboardV2Controller.php
// Controller for Dashboard V2 charts – returns sales data for Ticket, Residence and Visa in JSON format.

// Enable error reporting in development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connection.php';

// Helper to output JSON and terminate
function api_response($payload)
{
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

// Only logged-in users may access
if (!isset($_SESSION['user_id'])) {
    // Special case for navbar trends - provide real data even if not authenticated
    if (isset($_POST['action']) && $_POST['action'] === 'getRealTimeComparison') {
        try {
            // Connect to the database directly for this special case
            include_once 'connection.php';
            
            // Today's data (from midnight to now)
            $today = date('Y-m-d');
            $now = date('Y-m-d H:i:s');
            $currentHour = (int)date('H');
            
            // Past 7 days (excluding today)
            $pastDays = [];
            $pastDaysLabels = [];
            for ($i = 1; $i <= 7; $i++) {
                $pastDays[] = date('Y-m-d', strtotime("-{$i} day"));
                $pastDaysLabels[] = date('D', strtotime("-{$i} day")); // Day name (Mon, Tue, etc.)
            }
            
            $types = [
                'ticket'    => 'ticket',
                'visa'      => 'visa',
                'residence' => 'residence',
            ];
            
            $todayCounts = [];
            $pastDailyCounts = []; // Counts for each of the past 7 days
            $pastAverageCounts = []; 
            $pastAverageUpToNowCounts = [];
            $hourlyBreakdown = [];
            $hourlyBreakdownPast = [];
            $dailyBreakdown = []; // Data for each of the past 7 days
            
            foreach ($types as $key => $table) {
                // Today's count
                $sql = "SELECT COUNT(*) FROM {$table} WHERE datetime BETWEEN :start AND :end";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':start' => $today . ' 00:00:00', ':end' => $now]);
                $todayCounts[$key] = (int) $stmt->fetchColumn();
                
                // Past days total counts
                $totalPastCount = 0;
                $totalPastUpToNowCount = 0;
                $pastDailyCounts[$key] = [];
                
                foreach ($pastDays as $index => $pastDay) {
                    // Full day count
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':start' => $pastDay . ' 00:00:00', ':end' => $pastDay . ' 23:59:59']);
                    $count = (int) $stmt->fetchColumn();
                    $pastDailyCounts[$key][$pastDaysLabels[$index]] = $count;
                    $totalPastCount += $count;
                    
                    // Up to current time of day count (for fair comparison)
                    $currentTimeOfDay = date('H:i:s');
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':start' => $pastDay . ' 00:00:00', ':end' => $pastDay . ' ' . $currentTimeOfDay]);
                    $upToNowCount = (int) $stmt->fetchColumn();
                    $totalPastUpToNowCount += $upToNowCount;
                }
                
                // Calculate averages
                $pastAverageCounts[$key] = round($totalPastCount / count($pastDays), 1);
                $pastAverageUpToNowCounts[$key] = round($totalPastUpToNowCount / count($pastDays), 1);
                
                // Store daily breakdown for dashboard
                $dailyBreakdown[$key] = $pastDailyCounts[$key];
                
                // Initialize hourly data with zeros - for dashboard charts
                $hourlyBreakdown[$key] = [];
                $hourlyBreakdownPast[$key] = [];
                for ($i = 0; $i < 24; $i++) {
                    $hourLabel = sprintf("%02d:00", $i);
                    $hourlyBreakdown[$key][$hourLabel] = 0;
                    $hourlyBreakdownPast[$key][$hourLabel] = 0;
                }
                
                // Add basic hourly data (just enough for charts to work)
                $sqlHourly = "SELECT HOUR(datetime) as hour, COUNT(*) as count 
                             FROM {$table} 
                             WHERE DATE(datetime) = :date
                             GROUP BY HOUR(datetime) 
                             ORDER BY HOUR(datetime)";
                $stmt = $pdo->prepare($sqlHourly);
                $stmt->execute([':date' => $today]);
                
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $hour = sprintf("%02d:00", (int)$row['hour']);
                    $hourlyBreakdown[$key][$hour] = (int)$row['count'];
                }
            }
            
            // Calculate percentage changes compared to average
            $percentChanges = [];
            $percentChangesUpToNow = [];
            foreach ($types as $key => $table) {
                // Calculate vs full day average
                if ($pastAverageCounts[$key] > 0) {
                    $percentChanges[$key] = round((($todayCounts[$key] - $pastAverageCounts[$key]) / $pastAverageCounts[$key]) * 100, 1);
                } else {
                    $percentChanges[$key] = $todayCounts[$key] > 0 ? 100 : 0;
                }
                
                // Calculate vs same time of day average
                if ($pastAverageUpToNowCounts[$key] > 0) {
                    $percentChangesUpToNow[$key] = round((($todayCounts[$key] - $pastAverageUpToNowCounts[$key]) / $pastAverageUpToNowCounts[$key]) * 100, 1);
                } else {
                    $percentChangesUpToNow[$key] = $todayCounts[$key] > 0 ? 100 : 0;
                }
            }
            
            api_response([
                'status' => 'success',
                'today' => [
                    'counts' => $todayCounts,
                    'start' => $today . ' 00:00:00',
                    'end' => $now
                ],
                'pastAverage' => [
                    'counts' => $pastAverageCounts,
                    'upToNowCounts' => $pastAverageUpToNowCounts,
                    'dailyBreakdown' => $dailyBreakdown,
                    'days' => $pastDaysLabels
                ],
                'percentChanges' => $percentChanges,
                'percentChangesUpToNow' => $percentChangesUpToNow,
                'hourlyBreakdown' => $hourlyBreakdown,
                'hourlyBreakdownPast' => $hourlyBreakdownPast,
                'currentHour' => $currentHour
            ]);
        } catch (Exception $e) {
            api_response([
                'status' => 'error', 
                'message' => 'Error calculating real-time data: ' . $e->getMessage()
            ]);
        }
    } else {
        api_response(['status' => 'error', 'message' => 'Unauthorised']);
    }
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'getSalesData') {
    try {
        // Last 30 days including today
        $days      = 30;
        $endDate   = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-$days days +1 day")); // inclusive range

        // Build date index with zero defaults
        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            $d           = date('Y-m-d', strtotime($startDate . " +$i day"));
            $dates[$d] = [
                'ticket'    => 0,
                'visa'      => 0,
                'residence' => 0,
            ];
        }

        // Helper to populate results
        $populate = function ($sql, $fieldName) use (&$dates, $pdo, $startDate, $endDate) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':start' => $startDate, ':end' => $endDate]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $d = $row['date'];
                if (isset($dates[$d])) {
                    $dates[$d][$fieldName] = (float) $row['total'];
                }
            }
        };

        // Ticket count per day
        $populate(
            'SELECT DATE(datetime) AS date, COUNT(*) AS total FROM ticket WHERE DATE(datetime) BETWEEN :start AND :end GROUP BY DATE(datetime)',
            'ticket'
        );

        // Visa count per day
        $populate(
            'SELECT DATE(datetime) AS date, COUNT(*) AS total FROM visa WHERE DATE(datetime) BETWEEN :start AND :end GROUP BY DATE(datetime)',
            'visa'
        );

        // Residence count per day
        $populate(
            'SELECT DATE(datetime) AS date, COUNT(*) AS total FROM residence WHERE DATE(datetime) BETWEEN :start AND :end GROUP BY DATE(datetime)',
            'residence'
        );

        // Prepare sequential array for client-side consumption
        $out = [];
        foreach ($dates as $date => $values) {
            $out[] = array_merge(['date' => $date], $values);
        }

        api_response(['status' => 'success', 'data' => $out]);
    } catch (Exception $e) {
        api_response(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
    }
}

// Performance summary (month or year)
if ($action === 'getPerformance') {
    $period = isset($_POST['period']) ? $_POST['period'] : 'month';

    // Determine date ranges
    $currentStart = $currentEnd = $previousStart = $previousEnd = '';

    if ($period === 'year') {
        // Year-to-date vs last year-to-date (same date)
        $currentStart  = date('Y-01-01');
        $currentEnd    = date('Y-m-d');
        $previousStart = date('Y-01-01', strtotime('-1 year'));
        $previousEnd   = date('Y-m-d', strtotime('-1 year'));
    } elseif ($period === 'ytd') {
        // Current month vs year-to-date before this month
        $currentStart = date('Y-m-01');
        $currentEnd   = date('Y-m-d');

        // Year-to-date up to today (includes current month)
        $previousStart = date('Y-01-01');
        $previousEnd   = $currentEnd;
    } else { // default month
        // This month vs last month
        $currentStart  = date('Y-m-01');
        $currentEnd    = date('Y-m-d');
        // previous month first and last day
        $previousStart = date('Y-m-01', strtotime('-1 month', strtotime($currentStart)));
        $previousEnd   = date('Y-m-t', strtotime('-1 month'));
    }

    $types = [
        'ticket'    => 'ticket',
        'visa'      => 'visa',
        'residence' => 'residence',
    ];

    $currentCounts  = [];
    $previousCounts = [];

    foreach ($types as $key => $table) {
        // current period count
        $sql = "SELECT COUNT(*) FROM {$table} WHERE DATE(datetime) BETWEEN :start AND :end";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':start' => $currentStart, ':end' => $currentEnd]);
        $currentCounts[$key] = (int) $stmt->fetchColumn();

        // previous period count
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':start' => $previousStart, ':end' => $previousEnd]);
        $previousCounts[$key] = (int) $stmt->fetchColumn();
    }

    api_response([
        'status'   => 'success',
        'period'   => $period,
        'current'  => $currentCounts,
        'previous' => $previousCounts
    ]);
}

if ($action === 'getMonthlyCounts') {
    $year = isset($_POST['year']) ? (int)$_POST['year'] : (int)date('Y');

    $months = range(1, 12);
    $data = [
        'ticket'    => array_fill(1, 12, 0),
        'visa'      => array_fill(1, 12, 0),
        'residence' => array_fill(1, 12, 0)
    ];

    $types = [
        'ticket'    => 'ticket',
        'visa'      => 'visa',
        'residence' => 'residence'
    ];

    foreach ($types as $key => $table) {
        $sql = "SELECT MONTH(datetime) AS m, COUNT(*) AS total FROM {$table} WHERE YEAR(datetime)=:year GROUP BY MONTH(datetime)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':year' => $year]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $data[$key][(int)$row['m']] = (int)$row['total'];
        }
    }

    api_response(['status' => 'success', 'year' => $year, 'data' => $data]);
}

if ($action === 'getWeeklyCounts') {
    $year = isset($_POST['year']) ? (int)$_POST['year'] : (int)date('Y');
    $monthParam = isset($_POST['month']) ? (int)$_POST['month'] : null; // 1-12 or null for whole year

    // initialise as empty arrays; we'll fill dynamically
    $data = [
        'ticket'    => [],
        'visa'      => [],
        'residence' => []
    ];

    $types = [
        'ticket'    => 'ticket',
        'visa'      => 'visa',
        'residence' => 'residence'
    ];

    foreach ($types as $key => $table) {
        $sql = "SELECT WEEK(datetime, 3) AS wk, COUNT(*) AS total FROM {$table} WHERE YEAR(datetime)=:year";
        $params = [':year' => $year];
        if ($monthParam) {
            $sql .= " AND MONTH(datetime)=:month";
            $params[':month'] = $monthParam;
        }
        $sql .= " GROUP BY wk";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $wk = (int)$row['wk'];
            if ($wk == 0) $wk = 53; // edge-case first days of year 0 week
            $data[$key][$wk] = (int)$row['total'];
        }
    }

    api_response(['status' => 'success', 'year' => $year, 'month' => $monthParam, 'data' => $data]);
}

// Real-time comparison: Today vs Past Average
if ($action === 'getRealTimeComparison') {
    try {
        // Today's data (from midnight to now)
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $currentHour = (int)date('H');
        
        // Past 7 days (excluding today)
        $pastDays = [];
        $pastDaysLabels = [];
        for ($i = 1; $i <= 7; $i++) {
            $pastDays[] = date('Y-m-d', strtotime("-{$i} day"));
            $pastDaysLabels[] = date('D', strtotime("-{$i} day")); // Day name (Mon, Tue, etc.)
        }
        
        $types = [
            'ticket'    => 'ticket',
            'visa'      => 'visa',
            'residence' => 'residence',
        ];
        
        $todayCounts = [];
        $pastDailyCounts = []; // Counts for each of the past 7 days
        $pastAverageCounts = []; // Average of past 7 days
        $hourlyBreakdown = [];
        $hourlyBreakdownPast = []; // Average hourly breakdown for past days
        $dailyBreakdown = []; // Data for each of the past 7 days
        
        foreach ($types as $key => $table) {
            // Today's count
            $sql = "SELECT COUNT(*) FROM {$table} WHERE datetime BETWEEN :start AND :end";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':start' => $today . ' 00:00:00', ':end' => $now]);
            $todayCounts[$key] = (int) $stmt->fetchColumn();
            
            // Past days counts (for each day)
            $pastDailyCounts[$key] = [];
            $pastUpToNowCounts[$key] = []; // Same time of day comparison
            $totalPastCount = 0;
            $totalPastUpToNowCount = 0;
            
            foreach ($pastDays as $index => $pastDay) {
                // Full day count
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':start' => $pastDay . ' 00:00:00', ':end' => $pastDay . ' 23:59:59']);
                $count = (int) $stmt->fetchColumn();
                $pastDailyCounts[$key][$pastDaysLabels[$index]] = $count;
                $totalPastCount += $count;
                
                // Up to current time of day count (for fair comparison)
                $currentTimeOfDay = date('H:i:s');
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':start' => $pastDay . ' 00:00:00', ':end' => $pastDay . ' ' . $currentTimeOfDay]);
                $upToNowCount = (int) $stmt->fetchColumn();
                $pastUpToNowCounts[$key][$pastDaysLabels[$index]] = $upToNowCount;
                $totalPastUpToNowCount += $upToNowCount;
            }
            
            // Calculate averages
            $pastAverageCounts[$key] = round($totalPastCount / count($pastDays), 1);
            $pastAverageUpToNowCounts[$key] = round($totalPastUpToNowCount / count($pastDays), 1);
            
            // Store all past days data for visualization
            $dailyBreakdown[$key] = $pastDailyCounts[$key];
            
            // Hourly breakdown for today
            $sqlHourly = "SELECT HOUR(datetime) as hour, COUNT(*) as count 
                         FROM {$table} 
                         WHERE DATE(datetime) = :date
                         GROUP BY HOUR(datetime) 
                         ORDER BY HOUR(datetime)";
            $stmt = $pdo->prepare($sqlHourly);
            $stmt->execute([':date' => $today]);
            $hourlyBreakdown[$key] = [];
            
            // Initialize hourly data with zeros
            for ($i = 0; $i < 24; $i++) {
                $hourLabel = sprintf("%02d:00", $i);
                $hourlyBreakdown[$key][$hourLabel] = 0;
                $hourlyBreakdownPast[$key][$hourLabel] = 0;
            }
            
            // Fill in actual values for today
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $hour = sprintf("%02d:00", (int)$row['hour']);
                $hourlyBreakdown[$key][$hour] = (int)$row['count'];
            }
            
            // Get average hourly data for past days
            foreach ($pastDays as $pastDay) {
                $stmt = $pdo->prepare($sqlHourly);
                $stmt->execute([':date' => $pastDay]);
                
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $hour = sprintf("%02d:00", (int)$row['hour']);
                    // Add to existing value (will be divided later)
                    $hourlyBreakdownPast[$key][$hour] += (int)$row['count'];
                }
            }
            
            // Calculate hourly averages
            foreach ($hourlyBreakdownPast[$key] as $hour => $total) {
                $hourlyBreakdownPast[$key][$hour] = round($total / count($pastDays), 1);
            }
        }
        
        // Calculate percentage changes compared to average
        $percentChanges = [];
        $percentChangesUpToNow = [];
        foreach ($types as $key => $table) {
            // Calculate vs full day average
            if ($pastAverageCounts[$key] > 0) {
                $percentChanges[$key] = round((($todayCounts[$key] - $pastAverageCounts[$key]) / $pastAverageCounts[$key]) * 100, 1);
            } else {
                $percentChanges[$key] = $todayCounts[$key] > 0 ? 100 : 0;
            }
            
            // Calculate vs same time of day average
            if ($pastAverageUpToNowCounts[$key] > 0) {
                $percentChangesUpToNow[$key] = round((($todayCounts[$key] - $pastAverageUpToNowCounts[$key]) / $pastAverageUpToNowCounts[$key]) * 100, 1);
            } else {
                $percentChangesUpToNow[$key] = $todayCounts[$key] > 0 ? 100 : 0;
            }
        }
        
        api_response([
            'status' => 'success',
            'today' => [
                'counts' => $todayCounts,
                'start' => $today . ' 00:00:00',
                'end' => $now
            ],
            'pastAverage' => [
                'counts' => $pastAverageCounts,
                'upToNowCounts' => $pastAverageUpToNowCounts,
                'dailyBreakdown' => $dailyBreakdown,
                'days' => $pastDaysLabels
            ],
            'percentChanges' => $percentChanges,
            'percentChangesUpToNow' => $percentChangesUpToNow,
            'hourlyBreakdown' => $hourlyBreakdown,
            'hourlyBreakdownPast' => $hourlyBreakdownPast,
            'currentHour' => $currentHour
        ]);
    } catch (Exception $e) {
        api_response(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
    }
}

api_response(['status' => 'error', 'message' => 'Invalid action']);