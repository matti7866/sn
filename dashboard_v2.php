<?php
// dashboard_v2.php – visual dashboard showing Ticket, Residence and Visa sales graphs.

session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

include 'header.php';
include 'nav.php';
?>

<!-- Bootstrap & jQuery already loaded via global template; avoid duplicates to preserve styling -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container-fluid mt-4">
    <h1 class="page-header mb-4">Dashboard v2 – Sales Overview</h1>

    <!-- Real-time comparison section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Real-time Comparison: Today vs Past 7 Days Average</h4>
                        <button id="refreshRealTime" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="row mb-4" id="realtimeCards">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Tickets</h5>
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <h6>Today</h6>
                                            <h2 id="ticketToday">0</h2>
                                        </div>
                                        <div>
                                            <h6>Avg (7 days)</h6>
                                            <h2 id="ticketAverage">0</h2>
                                            <small class="text-muted">Past week</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span id="ticketChange" class="badge bg-info">0%</span>
                                        <span id="ticketTimeNote" class="badge bg-secondary ms-1 d-none">vs same time</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Residence</h5>
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <h6>Today</h6>
                                            <h2 id="residenceToday">0</h2>
                                        </div>
                                        <div>
                                            <h6>Avg (7 days)</h6>
                                            <h2 id="residenceAverage">0</h2>
                                            <small class="text-muted">Past week</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span id="residenceChange" class="badge bg-info">0%</span>
                                        <span id="residenceTimeNote" class="badge bg-secondary ms-1 d-none">vs same time</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Visa</h5>
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <h6>Today</h6>
                                            <h2 id="visaToday">0</h2>
                                        </div>
                                        <div>
                                            <h6>Avg (7 days)</h6>
                                            <h2 id="visaAverage">0</h2>
                                            <small class="text-muted">Past week</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span id="visaChange" class="badge bg-info">0%</span>
                                        <span id="visaTimeNote" class="badge bg-secondary ms-1 d-none">vs same time</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Hourly Activity Comparison</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="compareSwitch" checked>
                                <label class="form-check-label" for="compareSwitch">Fair comparison (up to current time)</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="hourlyChart" style="width:100%;height:300px;"></canvas>
                        </div>
                        <div class="col-md-4">
                            <canvas id="dailyChart" style="width:100%;height:300px;"></canvas>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-12 text-center small text-muted">
                            <div id="lastUpdated"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Last 30 Days Entries (Count)</h4>
                    <canvas id="salesChart" style="width:100%;max-height:400px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-2">
        <label class="me-2 fw-bold">Compare:</label>
        <select id="periodSelect" class="form-select form-select-sm" style="width:auto;">
            <option value="month" selected>This Month vs Last Month</option>
            <option value="year">Year-to-Date vs Last Year</option>
            <option value="ytd">Current Month vs Year-to-Date</option>
        </select>
    </div>

    <div class="row text-center mb-4" id="trendSummary" style="display:none;">
        <div class="col-4">
            <h6>Ticket <span id="ticketTrend" class="ms-2"></span></h6>
        </div>
        <div class="col-4">
            <h6>Residence <span id="residenceTrend" class="ms-2"></span></h6>
        </div>
        <div class="col-4">
            <h6>Visa <span id="visaTrend" class="ms-2"></span></h6>
        </div>
    </div>

    <!-- Yearly performance chart -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Monthly Counts – <span id="selectedYearLbl"></span></h4>
                <div>
                    <select id="yearSelect" class="form-select form-select-sm" style="width:auto;"></select>
                </div>
            </div>
            <canvas id="monthlyChart" style="width:100%;max-height:400px;"></canvas>
            <div class="row text-center small mt-2" id="trendMonth" style="display:none;">
                <div class="col-4">Ticket <span id="ticketTrendMonth"></span></div>
                <div class="col-4">Residence <span id="residenceTrendMonth"></span></div>
                <div class="col-4">Visa <span id="visaTrendMonth"></span></div>
            </div>
        </div>
    </div>

    <!-- Weekly performance chart -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <h4 class="mb-0">Weekly Counts – <span id="weeklyYearLbl"></span></h4>
                <select id="weekMonthSelect" class="form-select form-select-sm ms-3" style="width:auto;"></select>
            </div>
            <canvas id="weeklyChart" style="width:100%;max-height:400px;"></canvas>
            <div class="row text-center small mt-2" id="trendWeek" style="display:none;">
                <div class="col-4">Ticket <span id="ticketTrendWeek"></span></div>
                <div class="col-4">Residence <span id="residenceTrendWeek"></span></div>
                <div class="col-4">Visa <span id="visaTrendWeek"></span></div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for arrow icons (if not already loaded) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

