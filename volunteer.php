<?php
// Include Database Connection
include 'db_connect.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect text inputs
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $email = $conn->real_escape_string($_POST['email']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $aadhaar_no = $conn->real_escape_string($_POST['aadhaar_no']);
    $address = $conn->real_escape_string($_POST['address']);
    $education = $conn->real_escape_string($_POST['education']);
    $skills = $conn->real_escape_string($_POST['skills']);

    // File Upload Directory
    $target_dir = "uploads/";

    // Unique names for files to avoid overwriting
    $profile_photo = time() . "_" . basename($_FILES["profile_photo"]["name"]);
    $aadhaar_front = time() . "_" . basename($_FILES["aadhaar_front"]["name"]);
    $aadhaar_back = time() . "_" . basename($_FILES["aadhaar_back"]["name"]);

    // Move uploaded files to 'uploads' folder
    $uploadOk = 1;
    if (!move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $profile_photo) ||
        !move_uploaded_file($_FILES["aadhaar_front"]["tmp_name"], $target_dir . $aadhaar_front) ||
        !move_uploaded_file($_FILES["aadhaar_back"]["tmp_name"], $target_dir . $aadhaar_back)) {
        $uploadOk = 0;
        $error_msg = "Sorry, there was an error uploading your files. Please make sure the 'uploads' folder exists.";
    }

    if ($uploadOk == 1) {
        // Insert into Database
        $sql = "INSERT INTO volunteers (full_name, mobile, email, dob, aadhaar_no, address, education, skills, profile_photo, aadhaar_front, aadhaar_back) 
                VALUES ('$full_name', '$mobile', '$email', '$dob', '$aadhaar_no', '$address', '$education', '$skills', '$profile_photo', '$aadhaar_front', '$aadhaar_back')";

        if ($conn->query($sql) === TRUE) {
            $success_msg = "Thank you! Your volunteer registration has been successfully submitted.";
        } else {
            $error_msg = "Database Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Kameshwar Foundation | Become a Volunteer</title>
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

        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; background-color: var(--light-bg); }

        /* Navbar & Global */
        .text-blue { color: var(--primary-blue) !important; }
        .text-orange { color: var(--primary-orange) !important; }
        .modern-navbar { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .nav-link { color: var(--primary-blue) !important; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; position: relative; margin: 0 12px; transition: color 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -2px; left: 0; background-color: var(--primary-orange); transition: width 0.3s ease-in-out; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--primary-orange) !important; }

        .custom-toggler { border: none; background: transparent; width: 45px; height: 32px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .custom-toggler span { display: block; height: 4px; width: 100%; background-color: var(--primary-blue); border-radius: 4px; transition: all 0.3s ease-in-out; transform-origin: left center; }
        .custom-toggler[aria-expanded="true"] span:nth-child(1) { transform: rotate(45deg); }
        .custom-toggler[aria-expanded="true"] span:nth-child(2) { opacity: 0; width: 0; }
        .custom-toggler[aria-expanded="true"] span:nth-child(3) { transform: rotate(-45deg); }

        .btn-donate { background: linear-gradient(45deg, #d9534f, #ff6b6b); color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(217, 83, 79, 0.4); transition: all 0.3s ease; }
        .btn-donate:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(217, 83, 79, 0.6); color: white; }

        /* Page Banner */
        .page-header { background: linear-gradient(rgba(17, 26, 47, 0.85), rgba(17, 26, 47, 0.85)), url('img/h5.jpeg') center/cover no-repeat; padding: 80px 0; color: white; text-align: center; }
        .page-header h1 { font-size: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

        /* Custom Form Styling (Completely New Design) */
        .volunteer-card { background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); overflow: hidden; border-top: 5px solid var(--primary-orange); }
        .form-section-title { color: var(--primary-blue); font-weight: 700; font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--light-bg); display: flex; align-items: center; }
        .form-section-title i { color: var(--primary-orange); margin-right: 10px; font-size: 1.5rem; }
        
        /* Floating Labels override for modern look */
        .form-floating > .form-control, .form-floating > .form-select { border: 2px solid #e0e5ec; border-radius: 10px; }
        .form-floating > .form-control:focus, .form-floating > .form-select:focus { border-color: var(--primary-blue); box-shadow: none; }
        .form-floating > label { color: #888; font-weight: 500; }
        
        /* Custom File Input Styling */
        .custom-file-upload { border: 2px dashed #cbd5e1; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.3s ease; background: #f8fafc; cursor: pointer; position: relative; }
        .custom-file-upload:hover { border-color: var(--primary-orange); background: #fff; }
        .custom-file-upload i { font-size: 2.5rem; color: var(--primary-blue); margin-bottom: 10px; }
        .custom-file-upload input[type="file"] { position: absolute; left: 0; top: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-name-display { font-size: 0.85rem; color: var(--primary-green); font-weight: 600; margin-top: 10px; word-break: break-all; }

        .btn-submit-form { background: var(--primary-blue); color: white; border-radius: 30px; padding: 15px 40px; font-weight: bold; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; width: 100%; border: none; }
        .btn-submit-form:hover { background: var(--primary-orange); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 127, 0, 0.3); }

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
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span></span><span></span><span></span>
            </button>
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
            <h1>Volunteer Registration</h1>
            <p class="fs-5 mt-3 text-light opacity-75">Be the change you want to see in the world. Join our hands.</p>
        </div>
    </header>

    <section class="py-5" style="margin-top: -60px; position: relative; z-index: 10;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="volunteer-card p-4 p-md-5">
                        
                        <?php if($success_msg != ""): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error_msg != ""): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="volunteer.php" method="POST" enctype="multipart/form-data">
                            
                            <h3 class="form-section-title mt-2"><i class="fas fa-user-circle"></i> Personal Information</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="fullName" name="full_name" placeholder="John Doe" required>
                                        <label for="fullName"><i class="fas fa-user text-muted me-1"></i> Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="9876543210" pattern="[0-9]{10}" required>
                                        <label for="mobile"><i class="fas fa-phone-alt text-muted me-1"></i> Mobile Number</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                        <label for="email"><i class="fas fa-envelope text-muted me-1"></i> Email Address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                        <label for="dob"><i class="fas fa-calendar-alt text-muted me-1"></i> Date of Birth</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="aadhaar" name="aadhaar_no" placeholder="123412341234" pattern="[0-9]{12}" required>
                                        <label for="aadhaar"><i class="fas fa-id-card text-muted me-1"></i> 12 Digit Aadhaar Number</label>
                                    </div>
                                </div>
                            </div>

                            <h3 class="form-section-title mt-5"><i class="fas fa-map-marked-alt"></i> Communication Details</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="address" name="address" placeholder="Address" style="height: 100px" required></textarea>
                                        <label for="address"><i class="fas fa-map-marker-alt text-muted me-1"></i> Full Permanent Address</label>
                                    </div>
                                </div>
                            </div>

                            <h3 class="form-section-title mt-5"><i class="fas fa-graduation-cap"></i> Profile & Skills</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="education" name="education" placeholder="Degree" required>
                                        <label for="education"><i class="fas fa-user-graduate text-muted me-1"></i> Educational Qualification</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="skills" name="skills" placeholder="Teaching, Medical..." required>
                                        <label for="skills"><i class="fas fa-laptop-code text-muted me-1"></i> Special Skills / Expertise</label>
                                    </div>
                                </div>
                            </div>

                            <h3 class="form-section-title mt-5"><i class="fas fa-file-upload"></i> Documents Proof</h3>
                            <p class="text-muted small mb-3">Please upload clear images (JPG, PNG, PDF allowed. Max 2MB per file).</p>
                            
                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <div class="custom-file-upload">
                                        <i class="fas fa-camera-retro"></i>
                                        <h6 class="fw-bold text-dark mt-2">Profile Photo</h6>
                                        <span class="text-muted small d-block">Click or Drag File</span>
                                        <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.pdf" required onchange="showFileName(this, 'file1-name')">
                                        <div id="file1-name" class="file-name-display"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-file-upload">
                                        <i class="far fa-id-card"></i>
                                        <h6 class="fw-bold text-dark mt-2">Aadhaar Front</h6>
                                        <span class="text-muted small d-block">Click or Drag File</span>
                                        <input type="file" name="aadhaar_front" accept=".jpg,.jpeg,.png,.pdf" required onchange="showFileName(this, 'file2-name')">
                                        <div id="file2-name" class="file-name-display"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-file-upload">
                                        <i class="fas fa-id-card"></i>
                                        <h6 class="fw-bold text-dark mt-2">Aadhaar Back</h6>
                                        <span class="text-muted small d-block">Click or Drag File</span>
                                        <input type="file" name="aadhaar_back" accept=".jpg,.jpeg,.png,.pdf" required onchange="showFileName(this, 'file3-name')">
                                        <div id="file3-name" class="file-name-display"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 mx-auto text-center">
                                    <button type="submit" class="btn-submit-form"><i class="fas fa-paper-plane me-2"></i> Register As Volunteer</button>
                                    <a href="index.php" class="d-block mt-3 text-muted text-decoration-none hover-orange"><i class="fas fa-home me-1"></i> Back to Home</a>
                                </div>
                            </div>

                        </form>
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
                        <a href="https://www.facebook.com/share/1EN5WJnuoa/" target="_blank" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/kameshwarfoundation" target="_blank" class="text-light me-4 transition-hover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                        </a>
                        <a href="https://youtube.com/@kameshwary0065" target="_blank" class="text-light transition-hover">
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
                        <li><a href="volunteer.php">Become a Volunteer</a></li><li><a href="donate.php">Make a Donation</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="certificate.php">Download Certificate</a></li>
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });

        // JavaScript to show selected file name in custom upload box
        function showFileName(input, displayId) {
            const displayElement = document.getElementById(displayId);
            if(input.files && input.files[0]) {
                displayElement.innerHTML = "<i class='fas fa-check-circle text-success me-1'></i> " + input.files[0].name;
            } else {
                displayElement.innerHTML = "";
            }
        }
    </script>
</body>
</html>