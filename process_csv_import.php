<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'import_csv') {
    
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error occurred.']);
        exit();
    }

    $file = $_FILES['csv_file'];
    
    // Validate file type
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($fileExtension !== 'csv') {
        echo json_encode(['success' => false, 'message' => 'Please upload a valid CSV file.']);
        exit();
    }

    // Validate file size (10MB max)
    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size must be less than 10MB.']);
        exit();
    }

    $csvFile = fopen($file['tmp_name'], 'r');
    if (!$csvFile) {
        echo json_encode(['success' => false, 'message' => 'Could not read the CSV file.']);
        exit();
    }

    $results = [
        'total' => 0,
        'success' => 0,
        'errors' => 0,
        'duplicates' => 0,
        'email_errors' => 0,
        'error_details' => []
    ];

    // Skip header row
    $header = fgetcsv($csvFile);
    
    // Validate CSV headers
    $expectedHeaders = ['student_no', 'fname', 'mname', 'lname', 'bday', 'year_level', 'strand_course', 'personal_email'];
    if (!$header || count(array_intersect($expectedHeaders, $header)) < 7) {
        fclose($csvFile);
        echo json_encode(['success' => false, 'message' => 'Invalid CSV format. Please use the provided template.']);
        exit();
    }

    $rowNumber = 1; // Start from 1 (after header)

    while (($row = fgetcsv($csvFile)) !== FALSE) {
        $rowNumber++;
        $results['total']++;

        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }

        // Validate row has enough columns
        if (count($row) < 8) {
            $results['errors']++;
            $results['error_details'][] = "Row $rowNumber: Missing required columns";
            continue;
        }

        // Extract and sanitize data (same as your original logic)
        $student_no = trim($row[0]);
        $fname = ucwords(strtolower(trim($row[1])));
        $mname = ucwords(strtolower(trim($row[2])));
        if (empty($mname)) $mname = "N/A";
        $lname_display = ucwords(trim($row[3]));
        $raw_lname = strtolower(str_replace(' ', '', trim($row[3])));
        $bday = trim($row[4]);
        $year_level = trim($row[5]);
        $strand_course = trim($row[6]);
        $personal_email = trim($row[7]);

        // Validate required fields
        if (empty($student_no) || empty($fname) || empty($lname_display) || empty($bday) || empty($year_level) || empty($strand_course) || empty($personal_email)) {
            $results['errors']++;
            $results['error_details'][] = "Row $rowNumber: Missing required data";
            continue;
        }

        // Validate email format
        if (!filter_var($personal_email, FILTER_VALIDATE_EMAIL)) {
            $results['errors']++;
            $results['error_details'][] = "Row $rowNumber: Invalid email format";
            continue;
        }

        // Validate date format
        $dateCheck = DateTime::createFromFormat('Y-m-d', $bday);
        if (!$dateCheck || $dateCheck->format('Y-m-d') !== $bday) {
            $results['errors']++;
            $results['error_details'][] = "Row $rowNumber: Invalid date format (use YYYY-MM-DD)";
            continue;
        }

        // Check for duplicate student number (same as your original logic)
        $check = $con->prepare("SELECT student_no FROM form WHERE student_no = ?");
        $check->bind_param("s", $student_no);
        $check->execute();
        $exists = $check->get_result();

        if ($exists->num_rows > 0) {
            $results['duplicates']++;
            $results['error_details'][] = "Row $rowNumber: Student number $student_no already exists";
            continue;
        }

        // Generate email and password (same as your original logic)
        // Remove leading zeros, then keep last 6 digits
        $short_no = ltrim($student_no, '0'); 
        $short_no = substr($short_no, -6); 

        // Build email with short number
        $email = $raw_lname . "." . $short_no . "@guidanceportal.rosario.sti.edu.ph";

        // Generate default password
        $raw_password = $raw_lname . date("Ymd", strtotime($bday));
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

        // Insert into database (same as your original logic)
        $stmt = $con->prepare("INSERT INTO form 
            (student_no, fname, mname, lname, bday, email, pass, year_level, strand_course, personal_email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssssss",
            $student_no, $fname, $mname, $lname_display, $bday,
            $email, $hashed_password, $year_level, $strand_course, $personal_email
        );

        if ($stmt->execute()) {
            $results['success']++;

            // Send email (same as your original logic)
            
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gojannathaniel@gmail.com';
                $mail->Password = 'syoykadatugzoqlh';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your_email@gmail.com', 'Guidance Office');
                $mail->addAddress($personal_email, "$fname $lname_display");

                $mail->isHTML(true);
                $mail->Subject = "Your Guidance Portal Account";
                $mail->Body = "
                    <p>Hello <b>$fname $lname_display</b>,</p>
                    <p>Your student account has been created in the Guidance Portal.</p>
                    <p><b>Portal Email:</b> $email<br>
                    <b>Default Password:</b> $raw_password</p>
                    <p>Please log in and change your password immediately.</p>
                    <br><p>Regards,<br>Guidance Office</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                $results['email_errors']++;
                $results['error_details'][] = "Row $rowNumber: Student added but email failed - {$mail->ErrorInfo}";
            }
        } else {
            $results['errors']++;
            $results['error_details'][] = "Row $rowNumber: Database insertion failed";
        }
    }

    fclose($csvFile);

    // Prepare response message
    $message = "<strong>Import Results:</strong><br>";
    $message .= "• Total rows processed: {$results['total']}<br>";
    $message .= "• Successfully added: {$results['success']}<br>";
    
    if ($results['duplicates'] > 0) {
        $message .= "• Duplicates skipped: {$results['duplicates']}<br>";
    }
    
    if ($results['email_errors'] > 0) {
        $message .= "• Email sending errors: {$results['email_errors']}<br>";
    }
    
    if ($results['errors'] > 0) {
        $message .= "• Processing errors: {$results['errors']}<br>";
    }

    // Add error details if any
    if (!empty($results['error_details']) && count($results['error_details']) <= 10) {
        $message .= "<br><strong>Error Details:</strong><br>";
        foreach ($results['error_details'] as $error) {
            $message .= "• $error<br>";
        }
    } elseif (count($results['error_details']) > 10) {
        $message .= "<br><strong>Error Details:</strong><br>";
        for ($i = 0; $i < 10; $i++) {
            $message .= "• {$results['error_details'][$i]}<br>";
        }
        $remaining = count($results['error_details']) - 10;
        $message .= "• ... and $remaining more errors<br>";
    }

    $success = $results['success'] > 0;
    
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'stats' => $results
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method or action.']);
}
?>
