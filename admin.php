<?php
session_start();
include 'db_connect.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// 2. LOGOUT LOGIC
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// 3. ACTION HANDLERS
// ==========================================

// --- ADD CAMPAIGN CATEGORY DIRECTLY ---
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['cat_name']);
    $icon = $conn->real_escape_string($_POST['cat_icon']);
    $sql = "INSERT INTO campaign_categories (name, icon) VALUES ('$name', '$icon')";
    $conn->query($sql);
    header("Location: admin.php");
    exit();
}

// --- ADD CAMPAIGN (WITH DYNAMIC CATEGORY CREATION) ---
if (isset($_POST['add_campaign'])) {
    $title = $conn->real_escape_string($_POST['camp_title']);
    $category = $conn->real_escape_string($_POST['camp_category']);
    $goal = $conn->real_escape_string($_POST['camp_goal']);
    $content = $conn->real_escape_string($_POST['camp_content']);

    // Check if user selected "Add New Category"
    if ($category === '--NEW--' && !empty($_POST['custom_category'])) {
        $category = $conn->real_escape_string($_POST['custom_category']);
        // Check if it already exists, if not, create it with a default icon
        $check_cat = $conn->query("SELECT id FROM campaign_categories WHERE name = '$category'");
        if ($check_cat->num_rows == 0) {
            $conn->query("INSERT INTO campaign_categories (name, icon) VALUES ('$category', 'fas fa-hands-helping')");
        }
    }

    $image_name = "default.png"; 
    if(isset($_FILES['camp_image']) && $_FILES['camp_image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['camp_image']['name']);
        $target_path = "img/" . $image_name;
        move_uploaded_file($_FILES['camp_image']['tmp_name'], $target_path);
    }

    $sql = "INSERT INTO campaigns (title, category, goal_amount, image, article_content) VALUES ('$title', '$category', '$goal', '$image_name', '$content')";
    $conn->query($sql);
    header("Location: admin.php");
    exit();
}

// --- EDIT CAMPAIGN (WITH DYNAMIC CATEGORY CREATION) ---
if (isset($_POST['edit_campaign'])) {
    $camp_id = intval($_POST['camp_id']);
    $title = $conn->real_escape_string($_POST['camp_title']);
    $category = $conn->real_escape_string($_POST['camp_category']);
    $goal = $conn->real_escape_string($_POST['camp_goal']);
    $content = $conn->real_escape_string($_POST['camp_content']);

    // Check if user selected "Add New Category"
    if ($category === '--NEW--' && !empty($_POST['custom_category'])) {
        $category = $conn->real_escape_string($_POST['custom_category']);
        $check_cat = $conn->query("SELECT id FROM campaign_categories WHERE name = '$category'");
        if ($check_cat->num_rows == 0) {
            $conn->query("INSERT INTO campaign_categories (name, icon) VALUES ('$category', 'fas fa-hands-helping')");
        }
    }

    $image_query = "";
    if(isset($_FILES['camp_image']) && $_FILES['camp_image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['camp_image']['name']);
        $target_path = "img/" . $image_name;
        move_uploaded_file($_FILES['camp_image']['tmp_name'], $target_path);
        $image_query = ", image='$image_name'";
    }

    $sql = "UPDATE campaigns SET title='$title', category='$category', goal_amount='$goal', article_content='$content' $image_query WHERE id=$camp_id";
    $conn->query($sql);
    header("Location: admin.php?view_campaign=$camp_id");
    exit();
}

// Handle Action Links
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action == 'delete_contact' || $action == 'done_contact') {
        $conn->query("DELETE FROM contact_messages WHERE id = $id");
    } 
    elseif ($action == 'reject_volunteer') {
        $conn->query("DELETE FROM volunteers WHERE id = $id");
    } 
    elseif ($action == 'approve_volunteer') {
        $res = $conn->query("SELECT * FROM volunteers WHERE id = $id");
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $stmt = $conn->prepare("INSERT INTO our_volunteers (full_name, mobile, email, dob, aadhaar_no, address, education, skills, profile_photo, aadhaar_front, aadhaar_back) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $row['full_name'], $row['mobile'], $row['email'], $row['dob'], $row['aadhaar_no'], $row['address'], $row['education'], $row['skills'], $row['profile_photo'], $row['aadhaar_front'], $row['aadhaar_back']);
            if ($stmt->execute()) {
                $conn->query("DELETE FROM volunteers WHERE id = $id");
            }
        }
    } 
    elseif ($action == 'delete_our_volunteer') {
        $conn->query("DELETE FROM our_volunteers WHERE id = $id");
    }
    elseif ($action == 'delete_campaign') {
        $conn->query("DELETE FROM donations WHERE campaign_id = $id");
        $conn->query("DELETE FROM campaigns WHERE id = $id");
    }
    elseif ($action == 'delete_category') {
        $conn->query("DELETE FROM campaign_categories WHERE id = $id");
    }
    
    header("Location: admin.php");
    exit();
}

