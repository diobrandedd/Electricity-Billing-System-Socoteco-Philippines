<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperative Activities - SOCOTECO II</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #FF9A00;
            --secondary-yellow: #FFD93D;
            --dark-blue: #1e3a8a;
            --light-gray: #f8f9fa;
        }
        
        body {
            background-color: var(--light-gray);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-yellow));
            padding: 60px 0;
            color: white;
        }
        
        .content-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .content-card .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-yellow));
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }
        
        .btn-primary:hover {
            background-color: #e68900;
            border-color: #e68900;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: var(--primary-orange)">
        <div class="container">
            <a class="navbar-brand" href="users/userindex.php">
                <img src="img/socotecoLogo.png" alt="SOCOTECO II" class="d-inline-block align-text-top" style="max-height: 60px;">
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white">
                    <i class="fas fa-phone me-2"></i>(083) 553-5848 to 50
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: var(--secondary-yellow)">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="users/userindex.php"><i class="fas fa-home me-1"></i>Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-building me-1"></i>My Coop
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="company_profile.php"><i class="fas fa-info-circle me-2"></i>Company Profile</a></li>
                            <li><a class="dropdown-item" href="vision_mission.php"><i class="fas fa-eye me-2"></i>Vision, Mission, & Values</a></li>
                            <li><a class="dropdown-item" href="board_directors.php"><i class="fas fa-users me-2"></i>Board of Directors</a></li>
                            <li><a class="dropdown-item" href="management_team.php"><i class="fas fa-user-tie me-2"></i>Management Team</a></li>
                            <li><a class="dropdown-item" href="organizational_structure.php"><i class="fas fa-sitemap me-2"></i>Organizational Structure</a></li>
                            <li><a class="dropdown-item" href="coop_activities.php"><i class="fas fa-chart-bar me-2"></i>Coop Activities</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-newspaper me-1"></i>News & Updates
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="news_updates.php"><i class="fas fa-newspaper me-2"></i>Latest News</a></li>
                            <li><a class="dropdown-item" href="coop_activities.php#community"><i class="fas fa-heart me-2"></i>Community Programs (CSR Activities)</a></li>
                            <li><a class="dropdown-item" href="coop_activities.php#supply-demand"><i class="fas fa-chart-line me-2"></i>Daily Supply and Load Demand</a></li>
                            <li><a class="dropdown-item" href="coop_activities.php#generation-breakdown"><i class="fas fa-cogs me-2"></i>Breakdown of Generation Charge</a></li>
                            <li><a class="dropdown-item" href="coop_activities.php#rates"><i class="fas fa-percentage me-2"></i>Effective Rates</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-headset me-1"></i>Customer Service
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="customer_service.php"><i class="fas fa-headset me-2"></i>Customer Service Home</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#contact"><i class="fas fa-phone me-2"></i>Contact Information</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#hours"><i class="fas fa-clock me-2"></i>Operating Hours (Teller)</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#application"><i class="fas fa-search me-2"></i>Electric Service Application Status</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#sms"><i class="fas fa-sms me-2"></i>Register to SMS Broadcast System</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#support"><i class="fas fa-life-ring me-2"></i>Support Channels / Resources</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#complaint"><i class="fas fa-exclamation-triangle me-2"></i>Complaint Form</a></li>
                            <li><a class="dropdown-item" href="customer_service.php#calculator"><i class="fas fa-calculator me-2"></i>Estimated Bill Calculator</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cogs me-1"></i>Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="services.php"><i class="fas fa-cogs me-2"></i>Services Home</a></li>
                            <li><a class="dropdown-item" href="services.php#government"><i class="fas fa-building me-2"></i>Government Subsidized Projects</a></li>
                            <li><a class="dropdown-item" href="services.php#renewable"><i class="fas fa-leaf me-2"></i>Renewable Energy Projects</a></li>
                            <li><a class="dropdown-item" href="services.php#suppliers"><i class="fas fa-industry me-2"></i>Power Suppliers</a></li>
                            <li><a class="dropdown-item" href="services.php#payment"><i class="fas fa-credit-card me-2"></i>Payment Channels</a></li>
                            <li><a class="dropdown-item" href="services.php#electricians"><i class="fas fa-tools me-2"></i>Accredited Electricians</a></li>
                            <li><a class="dropdown-item" href="services.php#downloads"><i class="fas fa-download me-2"></i>Get Files (Downloads)</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="job_opportunity.php"><i class="fas fa-briefcase me-1"></i>Job Opportunity</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php"><i class="fas fa-user me-1"></i>Members Portal</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Cooperative Activities</h1>
                    <p class="lead mb-4">SOCOTECO II Celebrates the 16th National Electrification Awareness Month (NEAM) with Purpose and Action</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-chart-bar" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Cooperative Activities</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="lead">SOCOTECO II Celebrates the 16th National Electrification Awareness Month (NEAM) with Purpose and Action</p>
                        </div>
                    </div>
                    
                    <!-- NEAM 2025 Celebration -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>16th National Electrification Awareness Month (NEAM) 2025</h4>
                                    <p class="text-justify">
                                        In celebration of NEAM 2025, SOCOTECO II proudly continues its commitment to community development through various programs and activities. The cooperative remains dedicated to serving its member-consumers while promoting awareness about electrification and energy efficiency.
                                    </p>
                                    
                                    <h5 class="mt-4 mb-3">Brigada Eskwela, Brigada Electric Check</h5>
                                    <p class="text-justify">
                                        SOCOTECO II's "Brigada Eskwela, Brigada Electric Check" program continues to support schools in their mission to provide quality education. The program focuses on improving school facilities and ensuring the safety of teachers and students through proper electrical maintenance and safety checks.
                                    </p>
                                    
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Program Impact</h6>
                                        <p class="mb-0">Masayang tinanggap ni Gng. Teresa Rosaot, Principal ng Ignacio Solis Sr. Elementary School, ang donasyon at ipinaabot ang kanyang taos-pusong pasasalamat sa SOCOTECO II.</p>
                                    </div>
                                    
                                    <p class="text-justify">
                                        Ang "Brigada Eskwela, Brigada Electric Check" ng SOCOTECO II ay patuloy na kaagapay ng mga paaralan na may layuning mapabuti ang mga pasilidad at matiyak ang kaligtasan ng mga guro at mga mag-aaral.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Community Programs -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-heart me-2"></i>Community Programs (CSR Activities)</h4>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Educational Support</h5>
                                    <p class="card-text">Scholarship programs, school electrification, and educational materials distribution to support local schools and students.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Annual scholarship grants</li>
                                        <li><i class="fas fa-check text-success me-2"></i>School electrification projects</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Educational materials donation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Health & Wellness</h5>
                                    <p class="card-text">Medical missions, health awareness campaigns, and support for local health facilities and programs.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Free medical missions</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Health awareness seminars</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Medical equipment donation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Environmental Protection</h5>
                                    <p class="card-text">Tree planting activities, environmental awareness campaigns, and sustainable energy initiatives.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Annual tree planting</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Environmental seminars</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Clean energy promotion</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Power Supply & Load Demand -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-chart-line me-2"></i>Daily Supply and Load Demand</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Current Power Statistics</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="stat-item p-3 bg-light rounded mb-3">
                                                <h6 class="text-primary">Peak Demand</h6>
                                                <h4 class="mb-0">45.2 MW</h4>
                                                <small class="text-muted">Current peak load</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="stat-item p-3 bg-light rounded mb-3">
                                                <h6 class="text-primary">Available Capacity</h6>
                                                <h4 class="mb-0">50.0 MW</h4>
                                                <small class="text-muted">Total available power</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="stat-item p-3 bg-light rounded mb-3">
                                                <h6 class="text-primary">Reserve Margin</h6>
                                                <h4 class="mb-0">10.6%</h4>
                                                <small class="text-muted">System reliability</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="stat-item p-3 bg-light rounded mb-3">
                                                <h6 class="text-primary">System Loss</h6>
                                                <h4 class="mb-0">8.5%</h4>
                                                <small class="text-muted">Technical losses</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Power Sources</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-bolt text-warning me-2"></i>Coal Power Plant: 60%</li>
                                        <li class="mb-2"><i class="fas fa-leaf text-success me-2"></i>Renewable Energy: 25%</li>
                                        <li class="mb-2"><i class="fas fa-fire text-danger me-2"></i>Diesel Power Plant: 15%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Generation Charge Breakdown -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-cogs me-2"></i>Breakdown of Generation Charge</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Rate Components (per kWh)</h5>
                                    <div class="rate-breakdown">
                                        <div class="rate-item d-flex justify-content-between mb-2">
                                            <span>Generation Charge</span>
                                            <span class="fw-bold">₱4.50</span>
                                        </div>
                                        <div class="rate-item d-flex justify-content-between mb-2">
                                            <span>Transmission Charge</span>
                                            <span class="fw-bold">₱0.80</span>
                                        </div>
                                        <div class="rate-item d-flex justify-content-between mb-2">
                                            <span>Distribution Charge</span>
                                            <span class="fw-bold">₱1.20</span>
                                        </div>
                                        <div class="rate-item d-flex justify-content-between mb-2">
                                            <span>System Loss</span>
                                            <span class="fw-bold">₱0.50</span>
                                        </div>
                                        <div class="rate-item d-flex justify-content-between mb-2">
                                            <span>VAT (12%)</span>
                                            <span class="fw-bold">₱0.84</span>
                                        </div>
                                        <hr>
                                        <div class="rate-item d-flex justify-content-between">
                                            <span class="fw-bold">Total Rate</span>
                                            <span class="fw-bold text-primary">₱7.84</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Effective Rates by Category</h5>
                                    <div class="rate-categories">
                                        <div class="rate-category mb-3">
                                            <h6 class="text-primary">Residential</h6>
                                            <p class="mb-1">First 200 kWh: ₱7.84/kWh</p>
                                            <p class="mb-1">201-300 kWh: ₱8.50/kWh</p>
                                            <p class="mb-1">Above 300 kWh: ₱9.20/kWh</p>
                                        </div>
                                        <div class="rate-category mb-3">
                                            <h6 class="text-primary">Commercial</h6>
                                            <p class="mb-1">All consumption: ₱8.20/kWh</p>
                                        </div>
                                        <div class="rate-category">
                                            <h6 class="text-primary">Industrial</h6>
                                            <p class="mb-1">All consumption: ₱7.50/kWh</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>Recent Activities</h4>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">September 2024</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Annual General Membership Assembly</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tree Planting Activity in Tupi</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Medical Mission in Polomolok</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>School Electrification Project Launch</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Upcoming Events</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Environmental Awareness Seminar</li>
                                        <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Member-Consumer Education Program</li>
                                        <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Renewable Energy Workshop</li>
                                        <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Annual Christmas Outreach Program</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">SOCOTECO II</h5>
                    <p>South Cotabato II Electric Cooperative, Inc.</p>
                    <p>Jose Catolico Avenue, Brgy Lagao, General Santos City, 9500</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <p><i class="fas fa-phone me-2"></i>(083) 553-5848 to 50</p>
                    <p><i class="fas fa-mobile-alt me-2"></i>09177205365 / 09124094971</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="company_profile.php" class="text-white-50 text-decoration-none">Company Profile</a></li>
                        <li><a href="vision_mission.php" class="text-white-50 text-decoration-none">Vision, Mission & Values</a></li>
                        <li><a href="board_directors.php" class="text-white-50 text-decoration-none">Board of Directors</a></li>
                        <li><a href="management_team.php" class="text-white-50 text-decoration-none">Management Team</a></li>
                        <li><a href="organizational_structure.php" class="text-white-50 text-decoration-none">Organizational Structure</a></li>
                        <li><a href="coop_activities.php" class="text-white-50 text-decoration-none">Coop Activities</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2024 SOCOTECO II Official Website. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
