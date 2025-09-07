<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board of Directors - SOCOTECO II</title>
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
                    <h1 class="display-4 fw-bold mb-4">Board of Directors</h1>
                    <p class="lead mb-4">Meet the dedicated leaders guiding SOCOTECO II towards excellence.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-users" style="font-size: 8rem; opacity: 0.3;"></i>
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
                    <h3 class="card-title mb-0"><i class="fas fa-users me-2"></i>Board of Directors</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="lead">The Board of Directors information will be updated with the official details from the SOCOTECO II website.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Chairman</h5>
                                    <h6 class="text-primary">[Chairman Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Vice Chairman</h5>
                                    <h6 class="text-primary">[Vice Chairman Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Secretary</h5>
                                    <h6 class="text-primary">[Secretary Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Treasurer</h5>
                                    <h6 class="text-primary">[Treasurer Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Director</h5>
                                    <h6 class="text-primary">[Director Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Director</h5>
                                    <h6 class="text-primary">[Director Name]</h6>
                                    <p class="text-muted">Representing [Area]</p>
                                    <p class="small">Term: [Year] - [Year]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Board Responsibilities</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Governance</h5>
                                            <ul>
                                                <li>Strategic planning and policy formulation</li>
                                                <li>Oversight of cooperative operations</li>
                                                <li>Financial management and accountability</li>
                                                <li>Risk management and compliance</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Community Service</h5>
                                            <ul>
                                                <li>Representing member interests</li>
                                                <li>Community development initiatives</li>
                                                <li>Stakeholder engagement</li>
                                                <li>Transparency and communication</li>
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