// 4. FETCH SPECIFIC CAMPAIGN DATA FOR VIEW MODE
$view_camp_id = isset($_GET['view_campaign']) ? intval($_GET['view_campaign']) : 0;
$view_camp_data = null;
$view_camp_donors = null;

if($view_camp_id > 0) {
    $v_sql = "SELECT c.*, COALESCE(SUM(d.amount), 0) as raised, COUNT(d.id) as donors FROM campaigns c LEFT JOIN donations d ON c.id = d.campaign_id AND d.payment_status = 'Success' WHERE c.id = $view_camp_id GROUP BY c.id";
    $v_res = $conn->query($v_sql);
    if($v_res->num_rows > 0) {
        $view_camp_data = $v_res->fetch_assoc();
        $view_camp_donors = $conn->query("SELECT * FROM donations WHERE campaign_id = $view_camp_id AND payment_status = 'Success' ORDER BY created_at DESC");
    }
}

// 5. FETCH GENERAL DATA
$current_email = $_SESSION['admin_email'];
$admin_name = ($current_email == 'satyamkumar17379@gmail.com' || $current_email == '8757490154') ? "Satyam" : "Admin";

$contact_count = $conn->query("SELECT COUNT(*) AS total FROM contact_messages")->fetch_assoc()['total'];
$pending_vol_count = $conn->query("SELECT COUNT(*) AS total FROM volunteers")->fetch_assoc()['total'];
$approved_vol_count = $conn->query("SELECT COUNT(*) AS total FROM our_volunteers")->fetch_assoc()['total'];

$total_donation_query = $conn->query("SELECT SUM(amount) AS total_amt FROM donations WHERE payment_status = 'Success'");
$total_donation = $total_donation_query->fetch_assoc()['total_amt'];
if (!$total_donation) $total_donation = 0; 

$contacts_query = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$pending_vol_query = $conn->query("SELECT * FROM volunteers ORDER BY created_at DESC");
$approved_vol_query = $conn->query("SELECT * FROM our_volunteers ORDER BY approved_at DESC");
$donors_query = $conn->query("SELECT * FROM donations ORDER BY created_at DESC"); 
$campaigns_query = $conn->query("SELECT c.*, COALESCE(SUM(d.amount), 0) as raised, COUNT(d.id) as donors FROM campaigns c LEFT JOIN donations d ON c.id = d.campaign_id AND d.payment_status = 'Success' GROUP BY c.id ORDER BY c.created_at DESC");

