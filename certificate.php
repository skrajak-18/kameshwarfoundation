<?php
include 'db_connect.php';

// =====================================================================================
// SECTION 1: CERTIFICATE RENDER MODE (Preview or Download)
// =====================================================================================
if (isset($_GET['id']) && isset($_GET['mode'])) {
    $id = intval($_GET['id']);
    $mode = $_GET['mode'];

    // Fetch Donation Details
    $sql = "SELECT * FROM donations WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $donor_name = strtoupper($row['donor_name']);
        $date = date("d/m/Y", strtotime($row['created_at']));
        $receipt_no = $row['receipt_no'];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Certificate - <?php echo $receipt_no; ?></title>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
            <style>
                body {
                    background-color: #555; 
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                }
                /* A4 Landscape Size */
                .certificate-wrapper {
                    width: 1122px; 
                    height: 793px;
                    background: white;
                    position: relative;
                    padding: 40px;
                    box-sizing: border-box;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                }
                /* Borders */
                .border-outer {
                    border: 8px solid #0047AB; 
                    width: 100%;
                    height: 100%;
                    position: relative;
                    padding: 6px;
                    box-sizing: border-box;
                }
                .border-inner {
                    border: 3px solid #FF7F00; 
                    width: 100%;
                    height: 100%;
                    position: relative;
                    padding: 40px;
                    box-sizing: border-box;
                    text-align: center;
                    background: url('img/logo.png') center/300px no-repeat;
                }
                /* Watermark */
                .watermark-overlay {
                    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(255, 255, 255, 0.92);
                    z-index: 1;
                }
                .content { position: relative; z-index: 2; }
                
                /* Typography */
                .cert-title { font-family: 'Playfair Display', serif; color: #FF7F00; font-size: 32px; font-style: italic; letter-spacing: 2px; margin: 0; }
                .cert-subtitle { font-family: 'Playfair Display', serif; color: #0047AB; font-size: 20px; font-style: italic; margin-top: 5px; }
                .ngo-name { font-family: 'Poppins', sans-serif; color: #0047AB; font-size: 36px; font-weight: 700; margin: 15px 0 5px 0; line-height: 1.2; }
                .ngo-details { font-family: 'Poppins', sans-serif; color: #333; font-size: 13px; font-weight: 600; margin: 0; }
                
                .recognition-text { font-family: 'Poppins', sans-serif; color: #FF7F00; font-size: 50px; font-weight: 700; margin: 40px 0 20px 0; }
                .issued-to { font-family: 'Poppins', sans-serif; font-size: 18px; color: #555; margin-bottom: 10px; }
                .donor-name { font-family: 'Playfair Display', serif; font-size: 45px; color: #0047AB; font-weight: 700; border-bottom: 2px solid #0047AB; display: inline-block; padding: 0 40px; margin-bottom: 30px; }
                
                .paragraph { font-family: 'Poppins', sans-serif; font-size: 18px; color: #0047AB; font-weight: 600; max-width: 850px; margin: 0 auto; line-height: 1.7; }
                
                /* Footer Details */
                .cert-footer { position: absolute; bottom: 40px; left: 50px; right: 50px; display: flex; justify-content: space-between; align-items: flex-end; z-index: 2;}
                .left-details { text-align: left; font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 600; color: #0047AB; }
                
                .right-signature { text-align: center; font-family: 'Poppins', sans-serif; }
                .sign-org { font-size: 20px; color: #0047AB; font-weight: 700; margin: 0 0 5px 0; }
                .sign-line { border-top: 2px solid #FF7F00; padding-top: 5px; font-size: 14px; color: #FF7F00; font-weight: 600; margin: 0; width: 250px; }
                
                /* Corner Triangles */
                .corner { position: absolute; width: 0; height: 0; border-style: solid; z-index: 2; }
                .top-left { top: 0; left: 0; border-width: 150px 150px 0 0; border-color: #0047AB transparent transparent transparent; }
                .bottom-right { bottom: 0; right: 0; border-width: 0 0 150px 150px; border-color: transparent transparent #FF7F00 transparent; }
                
                /* Top Logo */
                .top-logo { position: absolute; top: 30px; right: 40px; width: 120px; z-index: 3; }
            </style>
        </head>
        <body>
            
            <div class="certificate-wrapper" id="certificate">
                <div class="border-outer">
                    <div class="border-inner">
                        <div class="watermark-overlay"></div>
                        <div class="corner top-left"></div>
                        <div class="corner bottom-right"></div>
                        
                        <img src="img/logo.png" alt="Logo" class="top-logo">

                        <div class="content">
                            <h1 class="cert-title">CERTIFICATE OF APPRECIATION</h1>
                            <h3 class="cert-subtitle">(Recognition of Noble Support)</h3>
                            
                            <h2 class="ngo-name">KAMESHWAR FOUNDATION</h2>
                            <p class="ngo-details">CIN: U88900BR2026NPL081824</p>
                            
                            <h1 class="recognition-text">Heartfelt Gratitude</h1>
                            <p class="issued-to">This certificate is proudly presented to</p>
                            
                            <div class="donor-name"><?php echo $donor_name; ?></div>
                            
                            <p class="paragraph">
                                In profound appreciation of your selfless dedication and benevolent support. Your compassion empowers us to bring education, hope, and fundamental resources to underprivileged communities. You are the true catalyst for the positive change we wish to see in the world.
                            </p>
                        </div>

                        <div class="cert-footer">
                            <div class="left-details">
                                <p style="margin: 0 0 10px 0;">DATE: <span style="border-bottom: 1px solid #0047AB; padding: 0 10px;"><?php echo $date; ?></span></p>
                                <p style="margin: 0;">CERTIFICATE NO: <span style="border-bottom: 1px solid #0047AB; padding: 0 10px;"><?php echo $receipt_no; ?></span></p>
                            </div>
                            <div class="right-signature">
                                <p class="sign-org">Kameshwar Foundation</p>
                                <p class="sign-line">AUTHORIZED SIGNATORY</p>
                            </div>
                        </div>
                        
                        <div style="position: absolute; bottom: 10px; left: 0; width: 100%; text-align: center; z-index: 2; font-family: 'Poppins', sans-serif; font-size: 12px; color: #0047AB; font-weight: 600;">
                            Vill- Araila, Block Hanuman Nagar, Darbhanga, Bihar (847106) | Mobile: +91 8757490154 | Email: info@kameshwarfoundation.in
                        </div>

                    </div>
                </div>
            </div>

            <script>
                window.onload = function() {
                    const mode = "<?php echo $mode; ?>";
                    const element = document.getElementById('certificate');
                    
                    if (mode === 'download') {
                        var opt = {
                            margin:       0,
                            filename:     '<?php echo $receipt_no; ?>_Certificate.pdf',
                            image:        { type: 'jpeg', quality: 1 },
                            html2canvas:  { scale: 2, useCORS: true },
                            jsPDF:        { unit: 'px', format: [1122, 793], orientation: 'landscape' }
                        };
                        
                        html2pdf().set(opt).from(element).save().then(function() {
                            setTimeout(() => { window.close(); }, 2000);
                        });
                    }
                };
            </script>
        </body>
        </html>
        <?php
        exit(); 
    } else {
        echo "<h3>Certificate not found or invalid ID.</h3>";
        exit();
    }
}
// =====================================================================================
// END OF SECTION 1
// =====================================================================================
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Certificate | Kameshwar Foundation</title>
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

        body { font-family: 'Poppins', sans-serif; background-color: var(--light-bg); overflow-x: hidden;}

        /* Navbar Styling */
        .modern-navbar { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-link { color: var(--primary-blue) !important; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; margin: 0 12px; transition: 0.3s; }
        .nav-link:hover { color: var(--primary-orange) !important; }
        
        .page-header { background: linear-gradient(rgba(17, 26, 47, 0.85), rgba(17, 26, 47, 0.85)), url('img/h1.jpeg') center/cover no-repeat; padding: 80px 0; color: white; text-align: center; }
        .page-header h1 { font-size: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

        /* Search Box */
        .search-container { background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); padding: 40px; margin-top: -50px; position: relative; z-index: 10; border-top: 5px solid var(--primary-orange); }
        .form-floating > .form-control { border: 2px solid #e0e5ec; border-radius: 10px; }
        .form-floating > .form-control:focus { border-color: var(--primary-blue); box-shadow: none; }
        
        .btn-search { background: var(--primary-blue); color: white; padding: 15px 30px; font-weight: bold; border-radius: 10px; border: none; width: 100%; transition: 0.3s; }
        .btn-search:hover { background: var(--primary-orange); transform: translateY(-2px); }

        /* Results Table */
        .results-box { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-top: 30px; }
        .custom-table th { background-color: var(--dark-bg); color: white; font-weight: 500; border: none; }
        .custom-table td { vertical-align: middle; }
        
        .btn-preview { background: rgba(0, 71, 171, 0.1); color: var(--primary-blue); padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: 0.3s; margin-right: 5px; display: inline-block;}
        .btn-preview:hover { background: var(--primary-blue); color: white; }
        
        .btn-download { background: var(--primary-orange); color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: 0.3s; display: inline-block;}
        .btn-download:hover { background: #e67300; color: white; transform: translateY(-2px); }

        /* Footer */
        .footer { background-color: var(--dark-bg); color: #ccc; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg modern-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/logo.png" alt="Kameshwar Foundation Logo" height="60" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
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

    <header class="page-header">
        <div class="container">
            <h1>Download Certificate</h1>
            <p class="fs-5 mt-3 text-light opacity-75">Access your official appreciation certificate.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="search-container">
                        <h4 class="fw-bold text-blue mb-4 text-center">Search Your Record</h4>
                        <form action="certificate.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="searchQuery" name="search_query" placeholder="Enter Email or Mobile No." required>
                                        <label for="searchQuery"><i class="fas fa-search text-muted me-1"></i> Enter Registered Email or Mobile No.</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="search_btn" class="btn-search"><i class="fas fa-arrow-right me-1"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    if (isset($_POST['search_btn'])) {
                        $search = $conn->real_escape_string($_POST['search_query']);
                        
                        $sql = "SELECT * FROM donations WHERE email = '$search' OR mobile = '$search' ORDER BY created_at DESC";
                        $result = $conn->query($sql);

                        echo "<div class='results-box'>";
                        echo "<h5 class='fw-bold text-orange mb-4'>Search Results for: '{$search}'</h5>";

                        if ($result->num_rows > 0) {
                            echo "<div class='table-responsive'><table class='table custom-table table-hover'>";
                            echo "<thead><tr><th>Date</th><th>Certificate No.</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
                            
                            while($row = $result->fetch_assoc()) {
                                $date = date("d M Y", strtotime($row['created_at']));
                                echo "<tr>";
                                echo "<td>{$date}</td>";
                                echo "<td><b class='text-blue'>{$row['receipt_no']}</b></td>";
                                echo "<td><span class='badge bg-success'>Verified</span></td>";
                                echo "<td>
                                        <a href='certificate.php?id={$row['id']}&mode=preview' target='_blank' class='btn-preview'><i class='fas fa-eye'></i> Preview</a>
                                        <a href='certificate.php?id={$row['id']}&mode=download' target='_blank' class='btn-download'><i class='fas fa-download'></i> Download PDF</a>
                                      </td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table></div>";
                        } else {
                            echo "<div class='alert alert-warning border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i> No records found for this email or mobile number. Please check and try again.</div>";
                        }
                        echo "</div>";
                    }
                    ?>
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
</body>
</html>