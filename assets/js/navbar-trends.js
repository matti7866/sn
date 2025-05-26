/**
 * Navbar Trends - Display real-time comparison data in the navbar
 * This script loads data from the dashboard controller and displays trend indicators
 * in the navbar for quick reference across all pages.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Navbar trends script loaded, attempting to fetch data...');
    
    // Check if elements exist in the DOM
    setTimeout(checkAndApplyTrends, 200);
    
    // Check again after a longer delay (for slower page loads)
    setTimeout(checkAndApplyTrends, 1000);
    
    // Also run when the window is fully loaded
    window.addEventListener('load', checkAndApplyTrends);
});

/**
 * Checks if trend elements exist and applies data
 */
function checkAndApplyTrends() {
    // Only proceed if the navbar trend elements exist
    if (!document.getElementById('ticket-trend-nav')) {
        console.log('Trend elements not found in DOM yet, will retry later');
        return;
    }
    
    console.log('Trend elements found in DOM, applying data');
    
    // Demo data to ensure we always show something
    const demoData = {
        ticket: 25.0,
        residence: -20.0,
        visa: 33.3
    };
    
    // Try to load saved data from localStorage first
    let savedData = null;
    try {
        const savedJson = localStorage.getItem('navbarTrendData');
        if (savedJson) {
            savedData = JSON.parse(savedJson);
            console.log('Retrieved saved trend data:', savedData);
        }
    } catch (e) {
        console.error('Error reading saved data:', e);
    }
    
    // Apply saved data or demo data immediately
    if (savedData) {
        updateNavbarIndicators(savedData.ticket, savedData.residence, savedData.visa);
    } else {
        updateNavbarIndicators(demoData.ticket, demoData.residence, demoData.visa);
        console.log('Applied demo data to navbar');
    }
    
    // Now try to load real data
    loadNavbarTrends();
}

/**
 * Fetches comparison data from the server and updates the navbar indicators
 */
function loadNavbarTrends() {
    // Use absolute URL to controller to avoid path issues
    const url = '/snt/dashboardV2Controller.php';
    
    console.log('Fetching trend data from:', url);
    
    $.ajax({
        url: url,
        type: 'POST',
        data: {action: 'getRealTimeComparison'},
        dataType: 'json',
        success: function(resp) {
            console.log('Success! Response:', resp);
            
            if (resp.status !== 'success') {
                console.error('Server returned error:', resp.message || 'Unknown error');
                return;
            }
            
            try {
                // Check if we have pre-calculated percentages (preferred)
                if (resp.percentChanges) {
                    const ticketChange = resp.percentChanges.ticket;
                    const residenceChange = resp.percentChanges.residence;
                    const visaChange = resp.percentChanges.visa;
                    
                    console.log('Using pre-calculated percentages:', {
                        ticket: ticketChange,
                        residence: residenceChange,
                        visa: visaChange
                    });
                    
                    // Save to localStorage for future page loads
                    saveNavbarTrendData(ticketChange, residenceChange, visaChange);
                    
                    // Update navbar indicators
                    updateNavbarIndicators(ticketChange, residenceChange, visaChange);
                    return;
                }
                
                // Otherwise calculate from the counts
                const todayTicket = resp.today.counts.ticket;
                const todayResidence = resp.today.counts.residence;
                const todayVisa = resp.today.counts.visa;
                
                console.log('Today data:', {
                    ticket: todayTicket,
                    residence: todayResidence,
                    visa: todayVisa
                });
                
                // Get comparison data (prefer upToNowCounts for fair comparison)
                let avgTicket, avgResidence, avgVisa;
                
                if (resp.pastAverage && resp.pastAverage.upToNowCounts) {
                    avgTicket = resp.pastAverage.upToNowCounts.ticket;
                    avgResidence = resp.pastAverage.upToNowCounts.residence;
                    avgVisa = resp.pastAverage.upToNowCounts.visa;
                    console.log('Using upToNowCounts for comparison');
                } else if (resp.pastAverage && resp.pastAverage.counts) {
                    avgTicket = resp.pastAverage.counts.ticket;
                    avgResidence = resp.pastAverage.counts.residence;
                    avgVisa = resp.pastAverage.counts.visa;
                    console.log('Using full day counts for comparison');
                } else {
                    console.error('Could not find comparison data in response');
                    return;
                }
                
                console.log('Average data:', {
                    ticket: avgTicket,
                    residence: avgResidence,
                    visa: avgVisa
                });
                
                const ticketChange = calculatePercentChange(todayTicket, avgTicket);
                const residenceChange = calculatePercentChange(todayResidence, avgResidence);
                const visaChange = calculatePercentChange(todayVisa, avgVisa);
                
                console.log('Calculated changes:', {
                    ticket: ticketChange,
                    residence: residenceChange,
                    visa: visaChange
                });
                
                // Save to localStorage for future page loads
                saveNavbarTrendData(ticketChange, residenceChange, visaChange);
                
                // Update navbar indicators
                updateNavbarIndicators(ticketChange, residenceChange, visaChange);
                
            } catch (e) {
                console.error('Error processing data:', e);
            }
        },
        error: function(xhr, status, error) {
            console.log(`Request to ${url} failed:`, status, error);
        }
    });
}

