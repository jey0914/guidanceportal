<?php
session_start();

// Calculate score from session data
$answers = $_SESSION['mh_answers'] ?? [];
$totalScore = 0;
$categoryScores = [
    'stress_anxiety' => 0,
    'mood_depression' => 0,
    'sleep_functioning' => 0
];

// Calculate total and category scores
if (!empty($answers)) {
    foreach ($answers as $questionNum => $value) {
        $totalScore += (int)$value;
        
        // Categorize questions
        if (in_array($questionNum, [1, 2, 3, 4])) {
            $categoryScores['stress_anxiety'] += (int)$value;
        } elseif (in_array($questionNum, [5, 6, 7, 8])) {
            $categoryScores['mood_depression'] += (int)$value;
        } elseif (in_array($questionNum, [9, 10])) {
            $categoryScores['sleep_functioning'] += (int)$value;
        }
    }
}

// Determine overall wellness level
function getWellnessLevel($score) {
    if ($score <= 20) return 'excellent';
    elseif ($score <= 30) return 'good';
    elseif ($score <= 40) return 'moderate';
    else return 'concerning';
}

$wellnessLevel = getWellnessLevel($totalScore);

// Get detailed interpretation
function getDetailedInterpretation($level) {
    switch ($level) {
        case 'excellent':
            return [
                'title' => 'Excellent Mental Wellness',
                'description' => 'Your responses indicate strong mental health and effective coping strategies. You appear to be managing stress well and maintaining good emotional balance.',
                'color' => 'green',
                'icon' => 'fa-smile-beam'
            ];
        case 'good':
            return [
                'title' => 'Good Mental Health',
                'description' => 'You show good mental wellness with minor areas for improvement. Some occasional stress or mood fluctuations are normal and manageable.',
                'color' => 'blue',
                'icon' => 'fa-smile'
            ];
        case 'moderate':
            return [
                'title' => 'Moderate Concerns',
                'description' => 'Your responses suggest some mental health challenges that may benefit from attention and support. Consider implementing stress management techniques.',
                'color' => 'yellow',
                'icon' => 'fa-meh'
            ];
        case 'concerning':
            return [
                'title' => 'Significant Concerns',
                'description' => 'Your responses indicate notable mental health challenges. We strongly recommend speaking with a mental health professional for personalized support.',
                'color' => 'red',
                'icon' => 'fa-frown'
            ];
    }
}

$interpretation = getDetailedInterpretation($wellnessLevel);

// Generate personalized recommendations
function getRecommendations($level, $categoryScores) {
    $recommendations = [];
    
    // Stress & Anxiety recommendations
    if ($categoryScores['stress_anxiety'] > 12) {
        $recommendations[] = [
            'category' => 'Stress Management',
            'icon' => 'fa-leaf',
            'color' => 'green',
            'items' => [
                'Practice deep breathing exercises daily',
                'Try progressive muscle relaxation',
                'Consider mindfulness meditation',
                'Establish regular exercise routine'
            ]
        ];
    }
    
    // Mood & Depression recommendations
    if ($categoryScores['mood_depression'] > 12) {
        $recommendations[] = [
            'category' => 'Mood Enhancement',
            'icon' => 'fa-sun',
            'color' => 'yellow',
            'items' => [
                'Engage in activities you enjoy',
                'Maintain social connections',
                'Consider journaling your thoughts',
                'Spend time outdoors daily'
            ]
        ];
    }
    
    // Sleep & Functioning recommendations
    if ($categoryScores['sleep_functioning'] > 6) {
        $recommendations[] = [
            'category' => 'Sleep & Daily Function',
            'icon' => 'fa-moon',
            'color' => 'purple',
            'items' => [
                'Establish consistent sleep schedule',
                'Create relaxing bedtime routine',
                'Limit screen time before bed',
                'Consider sleep hygiene practices'
            ]
        ];
    }
    
    // General recommendations based on overall level
    if ($level === 'concerning') {
        $recommendations[] = [
            'category' => 'Professional Support',
            'icon' => 'fa-user-md',
            'color' => 'red',
            'items' => [
                'Schedule appointment with counselor',
                'Contact student mental health services',
                'Reach out to trusted friend or family',
                'Consider crisis hotline if needed'
            ]
        ];
    }
    
    return $recommendations;
}

