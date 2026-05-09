<?php
include 'db_connect.php';

// RAZORPAY CREDENTIALS
$keyId = 'rzp_test_Sn4Ta1j6nNK9Yc';
$keySecret = 'VDNuWGzSYJbXVGQ4arYN5SIX';

// AJAX CALLS FOR RAZORPAY
if (isset($_POST['action']) && $_POST['action'] == 'create_order') {
    $amount = $_POST['amount'] * 100;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['amount' => $amount, 'currency' => 'INR']));
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    echo curl_exec($ch);
    exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'save_payment') {
    $camp_id = intval($_POST['campaign_id']);
    $payment_id = $conn->real_escape_string($_POST['payment_id']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $name = $conn->real_escape_string($_POST['name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $email = $conn->real_escape_string($_POST['email']);
    $is_anon = isset($_POST['is_anonymous']) && $_POST['is_anonymous'] == 'true' ? 1 : 0;

    $receipt_no = "KF-DON-" . date("Y") . "-" . rand(1000, 9999);
    if ($is_anon == 1) $name = "Anonymous Donor";

    $sql = "INSERT INTO donations (campaign_id, receipt_no, donor_name, mobile, email, amount, is_anonymous, razorpay_payment_id) 
            VALUES ($camp_id, '$receipt_no', '$name', '$mobile', '$email', '$amount', '$is_anon', '$payment_id')";

    if ($conn->query($sql)) echo json_encode(['status' => 'success', 'receipt_no' => $receipt_no]);
    exit();
}

// Fetch Campaign Data
if(!isset($_GET['id'])) { header("Location: campaigns.php"); exit(); }
$id = intval($_GET['id']);

$sql = "SELECT c.*, COALESCE(SUM(d.amount), 0) as raised, COUNT(d.id) as donors 
        FROM campaigns c LEFT JOIN donations d ON c.id = d.campaign_id AND d.payment_status = 'Success' 
        WHERE c.id = $id GROUP BY c.id";
$result = $conn->query($sql);
if($result->num_rows == 0) { header("Location: campaigns.php"); exit(); }

$camp = $result->fetch_assoc();
$percent = ($camp['goal_amount'] > 0) ? ($camp['raised'] / $camp['goal_amount']) * 100 : 0;
if($percent > 100) $percent = 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $camp['title']; ?> | Kameshwar Foundation</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        :root { --primary-blue: #0047AB; --primary-orange: #FF7F00; --light-bg: #f4f7f6; --dark-bg: #111a2f;}
        body { font-family: 'Poppins', sans-serif; background-color: var(--light-bg); overflow-x: hidden;}
        
        /* Modern Navbar Styling */
        .modern-navbar { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .nav-link { color: var(--primary-blue) !important; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; position: relative; margin: 0 12px; transition: color 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -2px; left: 0; background-color: var(--primary-orange); transition: width 0.3s ease-in-out; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--primary-orange) !important; }

        .custom-toggler { border: none; background: transparent; width: 45px; height: 32px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .custom-toggler span { display: block; height: 4px; width: 100%; background-color: var(--primary-blue); border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center; }
        .custom-toggler:focus { outline: none; box-shadow: none; }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); }
        .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; }
        .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }
        
        /* Image & Article */
        .camp-hero-img { width: 100%; height: 400px; object-fit: cover; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .camp-hero-mobile { height: 250px; border-radius: 15px; margin-bottom: 20px; }
        .camp-article { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); font-size: 1.05rem; line-height: 1.8; color: #555; }
        
        /* Donation Widget */
        .donation-widget { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); position: sticky; top: 100px; border-top: 5px solid #ff4757; }
        .progress-bar-custom { height: 10px; background: #e0e5ec; border-radius: 10px; overflow: hidden; margin: 15px 0; }
        .progress-fill { height: 100%; background: #8bc34a; }
        
        .amount-btn { border: 2px solid #e0e5ec; color: var(--primary-blue); font-weight: 600; padding: 10px; border-radius: 8px; cursor: pointer; text-align: center; }
        .amount-btn.active { border-color: var(--primary-blue); background: rgba(0, 71, 171, 0.05); }
        .form-control { border: 2px solid #e0e5ec; border-radius: 8px; padding: 10px; margin-bottom: 15px; }
        
        .proceed-btn { background: #ff4757; color: white; width: 100%; border: none; padding: 15px; border-radius: 10px; font-weight: bold; font-size: 1.1rem; display: flex; justify-content: space-between; transition: 0.3s;}
        .proceed-btn:hover { background: #ff6b81; }

        .success-screen { display: none; text-align: center; padding: 20px 0; }
        .btn-certificate { background: var(--primary-orange); color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none; display: inline-block; margin-top: 15px; }

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
                    <li class="nav-item"><a class="nav-link active" href="campaigns.php">CAMPAIGNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">GALLERY</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="donate.php" class="btn w-100" style="background: linear-gradient(45deg, #008000, #32cd32); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(0, 128, 0, 0.4); transition: all 0.3s ease; border: none;" onmouseover="this.style.background='linear-gradient(45deg, #006400, #228b22)'; this.style.boxShadow='0 6px 20px rgba(0, 128, 0, 0.6)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='linear-gradient(45deg, #008000, #32cd32)'; this.style.boxShadow='0 4px 15px rgba(0, 128, 0, 0.4)'; this.style.transform='translateY(0)';">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4 g-lg-5">
            
            <div class="col-12 d-lg-none mb-0 pb-0">
                <a href="campaigns.php" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back</a><br>
                <span class="badge bg-primary mb-2 px-3 py-2 fs-6"><?php echo $camp['category']; ?></span>
                <h2 class="fw-bold text-blue mb-3"><?php echo $camp['title']; ?></h2>
                <img src="img/<?php echo $camp['image']; ?>" class="camp-hero-img camp-hero-mobile w-100" alt="Campaign">
            </div>

            <div class="col-lg-5 order-1 order-lg-2 mt-2 mt-lg-0">
                <div class="donation-widget">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="fw-bold m-0 text-dark">₹<?php echo number_format($camp['raised']); ?></h4>
                        <span class="badge bg-success fs-6"><?php echo round($percent); ?>%</span>
                    </div>
                    <p class="text-muted small">raised of ₹<?php echo number_format($camp['goal_amount']); ?> goal</p>
                    
                    <div class="progress-bar-custom"><div class="progress-fill" style="width: <?php echo $percent; ?>%;"></div></div>
                    <p class="text-muted small mb-4">Supported by <strong><?php echo $camp['donors']; ?></strong> generous donors</p>
                    
                    <hr>
                    
                    <div id="formArea">
                        <h5 class="fw-bold text-dark mb-3">Make a Donation</h5>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(500, this)">₹500</div></div>
                            <div class="col-3"><div class="amount-btn active" onclick="selectAmount(1000, this)">₹1000</div></div>
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(2500, this)">₹2500</div></div>
                            <div class="col-3"><div class="amount-btn" onclick="selectAmount(5000, this)">₹5000</div></div>
                        </div>

                        <input type="number" id="cAmount" class="form-control" value="1000" onkeyup="updateBtn()">
                        <input type="text" id="cName" class="form-control" placeholder="Full Name" required>
                        <input type="tel" id="cMobile" class="form-control" placeholder="Mobile Number" required>
                        <input type="email" id="cEmail" class="form-control" placeholder="Email Address" required>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="cAnon">
                            <label class="form-check-label text-muted small">Donate Anonymously</label>
                        </div>

                        <button type="button" class="proceed-btn" id="payBtn" onclick="doPayment()">
                            <span>Donate</span><span id="btnAmt">₹1000</span>
                        </button>
                    </div>

                    <div class="success-screen" id="successArea">
                        <i class="fas fa-check-circle success-icon text-success fa-4x mb-3"></i>
                        <h3 class="fw-bold text-dark">Thank You!</h3>
                        <p class="text-muted">Your donation has been added to this campaign.</p>
                        <a href="certificate.php" class="btn-certificate">Download 80G Certificate</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 order-2 order-lg-1">
                <div class="d-none d-lg-block">
                    <a href="campaigns.php" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Campaigns</a><br>
                    <span class="badge bg-primary mb-3 px-3 py-2 fs-6"><?php echo $camp['category']; ?></span>
                    <h1 class="fw-bold text-blue mb-4"><?php echo $camp['title']; ?></h1>
                    <img src="img/<?php echo $camp['image']; ?>" class="camp-hero-img" alt="Campaign">
                </div>
                
                <div class="camp-article mt-4 mt-lg-0">
                    <h4 class="fw-bold text-dark mb-3">About the Campaign</h4>
                    <?php echo nl2br($camp['article_content']); ?>
                </div>
            </div>
            
        </div>
    </div>

    <footer class="footer pt-5 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">About Foundation</h5>
                    <p class="text-light opacity-75 mt-3 pe-lg-3" style="font-size: 0.95rem; line-height: 1.8;">Kameshwar Foundation is dedicated to empowering communities through education, financial aid, and essential resources. Join hands with us to build a future where every child has the opportunity to thrive.</p>
                    <div class="mt-4 d-flex align-items-center">
                        <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" rel="noopener noreferrer" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" rel="noopener noreferrer" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                        </a>
                        <a href="https://youtube.com/@kameshwary0065" target="_blank" rel="noopener noreferrer" class="text-light transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.781 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
                        </a>
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
                        <li><a href="volunteer.php">Become a Volunteer</a></li><li><a href="donate.php">Make a Donation</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="certificate.php">Download Certificate</a></li><li><a href="return.php">Return Policy</a></li><li><a href="refund.php">Refund Policy</a></li><li><a href="admin_login.php">Admin Panel</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Get In Touch</h5>
                    <ul class="list-unstyled text-light opacity-75 mt-3">
                        <li class="mb-4 d-flex"><div class="contact-icon-box me-3"><i class="fas fa-map-marker-alt text-orange"></i></div><span style="font-size: 0.95rem;">Registered in Bihar<br>CIN: U88900BR2026NPL081824<br>Patna, Bihar</span></li>
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
    <script>
        function selectAmount(amt, el) {
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('cAmount').value = amt;
            updateBtn();
        }
        function updateBtn() {
            let amt = document.getElementById('cAmount').value;
            document.getElementById('btnAmt').innerText = "₹" + (amt > 0 ? amt : 0);
        }

        function doPayment() {
            let amt = document.getElementById('cAmount').value;
            let name = document.getElementById('cName').value;
            let mob = document.getElementById('cMobile').value;
            let email = document.getElementById('cEmail').value;
            let anon = document.getElementById('cAnon').checked;

            if(!name || !mob || !email || amt < 1) { alert("Please fill all details validly."); return; }

            let btn = document.getElementById('payBtn');
            btn.innerHTML = "Processing..."; btn.disabled = true;

            let fd = new FormData();
            fd.append('action', 'create_order'); fd.append('amount', amt);

            fetch('', { method: 'POST', body: fd }).then(res => res.json()).then(order => {
                var opt = {
                    "key": "<?php echo $keyId; ?>", "amount": amt * 100, "currency": "INR", "name": "Kameshwar Foundation",
                    "order_id": order.id,
                    "handler": function (res) {
                        let f2 = new FormData();
                        f2.append('action', 'save_payment'); f2.append('campaign_id', <?php echo $camp['id']; ?>);
                        f2.append('payment_id', res.razorpay_payment_id); f2.append('amount', amt);
                        f2.append('name', name); f2.append('mobile', mob); f2.append('email', email); f2.append('is_anonymous', anon);

                        fetch('', { method: 'POST', body: f2 }).then(r => r.json()).then(data => {
                            if(data.status === 'success') {
                                document.getElementById('formArea').style.display = 'none';
                                document.getElementById('successArea').style.display = 'block';
                            }
                        });
                    },
                    "prefill": { "name": name, "email": email, "contact": mob },
                    "theme": { "color": "#ff4757" },
                    "modal": { "ondismiss": function() { updateBtn(); btn.disabled = false; } }
                };
                new Razorpay(opt).open();
            });
        }
    </script>
</body>
</html>