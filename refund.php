<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kameshwar Foundation | Refund Policy</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-blue: #0047AB; --primary-orange: #FF7F00; --primary-green: #008000; --dark-bg: #111a2f; --light-bg: #f4f7f6; }
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; background-color: var(--light-bg); }
        .text-blue { color: var(--primary-blue) !important; } .text-orange { color: var(--primary-orange) !important; }
        
        .modern-navbar { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .nav-link { color: var(--primary-blue) !important; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; position: relative; margin: 0 12px; transition: color 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -2px; left: 0; background-color: var(--primary-orange); transition: width 0.3s ease-in-out; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; } .nav-link:hover { color: var(--primary-orange) !important; }
        
        .custom-toggler { border: none; background: transparent; width: 45px; height: 32px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .custom-toggler span { display: block; height: 4px; width: 100%; background-color: var(--primary-blue); border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center; }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); } .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; } .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }

        .btn-donate { background: linear-gradient(45deg, #d9534f, #ff6b6b); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(217, 83, 79, 0.4); transition: all 0.3s ease; }
        .btn-donate:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(217, 83, 79, 0.6); color: white; }

        .page-header { background: linear-gradient(rgba(17, 26, 47, 0.85), rgba(17, 26, 47, 0.85)), url('img/h4.jpeg') center/cover no-repeat; padding: 80px 0; color: white; text-align: center; }
        .page-header h1 { font-size: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

        .policy-content { background: white; border-radius: 15px; padding: 40px 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); line-height: 1.8; color: #555; }
        .policy-content h4 { color: var(--primary-blue); font-weight: 700; margin-top: 30px; margin-bottom: 15px; }
        .last-updated { display: inline-block; background: var(--light-bg); padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; color: var(--primary-orange); font-weight: 600; margin-bottom: 30px; }

        .footer { background-color: var(--dark-bg); color: #ccc; }
        .footer-heading { color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
        .footer-heading::after { content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 40px; background-color: var(--primary-orange); }
        .footer-links li { margin-bottom: 15px; font-size: 0.95rem; } .footer-links a { color: #b0b8c9; text-decoration: none; transition: 0.3s; display: inline-block; } .footer-links a:hover { color: var(--primary-orange); transform: translateX(5px); }
        .developer-credit { color: var(--primary-orange); font-weight: 600; letter-spacing: 1px; }
        .contact-icon-box { background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px; }
        .transition-hover { transition: 0.3s ease; } .transition-hover:hover { color: var(--primary-orange) !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg modern-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php"><img src="img/logoo.jpg" alt="Kameshwar Foundation Logo" height="65"></a>
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span></span><span></span><span></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="campaigns.php">CAMPAIGNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0"><a href="donate.php" class="btn btn-donate w-100">Donate Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-header">
        <div class="container" data-aos="fade-up">
            <h1>Donation Refund Policy</h1>
            <p class="fs-5 mt-3">Understanding our guidelines for donation refunds.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="policy-content">
                        <div class="text-center"><span class="last-updated"><i class="fas fa-undo-alt me-2"></i> Last Updated: May 2026</span></div>

                        <p>At <strong>Kameshwar Foundation</strong>, we rely on the generosity of our donors to carry out our missions and campaigns. We take the utmost care to process donations as per the instructions given by our donors, both online and offline.</p>
                        
                        <p>However, we understand that errors can happen. In case of an unlikely event of an erroneous donation or if the donor would like to cancel their donation, Kameshwar Foundation will respond to the donor within 7 working days of receiving a valid request.</p>

                        <h4>1. General Rule for Refunds</h4>
                        <p>As a registered non-profit organization, all donations made to Kameshwar Foundation are considered final and <strong>non-refundable</strong>. The funds are immediately allocated to ongoing campaigns (education, financial aid, etc.) and it becomes administratively difficult to reverse transactions once utilized.</p>

                        <h4>2. Exceptions for Refund Processing</h4>
                        <p>A refund may be issued under the following exceptional circumstances:</p>
                        <ul>
                            <li><strong>Duplicate Transaction:</strong> If a donor's account is charged twice for a single donation due to a technical glitch on the payment gateway.</li>
                            <li><strong>Fraudulent Transaction:</strong> If a donation is made using a stolen card or unauthorized bank account, subject to investigation by the bank.</li>
                            <li><strong>Incorrect Amount:</strong> If an unintended amount is accidentally typed and processed (e.g., ₹10,000 instead of ₹1,000).</li>
                        </ul>

                        <h4>3. How to Request a Refund</h4>
                        <p>If your case falls under the exceptions mentioned above, you must request a refund within <strong>3 days</strong> of making the donation. Please send an email to <strong>info@kameshwarfoundation.in</strong> with the following details:</p>
                        <ul>
                            <li>Donor's Name and Contact Number.</li>
                            <li>Date of Donation.</li>
                            <li>Donation Amount.</li>
                            <li>Transaction ID / Order ID (Provided by the payment gateway).</li>
                            <li>Reason for requesting the refund.</li>
                        </ul>

                        <h4>4. Refund Approval & Processing Time</h4>
                        <p>Once we receive your refund request, our financial team will verify the transaction. If approved, the refund will be processed and credited back to the <strong>original source of payment</strong> (Credit Card, Bank Account, UPI) within <strong>7 to 10 business days</strong>. Note that cash or cheque refunds will not be issued for online donations.</p>

                        <h4>5. Tax Exemption Certificates (80G)</h4>
                        <p>If a tax exemption certificate (80G) has already been issued to the donor for the transaction in question, the refund request will be automatically declined, as the transaction has already been filed with the Income Tax Department.</p>

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
                        <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" class="text-light me-4 transition-hover"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg></a>
                        <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" class="text-light me-4 transition-hover"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a>
                        <a href="https://youtube.com/@kameshwary0065" target="_blank" class="text-light transition-hover"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="list-unstyled footer-links mt-3">
                        <li><a href="index.php">Home</a></li><li><a href="about.php">About Us</a></li><li><a href="campaigns.php">Campaigns</a></li><li><a href="donate.php">Monthly Donation</a></li><li><a href="contact.php">Contact Us</a></li><li><a href="terms.php">Terms & Condition</a></li><li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Important Links</h5>
                    <ul class="list-unstyled footer-links mt-3">
                        <li><a href="volunteer.php">Become a Volunteer</a></li><li><a href="donate.php">Make a Donation</a></li><li><a href="refund.php">Refund Policy</a></li><li><a href="return.php">Return Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Get In Touch</h5>
                    <ul class="list-unstyled text-light opacity-75 mt-3">
                        <li class="mb-4 d-flex"><div class="contact-icon-box me-3"><i class="fas fa-map-marker-alt text-orange"></i></div><span style="font-size: 0.95rem;">Vill- Araila, Block Hanuman Nagar,<br>Darbhanga, Bihar (847106)<br>CIN: U88900BR2026NPL081824</span></li>
                        <li class="mb-4 d-flex align-items-center"><div class="contact-icon-box me-3"><i class="fas fa-phone-alt text-orange"></i></div><span style="font-size: 0.95rem;">+91 8757490154</span></li>
                        <li class="d-flex align-items-center"><div class="contact-icon-box me-3"><i class="fas fa-envelope text-orange"></i></div><span style="font-size: 0.95rem;">info@kameshwarfoundation.in</span></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary mt-4 mb-3">
            <div class="row align-items-center pb-3">
                <div class="col-md-6 text-center text-md-start text-light opacity-75" style="font-size: 0.9rem;">&copy; 2026 Kameshwar Foundation. All Rights Reserved.</div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0" style="font-size: 0.9rem;"><span class="text-light opacity-75">Developed by</span> <span class="developer-credit">Satyam kumar</span></div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800, once: true, offset: 100 });</script>
</body>
</html>