$recommendations = getRecommendations($wellnessLevel, $categoryScores);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mental Health Assessment Results - GuidanceHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    .result-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      overflow: hidden;
      position: relative;
    }
    
    .result-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6, #ec4899);
    }
    
    .score-circle {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      margin: 0 auto;
    }
    
    .score-circle.excellent {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3);
    }
    
    .score-circle.good {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
    }
    
    .score-circle.moderate {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
    }
    
    .score-circle.concerning {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      box-shadow: 0 20px 40px rgba(239, 68, 68, 0.3);
    }
    
    .category-bar {
      height: 12px;
      border-radius: 6px;
      overflow: hidden;
      background: #e5e7eb;
    }
    
    .category-fill {
      height: 100%;
      border-radius: 6px;
      transition: width 1s ease-in-out;
    }
    
    .recommendation-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .recommendation-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
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
      animation: fadeInUp 0.8s ease-out;
    }
    
    @keyframes countUp {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .count-animation {
      animation: countUp 2s ease-out;
    }
    
    .wellness-badge {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
    }
    
    .wellness-badge.excellent {
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      color: #065f46;
    }
    
    .wellness-badge.good {
      background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
      color: #1e3a8a;
    }
    
    .wellness-badge.moderate {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      color: #92400e;
    }
    
    .wellness-badge.concerning {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      color: #991b1b;
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
            <i class="fas fa-chart-line text-purple-600"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800">Assessment Results</h1>
            <p class="text-sm text-gray-600">Your Mental Health Analysis</p>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-6 py-8">
    <div class="max-w-6xl mx-auto">
      
      <!-- Main Results Card -->
      <div class="result-card p-8 mb-8 animate-fadeInUp">
        <div class="text-center mb-8">
          <div class="score-circle <?= $wellnessLevel ?> mb-6 count-animation">
            <div class="text-center text-white">
              <div class="text-4xl font-bold mb-2"><?= $totalScore ?></div>
              <div class="text-lg opacity-90">out of 50</div>
            </div>
          </div>
          
          <div class="wellness-badge <?= $wellnessLevel ?> mb-4">
            <i class="fas <?= $interpretation['icon'] ?> mr-2"></i>
            <?= $interpretation['title'] ?>
          </div>
          
          <h2 class="text-3xl font-bold text-gray-800 mb-4">Your Mental Health Assessment Results</h2>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
            <?= $interpretation['description'] ?>
          </p>
        </div>

        <!-- Category Breakdown -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white/50 rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-heartbeat text-red-600"></i>
              </div>
              <div>
                <h3 class="font-semibold text-gray-800">Stress & Anxiety</h3>
                <p class="text-sm text-gray-600">Questions 1-4</p>
              </div>
            </div>
            <div class="category-bar mb-2">
              <div class="category-fill bg-gradient-to-r from-red-400 to-red-600" style="width: <?= ($categoryScores['stress_anxiety'] / 20) * 100 ?>%"></div>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Score: <?= $categoryScores['stress_anxiety'] ?>/20</span>
              <span class="font-medium text-gray-800"><?= round(($categoryScores['stress_anxiety'] / 20) * 100) ?>%</span>
            </div>
          </div>

          <div class="bg-white/50 rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-cloud-rain text-blue-600"></i>
              </div>
              <div>
                <h3 class="font-semibold text-gray-800">Mood & Emotions</h3>
                <p class="text-sm text-gray-600">Questions 5-8</p>
              </div>
            </div>
            <div class="category-bar mb-2">
              <div class="category-fill bg-gradient-to-r from-blue-400 to-blue-600" style="width: <?= ($categoryScores['mood_depression'] / 20) * 100 ?>%"></div>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Score: <?= $categoryScores['mood_depression'] ?>/20</span>
              <span class="font-medium text-gray-800"><?= round(($categoryScores['mood_depression'] / 20) * 100) ?>%</span>
            </div>
          </div>

          <div class="bg-white/50 rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <i class="fas fa-moon text-indigo-600"></i>
              </div>
              <div>
                <h3 class="font-semibold text-gray-800">Sleep & Function</h3>
                <p class="text-sm text-gray-600">Questions 9-10</p>
              </div>
            </div>
            <div class="category-bar mb-2">
              <div class="category-fill bg-gradient-to-r from-indigo-400 to-indigo-600" style="width: <?= ($categoryScores['sleep_functioning'] / 10) * 100 ?>%"></div>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Score: <?= $categoryScores['sleep_functioning'] ?>/10</span>
              <span class="font-medium text-gray-800"><?= round(($categoryScores['sleep_functioning'] / 10) * 100) ?>%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Personalized Recommendations -->
      <?php if (!empty($recommendations)): ?>
      <div class="mb-8 animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="text-center mb-8">
          <h3 class="text-2xl font-bold text-gray-800 mb-2">🎯 Personalized Recommendations</h3>
          <p class="text-gray-600">Based on your assessment, here are tailored suggestions for your wellness journey</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6">
          <?php foreach ($recommendations as $index => $rec): ?>
          <div class="recommendation-card p-6" style="animation-delay: <?= 0.3 + ($index * 0.1) ?>s;">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-12 h-12 bg-<?= $rec['color'] ?>-100 rounded-xl flex items-center justify-center">
                <i class="fas <?= $rec['icon'] ?> text-<?= $rec['color'] ?>-600 text-lg"></i>
              </div>
              <h4 class="text-lg font-semibold text-gray-800"><?= $rec['category'] ?></h4>
            </div>
            <ul class="space-y-2">
              <?php foreach ($rec['items'] as $item): ?>
              <li class="flex items-start space-x-2 text-gray-600">
                <i class="fas fa-check-circle text-<?= $rec['color'] ?>-500 mt-1 text-sm"></i>
                <span class="text-sm"><?= $item ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Resources & Next Steps -->
      <div class="grid md:grid-cols-2 gap-8 mb-8">
        <!-- Immediate Resources -->
        <div class="result-card p-8 animate-fadeInUp" style="animation-delay: 0.4s;">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-first-aid text-green-600 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Immediate Resources</h3>
          </div>
          
          <div class="space-y-4">
            <a href="tel:988" class="block p-4 bg-red-50 hover:bg-red-100 rounded-xl border border-red-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-phone text-red-600"></i>
                <div>
                  <div class="font-semibold text-red-800">Crisis Hotline</div>
                  <div class="text-sm text-red-600">24/7 immediate support - Call 988</div>
                </div>
              </div>
            </a>
            
            <a href="emergency_resources.php" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-xl border border-blue-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-shield-alt text-blue-600"></i>
                <div>
                  <div class="font-semibold text-blue-800">Emergency Resources</div>
                  <div class="text-sm text-blue-600">Quick access to help and support</div>
                </div>
              </div>
            </a>
            
            <a href="appointments.php" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-xl border border-purple-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-calendar-check text-purple-600"></i>
                <div>
                  <div class="font-semibold text-purple-800">Schedule Counseling</div>
                  <div class="text-sm text-purple-600">Book appointment with counselor</div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- Wellness Tools -->
        <div class="result-card p-8 animate-fadeInUp" style="animation-delay: 0.5s;">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-toolbox text-indigo-600 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Wellness Tools</h3>
          </div>
          
          <div class="space-y-4">
            <a href="meditation_guide.php" class="block p-4 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-om text-green-600"></i>
                <div>
                  <div class="font-semibold text-green-800">Meditation Guide</div>
                  <div class="text-sm text-green-600">Guided mindfulness exercises</div>
                </div>
              </div>
            </a>
            
            <a href="stress_guide.php" class="block p-4 bg-orange-50 hover:bg-orange-100 rounded-xl border border-orange-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-leaf text-orange-600"></i>
                <div>
                  <div class="font-semibold text-orange-800">Stress Management</div>
                  <div class="text-sm text-orange-600">Techniques for stress relief</div>
                </div>
              </div>
            </a>
            
            <a href="wellness_library.php" class="block p-4 bg-teal-50 hover:bg-teal-100 rounded-xl border border-teal-200 transition-colors">
              <div class="flex items-center space-x-3">
                <i class="fas fa-book-open text-teal-600"></i>
                <div>
                  <div class="font-semibold text-teal-800">Wellness Library</div>
                  <div class="text-sm text-teal-600">Educational resources and articles</div>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="result-card p-8 text-center animate-fadeInUp" style="animation-delay: 0.6s;">
        <h3 class="text-xl font-bold text-gray-800 mb-6">What would you like to do next?</h3>
        
        <div class="flex flex-wrap justify-center gap-4">
          <a href="mental_health_test.php" class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white px-8 py-4 rounded-2xl font-semibold hover:from-purple-700 hover:to-indigo-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-redo-alt mr-2"></i>
            Retake Assessment
          </a>
          
          <a href="test_history.php" class="bg-gradient-to-r from-blue-600 to-cyan-700 text-white px-8 py-4 rounded-2xl font-semibold hover:from-blue-700 hover:to-cyan-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-history mr-2"></i>
            View History
          </a>
          
          <a href="dashboard.php" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-8 py-4 rounded-2xl font-semibold hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-home mr-2"></i>
            Back to Dashboard
          </a>
        </div>
        
        <div class="mt-8 p-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200">
          <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <i class="fas fa-lightbulb text-white text-sm"></i>
            </div>
            <div class="text-left">
              <h4 class="font-semibold text-amber-800 mb-2">Remember</h4>
              <p class="text-amber-700 text-sm leading-relaxed">
                This assessment provides general insights about your mental wellness. For personalized advice and support, 
                consider speaking with a qualified mental health professional. Your mental health journey is unique, 
                and professional guidance can provide valuable personalized strategies.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Animate category bars on page load
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        const bars = document.querySelectorAll('.category-fill');
        bars.forEach(bar => {
          const width = bar.style.width;
          bar.style.width = '0%';
          setTimeout(() => {
            bar.style.width = width;
          }, 100);
        });
      }, 500);
    });

    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987c0b1246db0dcd',t:'MTc1OTMyMjU4MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
