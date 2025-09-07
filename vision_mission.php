<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vision, Mission & Values - SOCOTECO II</title>
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
                    <h1 class="display-4 fw-bold mb-4">Vision, Mission & Values</h1>
                    <p class="lead mb-4">Discover the guiding principles that drive SOCOTECO II's commitment to excellence.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-eye" style="font-size: 8rem; opacity: 0.3;"></i>
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
                    <h3 class="card-title mb-0"><i class="fas fa-eye me-2"></i>Vision, Mission & Core Values</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0"><i class="fas fa-eye me-2"></i>Our Vision</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-justify">
                                        [Vision statement to be updated with official SOCOTECO II vision from their website]
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0"><i class="fas fa-bullseye me-2"></i>Our Mission</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-justify">
                                        [Mission statement to be updated with official SOCOTECO II mission from their website]
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h4 class="mb-0"><i class="fas fa-heart me-2"></i>Core Values</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Service Excellence</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Integrity</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Transparency</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Accountability</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Innovation</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Community Partnership</strong></li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Environmental Stewardship</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Our Commitment</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">To Our Members</h5>
                                            <ul>
                                                <li>Provide reliable and affordable electricity services</li>
                                                <li>Ensure transparent and fair billing practices</li>
                                                <li>Maintain excellent customer service</li>
                                                <li>Support community development initiatives</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">To Our Community</h5>
                                            <ul>
                                                <li>Promote sustainable energy practices</li>
                                                <li>Support local economic development</li>
                                                <li>Invest in infrastructure improvements</li>
                                                <li>Foster environmental responsibility</li>
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
                        <li><a href="board_directors.php" class="text-white-50 text-decoration-none">Board of Directors</a></li>
                        <li><a href="management_team.php" class="text-white-50 text-decoration-none">Management Team</a></li>
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
