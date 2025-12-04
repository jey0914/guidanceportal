<?php
session_start();
include("db.php");

$msg = "";
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['step1_submit'])) {
        // Store form data in session and move to step 2
        $_SESSION['form_data'] = [
            'student_no' => trim($_POST['student_no']),
            'fname' => trim($_POST['fname']),
            'mname' => trim($_POST['mname']),
            'lname' => trim($_POST['lname']),
            'email' => trim($_POST['email'])
        ];
        
        if (!empty($_SESSION['form_data']['student_no']) && 
            !empty($_SESSION['form_data']['fname']) && 
            !empty($_SESSION['form_data']['lname']) && 
            !empty($_SESSION['form_data']['email'])) {
            header("Location: ?step=2");
            exit();
        } else {
            $msg = "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6'>
                        Please complete all required fields.
                    </div>";
        }
    }
    
    if (isset($_POST['verify_submit'])) {
        // Verify the information
        $data = $_SESSION['form_data'];
        
        $stmt = $con->prepare("SELECT * FROM form 
                               WHERE student_no = ? AND fname = ? AND lname = ? AND email = ? LIMIT 1");
        $stmt->bind_param("ssss", $data['student_no'], $data['fname'], $data['lname'], $data['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Generate 5-digit temporary password
            $tempPass = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $hashedPass = password_hash($tempPass, PASSWORD_DEFAULT);

            // Update the user's temp_pass
            $update = $con->prepare("UPDATE form SET temp_pass = ?, password_changed_at = NOW() WHERE email = ?");
            $update->bind_param("ss", $hashedPass, $data['email']);
            $update->execute();

            $_SESSION['temp_code'] = $tempPass;
            header("Location: ?step=3");
            exit();
        } else {
            $msg = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6'>
                        The information does not match our records. Please go back and check your details.
                    </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Step <?= $step ?> | Guidance Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            box-sizing: border-box;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }
        
        .step-circle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .step-active .step-circle {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .step-completed .step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .step-pending .step-circle {
            background: #e5e7eb;
            color: #6b7280;
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background: rgba(107, 114, 128, 0.1);
            border: 1px solid rgba(107, 114, 128, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(107, 114, 128, 0.2);
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .temp-code-display {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px dashed #3b82f6;
            padding: 2rem;
            border-radius: 1rem;
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 0.3em;
            text-align: center;
            margin: 1.5rem 0;
        }
    </style>
</head>
<body class="gradient-bg flex items-center justify-center p-4">
    <div class="form-card w-full max-w-lg p-8 fade-in">
        
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item <?= $step >= 1 ? ($step == 1 ? 'step-active' : 'step-completed') : 'step-pending' ?>">
                <div class="step-circle">
                    <?= $step > 1 ? '<i class="fas fa-check"></i>' : '1' ?>
                </div>
                <span class="text-sm font-medium">Enter Info</span>
            </div>
            
            <div class="step-item <?= $step >= 2 ? ($step == 2 ? 'step-active' : 'step-completed') : 'step-pending' ?>">
                <div class="step-circle">
                    <?= $step > 2 ? '<i class="fas fa-check"></i>' : '2' ?>
                </div>
                <span class="text-sm font-medium">Verify</span>
            </div>
            
            <div class="step-item <?= $step >= 3 ? 'step-active' : 'step-pending' ?>">
                <div class="step-circle">3</div>
                <span class="text-sm font-medium">Get Code</span>
            </div>
        </div>

        <?php if ($step == 1): ?>
            <!-- Step 1: Enter Information -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Enter Your Information</h2>
                <p class="text-gray-600 text-sm">Please provide your registration details</p>
            </div>

            <?php if(!empty($msg)) echo $msg; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Student Number</label>
                    <input type="text" name="student_no" placeholder="e.g. 2023-00123" 
                        class="input-field w-full border-2 border-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500" 
                        value="<?= isset($_SESSION['form_data']['student_no']) ? htmlspecialchars($_SESSION['form_data']['student_no']) : '' ?>" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2 text-sm">First Name</label>
                        <input type="text" name="fname" placeholder="Juan" 
                            class="input-field w-full border-2 border-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500" 
                            value="<?= isset($_SESSION['form_data']['fname']) ? htmlspecialchars($_SESSION['form_data']['fname']) : '' ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2 text-sm">Last Name</label>
                        <input type="text" name="lname" placeholder="Dela Cruz" 
                            class="input-field w-full border-2 border-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500" 
                            value="<?= isset($_SESSION['form_data']['lname']) ? htmlspecialchars($_SESSION['form_data']['lname']) : '' ?>" required>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Middle Name <span class="text-gray-400">(Optional)</span></label>
                    <input type="text" name="mname" placeholder="Santos" 
                        class="input-field w-full border-2 border-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500" 
                        value="<?= isset($_SESSION['form_data']['mname']) ? htmlspecialchars($_SESSION['form_data']['mname']) : '' ?>">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" 
                        class="input-field w-full border-2 border-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500" 
                        value="<?= isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : '' ?>" required>
                </div>

                <button type="submit" name="step1_submit" class="btn-primary w-full py-3 rounded-xl text-white font-semibold mt-6">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Continue to Verification
                </button>
            </form>

        <?php elseif ($step == 2): ?>
            <!-- Step 2: Verification -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-check text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Information</h2>
                <p class="text-gray-600 text-sm">Please confirm the details below are correct</p>
            </div>

            <?php if(!empty($msg)) echo $msg; ?>

            <?php if (isset($_SESSION['form_data'])): ?>
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Your Information:</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Student Number:</span>
                            <span class="font-medium"><?= htmlspecialchars($_SESSION['form_data']['student_no']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">
                                <?= htmlspecialchars($_SESSION['form_data']['fname']) ?> 
                                <?= !empty($_SESSION['form_data']['mname']) ? htmlspecialchars($_SESSION['form_data']['mname']) . ' ' : '' ?>
                                <?= htmlspecialchars($_SESSION['form_data']['lname']) ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium"><?= htmlspecialchars($_SESSION['form_data']['email']) ?></span>
                        </div>
                    </div>
                </div>

                <form method="POST" class="space-y-4">
                    <button type="submit" name="verify_submit" class="btn-primary w-full py-3 rounded-xl text-white font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verify & Generate Code
                    </button>
                </form>

                <div class="flex gap-3 mt-4">
                    <a href="?step=1" class="btn-secondary flex-1 py-3 rounded-xl text-gray-700 font-medium text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Edit Information
                    </a>
                </div>
            <?php endif; ?>

        <?php elseif ($step == 3): ?>
            <!-- Step 3: Success & Code Display -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">✅ Verification Successful!</h2>
                <p class="text-gray-600 text-sm">Your temporary access code has been generated</p>
            </div>

            <?php if (isset($_SESSION['temp_code'])): ?>
                <div class="text-center">
                    <p class="text-gray-700 mb-4 font-medium">Your temporary password is:</p>
                    
                    <div class="temp-code-display" id="tempCode">
                        <?= $_SESSION['temp_code'] ?>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 mt-6">
                        <button onclick="copyCode()" class="btn-primary flex-1 py-3 rounded-xl text-white font-semibold" id="copyBtn">
                            <i class="fas fa-copy mr-2"></i>
                            Copy Code
                        </button>
                        <a href="login.php" class="btn-primary flex-1 py-3 rounded-xl text-white font-semibold text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Go to Login
                        </a>
                    </div>
                    
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6">
                        <div class="flex items-center gap-2 text-amber-800">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-semibold">Important:</span>
                        </div>
                        <p class="text-amber-700 text-sm mt-2">
                            This code expires in 24 hours. Please login immediately and change your password.
                        </p>
                    </div>
                </div>
                
                <?php 
                // Clear the session data after displaying
                unset($_SESSION['form_data']);
                unset($_SESSION['temp_code']);
                ?>
            <?php endif; ?>

        <?php endif; ?>

        <!-- Back to Login -->
        <p class="text-sm mt-6 text-center text-gray-600">
            Remember your password? 
            <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-arrow-left mr-1"></i>Login here
            </a>
        </p>
    </div>

    <script>
        // Copy code functionality
        function copyCode() {
            const codeText = document.getElementById('tempCode').textContent.trim();
            const copyBtn = document.getElementById('copyBtn');
            
            navigator.clipboard.writeText(codeText).then(() => {
                copyBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                copyBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy mr-2"></i>Copy Code';
                    copyBtn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%)';
                }, 2000);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = codeText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                copyBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            });
        }

        // Auto-hide messages after 8 seconds
        const messageBox = document.querySelector('[class*="bg-red"], [class*="bg-yellow"]');
        if (messageBox) {
            setTimeout(() => {
                messageBox.style.opacity = '0';
                messageBox.style.transform = 'translateY(-10px)';
                setTimeout(() => messageBox.style.display = 'none', 300);
            }, 8000);
        }
    </script>
</body>
</html>
