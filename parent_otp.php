<?php
session_start();
include("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if(!isset($_SESSION['parent_email'])){
    header("Location: parent_login.php");
    exit;
}

$error = "";
$success = "";
$otpSent = false;

// ----------------- Auto-send OTP (first time) -----------------
if(!isset($_SESSION['otp'])){
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();

    sendOTP($_SESSION['parent_email'], $_SESSION['parent_name'], $otp, $error, $success, $otpSent);
}

// ----------------- Resend OTP (2-minute cooldown) -----------------
if(isset($_POST['resend_otp'])){
    if(isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time'] < 120)) {
        $error = "You can resend OTP only after 2 minutes.";
    } else {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();

        sendOTP($_SESSION['parent_email'], $_SESSION['parent_name'], $otp, $error, $success, $otpSent);
    }
}

// ----------------- Verify OTP -----------------

if(isset($_POST['verify_otp'])){
    // Combine 6 digits into a single OTP string
    $entered_otp = $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit4'] . $_POST['digit5'] . $_POST['digit6'];

    if(isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp'] && (time() - $_SESSION['otp_time']) <= 300){
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        header("Location: parent_dashboard.php");
        exit;
    } else {
        $error = "Invalid or expired OTP. Please try again.";
        $otpSent = true;
    }
}

// ----------------- Function to send OTP -----------------
function sendOTP($email, $name, $otp, &$error, &$success, &$otpSent){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'guidanceoffice879@gmail.com';
        $mail->Password = 'oenlipnkxqmteifm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('guidanceoffice879@gmail.com', 'Guidance Office');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Hello $name,<br>Your OTP code is <b>$otp</b>. It is valid for 5 minutes.";

        $mail->send();
        $success = "OTP has been sent to your email.";
        $otpSent = true;
    } catch (Exception $e) {
        $error = "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
<style>
body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f5f5dc;
}
.container {
    background-color: #fff;
    border-radius: 15px;
    padding: 30px 25px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 400px;
    width: 100%;
}
.email-icon {
    font-size: 50px;
    color: #FFA500;
    margin-bottom: 10px;
}
h2 {
    color: #333;
    margin-bottom: 10px;
}
.description {
    color: #666;
    margin-bottom: 20px;
    font-size: 14px;
}
.code-input-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.code-input {
    width: 45px;
    height: 50px;
    font-size: 20px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.verify-button, .send-button {
    background-color: #FFA500;
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    margin: 5px 0;
}
.verify-button:disabled, .send-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.change-email {
    margin-top: 10px;
    font-size: 13px;
}
.change-email a {
    color: #007bff;
    text-decoration: none;
}

.resend-code-link {
    background:none;
    border:none;
    color:#007bff;
    text-decoration:underline;
    cursor:pointer;
    font-size:13px;
    padding:0;
}

.resend-code {
    margin-top: 10px;
    color: #007bff;
    text-decoration: none;
    display: block;
    font-size: 13px;
}
.error { color: red; margin-bottom: 10px; font-size: 14px; }
.success { color: green; margin-bottom: 10px; font-size: 14px; }
</style>
</head>
<body>
<div class="container">
    <div class="email-icon">✉️</div>
    <h2>Verify Your Email Address</h2>
    <p class="description">Before you proceed, please request and enter your OTP.</p>

    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>

    <!-- Send OTP -->
   <form method="POST">
    <div class="code-input-container">
    </div>


    <!-- Verify OTP -->
    <div class="code-input-container">
        <input type="text" name="digit1" class="code-input" maxlength="1" required>
        <input type="text" name="digit2" class="code-input" maxlength="1" required>
        <input type="text" name="digit3" class="code-input" maxlength="1" required>
        <input type="text" name="digit4" class="code-input" maxlength="1" required>
        <input type="text" name="digit5" class="code-input" maxlength="1" required>
        <input type="text" name="digit6" class="code-input" maxlength="1" required>
    </div>
     <button type="submit" name="verify_otp" class="verify-button" <?php echo $otpSent ? '' : 'disabled'; ?>>Verify Email</button>
    </form>

    <form method="POST" style="text-align:center; margin-top:10px;">
    <button type="submit" name="resend_otp" class="resend-code-link">Resend OTP</button>
</form>

<script>
let resendBtn = document.querySelector('.resend-code-link');
let cooldown = 0;

<?php
if(isset($_SESSION['otp_time'])) {
    $remaining = 120 - (time() - $_SESSION['otp_time']);
    if($remaining > 0) echo "cooldown = $remaining;";
}
?>

function startCooldown(){
    resendBtn.disabled = true;
    let interval = setInterval(() => {
        resendBtn.textContent = `Resend OTP (${cooldown}s)`;
        cooldown--;
        if(cooldown < 0){
            resendBtn.disabled = false;
            resendBtn.textContent = "Resend OTP";
            clearInterval(interval);
        }
    }, 1000);
}

if(cooldown > 0) startCooldown();

const inputs = document.querySelectorAll(".code-input");

inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && input.value === "" && index > 0) {
            inputs[index - 1].focus();
        }
    });
});


</script>

</body>
</html>
