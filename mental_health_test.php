<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers']; // array of answers
    $_SESSION['mh_answers'] = $answers;

    // SIMPLE SCORING EXAMPLE
    $score = array_sum($answers);

    // SAVE SCORE IN SESSION
    $_SESSION['mh_score'] = $score;

    header("Location: test_result.php");
    exit;
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mental Health Assessment - GuidanceHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    .question-card {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid transparent;
    }
    
    .question-card.answered {
      border-color: #10b981;
      background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    }
    
    .radio-option {
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }
    
    .radio-option::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.1), transparent);
      transition: left 0.5s ease;
    }
    
    .radio-option:hover::before {
      left: 100%;
    }
    
    .radio-option input[type="radio"]:checked + .option-content {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
      color: white;
      transform: scale(1.02);
    }
    
    .progress-bar {
      transition: width 0.3s ease;
    }
    
    .floating-nav {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .question-number {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
      color: white;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 14px;
    }
    
    .category-badge {
      display: inline-flex;
      align-items: center;
      padding: 4px 12px;
      background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
      color: #7c3aed;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 12px;
    }
    
    .warning-banner {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border: 1px solid #f59e0b;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 24px;
    }
    
    .submit-section {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      border-radius: 20px;
      padding: 32px;
      text-align: center;
      border: 2px dashed #cbd5e1;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 min-h-screen">

  <!-- Header Navigation -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <a href="dashboard.php" class="flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="font-medium">Back to Dashboard</span>
          </a>
        </div>
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
            <i class="fas fa-brain text-purple-600"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800">Mental Health Assessment</h1>
            <p class="text-sm text-gray-600">Professional Evaluation Tool</p>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
      
      <!-- Assessment Header -->
      <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-8 animate-fadeInUp">
        <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 p-8 text-center text-white relative overflow-hidden">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
              <i class="fas fa-brain text-3xl"></i>
            </div>
            <h2 class="text-4xl font-extrabold mb-2">🧠 Mental Health Assessment</h2>
            <p class="text-purple-200 text-lg mb-4">Professional evaluation of your current mental wellness state</p>
            <div class="flex items-center justify-center space-x-6 text-sm">
              <div class="flex items-center space-x-2">
                <i class="fas fa-clock"></i>
                <span>15-20 minutes</span>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-shield-alt"></i>
                <span>Confidential</span>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-user-md"></i>
                <span>Professional Grade</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="bg-gray-100 h-2">
          <div id="progressBar" class="progress-bar bg-gradient-to-r from-purple-500 to-indigo-600 h-full" style="width: 0%"></div>
        </div>
        
        <!-- Progress Info -->
        <div class="p-6 bg-gradient-to-r from-purple-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-list-check text-purple-600 text-sm"></i>
              </div>
              <div>
                <p class="font-semibold text-gray-800">Progress</p>
                <p class="text-sm text-gray-600">Question <span id="currentQuestion">0</span> of <span id="totalQuestions">20</span></p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold text-gray-800"><span id="progressPercent">0</span>% Complete</p>
              <p class="text-sm text-gray-600">Keep going!</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Important Notice -->
      <div class="warning-banner animate-fadeInUp mb-8" style="animation-delay: 0.1s;">
        <div class="flex items-start space-x-3">
          <div class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
            <i class="fas fa-exclamation text-white text-sm"></i>
          </div>
          <div>
            <h3 class="font-semibold text-amber-800 mb-2">Important Notice</h3>
            <p class="text-amber-700 text-sm leading-relaxed">
              This assessment is for educational purposes and general wellness awareness. It is not a substitute for professional medical advice, diagnosis, or treatment. If you're experiencing severe mental health symptoms, please consult with a qualified healthcare professional immediately.
            </p>
          </div>
        </div>
      </div>

      <!-- Assessment Form -->
      <form method="post" id="assessmentForm" class="space-y-8">
        
        <!-- Stress & Anxiety Category -->
        <div class="space-y-6">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
              <i class="fas fa-heartbeat text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Stress & Anxiety Assessment</h3>
          </div>

          <!-- Question 1 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.2s;" data-question="1">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">1</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-heartbeat mr-1"></i>
                  Stress Level
                </div>
                <p class="font-semibold text-gray-800 text-lg">I feel stressed most of the time.</p>
                <p class="text-gray-600 text-sm mt-2">Consider your stress levels over the past two weeks.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[1]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[1]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[1]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[1]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[1]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 2 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.3s;" data-question="2">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">2</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-bed mr-1"></i>
                  Relaxation
                </div>
                <p class="font-semibold text-gray-800 text-lg">I find it hard to relax even during free time.</p>
                <p class="text-gray-600 text-sm mt-2">Think about your ability to unwind and feel calm.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[2]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[2]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[2]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[2]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[2]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 3 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.4s;" data-question="3">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">3</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-exclamation-triangle mr-1"></i>
                  Worry
                </div>
                <p class="font-semibold text-gray-800 text-lg">I worry excessively about things that might go wrong.</p>
                <p class="text-gray-600 text-sm mt-2">Consider how often anxious thoughts occupy your mind.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[3]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[3]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[3]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[3]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[3]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 4 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.5s;" data-question="4">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">4</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-heart mr-1"></i>
                  Physical Symptoms
                </div>
                <p class="font-semibold text-gray-800 text-lg">I experience physical symptoms of anxiety (racing heart, sweating, trembling).</p>
                <p class="text-gray-600 text-sm mt-2">Think about physical manifestations of stress or anxiety.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[4]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[4]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[4]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[4]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[4]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Mood & Depression Category -->
        <div class="space-y-6">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="fas fa-cloud-rain text-blue-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Mood & Emotional Well-being</h3>
          </div>

          <!-- Question 5 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.6s;" data-question="5">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">5</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-sun mr-1"></i>
                  Mood
                </div>
                <p class="font-semibold text-gray-800 text-lg">I feel sad, empty, or hopeless most days.</p>
                <p class="text-gray-600 text-sm mt-2">Reflect on your overall mood and emotional state recently.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[5]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[5]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[5]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[5]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[5]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 6 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.7s;" data-question="6">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">6</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-gamepad mr-1"></i>
                  Interest
                </div>
                <p class="font-semibold text-gray-800 text-lg">I have lost interest in activities I used to enjoy.</p>
                <p class="text-gray-600 text-sm mt-2">Consider your engagement with hobbies, social activities, or interests.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[6]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[6]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[6]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[6]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[6]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 7 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.8s;" data-question="7">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">7</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-battery-empty mr-1"></i>
                  Energy
                </div>
                <p class="font-semibold text-gray-800 text-lg">I feel tired or have little energy most of the time.</p>
                <p class="text-gray-600 text-sm mt-2">Think about your energy levels throughout the day.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[7]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[7]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[7]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[7]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[7]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 8 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 0.9s;" data-question="8">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">8</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-user-friends mr-1"></i>
                  Social Connection
                </div>
                <p class="font-semibold text-gray-800 text-lg">I prefer to isolate myself from friends and family.</p>
                <p class="text-gray-600 text-sm mt-2">Consider your desire for social interaction and connection.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[8]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[8]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[8]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[8]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[8]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Sleep & Daily Functioning Category -->
        <div class="space-y-6">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
              <i class="fas fa-moon text-indigo-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Sleep & Daily Functioning</h3>
          </div>

          <!-- Question 9 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 1.0s;" data-question="9">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">9</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-bed mr-1"></i>
                  Sleep Quality
                </div>
                <p class="font-semibold text-gray-800 text-lg">I have trouble falling asleep or staying asleep.</p>
                <p class="text-gray-600 text-sm mt-2">Think about your sleep patterns and quality of rest.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[9]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[9]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[9]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[9]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[9]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Question 10 -->
          <div class="question-card bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp" style="animation-delay: 1.1s;" data-question="10">
            <div class="flex items-start space-x-4 mb-6">
              <div class="question-number">10</div>
              <div class="flex-1">
                <div class="category-badge">
                  <i class="fas fa-utensils mr-1"></i>
                  Appetite
                </div>
                <p class="font-semibold text-gray-800 text-lg">My appetite has changed significantly (eating much more or much less).</p>
                <p class="text-gray-600 text-sm mt-2">Consider changes in your eating patterns or relationship with food.</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[10]" value="1" required class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-green-600 mb-1"><i class="fas fa-smile"></i></div>
                  <div class="text-sm">Never</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[10]" value="2" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-blue-600 mb-1"><i class="fas fa-meh"></i></div>
                  <div class="text-sm">Rarely</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[10]" value="3" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-yellow-600 mb-1"><i class="fas fa-meh-rolling-eyes"></i></div>
                  <div class="text-sm">Sometimes</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[10]" value="4" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-orange-600 mb-1"><i class="fas fa-frown"></i></div>
                  <div class="text-sm">Often</div>
                </div>
              </label>
              <label class="radio-option cursor-pointer">
                <input type="radio" name="answers[10]" value="5" class="sr-only">
                <div class="option-content bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl text-center font-medium transition-all">
                  <div class="text-red-600 mb-1"><i class="fas fa-tired"></i></div>
                  <div class="text-sm">Always</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Submit Section -->
        <div class="submit-section animate-fadeInUp" style="animation-delay: 1.2s;">
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-white text-2xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Ready to Submit Your Assessment?</h3>
          <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
            You've completed all questions! Click submit to receive your personalized mental health assessment results and recommendations.
          </p>
          
          <div class="flex items-center justify-center space-x-4 mb-8">
            <div class="flex items-center space-x-2 text-green-600">
              <i class="fas fa-shield-alt"></i>
              <span class="text-sm font-medium">Confidential</span>
            </div>
            <div class="flex items-center space-x-2 text-blue-600">
              <i class="fas fa-user-md"></i>
              <span class="text-sm font-medium">Professional Analysis</span>
            </div>
            <div class="flex items-center space-x-2 text-purple-600">
              <i class="fas fa-lightbulb"></i>
              <span class="text-sm font-medium">Personalized Tips</span>
            </div>
          </div>
          
          <button type="submit" id="submitBtn" disabled class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white px-12 py-4 rounded-2xl font-bold text-lg hover:from-purple-700 hover:to-indigo-800 transition-all duration-300 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
            <i class="fas fa-paper-plane mr-3"></i>
            Submit Assessment
          </button>
          
          <p class="text-sm text-gray-500 mt-4">
            <i class="fas fa-clock mr-1"></i>
            Results will be available immediately after submission
          </p>
        </div>
      </form>
    </div>
  </div>

  <!-- Floating Navigation -->
  <div class="floating-nav">
    <div class="bg-white rounded-2xl shadow-2xl p-4 border border-gray-200">
      <div class="flex items-center space-x-4">
        <button type="button" onclick="scrollToTop()" class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors">
          <i class="fas fa-arrow-up text-gray-600"></i>
        </button>
        <div class="text-center">
          <div class="text-sm font-semibold text-gray-800">Progress</div>
          <div class="text-xs text-gray-600"><span id="floatingProgress">0</span>%</div>
        </div>
        <button type="button" onclick="document.getElementById('assessmentForm').scrollIntoView({behavior: 'smooth', block: 'end'})" class="w-12 h-12 bg-purple-100 hover:bg-purple-200 rounded-xl flex items-center justify-center transition-colors">
          <i class="fas fa-arrow-down text-purple-600"></i>
        </button>
      </div>
    </div>
  </div>

  <script>
    const totalQuestions = 10;
    let answeredQuestions = 0;

    // Update progress tracking
    function updateProgress() {
      const answered = document.querySelectorAll('input[type="radio"]:checked').length;
      const progress = Math.round((answered / totalQuestions) * 100);
      
      document.getElementById('progressBar').style.width = progress + '%';
      document.getElementById('currentQuestion').textContent = answered;
      document.getElementById('totalQuestions').textContent = totalQuestions;
      document.getElementById('progressPercent').textContent = progress;
      document.getElementById('floatingProgress').textContent = progress;
      
      // Enable submit button when all questions are answered
      const submitBtn = document.getElementById('submitBtn');
      if (answered === totalQuestions) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
      } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
      }
      
      answeredQuestions = answered;
    }

    // Add event listeners to all radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
      radio.addEventListener('change', function() {
        // Mark question as answered
        const questionCard = this.closest('.question-card');
        questionCard.classList.add('answered');
        
        // Update progress
        updateProgress();
        
        // Smooth scroll to next question
        const currentQuestionNum = parseInt(questionCard.dataset.question);
        const nextQuestion = document.querySelector(`[data-question="${currentQuestionNum + 1}"]`);
        if (nextQuestion && currentQuestionNum < totalQuestions) {
          setTimeout(() => {
            nextQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }, 300);
        }
      });
    });

    // Scroll to top function
    function scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Form submission with loading state
    document.getElementById('assessmentForm').addEventListener('submit', function(e) {
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Processing...';
      submitBtn.disabled = true;
    });

    // Initialize progress on page load
    updateProgress();

    // Add smooth scrolling for better UX
    document.addEventListener('DOMContentLoaded', function() {
      // Animate question cards on scroll
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      document.querySelectorAll('.question-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
      });
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987c0227f6360dcd',t:'MTc1OTMyMjIxNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
