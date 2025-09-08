<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - SOCOTECO II</title>
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
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid var(--primary-orange);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
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
                    <h1 class="display-4 fw-bold mb-4">Company Profile</h1>
                    <p class="lead mb-4">Learn about SOCOTECO II's history, mission, and commitment to serving our communities.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-building" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title mb-0"><i class="fas fa-building me-2"></i>South Cotabato II Electric Cooperative, Inc. (SOCOTECO II)</h3>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="text-primary mb-3">South Cotabato II Electric Cooperative, Inc. (SOCOTECO II)</h4>
                            
                            <div class="mb-4">
                                <h5>About Us</h5>
                                <p class="text-justify">
                                    The South Cotabato II Electric Cooperative, Inc. (SOCOTECO II) is an active partner of the national government in the successful implementation of Rural Electrification Program. Organized on May 7, 1977 by virtue of Presidential Decree 269, it is a non-stock and non-profit electric cooperative, supervised and regulated by the National Electrification Administration.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5>Service Coverage</h5>
                                <p class="text-justify">
                                    Its franchise area include General Santos City, Sarangani Province and Tupi and Polomolok of South Cotabato. It has accomplished the 100% barangay electrification mandate in 2010 and continues to expand its distribution system to unenergized sitios and puroks through internal fund and government subsidies.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5>Achievements</h5>
                                <p class="text-justify">
                                    It remains to one of the cheapest distribution utilities in the country while working a highly-challenging Philippine power industry. As of December 2015, it has the highest power sales among electric cooperatives at 762 GWH sales. It has also pioneered the establishment of embedded generators and base load power plant to address supply sufficiency.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5>Member Benefits</h5>
                                <p class="text-justify">
                                    Despite being non-stock non-profit orientation, SOCOTECO II sustained giving of financial benefits to its members. For the year 2015, it gave back Php39.4 Million to its members in various forms.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5>Social Impact</h5>
                                <p class="text-justify">
                                    Through the provision of energy services, especially in the far-flung and unviable areas, SOCOTECO II helped in the achievement of social objectives and generation of economic growth in the Philippines.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5>Service Area</h5>
                                <p>We serve the following areas:</p>
                                <ul>
                                    <li>General Santos City</li>
                                    <li>Polomolok</li>
                                    <li>Tupi</li>
                                    <li>Alabel</li>
                                    <li>Malapatan</li>
                                    <li>Glan</li>
                                    <li>Malandag</li>
                                    <li>Malungon</li>
                                    <li>Maasim</li>
                                    <li>Kiamba</li>
                                    <li>Maitum</li>
                                    <li>Calumpang</li>
                                </ul>
                            </div>

                            <div class="mb-4">
                                <h5>Key Statistics</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="stat-card p-3 bg-light rounded">
                                            <h6 class="text-primary">Power Sales (2015)</h6>
                                            <h4 class="mb-0">762 GWH</h4>
                                            <small class="text-muted">Highest among electric cooperatives</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="stat-card p-3 bg-light rounded">
                                            <h6 class="text-primary">Member Benefits (2015)</h6>
                                            <h4 class="mb-0">₱39.4M</h4>
                                            <small class="text-muted">Returned to members</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>About the Logo</h5>
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <img src="./img/logo1.png" alt="SOCOTECO II Logo" class="img-fluid" style="max-height: 150px;">
                                    </div>
                                    <div class="col-md-9">
                                        <h6>Symbolism:</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Sparkling ray of lightning</strong> – forms the letter S, which stands for SOCOTECO II; symbolizes power</li>
                                            <li><strong>Radiant blue</strong> – symbolizes the burning desire to be of service to our customers</li>
                                            <li><strong>White color</strong> – at the base of the lightning is the light at a distance which represents the vision of SOCOTECO II</li>
                                            <li><strong>Blended colors of yellow, orange and red</strong> – connotes the color of Sun (fire) that radiates energy and is the ultimate source of light</li>
                                            <li><strong>Three-legged stripes</strong> – implies a strong foundation for SOCOTECO II. In engineering term, a three-legged table has a stronger base than that of a four-legged table. It represents the three major traits of SOCOTECO II necessary to attain its vision. These are commitment, endurance and perseverance.</li>
                                            <li><strong>Circle/circular motion/strokes of color</strong> – suggests an endless flow of action in our service to the customers.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Contact Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Main Office:</strong><br>
                                    Jose Catolico Avenue, Brgy Lagao<br>
                                    General Santos City, 9500</p>
                                    
                                    <p><strong>Phone Numbers:</strong><br>
                                    (083) 553-5848 to 50<br>
                                    (083) 552-3964<br>
                                    (083) 552-4313<br>
                                    (083) 552-4322</p>
                                    
                                    <p><strong>Mobile:</strong><br>
                                    09177205365 / 09124094971</p>
                                    
                                    <p><strong>Email:</strong><br>
                                    info@socoteco2.com</p>
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
                        <li><a href="vision_mission.php" class="text-white-50 text-decoration-none">Vision, Mission & Values</a></li>
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
