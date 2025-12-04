<?php
session_start();
include 'db.php';

// Assume admin is logged in
$admin_email = $_SESSION['admin_email'];

// Fetch admin data including hours_logged
$sql = "SELECT *, IFNULL(hours_logged, 0) AS total_hours FROM admin WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$total_hours = number_format($admin['total_hours'], 2);

// Fetch total students
$total_result = $con->query("SELECT COUNT(*) AS total FROM form");
$total_students = $total_result->fetch_assoc()['total'];

// Fetch total appointments
$total_sql = "SELECT COUNT(*) as count_all FROM appointments";
$count_all = $con->query($total_sql)->fetch_assoc()['count_all'];

// Fetch distinct students who have appointments
$student_sql = "SELECT COUNT(DISTINCT email) as count_students FROM appointments";
$count_students = $con->query($student_sql)->fetch_assoc()['count_students'];
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Profile</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      box-sizing: border-box;
    }

    .dashboard { display: flex; min-height: 100%; }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #fff;
      border-right: 1px solid #dee2e6;
      padding: 1.5rem 0;
      position: fixed;
      height: 100%;
      overflow-y: auto;
    }

    .sidebar h2 {
      padding: 0 1.5rem;
      margin-bottom: 2rem;
      font-size: 1.25rem;
      font-weight: 600;
      color: #495057;
    }

    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar li { margin-bottom: 0.25rem; }
    .sidebar a {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.75rem 1.5rem;
      color: #6c757d; text-decoration: none; font-weight: 500;
      transition: all 0.2s ease;
    }
    .sidebar a:hover { background-color: #f8f9fa; color: #495057; }
    .sidebar a.active { background-color: #e3f2fd; color: #1976d2; border-right: 3px solid #1976d2; }
    .sidebar a i { font-size: 1.1rem; width: 18px; text-align: center; }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      flex: 1;
      padding: 2rem;
      width: calc(100% - 250px);
    }

    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-size: 1.75rem;
      font-weight: 600;
      color: #212529;
      margin: 0;
    }

    .profile-header {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      padding: 2rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(135deg, #1976d2, #42a5f5);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      color: #fff;
      font-weight: 600;
      flex-shrink: 0;
    }

    .profile-info {
      flex: 1;
    }

    .profile-info h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #212529;
      margin: 0 0 0.5rem 0;
    }

    .profile-info .role {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 1rem;
    }

    .profile-meta {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
    }

    .profile-meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #495057;
      font-size: 0.9375rem;
    }

    .profile-meta-item i {
      color: #1976d2;
      font-size: 1.1rem;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .stat-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      padding: 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
    }

    .stat-icon.blue { background-color: #e3f2fd; color: #1976d2; }
    .stat-icon.green { background-color: #e8f5e9; color: #388e3c; }
    .stat-icon.orange { background-color: #fff3e0; color: #f57c00; }
    .stat-icon.purple { background-color: #f3e5f5; color: #7b1fa2; }

    .stat-info h3 {
      font-size: 1.75rem;
      font-weight: 600;
      color: #212529;
      margin: 0 0 0.25rem 0;
    }

    .stat-info p {
      font-size: 0.875rem;
      color: #6c757d;
      margin: 0;
    }

    .content-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
    }

    .content-card-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #dee2e6;
    }

    .content-card-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #495057;
      margin: 0;
    }

    .content-card-body {
      padding: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 0.625rem 0.875rem;
      font-size: 0.9375rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: #1976d2;
      box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
    }

    .btn-primary {
      background-color: #1976d2;
      border-color: #1976d2;
      padding: 0.625rem 1.5rem;
      font-weight: 500;
      border-radius: 6px;
      transition: all 0.2s ease;
    }

    .btn-primary:hover {
      background-color: #1565c0;
      border-color: #1565c0;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      padding: 0.625rem 1.5rem;
      font-weight: 500;
      border-radius: 6px;
    }

    .activity-item {
      display: flex;
      gap: 1rem;
      padding: 1rem 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #e3f2fd;
      color: #1976d2;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .activity-content {
      flex: 1;
    }

    .activity-content h4 {
      font-size: 0.9375rem;
      font-weight: 500;
      color: #212529;
      margin: 0 0 0.25rem 0;
    }

    .activity-content p {
      font-size: 0.875rem;
      color: #6c757d;
      margin: 0;
    }

    .toast-container {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 1050;
    }

    .toast {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      padding: 1rem 1.25rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      min-width: 300px;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .toast.success {
      border-left: 4px solid #28a745;
    }

    .toast-icon {
      font-size: 1.25rem;
      color: #28a745;
    }

    .toast-message {
      flex: 1;
      font-size: 0.9375rem;
      color: #212529;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        position: relative;
        height: auto;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
      }

      .dashboard {
        flex-direction: column;
      }

      .profile-header {
        flex-direction: column;
        text-align: center;
      }

      .profile-meta {
        justify-content: center;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>

  <div class="dashboard"><!-- Sidebar -->
   <aside class="sidebar">
    <h2>Admin Panel</h2>
    <nav>
     <ul>
      <li><a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
      <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
      <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
      <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
      <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
      <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
     </ul>
    </nav>
   </aside><!-- Main Content -->
   <main class="main-content">
    <div class="page-header">
     <h1 id="pageTitle">My Profile</h1>
    </div><!-- Profile Header -->
    <div class="profile-header">
     <div class="profile-avatar" id="profileAvatar">
    <?= strtoupper(substr($admin['first_name'],0,1) . substr($admin['last_name'],0,1)) ?>
  </div>

    <div class="profile-info">
  <h2 id="profileName"><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></h2>
  <p class="role" id="profileRole"><?= htmlspecialchars($admin['role'] ?? 'System Administrator') ?></p>
  <div class="profile-meta">
    <div class="profile-meta-item">
      <i class="bi bi-envelope"></i> 
      <span id="profileEmail"><?= htmlspecialchars($admin['email']) ?></span>
    </div>
    <div class="profile-meta-item">
      <i class="bi bi-telephone"></i> 
      <span id="profilePhone"><?= htmlspecialchars($admin['phone'] ?? '') ?></span>
    </div>
    <div class="profile-meta-item">
      <i class="bi bi-calendar"></i> 
      <span>Joined <?= date("M Y", strtotime($admin['created_at'] ?? 'now')) ?></span>
    </div>
  </div>
</div>
    </div><!-- Stats Grid -->
    <div class="stats-grid">
     <div class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-people"></i>
      </div>
      <div class="stat-info">
    <h3><?= $total_students ?></h3>
    <p>Students Managed</p>
</div>

     </div>
     <div class="stat-card">
      <div class="stat-icon green"><i class="bi bi-calendar-check"></i>
      </div>
      <div class="stat-info">
       <h3><?= $count_all ?></h3>
       <p>Appointments</p>
      </div>
     </div>
     <div class="stat-card">
      <div class="stat-icon orange"><i class="bi bi-file-text"></i>
      </div>
      <div class="stat-info">
       <h3>89</h3>
       <p>Reports Generated</p>
      </div>
     </div>
     <div class="stat-card">
      <div class="stat-icon purple"><i class="bi bi-clock-history"></i>
      </div>
      <div class="stat-info">
       <h3><?= $total_hours ?></h3>
       <p>Hours Logged</p>
      </div>
     </div>
    </div>
    <div class="row">

     <div class="col-lg-8"><!-- Personal Information -->
  <div class="content-card">
    <div class="content-card-header">
      <h3>Personal Information</h3>
    </div>
    <div class="content-card-body">
      <form id="personalInfoForm">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" value="<?= htmlspecialchars($admin['first_name']) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" value="<?= htmlspecialchars($admin['last_name']) ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="emailInput" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="emailInput" value="<?= htmlspecialchars($admin['email']) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label for="phoneInput" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phoneInput" value="<?= htmlspecialchars($admin['phone']) ?>">
          </div>
        </div>
        <div class="mb-3">
          <label for="bio" class="form-label">Bio</label>
          <textarea class="form-control" id="bio" rows="3"><?= htmlspecialchars($admin['bio']) ?></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department">
              <option value="admin" <?= $admin['department'] == "admin" ? "selected" : "" ?>>Administration</option>
              <option value="health" <?= $admin['department'] == "health" ? "selected" : "" ?>>Health Services</option>
              <option value="it" <?= $admin['department'] == "it" ? "selected" : "" ?>>IT Support</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" value="<?= htmlspecialchars($admin['location']) ?>">
          </div>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Save Changes
          </button>
          <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

   </main>
  </div>

  <div class="toast-container" id="toastContainer"></div>
 
<script>
let originalData = {
  first_name: "<?= addslashes($admin['first_name']) ?>",
  last_name: "<?= addslashes($admin['last_name']) ?>",
  email: "<?= addslashes($admin['email']) ?>",
  phone: "<?= addslashes($admin['phone']) ?>",
  bio: `<?= addslashes($admin['bio']) ?>`,
  department: "<?= addslashes($admin['department']) ?>",
  location: "<?= addslashes($admin['location']) ?>"
};

document.getElementById('personalInfoForm').addEventListener('submit', function(e){
  e.preventDefault();

  const formData = new FormData();
  formData.append("first_name", document.getElementById('firstName').value);
  formData.append("last_name", document.getElementById('lastName').value);
  formData.append("email", document.getElementById('emailInput').value);
  formData.append("phone", document.getElementById('phoneInput').value);
  formData.append("bio", document.getElementById('bio').value);
  formData.append("department", document.getElementById('department').value);
  formData.append("location", document.getElementById('location').value);

  fetch("admin_update_profile.php", { method: "POST", body: formData }) // <- updated here
    .then(resp => resp.text())
    .then(data => {
      if(data.trim() === "success"){
        showToast("Profile updated successfully!");
        // update header
        const name = formData.get("first_name") + " " + formData.get("last_name");
        document.getElementById('profileName').textContent = name;
        document.getElementById('profileEmail').textContent = formData.get("email");
        document.getElementById('profilePhone').textContent = formData.get("phone");
        document.getElementById('profileAvatar').textContent =
          (formData.get("first_name")[0]+formData.get("last_name")[0]).toUpperCase();

        // update originalData
        originalData = {
          first_name: formData.get("first_name"),
          last_name: formData.get("last_name"),
          email: formData.get("email"),
          phone: formData.get("phone"),
          bio: formData.get("bio"),
          department: formData.get("department"),
          location: formData.get("location")
        };
      } else {
        showToast("Failed to update profile!");
      }
    });
});

document.getElementById('cancelBtn').addEventListener('click', function(){
  document.getElementById('firstName').value = originalData.first_name;
  document.getElementById('lastName').value = originalData.last_name;
  document.getElementById('emailInput').value = originalData.email;
  document.getElementById('phoneInput').value = originalData.phone;
  document.getElementById('bio').value = originalData.bio;
  document.getElementById('department').value = originalData.department;
  document.getElementById('location').value = originalData.location;
});

    async function onConfigChange(newConfig) {
      config = newConfig;
      
      const pageTitle = config.page_title || defaultConfig.page_title;
      const adminName = config.admin_name || defaultConfig.admin_name;
      const adminRole = config.admin_role || defaultConfig.admin_role;
      const adminEmail = config.admin_email || defaultConfig.admin_email;
      const adminPhone = config.admin_phone || defaultConfig.admin_phone;
      const bioText = config.bio_text || defaultConfig.bio_text;

      document.getElementById('pageTitle').textContent = pageTitle;
      document.getElementById('profileName').textContent = adminName;
      document.getElementById('profileRole').textContent = adminRole;
      document.getElementById('profileEmail').textContent = adminEmail;
      document.getElementById('profilePhone').textContent = adminPhone;
      document.getElementById('profileAvatar').textContent = getInitials(adminName);
      document.getElementById('bio').value = bioText;

      const nameParts = adminName.split(' ');
      document.getElementById('firstName').value = nameParts[0] || 'Admin';
      document.getElementById('lastName').value = nameParts.slice(1).join(' ') || 'User';
      document.getElementById('emailInput').value = adminEmail;
      document.getElementById('phoneInput').value = adminPhone;

      const primaryColor = config.primary_color || defaultConfig.primary_color;
      const backgroundColor = config.background_color || defaultConfig.background_color;
      const cardBackground = config.card_background || defaultConfig.card_background;
      const textColor = config.text_color || defaultConfig.text_color;
      const secondaryText = config.secondary_text || defaultConfig.secondary_text;

      document.body.style.backgroundColor = backgroundColor;
      
      document.querySelectorAll('.profile-header, .content-card, .stat-card').forEach(card => {
        card.style.backgroundColor = cardBackground;
      });

      document.querySelectorAll('.btn-primary').forEach(btn => {
        btn.style.backgroundColor = primaryColor;
        btn.style.borderColor = primaryColor;
      });

      document.querySelectorAll('.sidebar a.active').forEach(link => {
        link.style.backgroundColor = `${primaryColor}15`;
        link.style.color = primaryColor;
        link.style.borderRightColor = primaryColor;
      });

      document.querySelector('.profile-avatar').style.background = `linear-gradient(135deg, ${primaryColor}, ${primaryColor}cc)`;

      document.querySelectorAll('.stat-icon.blue, .activity-icon').forEach(icon => {
        icon.style.backgroundColor = `${primaryColor}15`;
        icon.style.color = primaryColor;
      });

      document.querySelectorAll('.profile-meta-item i').forEach(icon => {
        icon.style.color = primaryColor;
      });

      document.querySelector('.page-header h1').style.color = textColor;
      document.querySelector('.profile-info h2').style.color = textColor;
      document.querySelectorAll('.stat-info h3, .activity-content h4').forEach(h => {
        h.style.color = textColor;
      });
      document.querySelectorAll('.profile-info .role, .stat-info p, .activity-content p, .profile-meta-item').forEach(p => {
        p.style.color = secondaryText;
      });
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities: (config) => ({
          recolorables: [
            {
              get: () => config.primary_color || defaultConfig.primary_color,
              set: (value) => {
                config.primary_color = value;
                window.elementSdk.setConfig({ primary_color: value });
              }
            },
            {
              get: () => config.background_color || defaultConfig.background_color,
              set: (value) => {
                config.background_color = value;
                window.elementSdk.setConfig({ background_color: value });
              }
            },
            {
              get: () => config.card_background || defaultConfig.card_background,
              set: (value) => {
                config.card_background = value;
                window.elementSdk.setConfig({ card_background: value });
              }
            },
            {
              get: () => config.text_color || defaultConfig.text_color,
              set: (value) => {
                config.text_color = value;
                window.elementSdk.setConfig({ text_color: value });
              }
            },
            {
              get: () => config.secondary_text || defaultConfig.secondary_text,
              set: (value) => {
                config.secondary_text = value;
                window.elementSdk.setConfig({ secondary_text: value });
              }
            }
          ],
          borderables: [],
          fontEditable: undefined,
          fontSizeable: undefined
        }),
        mapToEditPanelValues: (config) => new Map([
          ["page_title", config.page_title || defaultConfig.page_title],
          ["admin_name", config.admin_name || defaultConfig.admin_name],
          ["admin_role", config.admin_role || defaultConfig.admin_role],
          ["admin_email", config.admin_email || defaultConfig.admin_email],
          ["admin_phone", config.admin_phone || defaultConfig.admin_phone],
          ["bio_text", config.bio_text || defaultConfig.bio_text]
        ])
      });

      config = window.elementSdk.config;
      onConfigChange(config);
    }
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99cc4fb400180dcd',t:'MTc2Mjg0ODYwNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>