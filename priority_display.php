<?php
/**
 * Real-time Priority Number Display
 * Public display for showing current priority number and queue status
 * This page can be displayed on screens at service centers
 */

require_once 'config/config.php';
require_once 'includes/PriorityNumberGenerator.php';

$priorityGenerator = new PriorityNumberGenerator();
$currentStatus = $priorityGenerator->getCurrentPriorityNumber();
$queueStats = $priorityGenerator->getQueueStatistics();
$upcomingNumbers = $priorityGenerator->getUpcomingPriorityNumbers(5);

// Set page to auto-refresh every 10 seconds
header('Refresh: 10');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priority Queue Display - <?php echo getSystemSetting('company_name', 'SOCOTECO II'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Arial', sans-serif;
            color: white;
            overflow-x: hidden;
        }
        
        .display-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .main-display {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .current-number {
            font-size: 8rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin: 20px 0;
            animation: pulse 2s infinite;
        }
        
        .status-text {
            font-size: 2rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .upcoming-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .upcoming-number {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 20px;
            margin: 5px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .company-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
        }
        
        .company-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .timestamp {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .current-number {
                font-size: 5rem;
            }
            
            .status-text {
                font-size: 1.5rem;
            }
            
            .company-name {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="display-container">
        <!-- Company Header -->
        <div class="company-header fade-in">
            <div class="company-name">
                <i class="fas fa-bolt"></i> <?php echo getSystemSetting('company_name', 'SOCOTECO II'); ?>
            </div>
            <div class="company-subtitle">Priority Queue Display</div>
        </div>
        
        <!-- Main Display -->
        <div class="main-display fade-in">
            <div class="status-text">Currently Serving</div>
            <div class="current-number" id="current-number">
                <?php echo $currentStatus['current_priority_number'] ?? 0; ?>
            </div>
            <div class="status-text">Priority Number</div>
            <?php if ($currentCustomer): ?>
                <div class="customer-info mt-3">
                    <h3 class="text-white" id="current-customer-name">
                        <?php echo htmlspecialchars($currentCustomer['first_name'] . ' ' . $currentCustomer['last_name']); ?>
                    </h3>
                    <p class="text-white-50" id="current-customer-account">
                        Account: <?php echo htmlspecialchars($currentCustomer['account_number']); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Statistics Grid -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-number text-success" id="served-today">
                    <?php echo $currentStatus['served_count'] ?? 0; ?>
                </div>
                <div class="stat-label">Served Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number text-warning" id="pending-today">
                    <?php echo $queueStats['today_pending']; ?>
                </div>
                <div class="stat-label">Pending Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number text-info" id="daily-capacity">
                    <?php echo $currentStatus['daily_capacity'] ?? 1000; ?>
                </div>
                <div class="stat-label">Daily Capacity</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number text-primary" id="total-pending">
                    <?php echo $queueStats['total_pending']; ?>
                </div>
                <div class="stat-label">Total Pending</div>
            </div>
        </div>
        
        <!-- Upcoming Numbers -->
        <?php if (!empty($upcomingNumbers)): ?>
        <div class="upcoming-section fade-in">
            <h4 class="mb-3"><i class="fas fa-clock"></i> Next to be Served</h4>
            <div id="upcoming-numbers">
                <?php foreach ($upcomingNumbers as $number): ?>
                    <span class="upcoming-number"><?php echo $number['priority_number']; ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Timestamp -->
    <div class="timestamp">
        <i class="fas fa-clock"></i> Last updated: <span id="last-updated"><?php echo date('g:i A'); ?></span>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Update display every 10 seconds
            setInterval(updateDisplay, 10000);
            
            // Initial update
            updateDisplay();
        });
        
        function updateDisplay() {
            $.ajax({
                url: 'ajax/priority_display.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update current number with animation
                        var currentNumber = $('#current-number');
                        var newNumber = response.data.current_serving;
                        
                        if (currentNumber.text() != newNumber) {
                            currentNumber.fadeOut(300, function() {
                                $(this).text(newNumber).fadeIn(300);
                            });
                        }
                        
                        // Update customer information
                        if (response.data.current_customer) {
                            $('#current-customer-name').text(response.data.current_customer.name);
                            $('#current-customer-account').text('Account: ' + response.data.current_customer.account_number);
                        } else {
                            $('#current-customer-name').text('');
                            $('#current-customer-account').text('');
                        }
                        
                        // Update statistics
                        $('#served-today').text(response.data.served_today);
                        $('#pending-today').text(response.data.today_pending);
                        $('#daily-capacity').text(response.data.daily_capacity);
                        $('#total-pending').text(response.data.total_pending);
                        
                        // Update upcoming numbers
                        if (response.data.upcoming_numbers && response.data.upcoming_numbers.length > 0) {
                            var upcomingHtml = '';
                            response.data.upcoming_numbers.forEach(function(number) {
                                upcomingHtml += '<span class="upcoming-number">' + number.priority_number + '</span>';
                            });
                            $('#upcoming-numbers').html(upcomingHtml);
                        }
                        
                        // Update timestamp
                        $('#last-updated').text(new Date().toLocaleTimeString());
                    }
                },
                error: function() {
                    console.log('Error updating display');
                }
            });
        }
        
        // Add some visual effects
        $(document).ready(function() {
            // Add blinking effect to current number
            setInterval(function() {
                $('#current-number').toggleClass('text-warning');
            }, 1000);
        });
    </script>
</body>
</html>
