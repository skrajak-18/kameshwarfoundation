<?php
session_start();
include 'db_connect.php';

// Fetch Top 3 Campaigns based on highest raised amount
$sql_top_campaigns = "SELECT c.*, 
        COALESCE(SUM(d.amount), 0) as raised, 
        COUNT(d.id) as donors 
        FROM campaigns c 
        LEFT JOIN donations d ON c.id = d.campaign_id AND d.payment_status = 'Success' 
        GROUP BY c.id ORDER BY raised DESC LIMIT 3";
$top_campaigns = $conn->query($sql_top_campaigns);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Kameshwar Foundation | Home</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #0047AB; 
            --primary-orange: #FF7F00; 
            --primary-green: #008000; 
            --dark-bg: #111a2f; 
            --light-bg: #f4f7f6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .modern-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .nav-link {
            color: var(--primary-blue) !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            position: relative;
            margin: 0 12px;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-orange);
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--primary-orange) !important; }

        .custom-toggler { border: none; background: transparent; width: 45px; height: 32px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .custom-toggler span { display: block; height: 4px; width: 100%; background-color: var(--primary-blue); border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center; }
        .custom-toggler:focus { outline: none; box-shadow: none; }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); }
        .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; }
        .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }

        /* Hero Carousel */
        .hero-img { height: 85vh; object-fit: cover; filter: brightness(0.4); }
        .carousel-caption { bottom: 10%; padding-bottom: 30px; }
        .carousel-caption h2 { font-size: 3.5rem; font-weight: 700; text-shadow: 2px 2px 10px rgba(0,0,0,0.8); animation: fadeInDown 1s ease-out; }
        .carousel-caption p { font-size: 1.3rem; text-shadow: 1px 1px 5px rgba(0,0,0,0.8); animation: fadeInUp 1s ease-out 0.5s; animation-fill-mode: both; }

        .section-title { color: var(--primary-blue); font-weight: 700; position: relative; display: inline-block; margin-bottom: 2rem; }
        .section-title::after { content: ''; position: absolute; width: 50px; height: 3px; background: var(--primary-orange); bottom: -10px; left: 50%; transform: translateX(-50%); }

        .modern-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; border-bottom: 4px solid transparent; }
        .modern-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); border-bottom: 4px solid var(--primary-orange); }

        .impact-section { background: linear-gradient(135deg, var(--dark-bg), #002244); color: white; }
        .impact-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-radius: 15px; padding: 40px 20px; transition: transform 0.3s; }
        .impact-card:hover { transform: translateY(-10px); border-color: var(--primary-orange); }

        /* Campaign Cards Styles Added */
        .camp-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.3s; height: 100%; display: flex; flex-direction: column; text-align: left; }
        .camp-card:hover { transform: translateY(-10px); }
        .camp-img-box { position: relative; height: 220px; }
        .camp-img { width: 100%; height: 100%; object-fit: cover; }
        .tax-badge { position: absolute; top: 15px; right: 15px; background: #8bc34a; color: white; padding: 5px 12px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; }
        .camp-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        .camp-title { font-weight: 700; color: var(--primary-blue); font-size: 1.1rem; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .progress-bar-custom { height: 8px; background: #e0e5ec; border-radius: 10px; overflow: hidden; margin-bottom: 15px; }
        .progress-fill { height: 100%; background: #8bc34a; border-radius: 10px; }
        .stats-row { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 0.9rem; }
        .stat-box { text-align: center; }
        .stat-val { font-weight: 700; color: #333; font-size: 1rem; }
        .stat-lbl { color: #888; font-size: 0.8rem; }
        .btn-donate-card { background: #ff4757; color: white; width: 100%; border-radius: 10px; padding: 12px; font-weight: bold; text-align: center; text-decoration: none; transition: 0.3s; margin-top: auto; display: block;}
        .btn-donate-card:hover { background: #ff6b81; color: white; }

        /* Footer */
        .footer { background-color: var(--dark-bg); color: #ccc; }
        .footer-heading { color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
        .footer-heading::after { content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 40px; background-color: var(--primary-orange); }
        .footer-links li { margin-bottom: 15px; font-size: 0.95rem; }
        .footer-links a { color: #b0b8c9; text-decoration: none; transition: 0.3s; display: inline-block; }
        .footer-links a:hover { color: var(--primary-orange); transform: translateX(5px); }
        .developer-credit { color: var(--primary-orange); font-weight: 600; letter-spacing: 1px; }
        .contact-icon-box { background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px; }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg modern-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/logoo.jpg" alt="Kameshwar Foundation Logo" height="65">
            </a>
            
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="campaigns.php">CAMPAIGNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="donate.php" class="btn w-100" style="background: linear-gradient(45deg, #FF7F00, #ffa502); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(255, 127, 0, 0.4); transition: all 0.3s ease; border: none;" onmouseover="this.style.background='linear-gradient(45deg, #e67300, #ff9f00)'; this.style.boxShadow='0 6px 20px rgba(255, 127, 0, 0.6)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='linear-gradient(45deg, #FF7F00, #ffa502)'; this.style.boxShadow='0 4px 15px rgba(255, 127, 0, 0.4)'; this.style.transform='translateY(0)';">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/h1.jpeg" class="d-block w-100 hero-img" alt="Education Materials">
                <div class="carousel-caption d-flex flex-column justify-content-end align-items-center h-100">
                    <h2>Empowering Through Resources</h2>
                    <p>We provide free education materials to underprivileged children to support their learning journey.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/h4.jpeg" class="d-block w-100 hero-img" alt="Financial Help">
                <div class="carousel-caption d-flex flex-column justify-content-end align-items-center h-100">
                    <h2>Uplifting Communities</h2>
                    <p>Offering crucial financial assistance to families in need, ensuring a stable and secure future.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/h6.jpeg" class="d-block w-100 hero-img" alt="Free Education">
                <div class="carousel-caption d-flex flex-column justify-content-end align-items-center h-100">
                    <h2>Knowledge is Power</h2>
                    <p>Providing free, high-quality education to empower underprivileged children and transform lives.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section class="py-5" style="background-color: var(--light-bg);">
        <div class="container text-center">
            <h6 class="text-orange fw-bold tracking-wide" data-aos="fade-up">TOP CAUSES</h6>
            <h2 class="section-title" data-aos="fade-up">Our Campaigns</h2>
            
            <div class="row g-4 mt-2">
                <?php 
                if($top_campaigns && $top_campaigns->num_rows > 0):
                    while($camp = $top_campaigns->fetch_assoc()): 
                        $goal = $camp['goal_amount'];
                        $raised = $camp['raised'];
                        $percent = ($goal > 0) ? ($raised / $goal) * 100 : 0;
                        if($percent > 100) $percent = 100;
                ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="camp-card">
                        <div class="camp-img-box">
                            <img src="img/<?php echo $camp['image']; ?>" class="camp-img" alt="Campaign">
                            <span class="tax-badge"><i class="fas fa-check-circle"></i> Tax Benefit</span>
                        </div>
                        <div class="camp-body">
                            <h5 class="camp-title"><?php echo $camp['title']; ?></h5>
                            
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge bg-success"><?php echo round($percent); ?>%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: <?php echo $percent; ?>%;"></div>
                            </div>
                            
                            <div class="stats-row">
                                <div class="stat-box">
                                    <div class="stat-lbl">Goal</div>
                                    <div class="stat-val">₹<?php echo number_format($goal); ?></div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-lbl">Raised</div>
                                    <div class="stat-val text-success">₹<?php echo number_format($raised); ?></div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-lbl">Donors</div>
                                    <div class="stat-val"><?php echo $camp['donors']; ?></div>
                                </div>
                            </div>
                            
                            <a href="campaigns_detail.php?id=<?php echo $camp['id']; ?>" class="btn-donate-card">Donate Now</a>
                        </div>
                    </div>
                </div>
                <?php 
                    endwhile; 
                else:
                    echo "<div class='col-12 text-muted mt-4'>No campaigns found. Check back later!</div>";
                endif;
                ?>
            </div>

            <div class="mt-5" data-aos="fade-up">
                <a href="campaigns.php" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-bold" style="border-color: var(--primary-blue); color: var(--primary-blue); border-width: 2px;">
                    View All Campaigns <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container mt-4">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4" data-aos="fade-right">
                    <div class="position-relative">
                        <img src="img/founder.jpeg" alt="Founder" class="img-fluid rounded-4 shadow-lg w-100" style="border: 5px solid var(--primary-orange);">
                        <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded-end-3 shadow" style="transform: translateY(20px);">
                            <h5 class="fw-bold text-blue mb-0">Kameshwar Yadav</h5>
                            <small class="text-muted fw-bold">Founder</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-lg-5" data-aos="fade-left">
                    <h6 class="text-orange fw-bold tracking-wide">ABOUT THE FOUNDER</h6>
                    <h2 class="mb-4 text-blue fw-bold">Driven by Compassion, Building a Better Tomorrow</h2>
                    <p class="text-muted" style="line-height: 1.8;">Registered as a non-profit in Bihar, the Kameshwar Foundation is driven by a profound commitment to social welfare. Our founder realized early on that every individual deserves access to basic rights, education, and financial stability, leading to the birth of this noble initiative.</p>
                    <a href="about.php" class="btn btn-outline-primary mt-3 px-4 py-2 rounded-pill" style="border-color: var(--primary-blue); color: var(--primary-blue);">Read Full Story <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--light-bg);">
        <div class="container text-center" data-aos="fade-up">
            <h6 class="text-orange fw-bold">OUR CORE PURPOSE</h6>
            <h2 class="section-title">Our Mission</h2>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <p class="fs-5 text-secondary fst-italic">"To eradicate poverty through education and financial empowerment, creating a self-sustaining society where absolutely no child is left behind. We envision a world where opportunity is a right, not a privilege."</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="section-title" data-aos="fade-up">What We Do</h2>
            <div class="row mt-5 g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="modern-card">
                        <div class="icon-box bg-light text-blue rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Educational Drives</h4>
                        <p class="text-muted">We organize regular camps and schools to provide free education to children in remote areas.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="modern-card">
                        <div class="icon-box bg-light text-orange rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-pencil-alt fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Study Materials</h4>
                        <p class="text-muted">Distributing books, stationery, and learning tools so kids have everything they need to succeed.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="modern-card">
                        <div class="icon-box bg-light text-green rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Financial Aid</h4>
                        <p class="text-muted">Providing direct financial assistance to destitute families for medical emergencies and sustenance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--light-bg);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h6 class="text-orange fw-bold">INTEGRITY FIRST</h6>
                    <h2 class="mb-4 text-blue fw-bold">Why Trust Us?</h2>
                    <ul class="list-unstyled mt-4">
                        <li class="d-flex mb-4">
                            <i class="fas fa-check-circle text-green fa-2x me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold">100% Transparency</h5>
                                <p class="text-muted">Every rupee donated is meticulously accounted for and displayed in our annual reports.</p>
                            </div>
                        </li>
                        <li class="d-flex mb-4">
                            <i class="fas fa-check-circle text-green fa-2x me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold">Zero Administrative Deduction</h5>
                                <p class="text-muted">Your entire donation goes directly towards our core campaigns and field operations.</p>
                            </div>
                        </li>
                        <li class="d-flex mb-4">
                            <i class="fas fa-check-circle text-green fa-2x me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold">Government Registered</h5>
                                <p class="text-muted">We are a fully registered non-profit organization complying with all legal regulations.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="img/h2.jpeg" alt="Trust Image" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 impact-section text-center" id="impactSection">
        <div class="container">
            <span class="badge bg-orange text-white px-3 py-2 rounded-pill mb-3" style="background-color: var(--primary-orange);" data-aos="zoom-in">TRANSPARENCY & RESULTS</span>
            <h2 class="mb-5 fw-bold" data-aos="fade-up">Our Global Impact</h2>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="impact-card">
                        <i class="fas fa-users fa-3x text-orange mb-3"></i>
                        <h1 class="fw-bold display-5"><span class="counter" data-target="1200">0</span>+</h1>
                        <p class="mb-0 fs-5">Total Donors</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="impact-card">
                        <i class="fas fa-rupee-sign fa-3x text-orange mb-3"></i>
                        <h1 class="fw-bold display-5">₹<span class="counter" data-target="10">0</span> L</h1>
                        <p class="mb-0 fs-5">Worth Donations</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="impact-card">
                        <i class="fas fa-user-graduate fa-3x text-orange mb-3"></i>
                        <h1 class="fw-bold display-5"><span class="counter" data-target="500">0</span>+</h1>
                        <p class="mb-0 fs-5">Educated Child</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="impact-card">
                        <i class="fas fa-hands-helping fa-3x text-orange mb-3"></i>
                        <h1 class="fw-bold display-5"><span class="counter" data-target="150">0</span>+</h1>
                        <p class="mb-0 fs-5">Active Volunteers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container text-center" data-aos="fade-up">
            <h2 class="section-title">What People Say</h2>
            
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            
                            <div class="carousel-item active">
                                <div class="card border-0 shadow-sm p-5 rounded-4" style="background: var(--light-bg);">
                                    <i class="fas fa-quote-left fa-3x text-green mb-4 opacity-25"></i>
                                    <p class="fst-italic fs-5 text-secondary">"Kameshwar Foundation has completely changed the lives of kids in our village. The free education materials they provided gave these children a reason to smile and dream big again."</p>
                                    <h5 class="fw-bold text-blue mt-4">- Ramesh Kumar</h5>
                                    <small class="text-orange fw-bold">Local Teacher</small>
                                </div>
                            </div>
                            
                            <div class="carousel-item">
                                <div class="card border-0 shadow-sm p-5 rounded-4" style="background: var(--light-bg);">
                                    <i class="fas fa-quote-left fa-3x text-green mb-4 opacity-25"></i>
                                    <p class="fst-italic fs-5 text-secondary">"During our toughest times, the financial aid from the foundation helped my family survive. We are forever grateful to Kameshwar Yadav and the entire team for their selflessness."</p>
                                    <h5 class="fw-bold text-blue mt-4">- Sunita Devi</h5>
                                    <small class="text-orange fw-bold">Beneficiary</small>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <div class="card border-0 shadow-sm p-5 rounded-4" style="background: var(--light-bg);">
                                    <i class="fas fa-quote-left fa-3x text-green mb-4 opacity-25"></i>
                                    <p class="fst-italic fs-5 text-secondary">"Volunteering here has been the most rewarding experience of my life. Seeing the direct impact of our campaigns on the ground is truly incredible. 100% transparent and genuine work."</p>
                                    <h5 class="fw-bold text-blue mt-4">- Aman Singh</h5>
                                    <small class="text-orange fw-bold">Active Volunteer</small>
                                </div>
                            </div>

                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-sm btn-outline-primary rounded-circle me-2" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" style="width: 40px; height: 40px;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary rounded-circle" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" style="width: 40px; height: 40px;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer pt-5 pb-2" style="background-color: #111a2f; color: #ccc;">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">About Foundation</h5>
                    <p class="text-light opacity-75 mt-3 pe-lg-3" style="font-size: 0.95rem; line-height: 1.8;">
                        Kameshwar Foundation is dedicated to empowering communities through education, financial aid, and essential resources. Join hands with us to build a future where every child has the opportunity to thrive.
                    </p>
                    <div class="mt-4 d-flex align-items-center">
                        <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" rel="noopener noreferrer" style="color: #ccc; margin-right: 1.5rem; transition: 0.3s;" onmouseover="this.style.color='#FF7F00'" onmouseout="this.style.color='#ccc'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                        </a>
                        
                        <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" rel="noopener noreferrer" style="color: #ccc; margin-right: 1.5rem; transition: 0.3s;" onmouseover="this.style.color='#FF7F00'" onmouseout="this.style.color='#ccc'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                        </a>
                        
                        <a href="https://youtube.com/@kameshwary0065" target="_blank" rel="noopener noreferrer" style="color: #ccc; transition: 0.3s;" onmouseover="this.style.color='#FF7F00'" onmouseout="this.style.color='#ccc'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">Quick Links</h5>
                    <ul class="list-unstyled mt-3" style="padding-left: 0;">
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="index.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Home</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="about.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">About Us</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="campaigns.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Campaigns</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="donate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Monthly Donation</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="contact.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Contact Us</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="terms.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Terms & Condition</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="privacy.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">Important Links</h5>
                    <ul class="list-unstyled mt-3" style="padding-left: 0;">
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="volunteer.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Become a Volunteer</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="donate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Make a Donation</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="gallery.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Gallery</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="certificate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Download Certificate</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="return.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Return Policy</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="refund.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Refund Policy</a></li>
                        <li style="margin-bottom: 15px; font-size: 0.95rem;"><a href="admin_login.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Admin Panel</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">Get In Touch</h5>
                    <ul class="list-unstyled text-light opacity-75 mt-3" style="padding-left: 0;">
                        <li class="mb-4 d-flex">
                            <div class="contact-icon-box me-3" style="background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px;">
                                <i class="fas fa-map-marker-alt" style="color: #FF7F00;"></i>
                            </div>
                            <span style="font-size: 0.95rem; color: #ccc;">Registered in Bihar<br>CIN: U88900BR2026NPL081824<br>Patna, Bihar</span>
                        </li>
                        <li class="mb-4 d-flex align-items-center">
                            <div class="contact-icon-box me-3" style="background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px;">
                                <i class="fas fa-phone-alt" style="color: #FF7F00;"></i>
                            </div>
                            <span style="font-size: 0.95rem; color: #ccc;">+91 8757490154</span> 
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="contact-icon-box me-3" style="background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px;">
                                <i class="fas fa-envelope" style="color: #FF7F00;"></i>
                            </div>
                            <span style="font-size: 0.95rem; color: #ccc;">info@kameshwarfoundation.in</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr style="border-color: #555; margin-top: 1.5rem; margin-bottom: 1rem;">
            
            <div class="row align-items-center pb-3">
                <div class="col-md-6 text-center text-md-start text-light opacity-75" style="font-size: 0.9rem; color: #ccc;">
                    &copy; 2026 Kameshwar Foundation. All Rights Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0" style="font-size: 0.9rem;">
                    <span class="text-light opacity-75" style="color: #ccc;">Developed by</span> <span class="developer-credit" style="color: #FF7F00; font-weight: 600; letter-spacing: 1px;">Satyam kumar</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS Scroll Animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Dynamic Number Counter Animation Logic
        const counters = document.querySelectorAll('.counter');
        let hasCounted = false;

        const countUp = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const duration = 2000; 
                const increment = target / (duration / 30); 

                let current = 0;
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current);
                        setTimeout(updateCounter, 30);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });
        };

        const impactSection = document.getElementById('impactSection');
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !hasCounted) {
                countUp();
                hasCounted = true; 
            }
        }, { threshold: 0.3 });

        if (impactSection) {
            observer.observe(impactSection);
        }
    </script>
</body>
</html>