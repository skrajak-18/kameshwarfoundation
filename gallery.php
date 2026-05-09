<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Kameshwar Foundation | Gallery</title>
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
            background-color: var(--light-bg);
        }

        /* Utility Classes */
        .text-blue { color: var(--primary-blue) !important; }
        .text-orange { color: var(--primary-orange) !important; }
        .bg-orange { background-color: var(--primary-orange) !important; }

        /* Navbar Styling */
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
            content: ''; position: absolute; width: 0; height: 2px; bottom: -2px; left: 0;
            background-color: var(--primary-orange); transition: width 0.3s ease-in-out;
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--primary-orange) !important; }

        .custom-toggler {
            border: none; background: transparent; width: 45px; height: 32px;
            display: flex; flex-direction: column; justify-content: space-between; cursor: pointer;
        }
        .custom-toggler span {
            display: block; height: 4px; width: 100%; background-color: var(--primary-blue);
            border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center;
        }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); }
        .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; }
        .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }

        .btn-donate {
            background: linear-gradient(45deg, #d9534f, #ff6b6b); color: white; border-radius: 30px;
            padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(217, 83, 79, 0.4); transition: all 0.3s ease;
        }
        .btn-donate:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(217, 83, 79, 0.6); color: white; }

        .page-header {
            background: linear-gradient(rgba(17, 26, 47, 0.8), rgba(17, 26, 47, 0.8)), url('img/h6.jpeg') center/cover no-repeat;
            padding: 100px 0; color: white; text-align: center;
        }
        .page-header h1 { font-size: 3.5rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

        /* Gallery Filter Buttons */
        .gallery-filters { display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin-bottom: 40px; }
        .filter-btn {
            background: white; border: 2px solid var(--primary-blue); color: var(--primary-blue);
            padding: 8px 25px; border-radius: 30px; font-weight: 600; transition: all 0.3s ease;
        }
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-orange); border-color: var(--primary-orange);
            color: white; box-shadow: 0 5px 15px rgba(255, 127, 0, 0.3);
        }

        /* Gallery Item & Hover Effect */
        .gallery-item {
            position: relative; overflow: hidden; border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 25px;
            transition: all 0.4s ease; cursor: pointer;
        }
        .gallery-img { width: 100%; height: 250px; object-fit: cover; transition: transform 0.5s ease; }
        .gallery-item:hover .gallery-img { transform: scale(1.1); }
        
        .gallery-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 71, 171, 0.8);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.4s ease;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        
        /* Updated Click Text Styling */
        .click-text {
            color: white; font-size: 1.1rem; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; transform: translateY(20px); transition: transform 0.4s ease;
        }
        .gallery-item:hover .click-text { transform: translateY(0); }

        /* Video Section Styling */
        .video-card {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .video-card:hover {
            transform: translateY(-10px);
        }
        .video-thumbnail {
            width: 100%;
            height: 220px;
            object-fit: cover;
            filter: brightness(0.6);
            transition: filter 0.3s ease;
        }
        .video-card:hover .video-thumbnail {
            filter: brightness(0.4);
        }
        .play-btn-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 4rem;
            opacity: 0.8;
            transition: all 0.3s ease;
            text-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .video-card:hover .play-btn-overlay {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
            color: var(--primary-orange);
        }
        .video-platform-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .video-title-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            color: white;
            padding: 20px 15px 10px 15px;
        }

        /* Lightbox (Image Viewer) CSS */
        #lightbox {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.9); z-index: 9999;
            display: none; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        #lightbox.show { display: flex; opacity: 1; }
        
        #lightbox img {
            max-width: 90%; max-height: 85vh; border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
            transform: scale(0.8); transition: transform 0.3s ease;
        }
        #lightbox.show img { transform: scale(1); }
        
        .lightbox-close {
            position: absolute; top: 20px; right: 30px;
            color: white; font-size: 40px; cursor: pointer; transition: color 0.3s ease;
        }
        .lightbox-close:hover { color: var(--primary-orange); }

        /* Footer */
        .footer { background-color: var(--dark-bg); color: #ccc; }
        .footer-heading { color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
        .footer-heading::after { content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 40px; background-color: var(--primary-orange); }
        .footer-links li { margin-bottom: 15px; font-size: 0.95rem; }
        .footer-links a { color: #b0b8c9; text-decoration: none; transition: 0.3s; display: inline-block; }
        .footer-links a:hover { color: var(--primary-orange); transform: translateX(5px); }
        .developer-credit { color: var(--primary-orange); font-weight: 600; letter-spacing: 1px; }
        .contact-icon-box { background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px; }
        .transition-hover { transition: 0.3s ease; }
        .transition-hover:hover { color: var(--primary-orange) !important; }
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
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="campaigns.php">CAMPAIGNS</a></li>
                    <li class="nav-item"><a class="nav-link active" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="donate.php" class="btn w-100" style="background: linear-gradient(45deg, #FF7F00, #ffa502); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(255, 127, 0, 0.4); transition: all 0.3s ease; border: none;" onmouseover="this.style.background='linear-gradient(45deg, #e67300, #ff9f00)'; this.style.boxShadow='0 6px 20px rgba(255, 127, 0, 0.6)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='linear-gradient(45deg, #FF7F00, #ffa502)'; this.style.boxShadow='0 4px 15px rgba(255, 127, 0, 0.4)'; this.style.transform='translateY(0)';">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-header">
        <div class="container" data-aos="fade-up">
            <h1>Our Gallery</h1>
            <p class="fs-5 mt-3">Glimpses of hope, smiles, and the impact we create together.</p>
        </div>
    </header>

    <section class="py-5 bg-white">
        <div class="container py-4">
            
            <div class="gallery-filters" data-aos="fade-up">
                <button class="filter-btn active" data-filter="all">All Photos</button>
                <button class="filter-btn" data-filter="education">Educational Drives</button>
                <button class="filter-btn" data-filter="finance">Financial Aid</button>
                <button class="filter-btn" data-filter="team">Our Team</button>
            </div>

            <div class="row" id="gallery-container">
                
                <div class="col-lg-4 col-md-6 gallery-box education all" data-aos="zoom-in">
                    <div class="gallery-item">
                        <img src="img/h1.jpeg" alt="Education Drive" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box finance all" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-item">
                        <img src="img/h2.jpeg" alt="Financial Help" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box education all" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-item">
                        <img src="img/h3.jpeg" alt="School Campaign" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box finance all" data-aos="zoom-in">
                    <div class="gallery-item">
                        <img src="img/h4.jpeg" alt="Community Help" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box education all" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-item">
                        <img src="img/h5.jpeg" alt="Free Education" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box education all" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-item">
                        <img src="img/h6.jpeg" alt="Children Education" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box finance all" data-aos="zoom-in">
                    <div class="gallery-item">
                        <img src="img/h7.jpeg" alt="Trust Campaign" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box team all" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-item">
                        <img src="img/founder.jpeg" alt="Founder" class="gallery-img" style="object-position: top;">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gallery-box team all" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-item">
                        <img src="img/team.jpeg" alt="Core Team" class="gallery-img">
                        <div class="gallery-overlay">
                            <span class="click-text"><i class="fas fa-search-plus me-1"></i> Click to View</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--dark-bg);">
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8" data-aos="fade-up">
                    <h6 class="text-orange fw-bold">SPREADING AWARENESS</h6>
                    <h2 class="text-white fw-bold mb-3" style="position: relative; display: inline-block;">
                        Our Viral Impact
                        <div style="position: absolute; width: 50px; height: 3px; background: var(--primary-orange); bottom: -10px; left: 50%; transform: translateX(-50%);"></div>
                    </h2>
                    <p class="text-light opacity-75 mt-4">Watch our campaigns in action. Follow us on social media to see how your support is directly transforming lives on the ground.</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <a href="https://youtube.com/@kameshwary0065" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="video-card">
                            <img src="img/h4.jpeg" alt="YouTube Video" class="video-thumbnail">
                            <div class="video-platform-badge"><i class="fab fa-youtube text-danger me-1"></i> YouTube</div>
                            <div class="play-btn-overlay"><i class="fab fa-youtube"></i></div>
                            <div class="video-title-bar">
                                <h6 class="mb-0 fw-bold">Watch Our Latest Mission</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="video-card">
                            <img src="img/h2.jpeg" alt="Instagram Reel" class="video-thumbnail">
                            <div class="video-platform-badge" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);"><i class="fab fa-instagram me-1"></i> Instagram</div>
                            <div class="play-btn-overlay"><i class="fab fa-instagram"></i></div>
                            <div class="video-title-bar">
                                <h6 class="mb-0 fw-bold">Daily Impact Updates</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="video-card">
                            <img src="img/h7.jpeg" alt="Facebook Video" class="video-thumbnail">
                            <div class="video-platform-badge" style="background: #1877F2;"><i class="fab fa-facebook-f me-1"></i> Facebook</div>
                            <div class="play-btn-overlay"><i class="fab fa-facebook"></i></div>
                            <div class="video-title-bar">
                                <h6 class="mb-0 fw-bold">Community Support Drive</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 text-center" style="background-color: var(--light-bg);">
        <div class="container py-4" data-aos="zoom-in">
            <h2 class="fw-bold text-blue mb-3">Help Us Create More Smiles</h2>
            <p class="text-muted fs-5 mb-4 max-w-700 mx-auto">Every picture tells a story of change. You can be the reason behind the next success story.</p>
            <a href="volunteer.php" class="btn btn-outline-primary btn-lg rounded-pill px-4 me-3 mb-2" style="border-color: var(--primary-blue); color: var(--primary-blue); border-width: 2px;">Become a Volunteer</a>
            <a href="donate.php" class="btn btn-donate btn-lg rounded-pill px-5 mb-2 shadow-lg">Donate Now</a>
        </div>
    </section>

    <footer class="footer pt-5 pb-2">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">About Foundation</h5>
                    <p class="text-light opacity-75 mt-3 pe-lg-3" style="font-size: 0.95rem; line-height: 1.8;">
                        Kameshwar Foundation is dedicated to empowering communities through education, financial aid, and essential resources. Join hands with us to build a future where every child has the opportunity to thrive.
                    </p>
                    <div class="mt-4 d-flex align-items-center">
                        <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" rel="noopener noreferrer" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512">
                                <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" rel="noopener noreferrer" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512">
                                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                            </svg>
                        </a>
                        <a href="https://youtube.com/@kameshwary0065" target="_blank" rel="noopener noreferrer" class="text-light transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 576 512">
                                <path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="list-unstyled footer-links mt-3">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="campaigns.php">Campaigns</a></li>
                        <li><a href="donate.php">Monthly Donation</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="terms.php">Terms & Condition</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Important Links</h5>
                    <ul class="list-unstyled footer-links mt-3">
                        <li><a href="volunteer.php">Become a Volunteer</a></li>
                        <li><a href="donate.php">Make a Donation</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="certificate.php">Download Certificate</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Get In Touch</h5>
                    <ul class="list-unstyled text-light opacity-75 mt-3">
                        <li class="mb-4 d-flex">
                            <div class="contact-icon-box me-3"><i class="fas fa-map-marker-alt text-orange"></i></div>
                            <span style="font-size: 0.95rem;">Registered in Bihar<br>CIN: U88900BR2026NPL081824<br>Patna, Bihar</span>
                        </li>
                        <li class="mb-4 d-flex align-items-center">
                            <div class="contact-icon-box me-3"><i class="fas fa-phone-alt text-orange"></i></div>
                            <span style="font-size: 0.95rem;">+91 8757490154</span> 
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="contact-icon-box me-3"><i class="fas fa-envelope text-orange"></i></div>
                            <span style="font-size: 0.95rem;">info@kameshwarfoundation.in</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary mt-4 mb-3">
            
            <div class="row align-items-center pb-3">
                <div class="col-md-6 text-center text-md-start text-light opacity-75" style="font-size: 0.9rem;">
                    &copy; 2026 Kameshwar Foundation. All Rights Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0" style="font-size: 0.9rem;">
                    <span class="text-light opacity-75">Developed by</span> <span class="developer-credit">Satyam kumar</span>
                </div>
            </div>
        </div>
    </footer>

    <div id="lightbox">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="" alt="Full Screen Image">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize Animations
        AOS.init({ duration: 800, once: true, offset: 100 });

        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. FILTER LOGIC ---
            const filterBtns = document.querySelectorAll('.filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-box');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    let filterValue = this.getAttribute('data-filter');

                    galleryItems.forEach(item => {
                        if(filterValue === 'all') {
                            item.style.display = 'block';
                            item.classList.remove('aos-animate');
                            setTimeout(() => { item.classList.add('aos-animate'); }, 50);
                        } else {
                            if(item.classList.contains(filterValue)) {
                                item.style.display = 'block';
                                item.classList.remove('aos-animate');
                                setTimeout(() => { item.classList.add('aos-animate'); }, 50);
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });

            // --- 2. LIGHTBOX LOGIC ---
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const closeBtn = document.querySelector('.lightbox-close');
            const galleryElements = document.querySelectorAll('.gallery-item');

            // Open Lightbox on Image Click
            galleryElements.forEach(item => {
                item.addEventListener('click', function() {
                    const imgSrc = this.querySelector('.gallery-img').getAttribute('src');
                    lightboxImg.setAttribute('src', imgSrc);
                    lightbox.classList.add('show');
                });
            });

            // Close Lightbox on 'X' click
            closeBtn.addEventListener('click', function() {
                lightbox.classList.remove('show');
            });

            // Close Lightbox on background click
            lightbox.addEventListener('click', function(e) {
                if (e.target !== lightboxImg) {
                    lightbox.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>