<script>
$(function () {
    // Real-time comparison data
    let hourlyChart;
    let dailyChart;
    let compareSameTime = true;
    
    function loadRealTimeComparison() {
        $.post('dashboardV2Controller.php', {action: 'getRealTimeComparison'}, function(resp) {
            if (resp.status !== 'success') {
                console.error(resp.message || 'Failed to fetch real-time data');
                return;
            }
            
            // Update counters
            $('#ticketToday').text(resp.today.counts.ticket);
            $('#ticketAverage').text(resp.pastAverage.counts.ticket);
            
            $('#residenceToday').text(resp.today.counts.residence);
            $('#residenceAverage').text(resp.pastAverage.counts.residence);
            
            $('#visaToday').text(resp.today.counts.visa);
            $('#visaAverage').text(resp.pastAverage.counts.visa);
            
            // Store comparison data for toggle
            window.comparisonData = resp;
            
            // Update based on current comparison mode
            updateComparisonView();
            
            // Draw daily breakdown chart (past 7 days)
            updateDailyChart(resp.pastAverage.dailyBreakdown, resp.pastAverage.days);
            
            // Update last refreshed time
            $('#lastUpdated').text('Last updated: ' + new Date().toLocaleString());
            
            // Update navbar with the same data
            updateNavbarFromDashboard();
        }, 'json');
    }
    
    function updateComparisonView() {
        if (!window.comparisonData) return;
        
        const resp = window.comparisonData;
        const sameTimeMode = $('#compareSwitch').is(':checked');
        
        // Show or hide "vs same time" badges
        $('.badge[id$="TimeNote"]').toggleClass('d-none', !sameTimeMode);
        
        if (sameTimeMode) {
            // Use "up to now" counts for fair comparison
            updateChangeBadge('ticketChange', calculatePercentChange(
                resp.today.counts.ticket, 
                resp.pastAverage.upToNowCounts.ticket
            ));
            updateChangeBadge('residenceChange', calculatePercentChange(
                resp.today.counts.residence, 
                resp.pastAverage.upToNowCounts.residence
            ));
            updateChangeBadge('visaChange', calculatePercentChange(
                resp.today.counts.visa, 
                resp.pastAverage.upToNowCounts.visa
            ));
        } else {
            // Use full day average
            updateChangeBadge('ticketChange', resp.percentChanges.ticket);
            updateChangeBadge('residenceChange', resp.percentChanges.residence);
            updateChangeBadge('visaChange', resp.percentChanges.visa);
        }
        
        // Update hourly chart
        updateHourlyChart(resp.hourlyBreakdown, resp.hourlyBreakdownPast, resp.currentHour, sameTimeMode);
    }
    
    function calculatePercentChange(current, previous) {
        if (previous === 0) return current > 0 ? 100 : 0;
        return Math.round(((current - previous) / previous) * 100 * 10) / 10; // Round to 1 decimal
    }
    
    function updateChangeBadge(id, percentChange) {
        const badge = $('#' + id);
        
        // Remove previous classes
        badge.removeClass('bg-success bg-danger bg-info');
        
        // Add appropriate class based on value
        if (percentChange > 0) {
            badge.addClass('bg-success');
            badge.html('<i class="fas fa-arrow-up"></i> ' + percentChange + '%');
        } else if (percentChange < 0) {
            badge.addClass('bg-danger');
            badge.html('<i class="fas fa-arrow-down"></i> ' + Math.abs(percentChange) + '%');
        } else {
            badge.addClass('bg-info');
            badge.html('0%');
        }
    }
    
    function updateHourlyChart(todayData, avgPastData, currentHour, sameTimeMode) {
        const ctx = document.getElementById('hourlyChart').getContext('2d');
        
        // Extract hours as labels
        const hours = Object.keys(todayData.ticket);
        
        // Prepare datasets
        const ticketDataToday = hours.map(h => todayData.ticket[h]);
        const residenceDataToday = hours.map(h => todayData.residence[h]);
        const visaDataToday = hours.map(h => todayData.visa[h]);
        
        const ticketDataAvg = hours.map(h => avgPastData.ticket[h]);
        const residenceDataAvg = hours.map(h => avgPastData.residence[h]);
        const visaDataAvg = hours.map(h => avgPastData.visa[h]);
        
        // For fair comparison, limit to current hour
        let visibleHours, effectiveLabels;
        
        if (sameTimeMode && currentHour < 23) {
            // Only show up to current hour (+1 for readability)
            const endHour = Math.min(currentHour + 1, 23);
            visibleHours = hours.slice(0, endHour + 1);
            effectiveLabels = visibleHours;
        } else {
            visibleHours = hours;
            effectiveLabels = hours;
        }
        
        if (hourlyChart) hourlyChart.destroy();
        
        hourlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: effectiveLabels,
                datasets: [
                    {
                        label: 'Today - Ticket',
                        data: ticketDataToday,
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0,0,0,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Avg - Ticket',
                        data: ticketDataAvg,
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0,0,0,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        borderDash: [5, 5]
                    },
                    {
                        label: 'Today - Residence',
                        data: residenceDataToday,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220,53,69,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Avg - Residence',
                        data: residenceDataAvg,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220,53,69,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        borderDash: [5, 5]
                    },
                    {
                        label: 'Today - Visa',
                        data: visaDataToday,
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253,126,20,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Avg - Visa',
                        data: visaDataAvg,
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253,126,20,0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        min: 0,
                        max: sameTimeMode && currentHour < 23 ? Math.min(currentHour + 1, 23) : 23
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: sameTimeMode ? 
                            'Hourly Activity (Today vs 7-Day Average up to current time)' :
                            'Hourly Activity (Today vs Full Day 7-Day Average)'
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return 'Hour: ' + tooltipItems[0].label;
                            }
                        }
                    }
                }
            }
        });
    }
    
    function updateDailyChart(dailyData, daysLabels) {
        const ctx = document.getElementById('dailyChart').getContext('2d');
        
        if (dailyChart) dailyChart.destroy();
        
        // Create datasets for each type
        const datasets = [
            {
                label: 'Ticket',
                data: daysLabels.map(day => dailyData.ticket[day] || 0),
                backgroundColor: 'rgba(0,0,0,0.6)',
                borderColor: '#000000',
                borderWidth: 1
            },
            {
                label: 'Residence',
                data: daysLabels.map(day => dailyData.residence[day] || 0),
                backgroundColor: 'rgba(220,53,69,0.6)',
                borderColor: '#dc3545',
                borderWidth: 1
            },
            {
                label: 'Visa',
                data: daysLabels.map(day => dailyData.visa[day] || 0),
                backgroundColor: 'rgba(253,126,20,0.6)',
                borderColor: '#fd7e14',
                borderWidth: 1
            }
        ];
        
        dailyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: daysLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Breakdown (Past 7 Days)'
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initial load of real-time data
    loadRealTimeComparison();
    
    // Toggle between comparison modes
    $('#compareSwitch').on('change', function() {
        updateComparisonView();
        // Also update navbar when comparison mode changes
        updateNavbarFromDashboard();
    });
    
    // Refresh button handler
    $('#refreshRealTime').on('click', function() {
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...').prop('disabled', true);
        loadRealTimeComparison();
        setTimeout(() => {
            $(this).html('<i class="fas fa-sync-alt"></i> Refresh').prop('disabled', false);
        }, 1000);
    });
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        loadRealTimeComparison();
        // Also update navbar on auto-refresh
        setTimeout(updateNavbarFromDashboard, 1000);
    }, 300000); // 5 minutes in milliseconds
    
    // Function to update the navbar indicators
    function updateNavbarIndicators(ticketChange, residenceChange, visaChange) {
        // Create or update the navbar trend indicators
        const ticketBadge = createTrendBadge('ticket-trend-nav', ticketChange, 'Ticket');
        const residenceBadge = createTrendBadge('residence-trend-nav', residenceChange, 'Residence');
        const visaBadge = createTrendBadge('visa-trend-nav', visaChange, 'Visa');
        
        // Add to navbar container
        let navTrendsContainer = document.getElementById('navbar-trends');
        if (!navTrendsContainer) {
            // Find the navbar section
            const navbarNav = document.querySelector('.navbar-nav');
            if (navbarNav) {
                // Create container after the digital clock
                navTrendsContainer = document.createElement('div');
                navTrendsContainer.id = 'navbar-trends';
                navTrendsContainer.className = 'navbar-item d-none d-lg-block';
                navTrendsContainer.style.cssText = 'background: rgba(0,0,0,0.15); border-radius: 8px; padding: 5px 10px; margin-left: 10px;';
                
                // Add title
                const title = document.createElement('div');
                title.className = 'text-white small mb-1';
                title.innerHTML = 'Today vs 7-Day Avg:';
                navTrendsContainer.appendChild(title);
                
                // Create badge container
                const badgeContainer = document.createElement('div');
                badgeContainer.className = 'd-flex gap-2';
                badgeContainer.id = 'navbar-trend-badges';
                navTrendsContainer.appendChild(badgeContainer);
                
                // Insert after digital clock
                const clockItem = document.querySelector('.navbar-nav .navbar-item:first-child');
                if (clockItem && clockItem.nextSibling) {
                    navbarNav.insertBefore(navTrendsContainer, clockItem.nextSibling);
                } else {
                    navbarNav.appendChild(navTrendsContainer);
                }
            }
        }
        
        // Update badge container
        const badgeContainer = document.getElementById('navbar-trend-badges');
        if (badgeContainer) {
            badgeContainer.innerHTML = '';
            badgeContainer.appendChild(ticketBadge);
            badgeContainer.appendChild(residenceBadge);
            badgeContainer.appendChild(visaBadge);
        }
    }
    
    function createTrendBadge(id, percentChange, label) {
        const container = document.createElement('div');
        container.className = 'd-flex flex-column align-items-center';
        
        const badge = document.createElement('span');
        badge.id = id;
        
        // Set badge color and icon based on value
        if (percentChange > 0) {
            badge.className = 'badge bg-success text-white';
            badge.innerHTML = `<i class="fas fa-arrow-up"></i> ${percentChange}%`;
        } else if (percentChange < 0) {
            badge.className = 'badge bg-danger text-white';
            badge.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(percentChange)}%`;
        } else {
            badge.className = 'badge bg-secondary text-white';
            badge.innerHTML = '0%';
        }
        
        const labelEl = document.createElement('span');
        labelEl.className = 'text-white-50 small';
        labelEl.textContent = label;
        
        container.appendChild(badge);
        container.appendChild(labelEl);
        return container;
    }
    
    // Update navbar indicators whenever we update the main comparison view
    const originalUpdateComparisonView = updateComparisonView;
    updateComparisonView = function() {
        originalUpdateComparisonView();
        
        if (window.comparisonData) {
            const resp = window.comparisonData;
            const sameTimeMode = $('#compareSwitch').is(':checked');
            
            // Update navbar with the same percentages shown in the main view
            if (sameTimeMode) {
                updateNavbarIndicators(
                    calculatePercentChange(resp.today.counts.ticket, resp.pastAverage.upToNowCounts.ticket),
                    calculatePercentChange(resp.today.counts.residence, resp.pastAverage.upToNowCounts.residence),
                    calculatePercentChange(resp.today.counts.visa, resp.pastAverage.upToNowCounts.visa)
                );
            } else {
                updateNavbarIndicators(
                    resp.percentChanges.ticket,
                    resp.percentChanges.residence,
                    resp.percentChanges.visa
                );
            }
        }
    };

    const ctx = document.getElementById('salesChart').getContext('2d');

    $.post('dashboardV2Controller.php', {action: 'getSalesData'}, function (resp) {
        if (resp.status !== 'success') {
            alert(resp.message || 'Failed to fetch data');
            return;
        }

        const labels = [];
        const ticketData = [];
        const visaData = [];
        const residenceData = [];

        resp.data.forEach(item => {
            labels.push(item.date);
            ticketData.push(item.ticket);
            visaData.push(item.visa);
            residenceData.push(item.residence);
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ticket Count',
                        data: ticketData,
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0,0,0,0.6)',
                        borderWidth: 1
                    },
                    {
                        label: 'Residence Count',
                        data: residenceData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220,53,69,0.6)',
                        borderWidth: 1
                    },
                    {
                        label: 'Visa Count',
                        data: visaData,
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253,126,20,0.6)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 90,
                            minRotation: 45
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y || 0;
                                return `${label}: ${value.toLocaleString()}`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // ------- Trend Summary (Performance) ---------
        function updateTrend(elId, currentVal, prevVal) {
            const el = $('#' + elId);
            let html = '';
            if (prevVal === 0) {
                if (currentVal === 0) {
                    html = '<span class="text-muted">0%</span>';
                } else {
                    html = '<i class="fas fa-arrow-up text-success"></i> 100%';
                }
            } else {
                const diff = ((currentVal - prevVal) / prevVal) * 100;
                const rounded = diff.toFixed(1);
                if (diff > 0) {
                    html = `<i class="fas fa-arrow-up text-success"></i> ${rounded}%`;
                } else if (diff < 0) {
                    html = `<i class="fas fa-arrow-down text-danger"></i> ${Math.abs(rounded)}%`;
                } else {
                    html = '<span class="text-muted">0%</span>';
                }
            }
            el.html(html);
        }

        function loadPerformance(period = 'month') {
            $.post('dashboardV2Controller.php', {action: 'getPerformance', period: period}, function (perf) {
                if (perf.status !== 'success') {
                    console.error(perf.message);
                    return;
                }
                updateTrend('ticketTrend', perf.current.ticket, perf.previous.ticket);
                updateTrend('residenceTrend', perf.current.residence, perf.previous.residence);
                updateTrend('visaTrend', perf.current.visa, perf.previous.visa);
                $('#trendSummary').show();
            }, 'json');
        }

        // initial load
        loadPerformance('month');

        // period selector change
        $('#periodSelect').on('change', function () {
            loadPerformance($(this).val());
        });

        // --------- Monthly chart ---------
        const monthCtx = document.getElementById('monthlyChart').getContext('2d');
        let monthlyChart;

        function populateYearDropdown() {
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= currentYear - 5; y--) {
                $('#yearSelect').append(`<option value="${y}">${y}</option>`);
            }
            $('#yearSelect').val(currentYear);
            $('#selectedYearLbl').text(currentYear);
        }

        function loadMonthlyChart(year) {
            $.post('dashboardV2Controller.php', {action: 'getMonthlyCounts', year: year}, function (resp) {
                if (resp.status !== 'success') {
                    console.error(resp.message);
                    return;
                }
                $('#selectedYearLbl').text(resp.year);
                const monthsLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                const tData = Object.values(resp.data.ticket);
                const rData = Object.values(resp.data.residence);
                const vData = Object.values(resp.data.visa);

                if (monthlyChart) monthlyChart.destroy();
                monthlyChart = new Chart(monthCtx, {
                    type: 'bar',
                    data: {
                        labels: monthsLabels,
                        datasets: [
                            {
                                label: 'Ticket',
                                data: tData,
                                backgroundColor: 'rgba(0,0,0,0.6)',
                                borderColor: '#000',
                                borderWidth: 1
                            },
                            {
                                label: 'Residence',
                                data: rData,
                                backgroundColor: 'rgba(220,53,69,0.6)',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            },
                            {
                                label: 'Visa',
                                data: vData,
                                backgroundColor: 'rgba(253,126,20,0.6)',
                                borderColor: '#fd7e14',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {position:'bottom'}
                        }
                    }
                });

                // compute trend using last month that has data (or current calendar month)
                let curIdx = tData.length - 1;
                while (curIdx > 0 && (tData[curIdx] + rData[curIdx] + vData[curIdx]) === 0) {
                    curIdx--; // find last non-zero month
                }
                const prevIdx = curIdx > 0 ? curIdx - 1 : 0;

                const curTicket = tData[curIdx];
                const prevTicket = tData[prevIdx];
                const curRes = rData[curIdx];
                const prevRes = rData[prevIdx];
                const curVisa = vData[curIdx];
                const prevVisa = vData[prevIdx];

                updateTrend('ticketTrendMonth', curTicket, prevTicket);
                updateTrend('residenceTrendMonth', curRes, prevRes);
                updateTrend('visaTrendMonth', curVisa, prevVisa);
                $('#trendMonth').show();
            }, 'json');
        }

        populateYearDropdown();
        loadMonthlyChart($('#yearSelect').val());

        $('#yearSelect').on('change', function(){
            loadMonthlyChart($(this).val());
        });

        const weekCtx = document.getElementById('weeklyChart').getContext('2d');
        let weeklyChart;

        function populateWeekMonthDropdown(){
            const currMonth = new Date().getMonth()+1;
            const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            monthNames.forEach((n,i)=>{
                $('#weekMonthSelect').append(`<option value="${i+1}">${n}</option>`);
            });
            $('#weekMonthSelect').val(currMonth);
        }

        function loadWeeklyChart(year, month){
            $.post('dashboardV2Controller.php', {action: 'getWeeklyCounts', year: year, month: month}, function(resp){
                if (resp.status !== 'success') {console.error(resp.message);return;}
                $('#weeklyYearLbl').text(resp.year);
                const weekNums = Object.keys(resp.data.ticket).sort((a,b)=>a-b);
                const weekLabels = weekNums.map(w=>`W${w}`);
                const tData = weekNums.map(w=>resp.data.ticket[w]||0);
                const rData = weekNums.map(w=>resp.data.residence[w]||0);
                const vData = weekNums.map(w=>resp.data.visa[w]||0);

                if (weeklyChart) weeklyChart.destroy();
                weeklyChart = new Chart(weekCtx, {
                    type: 'bar',
                    data: {
                        labels: weekLabels,
                        datasets: [
                            {label:'Ticket', data:tData, backgroundColor:'rgba(0,0,0,0.6)', borderColor:'#000', borderWidth:1},
                            {label:'Residence', data:rData, backgroundColor:'rgba(220,53,69,0.6)', borderColor:'#dc3545', borderWidth:1},
                            {label:'Visa', data:vData, backgroundColor:'rgba(253,126,20,0.6)', borderColor:'#fd7e14', borderWidth:1}
                        ]
                    },
                    options:{
                        responsive:true,
                        maintainAspectRatio:true,
                        aspectRatio:2,
                        scales:{y:{beginAtZero:true}},
                        plugins:{legend:{position:'bottom'}}
                    }
                });

                const sumArray = arr => arr.reduce((a,b)=>a+b,0);
                const curTicketW = sumArray(tData);
                const curResW = sumArray(rData);
                const curVisaW = sumArray(vData);

                // load previous month data for comparison
                const selectedMonth = parseInt($('#weekMonthSelect').val());
                let prevMonth = selectedMonth -1;
                let compareYear = $('#yearSelect').val();
                if(prevMonth==0){prevMonth=12;compareYear=compareYear-1;}
                $.post('dashboardV2Controller.php',{action:'getWeeklyCounts',year:compareYear,month:prevMonth},function(prevResp){
                   let pTicket=0,pRes=0,pVisa=0;
                   if(prevResp.status==='success'){
                       pTicket=sumArray(Object.values(prevResp.data.ticket));
                       pRes=sumArray(Object.values(prevResp.data.residence));
                       pVisa=sumArray(Object.values(prevResp.data.visa));
                   }
                   updateTrend('ticketTrendWeek',curTicketW,pTicket);
                   updateTrend('residenceTrendWeek',curResW,pRes);
                   updateTrend('visaTrendWeek',curVisaW,pVisa);
                   $('#trendWeek').show();
                },'json');
            }, 'json');
        }

        // populate dropdowns and initial weekly chart
        populateWeekMonthDropdown();
        loadWeeklyChart($('#yearSelect').val(), $('#weekMonthSelect').val());

        function refreshWeekly(){
            loadWeeklyChart($('#yearSelect').val(), $('#weekMonthSelect').val());
        }

        $('#yearSelect').on('change', refreshWeekly);
        $('#weekMonthSelect').on('change', refreshWeekly);
    }, 'json');

    // Update based on current comparison mode
    function updateNavbarFromDashboard() {
        if (!window.comparisonData) return;
        
        // IMPORTANT: The most reliable approach is to directly copy the HTML from dashboard badges
        // to navbar badges to ensure they show exactly the same content
        const ticketBadge = document.getElementById('ticketChange');
        const residenceBadge = document.getElementById('residenceChange');
        const visaBadge = document.getElementById('visaChange');
        
        if (!ticketBadge || !residenceBadge || !visaBadge) {
            console.error('Dashboard badges not found');
            return;
        }
        
        // Copy the exact HTML from dashboard badges to navbar badges
        const ticketNavBadge = document.getElementById('ticket-trend-nav');
        const residenceNavBadge = document.getElementById('residence-trend-nav');
        const visaNavBadge = document.getElementById('visa-trend-nav');
        
        if (ticketNavBadge && ticketBadge) {
            // Copy classes
            ticketNavBadge.className = ticketBadge.className.replace('bg-info', 'bg-secondary');
            // Copy HTML content (this ensures exact same format and values)
            ticketNavBadge.innerHTML = ticketBadge.innerHTML;
            
            // Fix icon classes if needed
            if (ticketNavBadge.innerHTML.includes('fas fa-')) {
                ticketNavBadge.innerHTML = ticketNavBadge.innerHTML.replace('fas fa-', 'fa fa-');
            }
        }
        
        if (residenceNavBadge && residenceBadge) {
            // Copy classes
            residenceNavBadge.className = residenceBadge.className.replace('bg-info', 'bg-secondary');
            // Copy HTML content
            residenceNavBadge.innerHTML = residenceBadge.innerHTML;
            
            // Fix icon classes if needed
            if (residenceNavBadge.innerHTML.includes('fas fa-')) {
                residenceNavBadge.innerHTML = residenceNavBadge.innerHTML.replace('fas fa-', 'fa fa-');
            }
        }
        
        if (visaNavBadge && visaBadge) {
            // Copy classes
            visaNavBadge.className = visaBadge.className.replace('bg-info', 'bg-secondary');
            // Copy HTML content
            visaNavBadge.innerHTML = visaBadge.innerHTML;
            
            // Fix icon classes if needed
            if (visaNavBadge.innerHTML.includes('fas fa-')) {
                visaNavBadge.innerHTML = visaNavBadge.innerHTML.replace('fas fa-', 'fa fa-');
            }
        }
        
        console.log('Directly copied badge HTML from dashboard to navbar');
    }
});
</script>

<?php include 'footer.php'; ?> 