<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOCOTECO II - Customer Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #FF9A00;
            --secondary-yellow: #FFD93D;
            --dark-blue: #1e3a8a;
            --light-gray: #f8f9fa;
        }
        
        .navbar-brand img {
            max-height: 60px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-yellow));
            padding: 60px 0;
            color: white;
        }
        
        .service-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--primary-orange);
            margin-bottom: 1rem;
        }
        
        .news-card {
            border-left: 4px solid var(--primary-orange);
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .contact-info {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 20px;
        }
        
        .footer {
            background: var(--dark-blue);
            color: white;
            padding: 40px 0 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }
        
        .btn-primary:hover {
            background-color: #e68900;
            border-color: #e68900;
        }
        
        .navbar-nav .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 10px;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-orange) !important;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: var(--primary-orange)">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../img/socotecoLogo.png" alt="SOCOTECO II" class="d-inline-block align-text-top">
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
                        <a class="nav-link" href="../dashboard.php"><i class="fas fa-home me-1"></i>Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-building me-1"></i>My Coop
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../company_profile.php"><i class="fas fa-info-circle me-2"></i>Company Profile</a></li>
                            <li><a class="dropdown-item" href="../vision_mission.php"><i class="fas fa-eye me-2"></i>Vision, Mission, & Values</a></li>
                            <li><a class="dropdown-item" href="../board_directors.php"><i class="fas fa-users me-2"></i>Board of Directors</a></li>
                            <li><a class="dropdown-item" href="../management_team.php"><i class="fas fa-user-tie me-2"></i>Management Team</a></li>
                            <li><a class="dropdown-item" href="../organizational_structure.php"><i class="fas fa-sitemap me-2"></i>Organizational Structure</a></li>
                            <li><a class="dropdown-item" href="../coop_activities.php"><i class="fas fa-chart-bar me-2"></i>Coop Activities</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-newspaper me-1"></i>News & Updates
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../news_updates.php"><i class="fas fa-newspaper me-2"></i>Latest News</a></li>
                            <li><a class="dropdown-item" href="../coop_activities.php#community"><i class="fas fa-heart me-2"></i>Community Programs (CSR Activities)</a></li>
                            <li><a class="dropdown-item" href="../coop_activities.php#supply-demand"><i class="fas fa-chart-line me-2"></i>Daily Supply and Load Demand</a></li>
                            <li><a class="dropdown-item" href="../coop_activities.php#generation-breakdown"><i class="fas fa-cogs me-2"></i>Breakdown of Generation Charge</a></li>
                            <li><a class="dropdown-item" href="../coop_activities.php#rates"><i class="fas fa-percentage me-2"></i>Effective Rates</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-headset me-1"></i>Customer Service
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../customer_service.php"><i class="fas fa-headset me-2"></i>Customer Service Home</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#contact"><i class="fas fa-phone me-2"></i>Contact Information</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#hours"><i class="fas fa-clock me-2"></i>Operating Hours (Teller)</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#application"><i class="fas fa-search me-2"></i>Electric Service Application Status</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#sms"><i class="fas fa-sms me-2"></i>Register to SMS Broadcast System</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#support"><i class="fas fa-life-ring me-2"></i>Support Channels / Resources</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#complaint"><i class="fas fa-exclamation-triangle me-2"></i>Complaint Form</a></li>
                            <li><a class="dropdown-item" href="../customer_service.php#calculator"><i class="fas fa-calculator me-2"></i>Estimated Bill Calculator</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cogs me-1"></i>Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../services.php"><i class="fas fa-cogs me-2"></i>Services Home</a></li>
                            <li><a class="dropdown-item" href="../services.php#government"><i class="fas fa-building me-2"></i>Government Subsidized Projects</a></li>
                            <li><a class="dropdown-item" href="../services.php#renewable"><i class="fas fa-leaf me-2"></i>Renewable Energy Projects</a></li>
                            <li><a class="dropdown-item" href="../services.php#suppliers"><i class="fas fa-industry me-2"></i>Power Suppliers</a></li>
                            <li><a class="dropdown-item" href="../services.php#payment"><i class="fas fa-credit-card me-2"></i>Payment Channels</a></li>
                            <li><a class="dropdown-item" href="../services.php#electricians"><i class="fas fa-tools me-2"></i>Accredited Electricians</a></li>
                            <li><a class="dropdown-item" href="../services.php#downloads"><i class="fas fa-download me-2"></i>Get Files (Downloads)</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../job_opportunity.php"><i class="fas fa-briefcase me-1"></i>Job Opportunity</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../users.php"><i class="fas fa-user me-1"></i>Members Portal</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Hi!</h1>
                    <h2 class="h3 mb-4">How may we be of service to you today?</h2>
                    <p class="lead mb-4">Welcome to SOCOTECO II Customer Portal. Manage your electricity account, view bills, and access services online.</p>
                    <div class="d-flex gap-3">
                        <a href="../bills.php" class="btn btn-light btn-lg">
                            <i class="fas fa-file-invoice me-2"></i>View My Bills
                        </a>
                        <a href="../payments.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Make Payment
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-bolt" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="display-5 fw-bold text-dark">Our Services</h2>
                    <p class="lead text-muted">Everything you need for your electricity needs</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-invoice-dollar service-icon"></i>
                            <h5 class="card-title">Bill Inquiry</h5>
                            <p class="card-text">View and download your electricity bills online</p>
                            <a href="../bills.php" class="btn btn-primary">View Bills</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-credit-card service-icon"></i>
                            <h5 class="card-title">Payment</h5>
                            <p class="card-text">Pay your bills through various payment channels</p>
                            <a href="../payments.php" class="btn btn-primary">Make Payment</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-tachometer-alt service-icon"></i>
                            <h5 class="card-title">Meter Reading</h5>
                            <p class="card-text">Submit and view your meter readings</p>
                            <a href="../meter_readings.php" class="btn btn-primary">Submit Reading</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-headset service-icon"></i>
                            <h5 class="card-title">Support</h5>
                            <p class="card-text">Get help and support for your concerns</p>
                            <a href="../settings.php#support" class="btn btn-primary">Get Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-5" style="background-color: var(--light-gray)">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="display-5 fw-bold text-dark">Latest News!</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="news-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-primary fw-bold mb-0">NOTICE OF SCHEDULED POWER INTERRUPTION</h6>
                            <small class="text-muted">September 09, 2025</small>
                        </div>
                        <p class="text-muted mb-2">Friday, 5 September, 2025 10:19 AM</p>
                        <a href="../reports.php#power-interruption" class="text-decoration-none">Read more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="news-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-danger fw-bold mb-0">EMERGENCY POWER INTERRUPTION</h6>
                            <small class="text-muted">September 06, 2025</small>
                        </div>
                        <p class="text-muted mb-2">Thursday, 4 September, 2025 5:08 PM</p>
                        <a href="../reports.php#emergency-interruption" class="text-decoration-none">Read more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-4">Our Locations</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-info mb-4">
                                <h5 class="fw-bold text-primary">Main Office</h5>
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Socoteco II J. Catolico Avenue, Lagao, General Santos City</p>
                                <p class="mb-1"><i class="fas fa-phone me-2"></i>(083) 553-5848 to 50</p>
                                <p class="mb-0"><i class="fas fa-mobile-alt me-2"></i>09177205365 / 09124094971</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary mb-3">Sub-Offices</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Calumpang</strong><br>09639331803</p>
                                    <p class="mb-1"><strong>Polomolok</strong><br>09815059290</p>
                                    <p class="mb-1"><strong>Tupi</strong><br>09085663964</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Alabel</strong><br>09977547974</p>
                                    <p class="mb-1"><strong>Malapatan</strong><br>09554488417</p>
                                    <p class="mb-1"><strong>Glan</strong><br>09752359732</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h5 class="fw-bold text-primary mb-3">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="../bills.php#calculator" class="btn btn-outline-primary">
                                <i class="fas fa-calculator me-2"></i>Bill Calculator
                            </a>
                            <a href="../reports.php#downloads" class="btn btn-outline-primary">
                                <i class="fas fa-file-alt me-2"></i>Download Forms
                            </a>
                            <a href="../settings.php#faq" class="btn btn-outline-primary">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                            <a href="../customers.php#complaint" class="btn btn-outline-primary">
                                <i class="fas fa-exclamation-triangle me-2"></i>Report Outage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">SOCOTECO II</h5>
                    <p>South Cotabato II Electric Cooperative, Inc.</p>
                    <p>Jose Catolico Avenue, Brgy Lagao, General Santos City, 9500</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="../settings.php" class="text-white-50 text-decoration-none">Company Profile</a></li>
                        <li><a href="../settings.php#support" class="text-white-50 text-decoration-none">Customer Service</a></li>
                        <li><a href="../payments.php" class="text-white-50 text-decoration-none">Payment Channels</a></li>
                        <li><a href="../reports.php#jobs" class="text-white-50 text-decoration-none">Job Opportunities</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <p><i class="fas fa-phone me-2"></i>(083) 553-5848 to 50</p>
                    <p><i class="fas fa-mobile-alt me-2"></i>09177205365 / 09124094971</p>
                    <div class="mt-3">
                        <a href="https://www.facebook.com/socoteco2" target="_blank" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="https://www.twitter.com/socoteco2" target="_blank" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="https://www.instagram.com/socoteco2" target="_blank" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>