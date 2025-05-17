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
});
</script>

<?php include 'footer.php'; ?> 