// Fetch Dynamic Categories
$cat_list_query = $conn->query("SELECT cc.*, (SELECT COUNT(id) FROM campaigns WHERE category = cc.name) as camp_count FROM campaign_categories cc ORDER BY cc.name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Kameshwar Foundation</title>
    <link rel="icon" type="image/jpeg" href="img/logoo.jpg">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-blue: #0047AB; --primary-orange: #FF7F00; --dark-bg: #111a2f; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; overflow-x: hidden; }

        #wrapper { display: flex; transition: all 0.3s; }
        #sidebar-wrapper { min-height: 100vh; width: 250px; background: var(--dark-bg); color: white; transition: margin 0.3s; }
        #sidebar-wrapper .sidebar-heading { padding: 20px; font-size: 1.2rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; }
        #sidebar-wrapper .sidebar-heading img { width: 35px; border-radius: 50%; margin-right: 10px; }
        .list-group-item { background: transparent; color: #a0aabf; border: none; padding: 15px 20px; font-weight: 500; transition: 0.3s; cursor: pointer; }
        .list-group-item:hover, .list-group-item.active { background: rgba(255, 127, 0, 0.1); color: var(--primary-orange); border-right: 4px solid var(--primary-orange); }
        .list-group-item i { width: 25px; }
        
        #page-content-wrapper { width: 100%; }
        
        .top-navbar { background: white; padding: 15px 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .toggle-btn { background: var(--primary-blue); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .logout-btn { background: #d9534f; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
        .logout-btn:hover { background: #c9302c; color: white; }

        .content-section { padding: 30px; display: none; }
        .content-section.active { display: block; }
        .welcome-text { color: var(--primary-blue); font-weight: 700; margin-bottom: 20px; }

        .stat-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 5px solid; position: relative; }
        .stat-card.blue { border-color: var(--primary-blue); }
        .stat-card.orange { border-color: var(--primary-orange); }
        .stat-card.green { border-color: green; }
        .stat-card.red { border-color: #d9534f; }
        .stat-icon { font-size: 2.5rem; opacity: 0.1; position: absolute; right: 20px; top: 25px; }
        .stat-value { font-size: 2rem; font-weight: 700; color: #333; line-height: 1; }
        .stat-title { color: #888; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 10px; }

        .table-box { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow-x: auto; margin-bottom: 20px;}
        .admin-table th { background-color: var(--dark-bg); color: white; font-weight: 500; font-size: 0.9rem; padding: 15px; white-space: nowrap; border: none; }
        .admin-table td { padding: 15px; vertical-align: middle; color: #555; font-size: 0.9rem; border-bottom: 1px solid #f0f0f0; }
        
        .search-box { border: 2px solid #e0e5ec; border-radius: 30px; overflow: hidden; display: flex; align-items: center; max-width: 350px; background: white; }
        .search-box i { padding: 0 15px; color: var(--primary-blue); }
        .search-box input { border: none; padding: 10px; outline: none; width: 100%; box-shadow: none; }

        .btn-action { padding: 6px 12px; border-radius: 5px; font-weight: 600; font-size: 0.8rem; text-decoration: none; margin-right: 5px; display: inline-block; transition: 0.3s; border: none; cursor: pointer; }
        .btn-done { background: rgba(0, 128, 0, 0.1); color: green; } .btn-done:hover { background: green; color: white; }
        .btn-del { background: rgba(217, 83, 79, 0.1); color: #d9534f; } .btn-del:hover { background: #d9534f; color: white; }
        .btn-del[disabled] { opacity: 0.5; pointer-events: none; cursor: not-allowed; }
        .btn-approve { background: rgba(0, 71, 171, 0.1); color: var(--primary-blue); } .btn-approve:hover { background: var(--primary-blue); color: white; }
        .btn-orange { background: rgba(255, 127, 0, 0.1); color: var(--primary-orange); } .btn-orange:hover { background: var(--primary-orange); color: white; }
        
        .form-floating > .form-control, .form-floating > .form-select { border: 2px solid #e0e5ec; border-radius: 10px; }
        .form-floating > .form-control:focus, .form-floating > .form-select:focus { border-color: var(--primary-blue); box-shadow: none; }
        .btn-primary-custom { background: var(--primary-blue); color: white; border-radius: 30px; padding: 12px 30px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; border: none; cursor: pointer;}
        .btn-primary-custom:hover { background: var(--primary-orange); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255, 127, 0, 0.3); }

        .progress-bar-custom { height: 8px; background: #e0e5ec; border-radius: 10px; overflow: hidden; margin-top: 5px; width: 100%; min-width: 150px;}
        .progress-fill { height: 100%; background: #8bc34a; }

        @media (max-width: 768px) {
            #sidebar-wrapper { margin-left: -250px; }
            #wrapper.toggled #sidebar-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div id="wrapper">
        
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <img src="img/logoo.jpg" alt="Logo"> KF Admin
            </div>
            <div class="list-group list-group-flush mt-3" id="sidebarLinks">
                <a class="list-group-item <?php if($view_camp_id==0) echo 'active'; ?>" onclick="switchSection('dashboard', this)"><i class="fas fa-home"></i> Dashboard</a>
                
                <?php if($view_camp_id > 0): ?>
                    <a class="list-group-item active" onclick="switchSection('view_campaign', this)"><i class="fas fa-eye"></i> Campaign Details</a>
                <?php endif; ?>

                <a class="list-group-item" onclick="switchSection('donors', this)"><i class="fas fa-hand-holding-heart"></i> All Donors</a>
                <a class="list-group-item" onclick="switchSection('campaigns_growth', this)"><i class="fas fa-chart-line"></i> Manage Campaigns</a>
                <a class="list-group-item" onclick="switchSection('add_campaign', this)"><i class="fas fa-plus-circle"></i> Add Campaign</a>
                <a class="list-group-item" onclick="switchSection('manage_categories', this)"><i class="fas fa-tags"></i> Campaign Sections</a>
                <a class="list-group-item" onclick="switchSection('contacts', this)"><i class="fas fa-envelope"></i> Contact Requests</a>
                <a class="list-group-item" onclick="switchSection('pending_volunteers', this)"><i class="fas fa-user-clock"></i> Volunteers</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            
            <nav class="top-navbar">
                <button class="toggle-btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
                <a href="admin.php?logout=true" class="logout-btn"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </nav>

            <?php if($view_camp_id > 0 && $view_camp_data): 
                $c_goal = $view_camp_data['goal_amount'];
                $c_raised = $view_camp_data['raised'];
                $c_percent = ($c_goal > 0) ? ($c_raised / $c_goal) * 100 : 0;
                if($c_percent > 100) $c_percent = 100;
            ?>
            <div id="view_campaign" class="content-section active">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="welcome-text mb-0"><i class="fas fa-bullseye text-orange me-2"></i> Campaign Overview</h3>
                    <div>
                        <button class="btn-action btn-orange" onclick="document.getElementById('editCampaignForm').style.display='block';"><i class="fas fa-edit"></i> Edit</button>
                        <a href="admin.php" class="btn-action btn-del"><i class="fas fa-times"></i> Close</a>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-lg-3 col-md-6">
                        <img src="img/<?php echo $view_camp_data['image']; ?>" class="img-fluid rounded shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-lg-9 col-md-6">
                        <div class="table-box" style="margin-bottom:0;">
                            <h5 class="fw-bold text-blue"><?php echo $view_camp_data['title']; ?> <span class="badge bg-secondary ms-2"><?php echo $view_camp_data['category']; ?></span></h5>
                            <div class="d-flex justify-content-between small text-muted mt-3">
                                <span><b class="text-success fs-6">₹<?php echo number_format($c_raised); ?></b> Raised</span>
                                <span><b class="text-dark fs-6">₹<?php echo number_format($c_goal); ?></b> Goal</span>
                            </div>
                            <div class="progress-bar-custom" style="height: 12px;"><div class="progress-fill" style="width: <?php echo $c_percent; ?>%;"></div></div>
                            <div class="mt-2 text-muted"><i class="fas fa-users text-orange"></i> Total Donors: <b><?php echo $view_camp_data['donors']; ?></b></div>
                        </div>
                    </div>
                </div>

                <div class="table-box mb-4" id="editCampaignForm" style="display: none; border-top: 4px solid var(--primary-orange);">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold text-dark"><i class="fas fa-edit text-orange"></i> Update Campaign Details</h5>
                        <button class="btn-close" onclick="document.getElementById('editCampaignForm').style.display='none';"></button>
                    </div>
                    <form action="admin.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="camp_id" value="<?php echo $view_camp_data['id']; ?>">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="camp_title" value="<?php echo $view_camp_data['title']; ?>" required>
                                    <label>Campaign Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <select class="form-select" id="editCampCat" name="camp_category" onchange="toggleCustomCat('editCampCat', 'customEditCatDiv')" required>
                                        <?php 
                                        $cat_list_query->data_seek(0);
                                        while($cat = $cat_list_query->fetch_assoc()) {
                                            $sel = ($view_camp_data['category'] == $cat['name']) ? "selected" : "";
                                            echo "<option value='{$cat['name']}' $sel>{$cat['name']}</option>";
                                        }
                                        ?>
                                        <option value="--NEW--" class="fw-bold text-primary">➕ Add New Category...</option>
                                    </select>
                                    <label>Category</label>
                                </div>
                                <div class="form-floating" id="customEditCatDiv" style="display: none;">
                                    <input type="text" class="form-control border-primary" name="custom_category" placeholder="Type new category">
                                    <label class="text-primary">Type New Category Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="camp_goal" value="<?php echo $view_camp_data['goal_amount']; ?>" required>
                                    <label>Goal Amount (₹)</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small fw-bold ms-1 mb-1">Update Display Image (Optional)</label>
                                <input type="file" class="form-control p-3" name="camp_image" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="camp_content" style="height: 150px" required><?php echo $view_camp_data['article_content']; ?></textarea>
                                    <label>Full Article / Description</label>
                                </div>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" name="edit_campaign" class="btn-primary-custom"><i class="fas fa-save me-2"></i> Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <h5 class="fw-bold text-dark mt-4 mb-3">Donors for this Campaign</h5>
                <div class="table-box">
                    <table class="table admin-table align-middle">
                        <thead><tr><th>#</th><th>Date</th><th>Receipt No.</th><th>Donor Name & Contact</th><th>Amount</th><th>Certificate Actions</th></tr></thead>
                        <tbody>
                            <?php 
                            if($view_camp_donors->num_rows > 0) {
                                $vd_cnt = 1;
                                while($d_row = $view_camp_donors->fetch_assoc()) {
                                    echo "<tr><td><b>{$vd_cnt}</b></td><td><small class='text-muted'>".date("d M Y", strtotime($d_row['created_at']))."</small></td><td><b class='text-blue'>{$d_row['receipt_no']}</b></td><td><b>{$d_row['donor_name']}</b><br><small>{$d_row['mobile']}</small></td><td><span class='badge bg-success fs-6'>₹{$d_row['amount']}</span></td><td><a href='certificate.php?id={$d_row['id']}&mode=preview' target='_blank' class='btn-action btn-approve'><i class='fas fa-eye'></i> Preview</a> <a href='certificate.php?id={$d_row['id']}&mode=download' target='_blank' class='btn-action btn-orange'><i class='fas fa-download'></i> Download</a></td></tr>";
                                    $vd_cnt++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No donations received for this campaign yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div id="dashboard" class="content-section <?php if($view_camp_id==0) echo 'active'; ?>">
                <h2 class="welcome-text">Welcome, <?php echo $admin_name; ?>! <i class="fas fa-hand-sparkles text-warning ms-1"></i></h2>
                <p class="text-muted mb-4">Here is the summary of your NGO operations.</p>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6"><div class="stat-card red"><div class="stat-title">Total Donations</div><div class="stat-value text-danger">₹<?php echo number_format($total_donation); ?></div><i class="fas fa-rupee-sign stat-icon text-danger"></i></div></div>
                    <div class="col-lg-3 col-md-6"><div class="stat-card blue"><div class="stat-title">Contact Queries</div><div class="stat-value text-blue"><?php echo $contact_count; ?></div><i class="fas fa-inbox stat-icon text-blue"></i></div></div>
                    <div class="col-lg-3 col-md-6"><div class="stat-card orange"><div class="stat-title">Pending Vols</div><div class="stat-value text-orange"><?php echo $pending_vol_count; ?></div><i class="fas fa-user-clock stat-icon text-orange"></i></div></div>
                    <div class="col-lg-3 col-md-6"><div class="stat-card green"><div class="stat-title">Approved Vols</div><div class="stat-value text-success"><?php echo $approved_vol_count; ?></div><i class="fas fa-users stat-icon text-success"></i></div></div>
                </div>
            </div>

            <div id="manage_categories" class="content-section">
                <h3 class="welcome-text mb-4"><i class="fas fa-tags text-orange me-2"></i> Manage Campaign Sections</h3>
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="table-box">
                            <h5 class="fw-bold mb-3">Add New Section</h5>
                            <form action="admin.php" method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="cat_name" placeholder="Name" required>
                                    <label>Section Name (e.g., Medical Relief)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="cat_icon" placeholder="Icon" value="fas fa-hands-helping" required>
                                    <label>Icon Class (FontAwesome)</label>
                                </div>
                                <button type="submit" name="add_category" class="btn-primary-custom w-100">Add Section</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="table-box">
                            <table class="table admin-table align-middle">
                                <thead><tr><th>Icon</th><th>Section Name</th><th>Active Campaigns</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php 
                                    if($cat_list_query->num_rows > 0) {
                                        $cat_list_query->data_seek(0);
                                        while($cat = $cat_list_query->fetch_assoc()) {
                                            $disabled = ($cat['camp_count'] > 0) ? "disabled title='Cannot delete section in use'" : "";
                                            echo "<tr>";
                                            echo "<td><i class='{$cat['icon']} fa-lg text-blue'></i></td>";
                                            echo "<td><b>{$cat['name']}</b></td>";
                                            echo "<td><span class='badge bg-warning text-dark'>{$cat['camp_count']} Campaigns</span></td>";
                                            echo "<td><a href='admin.php?action=delete_category&id={$cat['id']}' class='btn-action btn-del' $disabled onclick='return confirm(\"Delete this section permanently?\");'><i class='fas fa-trash'></i> Delete</a></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="add_campaign" class="content-section">
                <h3 class="welcome-text mb-4"><i class="fas fa-plus-circle text-orange me-2"></i> Add New Campaign</h3>
                <div class="table-box" style="max-width: 800px;">
                    <form action="admin.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="camp_title" placeholder="Title" required>
                                    <label>Campaign Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <select class="form-select" id="addCampCat" name="camp_category" onchange="toggleCustomCat('addCampCat', 'customAddCatDiv')" required>
                                        <option value="" disabled selected>Select a Category...</option>
                                        <?php 
                                        $cat_list_query->data_seek(0);
                                        while($cat = $cat_list_query->fetch_assoc()) {
                                            echo "<option value='{$cat['name']}'>{$cat['name']}</option>";
                                        }
                                        ?>
                                        <option value="--NEW--" class="fw-bold text-primary">➕ Add New Category...</option>
                                    </select>
                                    <label>Category (Section)</label>
                                </div>
                                <div class="form-floating" id="customAddCatDiv" style="display: none;">
                                    <input type="text" class="form-control border-primary" name="custom_category" placeholder="Type new category">
                                    <label class="text-primary">Type New Category Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="camp_goal" placeholder="Goal" required>
                                    <label>Goal Amount (₹)</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small fw-bold ms-1 mb-1">Upload Campaign Image</label>
                                <input type="file" class="form-control p-3" name="camp_image" accept="image/*" required>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="camp_content" style="height: 150px" required></textarea>
                                    <label>Full Article / Description</label>
                                </div>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" name="add_campaign" class="btn-primary-custom"><i class="fas fa-paper-plane me-2"></i> Publish Campaign</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="campaigns_growth" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="welcome-text mb-0"><i class="fas fa-chart-line text-orange me-2"></i> Campaigns Growth</h3>
                </div>
                <div class="table-box">
                    <table class="table admin-table align-middle">
                        <thead><tr><th>#</th><th>Campaign Details</th><th>Category</th><th>Progress / Growth</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php 
                            if($campaigns_query->num_rows > 0) {
                                $cp_cnt = 1;
                                while($camp = $campaigns_query->fetch_assoc()) {
                                    $percent = ($camp['goal_amount'] > 0) ? ($camp['raised'] / $camp['goal_amount']) * 100 : 0;
                                    if($percent > 100) $percent = 100;
                                    echo "<tr><td><b>{$cp_cnt}</b></td><td><div class='d-flex align-items-center'><img src='img/{$camp['image']}' style='width: 60px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 10px;'><b class='text-blue' style='max-width: 250px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>{$camp['title']}</b></div></td><td><span class='badge bg-light text-dark border'>{$camp['category']}</span></td><td><div class='d-flex justify-content-between small text-muted'><span>₹{$camp['raised']} Raised</span><span>₹{$camp['goal_amount']} Goal</span></div><div class='progress-bar-custom'><div class='progress-fill' style='width: {$percent}%;'></div></div><div class='small text-muted mt-1'><i class='fas fa-users text-orange'></i> {$camp['donors']} Donors</div></td><td><a href='admin.php?view_campaign={$camp['id']}' class='btn-action btn-approve mb-1'><i class='fas fa-eye'></i> View & Edit</a> <a href='admin.php?action=delete_campaign&id={$camp['id']}' class='btn-action btn-del' onclick='return confirm(\"Delete the campaign AND all its donation records?\");'><i class='fas fa-trash'></i> Delete</a></td></tr>";
                                    $cp_cnt++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="donors" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="welcome-text mb-0"><i class="fas fa-hand-holding-heart text-danger me-2"></i> All Donors</h3>
                    <div class="search-box shadow-sm"><i class="fas fa-search"></i><input type="text" id="donorSearchInput" onkeyup="searchDonors()" placeholder="Search Email or Mobile..."></div>
                </div>
                <div class="table-box">
                    <table class="table admin-table align-middle" id="donorsTable">
                        <thead><tr><th>#</th><th>Date</th><th>Receipt No.</th><th>Donor Details</th><th>Amount</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php 
                            if($donors_query->num_rows > 0) {
                                $d_cnt = 1;
                                while($row = $donors_query->fetch_assoc()) {
                                    echo "<tr class='donor-row'><td><b>{$d_cnt}</b></td><td><small class='text-muted'>".date("d M Y", strtotime($row['created_at']))."</small></td><td><b class='text-blue'>{$row['receipt_no']}</b></td><td class='donor-info'><b>{$row['donor_name']}</b><br><small>{$row['email']}</small><br><small>{$row['mobile']}</small></td><td><span class='badge bg-success fs-6'>₹{$row['amount']}</span></td><td><a href='certificate.php?id={$row['id']}&mode=preview' target='_blank' class='btn-action btn-approve'><i class='fas fa-eye'></i> Preview</a> <a href='certificate.php?id={$row['id']}&mode=download' target='_blank' class='btn-action btn-orange'><i class='fas fa-download'></i></a></td></tr>";
                                    $d_cnt++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="contacts" class="content-section"><h3 class="welcome-text mb-4">Contact Requests</h3><div class="table-box"><table class="table admin-table"><tbody><?php while($row = $contacts_query->fetch_assoc()) { echo "<tr><td>{$row['full_name']}</td><td>{$row['phone']}</td><td>{$row['subject']}</td><td><a href='admin.php?action=done_contact&id={$row['id']}' class='btn-action btn-done'>Done</a></td></tr>"; } ?></tbody></table></div></div>
            <div id="pending_volunteers" class="content-section"><h3 class="welcome-text mb-4">Pending Volunteers</h3><div class="table-box"><table class="table admin-table"><tbody><?php while($row = $pending_vol_query->fetch_assoc()) { echo "<tr><td>{$row['full_name']}</td><td><a href='uploads/{$row['profile_photo']}' target='_blank'>Docs</a></td><td><a href='admin.php?action=approve_volunteer&id={$row['id']}' class='btn-action btn-approve'>Approve</a></td></tr>"; } ?></tbody></table></div></div>
        </div>
    </div>

    <script>
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });

        // Script to toggle Custom Category Input box
        function toggleCustomCat(selectId, inputDivId) {
            var selectObj = document.getElementById(selectId);
            var inputDiv = document.getElementById(inputDivId);
            if(selectObj.value === '--NEW--') {
                inputDiv.style.display = 'block';
                inputDiv.querySelector('input').setAttribute('required', 'true');
            } else {
                inputDiv.style.display = 'none';
                inputDiv.querySelector('input').removeAttribute('required');
            }
        }

        function switchSection(sectionId, clickedItem) {
            document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
            if(window.location.search.includes('view_campaign') && sectionId !== 'view_campaign') {
                window.location.href = 'admin.php'; 
                return;
            }
            document.getElementById(sectionId).classList.add('active');
            document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
            clickedItem.classList.add('active');
            
            if(sectionId === 'donors') {
                document.getElementById("donorSearchInput").value = "";
                searchDonors();
            }
        }

        function searchDonors() {
            var input = document.getElementById("donorSearchInput").value.toUpperCase();
            var tr = document.getElementById("donorsTable").getElementsByClassName("donor-row");
            for (var i = 0; i < tr.length; i++) {
                var td = tr[i].getElementsByClassName("donor-info")[0];
                if (td) {
                    tr[i].style.display = (td.textContent || td.innerText).toUpperCase().indexOf(input) > -1 ? "" : "none";
                }       
            }
        }
    </script>
</body>
</html>