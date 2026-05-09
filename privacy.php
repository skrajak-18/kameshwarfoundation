<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Kameshwar Foundation | Privacy Policy</title>
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

        /* Color Utility Classes */
        .text-blue { color: var(--primary-blue) !important; }
        .text-orange { color: var(--primary-orange) !important; }
        .text-green { color: var(--primary-green) !important; }

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

        /* Page Header Banner */
        .page-header {
            background: linear-gradient(rgba(17, 26, 47, 0.85), rgba(17, 26, 47, 0.85)), url('img/h4.jpeg') center/cover no-repeat;
            padding: 80px 0; color: white; text-align: center;
        }
        .page-header h1 { font-size: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

        /* Content Styling */
        .policy-content {
            background: white;
            border-radius: 15px;
            padding: 40px 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            line-height: 1.8;
            color: #555;
        }
        .policy-content h4 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .policy-content p {
            margin-bottom: 15px;
            font-size: 1.05rem;
        }
        .policy-content ul {
            margin-bottom: 20px;
        }
        .policy-content li {
            margin-bottom: 10px;
        }
        .last-updated {
            display: inline-block;
            background: var(--light-bg);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: var(--primary-orange);
            font-weight: 600;
            margin-bottom: 30px;
        }

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
                <span></span><span></span><span></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="campaigns.php">CAMPAIGNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="donate.php" class="btn btn-donate w-100">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-header">
        <div class="container" data-aos="fade-up">
            <h1>Privacy Policy</h1>
            <p class="fs-5 mt-3">How we collect, use, and protect your personal data.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="policy-content">
                        
                        <div class="text-center">
                            <span class="last-updated"><i class="fas fa-shield-alt me-2"></i> Last Updated: May 2026</span>
                        </div>

                        <p>At <strong>Kameshwar Foundation</strong> (CIN: U88900BR2026NPL081824), accessible from www.kameshwarfoundation.in, one of our main priorities is the privacy of our visitors and donors. This Privacy Policy document contains types of information that is collected and recorded by Kameshwar Foundation and how we use it.</p>

                        <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>

                        <h4>1. Information We Collect</h4>
                        <p>We collect personal information when you voluntarily provide it to us. The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information. This includes:</p>
                        <ul>
                            <li><strong>Donor Information:</strong> Name, Email Address, Phone Number, Postal Address, and PAN Number (required for issuing 80G tax exemption receipts in India).</li>
                            <li><strong>Volunteer Information:</strong> Name, Contact details, Date of Birth, and background information when you sign up to volunteer.</li>
                            <li><strong>Payment Information:</strong> When you make a donation, your payment details (credit/debit card numbers, UPI IDs) are processed securely by our third-party payment gateways. We do not store your highly sensitive payment credentials on our servers.</li>
                        </ul>

                        <h4>2. How We Use Your Information</h4>
                        <p>We use the information we collect in various ways, including to:</p>
                        <ul>
                            <li>Process and acknowledge your donations securely.</li>
                            <li>Issue official donation receipts and tax exemption certificates.</li>
                            <li>Provide, operate, and maintain our website.</li>
                            <li>Send you updates about our campaigns, impact reports, and upcoming events (you can opt-out at any time).</li>
                            <li>Comply with legal obligations set by the Government of India and the Ministry of Corporate Affairs (MCA).</li>
                        </ul>

                        <h4>3. Data Security and Protection</h4>
                        <p>Kameshwar Foundation takes the security of your data very seriously. We implement appropriate technical and organizational measures to protect your personal information against accidental or unlawful destruction, loss, alteration, and unauthorized disclosure or access. Our website uses secure SSL encryption to ensure data safety.</p>

                        <h4>4. Sharing of Your Information</h4>
                        <p>We <strong>do not sell, rent, or trade</strong> your personal information to third parties. We may share your information only in the following situations:</p>
                        <ul>
                            <li><strong>Service Providers:</strong> With trusted payment gateways to securely process your donations.</li>
                            <li><strong>Legal Requirements:</strong> If required by law, court order, or governmental authority (such as the Income Tax Department of India for tax audits).</li>
                        </ul>

                        <h4>5. Log Files and Cookies</h4>
                        <p>Kameshwar Foundation follows a standard procedure of using log files. These files log visitors when they visit websites. The information collected includes internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, and referring/exit pages. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, and tracking users' movement on the website.</p>
                        <p>Like any other website, we use "cookies" to store information including visitors' preferences, and the pages on the website that the visitor accessed or visited to optimize the users' experience.</p>

                        <h4>6. Your Privacy Rights</h4>
                        <p>You have the right to request access to the personal data we hold about you. You can also request that we correct any information you believe is inaccurate or request that we erase your personal data, under certain conditions. To exercise these rights, please contact us.</p>

                        <h4>7. Children's Information</h4>
                        <p>Another part of our priority is adding protection for children while using the internet. Kameshwar Foundation does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>

                        <div class="mt-5 p-4 bg-light rounded text-center border">
                            <h5 class="text-blue fw-bold mb-3">Contacting the Privacy Officer</h5>
                            <p class="mb-1">If you have any questions or concerns regarding our Privacy Policy or data processing, please contact us at:</p>
                            <p class="mb-0 fw-bold"><i class="fas fa-envelope text-orange me-2"></i> info@kameshwarfoundation.in</p>
                            <p class="mb-0 fw-bold"><i class="fas fa-phone-alt text-orange me-2"></i> +91 8757490154</p>
                            <p class="mb-0 mt-2 text-muted small">Registered in Patna, Bihar, India.</p>
                        </div>

                    </div>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
    </script>
</body>
</html>