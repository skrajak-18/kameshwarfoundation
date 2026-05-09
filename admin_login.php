<?php
session_start();
include 'db_connect.php'; 

$error_msg = "";

// =========================================================================
// AUTO-SETUP LOGIC: Default Admins aur unke Hashed Passwords insert karna
// =========================================================================
$check_admin = $conn->query("SELECT * FROM admin_users");
if ($check_admin->num_rows == 0) {
    $default_password = "";
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO admin_users (email, password, role) VALUES 
    ('kameshwarfoundation@gmail.com', '$hashed_password', 'Super Admin'), 
    ('satyamkumar17379@gmail.com', '$hashed_password', 'Admin')";
    
    $conn->query($insert_sql);
}
// =========================================================================

// LOGIN LOGIC
if (isset($_POST['login_btn'])) {
    $selected_email = $conn->real_escape_string($_POST['admin_email']);
    $entered_password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE email = '$selected_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($entered_password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_role'] = $row['role'];
            
            header("Location: admin.php");
            exit();
        } else {
            $error_msg = "Incorrect Password! Please try again.";
        }
    } else {
        $error_msg = "Admin account not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Secure Login | Kameshwar Foundation</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #0047AB; 
            --primary-orange: #FF7F00; 
            --dark-bg: #111a2f; 
            --light-bg: #f4f7f6;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #111a2f 0%, #0047AB 100%); 
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
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

        /* Login Box Styling */
        .login-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        
        .login-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 15px 50px rgba(0,0,0,0.5); 
            width: 100%; 
            max-width: 420px; 
            padding: 40px; 
            text-align: center; 
            border-top: 6px solid var(--primary-orange); 
        }
        .logo-img { width: 90px; margin-bottom: 20px; }
        
        .form-floating > .form-control, .form-floating > .form-select { border: 2px solid #e0e5ec; border-radius: 10px; }
        .form-floating > .form-control:focus, .form-floating > .form-select:focus { border-color: var(--primary-blue); box-shadow: none; }
        .form-floating > label { color: #888; }
        
        .btn-primary-custom { 
            background: var(--primary-blue); 
            color: white; 
            border-radius: 30px; 
            padding: 12px; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            transition: all 0.3s ease; 
            border: none; 
            width: 100%; 
            margin-top: 10px; 
        }
        .btn-primary-custom:hover { background: var(--primary-orange); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255, 127, 0, 0.3); }
        
        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; }

        /* Footer Styling */
        .footer { background-color: var(--dark-bg); color: #ccc; }
        .footer-heading { color: white; font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
        .footer-heading::after { content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 40px; background-color: var(--primary-orange); }
        .footer-links li { margin-bottom: 15px; font-size: 0.95rem; }
        .footer-links a { color: #b0b8c9; text-decoration: none; transition: 0.3s; display: inline-block; }
        .footer-links a:hover { color: var(--primary-orange); transform: translateX(5px); }
        .developer-credit { color: var(--primary-orange); font-weight: 600; letter-spacing: 1px; }
        .contact-icon-box { background-color: rgba(255, 255, 255, 0.05); width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 5px; }
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
                        <a href="donate.php" class="btn w-100" style="background: linear-gradient(45deg, #FF7F00, #ffa502); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(255, 127, 0, 0.4); transition: all 0.3s ease; border: none;" onmouseover="this.style.background='linear-gradient(45deg, #e67300, #ff9f00)'; this.style.boxShadow='0 6px 20px rgba(255, 127, 0, 0.6)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='linear-gradient(45deg, #FF7F00, #ffa502)'; this.style.boxShadow='0 4px 15px rgba(255, 127, 0, 0.4)'; this.style.transform='translateY(0)';">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="login-wrapper">
        <div class="login-card mx-auto">
            <img src="img/logoo.jpg" alt="Logo" class="logo-img rounded-circle shadow-sm">
            <h3 class="fw-bold mb-1" style="color: var(--primary-blue);">Admin Portal</h3>
            <p class="text-muted small mb-4">Enter your credentials to access</p>

            <?php if($error_msg != ""): ?>
                <div class="alert alert-danger small py-2"><i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="admin_login.php" method="POST">
                
                <div class="form-floating mb-3">
                    <select class="form-select" id="admin_email" name="admin_email" required>
                        <option value="" disabled selected>Select Admin Email</option>
                        <?php
                        $sql = "SELECT email FROM admin_users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['email']."'>".$row['email']."</option>";
                            }
                        }
                        ?>
                    </select>
                    <label for="admin_email"><i class="fas fa-envelope me-1"></i> Admin Account</label>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-1"></i> Password</label>
                    <i class="fas fa-eye password-toggle" id="togglePassword" title="Show/Hide Password"></i>
                </div>
                
                <button type="submit" name="login_btn" class="btn-primary-custom">
                    <i class="fas fa-sign-in-alt me-2"></i> Secure Login
                </button>
            </form>

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

    <script>
        // Password Show/Hide Toggle Logic
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>