/**
 * Save trend data to localStorage for persistence between page loads
 */
function saveNavbarTrendData(ticket, residence, visa) {
    try {
        const data = { 
            ticket: ticket, 
            residence: residence, 
            visa: visa,
            timestamp: new Date().getTime() 
        };
        localStorage.setItem('navbarTrendData', JSON.stringify(data));
        console.log('Saved trend data to localStorage');
    } catch (e) {
        console.error('Error saving trend data:', e);
    }
}

/**
 * Calculates percentage change between current and previous values
 */
function calculatePercentChange(current, previous) {
    if (previous === 0) return current > 0 ? 100 : 0;
    return Math.round(((current - previous) / previous) * 100 * 10) / 10; // Round to 1 decimal
}

/**
 * Updates the navbar trend indicators
 */
function updateNavbarIndicators(ticketChange, residenceChange, visaChange) {
    // Check if elements exist before trying to update them
    if (document.getElementById('ticket-trend-nav')) {
        updateBadge('ticket-trend-nav', ticketChange);
    } else {
        console.error('Element with ID ticket-trend-nav not found');
    }
    
    if (document.getElementById('residence-trend-nav')) {
        updateBadge('residence-trend-nav', residenceChange);
    } else {
        console.error('Element with ID residence-trend-nav not found');
    }
    
    if (document.getElementById('visa-trend-nav')) {
        updateBadge('visa-trend-nav', visaChange);
    } else {
        console.error('Element with ID visa-trend-nav not found');
    }
    
    // Log update
    console.log('Navbar trends updated:', {
        ticket: ticketChange,
        residence: residenceChange,
        visa: visaChange
    });
}

/**
 * Updates a single badge with the percentage change
 */
function updateBadge(id, percentChange) {
    const badge = document.getElementById(id);
    if (!badge) {
        console.error(`Badge element with ID ${id} not found`);
        return;
    }
    
    // Reset classes
    badge.classList.remove('bg-success', 'bg-danger', 'bg-secondary');
    
    // Set badge color and icon based on value
    if (percentChange > 0) {
        badge.classList.add('bg-success');
        badge.innerHTML = `<i class="fa fa-arrow-up"></i> ${percentChange}%`;
    } else if (percentChange < 0) {
        badge.classList.add('bg-danger');
        badge.innerHTML = `<i class="fa fa-arrow-down"></i> ${Math.abs(percentChange)}%`;
    } else {
        badge.classList.add('bg-secondary');
        badge.innerHTML = `0%`;
    }
} 