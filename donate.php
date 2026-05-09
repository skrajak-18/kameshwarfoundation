<?php
// Include Database Connection
include 'db_connect.php';

// RAZORPAY CREDENTIALS
$keyId = 'rzp_test_Sn4Ta1j6nNK9Yc';
$keySecret = 'VDNuWGzSYJbXVGQ4arYN5SIX';

// 1. AJAX CALL - CREATE RAZORPAY ORDER
if (isset($_POST['action']) && $_POST['action'] == 'create_order') {
    $amount = $_POST['amount'] * 100; // Razorpay accepts amount in paise
    $receipt = 'rcptid_' . time();

    // cURL request to create order
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'amount' => $amount,
        'currency' => 'INR',
        'receipt' => $receipt
    ]));
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    echo $response; // Return order details to JS
    exit();
}

// 2. AJAX CALL - SAVE PAYMENT TO DATABASE AFTER SUCCESS
if (isset($_POST['action']) && $_POST['action'] == 'save_payment') {
    $payment_id = $conn->real_escape_string($_POST['payment_id']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $name = $conn->real_escape_string($_POST['name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $email = $conn->real_escape_string($_POST['email']);
    $is_anon = isset($_POST['is_anonymous']) && $_POST['is_anonymous'] == 'true' ? 1 : 0;

    // Generate Unique Receipt Number (e.g., KF-DON-2026-001)
    $year = date("Y");
    $rand = rand(1000, 9999);
    $receipt_no = "KF-DON-" . $year . "-" . $rand;

    // If Anonymous, save name as Anonymous
    if ($is_anon == 1) {
        $name = "Anonymous Donor";
    }

    $sql = "INSERT INTO donations (receipt_no, donor_name, mobile, email, amount, is_anonymous, razorpay_payment_id) 
            VALUES ('$receipt_no', '$name', '$mobile', '$email', '$amount', '$is_anon', '$payment_id')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'receipt_no' => $receipt_no]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation | Kameshwar Foundation</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        :root {
            --primary-blue: #0047AB; 
            --primary-orange: #FF7F00; 
            --dark-bg: #111a2f; 
            --light-bg: #f4f7f6;
        }

        body { font-family: 'Poppins', sans-serif; background-color: var(--light-bg); overflow-x: hidden;}

        /* Navbar Styling */
        .modern-navbar { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-link { color: var(--primary-blue) !important; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; margin: 0 12px; }
        
        /* Modern Toggle Menu Icon */
        .custom-toggler { border: none; background: transparent; width: 45px; height: 32px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .custom-toggler span { display: block; height: 4px; width: 100%; background-color: var(--primary-blue); border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center; }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); }
        .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; }
        .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }

        /* Donation Box Styling */
        .donation-container { background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); overflow: hidden; margin-top: 50px; margin-bottom: 50px; }
        .donation-image { background: linear-gradient(rgba(0, 71, 171, 0.7), rgba(17, 26, 47, 0.9)), url('img/h1.jpeg') center/cover no-repeat; min-height: 100%; padding: 40px; color: white; display: flex; flex-direction: column; justify-content: center; }
        .donation-form-area { padding: 50px; }
        
        .amount-btn { border: 2px solid #e0e5ec; background: white; color: var(--primary-blue); font-weight: 600; padding: 10px 20px; border-radius: 10px; cursor: pointer; transition: 0.3s; width: 100%; text-align: center; margin-bottom: 10px; }
        .amount-btn:hover, .amount-btn.active { border-color: var(--primary-blue); background: rgba(0, 71, 171, 0.05); }
        
        .form-control { border: 2px solid #e0e5ec; border-radius: 8px; padding: 12px; }
        .form-control:focus { border-color: var(--primary-blue); box-shadow: none; }
        
        .proceed-btn { background: var(--primary-blue); color: white; border-radius: 10px; padding: 15px; font-weight: bold; width: 100%; border: none; font-size: 1.1rem; transition: 0.3s; display: flex; justify-content: space-between; align-items: center; }
        .proceed-btn:hover { background: var(--dark-bg); }

        .secure-badge { font-size: 0.8rem; color: #888; display: flex; align-items: center; justify-content: center; margin-top: 15px; }
        .secure-badge img { height: 25px; margin-left: 10px; opacity: 0.6; }

        /* Success Screen */
        .success-screen { display: none; text-align: center; padding: 40px 0; }
        .success-icon { font-size: 5rem; color: #28a745; margin-bottom: 20px; }
        .btn-certificate { background: var(--primary-orange); color: white; font-weight: bold; padding: 12px 30px; border-radius: 30px; text-decoration: none; display: inline-block; margin-top: 20px; transition: 0.3s; }
        .btn-certificate:hover { background: #e67300; color: white; transform: translateY(-2px); }

        /* Footer */
        .footer { background-color: var(--dark-bg); color: #ccc; }
        .footer-heading { color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
        .footer-heading::after { content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 40px; background-color: var(--primary-orange); }
        .footer-links li { margin-bottom: 15px; font-size: 0.95rem; }
        .footer-links a { color: #b0b8c9; text-decoration: none; transition: 0.3s; display: inline-block; }
        .footer-links a:hover { color: var(--primary-orange); transform: translateX(5px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg modern-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/logo.png" alt="Kameshwar Foundation Logo" height="60" class="me-2">
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
                    <li class="nav-item"><a class="nav-link" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="donate.php" class="btn w-100" style="background: linear-gradient(45deg, #008000, #32cd32); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold;">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="donation-container">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="donation-image">
                        <img src="img/logo.png" alt="Logo" style="width: 80px; background: white; padding: 5px; border-radius: 10px; margin-bottom: 20px;">
                        <h2 class="fw-bold mb-3">Your Support Matters</h2>
                        <p class="fs-5 opacity-75 fst-italic">"We make a living by what we get, but we make a life by what we give."</p>
                        <div class="mt-4">
                            <span class="badge bg-success p-2 fs-6"><i class="fas fa-check-circle me-1"></i> Verified NGO</span>
                            <span class="badge bg-primary p-2 fs-6 mt-2"><i class="fas fa-file-invoice-dollar me-1"></i> 80G Tax Benefits Available</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="donation-form-area" id="donationFormArea">
                        <h6 class="text-orange fw-bold mb-1">SECURE DONATION</h6>
                        <h3 class="fw-bold text-blue mb-4">Make a Contribution</h3>

                        <label class="fw-bold text-muted small mb-2">Select Amount</label>
                        <div class="row g-2 mb-3">
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(500, this)">₹500</div></div>
                            <div class="col-3"><div class="amount-btn active" onclick="selectAmount(1000, this)">₹1,000</div></div>
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(2500, this)">₹2,500</div></div>
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(5000, this)">₹5,000</div></div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold">₹</span>
                                <input type="number" id="customAmount" class="form-control border-start-0" value="1000" onkeyup="updateButtonAmount()">
                            </div>
                        </div>

                        <label class="fw-bold text-muted small mb-2">Personal Details</label>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input type="text" id="donorName" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" id="donorMobile" class="form-control" placeholder="Mobile Number" required>
                            </div>
                            <div class="col-12">
                                <input type="email" id="donorEmail" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isAnonymous">
                            <label class="form-check-label text-muted small" for="isAnonymous">
                                Make this donation anonymous (Your name won't be displayed publicly)
                            </label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                            <label class="form-check-label text-muted small" for="termsCheck">
                                I am an Indian citizen and I agree to the <a href="terms.php">Terms & Conditions</a>.
                            </label>
                        </div>

                        <button type="button" class="proceed-btn" id="payBtn" onclick="initiatePayment()">
                            <span>TOTAL PAYABLE</span>
                            <span><span id="btnAmountText">₹1000</span> &nbsp;<i class="fas fa-arrow-right"></i></span>
                        </button>

                        <div class="secure-badge">
                            <i class="fas fa-lock me-1"></i> 100% Secure Payments via Razorpay
                        </div>
                    </div>

                    <div class="success-screen" id="successScreen">
                        <i class="fas fa-check-circle success-icon"></i>
                        <h2 class="fw-bold text-blue">Payment Successful!</h2>
                        <p class="text-muted mt-2">Thank you for your generous contribution. Your support helps us transform lives.</p>
                        <p class="text-muted">Transaction ID: <strong id="showTxnId"></strong></p>
                        
                        <a href="certificate.php" class="btn-certificate">
                            <i class="fas fa-download me-2"></i> Download 80G Certificate
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                        </svg>
                    </a>
                    
                    <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" rel="noopener noreferrer" style="color: #ccc; margin-right: 1.5rem; transition: 0.3s;" onmouseover="this.style.color='#FF7F00'" onmouseout="this.style.color='#ccc'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512">
                            <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                        </svg>
                    </a>
                    
                    <a href="https://youtube.com/@kameshwary0065" target="_blank" rel="noopener noreferrer" style="color: #ccc; transition: 0.3s;" onmouseover="this.style.color='#FF7F00'" onmouseout="this.style.color='#ccc'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 576 512">
                            <path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">Quick Links</h5>
                <ul class="list-unstyled mt-3" style="padding-left: 0;">
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="index.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Home</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="about.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">About Us</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="campaigns.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Campaigns</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="donate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Monthly Donation</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="contact.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Contact Us</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="terms.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Terms & Condition</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="privacy.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Privacy Policy</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-heading" style="color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; border-bottom: 3px solid #FF7F00; display: inline-block;">Important Links</h5>
                <ul class="list-unstyled mt-3" style="padding-left: 0;">
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="volunteer.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Become a Volunteer</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="donate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Make a Donation</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="gallery.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Gallery</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="certificate.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Download Certificate</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="return.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Return Policy</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="refund.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Refund Policy</a>
                    </li>
                    <li style="margin-bottom: 15px; font-size: 0.95rem;">
                        <a href="admin_login.php" style="color: #b0b8c9; text-decoration: none; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.color='#FF7F00'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='#b0b8c9'; this.style.transform='translateX(0)';">Admin Panel</a>
                    </li>
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

    <script>
        function selectAmount(amount, element) {
            document.querySelectorAll('.amount-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            
            document.getElementById('customAmount').value = amount;
            updateButtonAmount();
        }

        function updateButtonAmount() {
            let amount = document.getElementById('customAmount').value;
            if(amount === "" || amount <= 0) amount = 0;
            document.getElementById('btnAmountText').innerText = "₹" + amount;
        }

        function initiatePayment() {
            let amount = document.getElementById('customAmount').value;
            let name = document.getElementById('donorName').value;
            let mobile = document.getElementById('donorMobile').value;
            let email = document.getElementById('donorEmail').value;
            let isAnon = document.getElementById('isAnonymous').checked;
            let terms = document.getElementById('termsCheck').checked;

            if(!name || !mobile || !email || !terms) {
                alert("Please fill all details and accept the terms.");
                return;
            }
            if(amount < 1) {
                alert("Please enter a valid amount.");
                return;
            }

            let payBtn = document.getElementById('payBtn');
            payBtn.innerHTML = "Processing... <i class='fas fa-spinner fa-spin'></i>";
            payBtn.disabled = true;

            let formData = new FormData();
            formData.append('action', 'create_order');
            formData.append('amount', amount);

            fetch('donate.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(orderData => {
                
                var options = {
                    "key": "<?php echo $keyId; ?>", 
                    "amount": amount * 100, 
                    "currency": "INR",
                    "name": "Kameshwar Foundation",
                    "description": "Donation for Social Welfare",
                    "image": "img/logo.png",
                    "order_id": orderData.id, 
                    "handler": function (response) {
                        savePayment(response.razorpay_payment_id, amount, name, mobile, email, isAnon);
                    },
                    "prefill": {
                        "name": name,
                        "email": email,
                        "contact": mobile
                    },
                    "theme": {
                        "color": "#0047AB"
                    },
                    "modal": {
                        "ondismiss": function() {
                            payBtn.innerHTML = "<span>TOTAL PAYABLE</span><span>₹" + amount + " &nbsp;<i class='fas fa-arrow-right'></i></span>";
                            payBtn.disabled = false;
                        }
                    }
                };

                var rzp1 = new Razorpay(options);
                rzp1.open();
            })
            .catch(error => {
                alert("Error initializing payment. Please try again.");
                payBtn.innerHTML = "<span>TOTAL PAYABLE</span><span>₹" + amount + " &nbsp;<i class='fas fa-arrow-right'></i></span>";
                payBtn.disabled = false;
            });
        }

        function savePayment(payment_id, amount, name, mobile, email, isAnon) {
            let formData = new FormData();
            formData.append('action', 'save_payment');
            formData.append('payment_id', payment_id);
            formData.append('amount', amount);
            formData.append('name', name);
            formData.append('mobile', mobile);
            formData.append('email', email);
            formData.append('is_anonymous', isAnon);

            fetch('donate.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('donationFormArea').style.display = 'none';
                    document.getElementById('successScreen').style.display = 'block';
                    document.getElementById('showTxnId').innerText = payment_id;
                } else {
                    alert("Payment received, but failed to save in database. Keep your transaction ID: " + payment_id);
                }
            });
        }
    </script>
</body>
</html>