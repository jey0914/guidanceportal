<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQ - Frequently Asked Questions | Guidance Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      box-sizing: border-box;
    }
    
    /* Custom animations */
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
    
    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
    }
    
    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
    
    .animate-slideInLeft {
      animation: slideInLeft 0.8s ease-out;
    }
    
    .animate-slideInRight {
      animation: slideInRight 0.8s ease-out;
    }
    
    .animate-pulse-custom {
      animation: pulse 2s ease-in-out infinite;
    }
    
    /* Gradient backgrounds */
    .gradient-bg {
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 50%, #1e3a8a 100%);
    }
    
    .gradient-text {
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .guidance-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .faq-gradient {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    /* Hover effects */
    .hover-lift {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    
    /* Card hover effects */
    .card-hover {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .card-hover::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .card-hover:hover::before {
      left: 100%;
    }
    
    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .mobile-menu.active {
      transform: translateX(0);
    }
    
    /* Accordion animations */
    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out, padding 0.3s ease-out;
    }
    
    .accordion-content.active {
      max-height: 1000px;
      transition: max-height 0.5s ease-in, padding 0.3s ease-in;
    }
    
    .accordion-icon {
      transition: transform 0.3s ease;
    }
    
    .accordion-icon.rotated {
      transform: rotate(180deg);
    }
    
    /* Category tabs */
    .category-tab {
      transition: all 0.3s ease;
      position: relative;
    }
    
    .category-tab.active {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }
    
    .category-tab::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      width: 80%;
      height: 3px;
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      transition: transform 0.3s ease;
    }
    
    .category-tab.active::after {
      transform: translateX(-50%) scaleX(1);
    }
    
    /* Search functionality */
    .search-highlight {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      padding: 2px 4px;
      border-radius: 4px;
      font-weight: 600;
    }
    
    /* Smooth scroll behavior */
    html {
      scroll-behavior: smooth;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    }
    
    /* FAQ item styling */
    .faq-item {
      border: 2px solid transparent;
      transition: all 0.3s ease;
    }
    
    .faq-item:hover {
      border-color: #e0e7ff;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .faq-item.active {
      border-color: #6366f1;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }
    
    /* Quick stats */
    .stat-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border: 1px solid #e2e8f0;
    }
    
    /* Contact cards */
    .contact-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .contact-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }
  </style>
</head>
<body class="font-sans text-gray-800 bg-gray-50">

  <!-- Header/Navbar -->
  <header class="bg-white/95 backdrop-blur-sm shadow-lg fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 guidance-gradient rounded-xl flex items-center justify-center shadow-lg">
          <span class="text-white font-bold text-xl">🎯</span>
        </div>
        <h1 class="text-2xl font-bold gradient-text">GuidanceHub</h1>
      </div>
      
      <!-- Desktop Navigation -->
      <nav class="hidden md:flex space-x-8">
        <a href="index.php" class="hover:text-blue-600 transition-colors font-medium relative group">
          Home
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
        </a>
        <a href="about.php" class="hover:text-blue-600 transition-colors font-medium relative group">
          About
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
        </a>
        <a href="courses.php" class="hover:text-blue-600 transition-colors font-medium relative group">
          Courses
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
        </a>
        <a href="contact.php" class="hover:text-blue-600 transition-colors font-medium relative group">
          Contact
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
        </a>
      </nav>
      
      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-blue-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 left-0 w-full h-full bg-white z-40 md:hidden">
      <div class="flex flex-col p-6 space-y-6 mt-16">
        <a href="index.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Home</a>
        <a href="about.php" class="text-xl font-medium hover:text-blue-600 transition-colors">About</a>
        <a href="courses.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
        <a href="contact.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="faq-gradient pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full animate-pulse-custom"></div>
    
    <div class="container mx-auto px-6 text-center text-white relative z-10">
      <div class="max-w-4xl mx-auto animate-fadeInUp">
        <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
          ❓ Help & Support
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Frequently Asked
          <span class="block text-yellow-300">Questions</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto mb-8">
          Find quick answers to common questions about the GuidanceHub portal. Everything students and parents need to know.
        </p>
        
        <!-- Quick Stats -->
        <div class="grid md:grid-cols-3 gap-6 max-w-2xl mx-auto">
          <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl">
            <div class="text-2xl font-bold">50+</div>
            <div class="text-white/80 text-sm">Questions Answered</div>
          </div>
          <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl">
            <div class="text-2xl font-bold">24/7</div>
            <div class="text-white/80 text-sm">Support Available</div>
          </div>
          <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl">
            <div class="text-2xl font-bold">98%</div>
            <div class="text-white/80 text-sm">Issues Resolved</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search Section -->
  <section class="py-12 -mt-10 relative z-10">
    <div class="container mx-auto px-6">
      <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100">
          <h2 class="text-2xl font-bold gradient-text mb-6 text-center">🔍 Search FAQ</h2>
          <div class="relative">
            <input 
              type="text" 
              id="searchInput" 
              placeholder="Type your question here..." 
              class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-pink-500 focus:outline-none transition-colors"
            >
            <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
          </div>
          <div id="searchResults" class="mt-4 hidden">
            <p class="text-gray-600 text-sm">Found <span id="resultCount">0</span> results</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Category Tabs -->
  <section class="py-8">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto">
        <div class="flex flex-wrap justify-center gap-4 mb-8">
          <button class="category-tab active px-6 py-3 rounded-2xl font-semibold transition-all duration-300 bg-gray-100 text-gray-700" data-category="all">
            🌟 All Questions
          </button>
          <button class="category-tab px-6 py-3 rounded-2xl font-semibold transition-all duration-300 bg-gray-100 text-gray-700" data-category="students">
            🎓 For Students
          </button>
          <button class="category-tab px-6 py-3 rounded-2xl font-semibold transition-all duration-300 bg-gray-100 text-gray-700" data-category="parents">
            👨‍👩‍👧‍👦 For Parents
          </button>
          <button class="category-tab px-6 py-3 rounded-2xl font-semibold transition-all duration-300 bg-gray-100 text-gray-700" data-category="appointments">
            📅 Appointments
          </button>
          <button class="category-tab px-6 py-3 rounded-2xl font-semibold transition-all duration-300 bg-gray-100 text-gray-700" data-category="technical">
            💻 Technical
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Content -->
  <main class="py-12">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto">
        
        <!-- Students FAQ -->
        <div class="faq-category" data-category="students">
          <div class="mb-12">
            <h2 class="text-3xl font-bold gradient-text mb-8 text-center">🎓 Questions for Students</h2>
            
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="book counseling session appointment schedule">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">📅</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">How do I book a counseling session?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <p class="text-gray-700 mb-4">Booking a counseling session is easy! Follow these simple steps:</p>
                    <ol class="space-y-3 text-gray-700">
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">1</span>
                        <span><strong>Log into your account</strong> using your student ID and password</span>
                      </li>
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</span>
                        <span><strong>Click "Book Appointment"</strong> on your dashboard</span>
                      </li>
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</span>
                        <span><strong>Choose your preferred date and time</strong> from available slots</span>
                      </li>
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">4</span>
                        <span><strong>Select the type of counseling</strong> you need (academic, personal, career)</span>
                      </li>
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">5</span>
                        <span><strong>Provide a brief description</strong> of what you'd like to discuss</span>
                      </li>
                      <li class="flex items-start space-x-3">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">6</span>
                        <span><strong>Submit your request</strong> and wait for confirmation</span>
                      </li>
                    </ol>
                    <div class="mt-4 p-4 bg-blue-100 rounded-lg">
                      <p class="text-blue-800 text-sm"><strong>💡 Tip:</strong> Book at least 24 hours in advance for better availability!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="walk in appointment without booking emergency">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🚶</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Can I walk in without an appointment?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                    <p class="text-gray-700 mb-4">Yes, but with some limitations:</p>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ Walk-ins Accepted:</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• Emergency situations</li>
                          <li>• Crisis counseling needs</li>
                          <li>• Quick questions (under 10 minutes)</li>
                          <li>• Document pick-up/drop-off</li>
                        </ul>
                      </div>
                      <div class="bg-white p-4 rounded-lg border border-red-300">
                        <h4 class="font-semibold text-red-800 mb-2">❌ Appointment Required:</h4>
                        <ul class="space-y-1 text-red-700 text-sm">
                          <li>• Regular counseling sessions</li>
                          <li>• Academic planning meetings</li>
                          <li>• Career guidance sessions</li>
                          <li>• Assessment appointments</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="bg-yellow-100 p-4 rounded-lg">
                      <h4 class="font-semibold text-yellow-800 mb-2">⏰ Walk-in Hours:</h4>
                      <ul class="space-y-1 text-yellow-700 text-sm">
                        <li>• Monday - Friday: 10:00 AM - 12:00 PM, 2:00 PM - 4:00 PM</li>
                        <li>• Saturday: 9:00 AM - 12:00 PM</li>
                        <li>• Sunday: Emergency only</li>
                      </ul>
                    </div>
                    
                    <div class="mt-4 p-4 bg-green-100 rounded-lg">
                      <p class="text-green-800 text-sm"><strong>⚠️ Note:</strong> Walk-ins are served on a first-come, first-served basis and may have longer wait times.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="confidential privacy sessions private information">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🔒</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Are my sessions confidential?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-purple-50 p-6 rounded-xl border border-purple-200">
                    <p class="text-gray-700 mb-4"><strong>Yes, absolutely!</strong> Your privacy is our top priority. Here's what you need to know:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">🛡️ What's Protected:</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• Everything you discuss in counseling sessions</li>
                          <li>• Your personal information and records</li>
                          <li>• Assessment results and evaluations</li>
                          <li>• Appointment history and notes</li>
                          <li>• Any documents you share with counselors</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-orange-300">
                        <h4 class="font-semibold text-orange-800 mb-2">⚠️ Exceptions (Legal Requirements):</h4>
                        <ul class="space-y-1 text-orange-700 text-sm">
                          <li>• Risk of harm to yourself or others</li>
                          <li>• Suspected child abuse or neglect</li>
                          <li>• Court-ordered disclosure</li>
                          <li>• Medical emergencies</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">👨‍👩‍👧‍👦 Parent/Guardian Access:</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Students 18+: Full confidentiality, no parent access</li>
                          <li>• Students under 18: Limited parent access to general progress</li>
                          <li>• Specific session details remain confidential</li>
                          <li>• Emergency situations may require parent notification</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-purple-100 rounded-lg">
                      <p class="text-purple-800 text-sm"><strong>📋 Your Rights:</strong> You can request to see your records, ask for corrections, and understand how your information is used.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="appointment approved notification confirmation email">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">📧</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">How will I know if my appointment is approved?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-200">
                    <p class="text-gray-700 mb-4">You'll receive multiple notifications when your appointment status changes:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ Approval Notifications:</h4>
                        <ul class="space-y-2 text-green-700 text-sm">
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span><strong>Email:</strong> Sent to your STI email address</span>
                          </li>
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span><strong>SMS:</strong> Text message to your registered phone</span>
                          </li>
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span><strong>Portal Notification:</strong> Alert when you log in</span>
                          </li>
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span><strong>Dashboard Update:</strong> Status changes to "Confirmed"</span>
                          </li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-red-300">
                        <h4 class="font-semibold text-red-800 mb-2">❌ If Declined:</h4>
                        <ul class="space-y-2 text-red-700 text-sm">
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Email with reason for decline</span>
                          </li>
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Alternative time slots suggested</span>
                          </li>
                          <li class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Option to reschedule immediately</span>
                          </li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">⏰ Response Timeline:</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Regular appointments: Within 24 hours</li>
                          <li>• Urgent requests: Within 4 hours</li>
                          <li>• Emergency situations: Within 1 hour</li>
                          <li>• Weekend requests: Next business day</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-indigo-100 rounded-lg">
                      <p class="text-indigo-800 text-sm"><strong>📱 Pro Tip:</strong> Add guidance@sti.edu.ph to your contacts to ensure emails don't go to spam!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="cancel reschedule appointment change time date">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🔄</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Can I cancel or reschedule my appointment?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-orange-50 p-6 rounded-xl border border-orange-200">
                    <p class="text-gray-700 mb-4"><strong>Yes, you can!</strong> We understand that schedules change. Here's how:</p>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ How to Cancel/Reschedule:</h4>
                        <ol class="space-y-1 text-green-700 text-sm">
                          <li>1. Log into your portal account</li>
                          <li>2. Go to "My Appointments"</li>
                          <li>3. Find your appointment</li>
                          <li>4. Click "Cancel" or "Reschedule"</li>
                          <li>5. Confirm your action</li>
                        </ol>
                      </div>
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">📞 Alternative Methods:</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Call: (02) 1234-5678</li>
                          <li>• Email: guidance@sti.edu.ph</li>
                          <li>• Visit the guidance office</li>
                          <li>• SMS: 0917-123-4567</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="space-y-3">
                      <div class="bg-yellow-100 p-4 rounded-lg">
                        <h4 class="font-semibold text-yellow-800 mb-2">⏰ Time Limits:</h4>
                        <ul class="space-y-1 text-yellow-700 text-sm">
                          <li>• <strong>Free cancellation:</strong> 4+ hours before appointment</li>
                          <li>• <strong>Late cancellation:</strong> 2-4 hours before (warning issued)</li>
                          <li>• <strong>No-show:</strong> Less than 2 hours or no cancellation</li>
                        </ul>
                      </div>
                      
                      <div class="bg-red-100 p-4 rounded-lg">
                        <h4 class="font-semibold text-red-800 mb-2">⚠️ Cancellation Policy:</h4>
                        <ul class="space-y-1 text-red-700 text-sm">
                          <li>• 3 late cancellations = 1 week booking restriction</li>
                          <li>• 2 no-shows = 2 week booking restriction</li>
                          <li>• Repeated violations may result in account suspension</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-orange-100 rounded-lg">
                      <p class="text-orange-800 text-sm"><strong>💡 Best Practice:</strong> Cancel as early as possible to help other students get appointments!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Parents FAQ -->
        <div class="faq-category" data-category="parents">
          <div class="mb-12">
            <h2 class="text-3xl font-bold gradient-text mb-8 text-center">👨‍👩‍👧‍👦 Questions for Parents</h2>
            
            <!-- Parent FAQ Item 1 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="parent access child information progress reports">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">👁️</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Can I access my child's counseling information?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-teal-50 p-6 rounded-xl border border-teal-200">
                    <p class="text-gray-700 mb-4">Access depends on your child's age and the type of information:</p>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ What You Can Access:</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• General academic progress reports</li>
                          <li>• Attendance at counseling sessions</li>
                          <li>• Goal-setting and achievement updates</li>
                          <li>• Crisis intervention notifications</li>
                          <li>• Referral recommendations</li>
                        </ul>
                      </div>
                      <div class="bg-white p-4 rounded-lg border border-red-300">
                        <h4 class="font-semibold text-red-800 mb-2">❌ What's Protected:</h4>
                        <ul class="space-y-1 text-red-700 text-sm">
                          <li>• Specific session discussions</li>
                          <li>• Personal confidential matters</li>
                          <li>• Private thoughts and feelings</li>
                          <li>• Peer relationship details</li>
                          <li>• Sensitive personal issues</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="space-y-3">
                      <div class="bg-blue-100 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">👶 Students Under 18:</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Parents have broader access to information</li>
                          <li>• Can receive progress summaries</li>
                          <li>• Notified of major concerns or issues</li>
                          <li>• Can request family counseling sessions</li>
                        </ul>
                      </div>
                      
                      <div class="bg-purple-100 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-800 mb-2">🎓 Students 18 and Over:</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• Student must provide written consent</li>
                          <li>• Limited access to general information only</li>
                          <li>• Emergency situations may override privacy</li>
                          <li>• Student controls what parents can know</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-teal-100 rounded-lg">
                      <p class="text-teal-800 text-sm"><strong>📞 To Request Information:</strong> Contact the guidance office at (02) 1234-5678 or email parents@sti.edu.ph</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Parent FAQ Item 2 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="parent book appointment child schedule counseling">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">📅</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Can I book an appointment for my child?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-pink-50 p-6 rounded-xl border border-pink-200">
                    <p class="text-gray-700 mb-4"><strong>Yes, but it depends on the situation and your child's age:</strong></p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ When You Can Book:</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• Child is under 18 years old</li>
                          <li>• Emergency or crisis situations</li>
                          <li>• Child has given explicit permission</li>
                          <li>• Academic performance concerns</li>
                          <li>• Behavioral issues at home or school</li>
                          <li>• Family counseling sessions</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-orange-300">
                        <h4 class="font-semibold text-orange-800 mb-2">📋 How to Book for Your Child:</h4>
                        <ol class="space-y-1 text-orange-700 text-sm">
                          <li>1. Call the guidance office: (02) 1234-5678</li>
                          <li>2. Email: parents@sti.edu.ph</li>
                          <li>3. Visit the campus guidance center</li>
                          <li>4. Provide your child's student ID</li>
                          <li>5. Explain the reason for the appointment</li>
                          <li>6. Confirm your relationship to the student</li>
                        </ol>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">👨‍👩‍👧‍👦 Family Sessions Available:</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Parent-student joint counseling</li>
                          <li>• Family therapy sessions</li>
                          <li>• Academic planning meetings</li>
                          <li>• Crisis intervention support</li>
                          <li>• Communication improvement sessions</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-pink-100 rounded-lg">
                      <p class="text-pink-800 text-sm"><strong>💡 Recommendation:</strong> Discuss with your child first when possible - their cooperation makes counseling more effective!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Parent FAQ Item 3 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="parent portal account access child information">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🔐</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">Do parents get their own portal access?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-cyan-50 p-6 rounded-xl border border-cyan-200">
                    <p class="text-gray-700 mb-4"><strong>Yes!</strong> We offer a Parent Portal for enhanced communication and involvement:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ Parent Portal Features:</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• View your child's appointment schedule</li>
                          <li>• Receive progress notifications</li>
                          <li>• Access general counseling reports</li>
                          <li>• Book family counseling sessions</li>
                          <li>• Communicate with counselors</li>
                          <li>• Update emergency contact information</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">📝 How to Get Access:</h4>
                        <ol class="space-y-1 text-blue-700 text-sm">
                          <li>1. Visit the guidance office with valid ID</li>
                          <li>2. Complete the Parent Portal Registration form</li>
                          <li>3. Provide proof of relationship to student</li>
                          <li>4. For students 18+: Get written consent</li>
                          <li>5. Receive login credentials via email</li>
                          <li>6. Complete the setup process online</li>
                        </ol>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">🔒 Privacy & Security:</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• Secure login with two-factor authentication</li>
                          <li>• Limited access based on student's age</li>
                          <li>• Confidential information remains protected</li>
                          <li>• Activity logs for transparency</li>
                          <li>• Regular security updates</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-cyan-100 rounded-lg">
                      <p class="text-cyan-800 text-sm"><strong>📞 Need Help?</strong> Call our Parent Support Line: (02) 1234-5679 or email parentportal@sti.edu.ph</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Appointments FAQ -->
        <div class="faq-category" data-category="appointments">
          <div class="mb-12">
            <h2 class="text-3xl font-bold gradient-text mb-8 text-center">📅 Appointment Questions</h2>
            
            <!-- Appointment FAQ Item 1 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="appointment types counseling academic career personal">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🎯</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">What types of appointments are available?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-200">
                    <p class="text-gray-700 mb-4">We offer various types of counseling appointments to meet your needs:</p>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">📚 Academic Counseling</h4>
                        <ul class="space-y-1 text-blue-700 text-sm">
                          <li>• Course selection and planning</li>
                          <li>• Study skills and time management</li>
                          <li>• Academic performance issues</li>
                          <li>• Learning difficulties support</li>
                          <li>• Exam anxiety and stress</li>
                        </ul>
                        <p class="text-blue-600 text-xs mt-2"><strong>Duration:</strong> 30-45 minutes</p>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">💼 Career Guidance</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• Career exploration and planning</li>
                          <li>• Job search strategies</li>
                          <li>• Resume and interview preparation</li>
                          <li>• Internship opportunities</li>
                          <li>• Graduate school planning</li>
                        </ul>
                        <p class="text-purple-600 text-xs mt-2"><strong>Duration:</strong> 45-60 minutes</p>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">💚 Personal Counseling</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• Emotional support and wellness</li>
                          <li>• Stress and anxiety management</li>
                          <li>• Relationship and social issues</li>
                          <li>• Self-esteem and confidence</li>
                          <li>• Life transitions and changes</li>
                        </ul>
                        <p class="text-green-600 text-xs mt-2"><strong>Duration:</strong> 45-60 minutes</p>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-red-300">
                        <h4 class="font-semibold text-red-800 mb-2">🚨 Crisis Intervention</h4>
                        <ul class="space-y-1 text-red-700 text-sm">
                          <li>• Emergency emotional support</li>
                          <li>• Mental health crisis assistance</li>
                          <li>• Safety planning and resources</li>
                          <li>• Immediate referral services</li>
                          <li>• 24/7 crisis hotline access</li>
                        </ul>
                        <p class="text-red-600 text-xs mt-2"><strong>Duration:</strong> As needed</p>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-orange-300">
                        <h4 class="font-semibold text-orange-800 mb-2">👨‍👩‍👧‍👦 Group Sessions</h4>
                        <ul class="space-y-1 text-orange-700 text-sm">
                          <li>• Study groups and workshops</li>
                          <li>• Peer support groups</li>
                          <li>• Social skills development</li>
                          <li>• Stress management workshops</li>
                          <li>• Career exploration groups</li>
                        </ul>
                        <p class="text-orange-600 text-xs mt-2"><strong>Duration:</strong> 60-90 minutes</p>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-teal-300">
                        <h4 class="font-semibold text-teal-800 mb-2">📋 Assessment Services</h4>
                        <ul class="space-y-1 text-teal-700 text-sm">
                          <li>• Personality assessments</li>
                          <li>• Career interest inventories</li>
                          <li>• Learning style evaluations</li>
                          <li>• Mental health screenings</li>
                          <li>• Academic aptitude tests</li>
                        </ul>
                        <p class="text-teal-600 text-xs mt-2"><strong>Duration:</strong> 60-120 minutes</p>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-emerald-100 rounded-lg">
                      <p class="text-emerald-800 text-sm"><strong>📞 Not sure which type you need?</strong> Call (02) 1234-5678 and our staff will help you choose the right appointment type!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Appointment FAQ Item 2 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="appointment duration how long session time">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">⏰</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">How long do appointments typically last?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-amber-50 p-6 rounded-xl border border-amber-200">
                    <p class="text-gray-700 mb-4">Appointment duration varies based on the type of service and your individual needs:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-3">⏱️ Standard Session Lengths:</h4>
                        <div class="space-y-2">
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Quick Consultation</span>
                            <span class="text-blue-600 text-sm">15-20 minutes</span>
                          </div>
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Academic Counseling</span>
                            <span class="text-blue-600 text-sm">30-45 minutes</span>
                          </div>
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Personal Counseling</span>
                            <span class="text-blue-600 text-sm">45-60 minutes</span>
                          </div>
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Career Guidance</span>
                            <span class="text-blue-600 text-sm">45-60 minutes</span>
                          </div>
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Assessment Services</span>
                            <span class="text-blue-600 text-sm">60-120 minutes</span>
                          </div>
                          <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-blue-700 font-medium">Group Sessions</span>
                            <span class="text-blue-600 text-sm">60-90 minutes</span>
                          </div>
                        </div>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">✅ What Affects Session Length:</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• Complexity of the issue being discussed</li>
                          <li>• Type of counseling or service needed</li>
                          <li>• Your comfort level and pace</li>
                          <li>• Whether it's a first visit or follow-up</li>
                          <li>• Availability of counselor's schedule</li>
                          <li>• Emergency or crisis situations</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">🔄 Extended Sessions:</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• Can be arranged for complex issues</li>
                          <li>• May require advance scheduling</li>
                          <li>• Subject to counselor availability</li>
                          <li>• Usually scheduled during less busy periods</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-amber-100 rounded-lg">
                      <p class="text-amber-800 text-sm"><strong>💡 Planning Tip:</strong> Arrive 10 minutes early for paperwork and allow extra time after your appointment in case it runs longer than expected.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Technical FAQ -->
        <div class="faq-category" data-category="technical">
          <div class="mb-12">
            <h2 class="text-3xl font-bold gradient-text mb-8 text-center">💻 Technical Questions</h2>
            
            <!-- Technical FAQ Item 1 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="forgot password reset login account access">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">🔑</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">I forgot my password. How do I reset it?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-red-50 p-6 rounded-xl border border-red-200">
                    <p class="text-gray-700 mb-4">Don't worry! Resetting your password is quick and easy:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">🔄 Self-Service Reset:</h4>
                        <ol class="space-y-1 text-blue-700 text-sm">
                          <li>1. Go to the login page</li>
                          <li>2. Click "Forgot Password?" link</li>
                          <li>3. Enter your student ID or email</li>
                          <li>4. Check your email for reset instructions</li>
                          <li>5. Click the reset link (valid for 24 hours)</li>
                          <li>6. Create a new strong password</li>
                          <li>7. Log in with your new password</li>
                        </ol>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">📧 Email Not Received?</h4>
                        <ul class="space-y-1 text-green-700 text-sm">
                          <li>• Check your spam/junk folder</li>
                          <li>• Verify you entered the correct email</li>
                          <li>• Wait 5-10 minutes for delivery</li>
                          <li>• Add noreply@sti.edu.ph to your contacts</li>
                          <li>• Try the reset process again</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-orange-300">
                        <h4 class="font-semibold text-orange-800 mb-2">🆘 Need Additional Help?</h4>
                        <ul class="space-y-1 text-orange-700 text-sm">
                          <li>• Call IT Support: (02) 1234-5680</li>
                          <li>• Email: techsupport@sti.edu.ph</li>
                          <li>• Visit the IT Help Desk on campus</li>
                          <li>• Live chat available 8 AM - 6 PM</li>
                        </ul>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">🔒 Password Requirements:</h4>
                        <ul class="space-y-1 text-purple-700 text-sm">
                          <li>• At least 8 characters long</li>
                          <li>• Include uppercase and lowercase letters</li>
                          <li>• At least one number</li>
                          <li>• At least one special character (!@#$%)</li>
                          <li>• Cannot be the same as your last 3 passwords</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-red-100 rounded-lg">
                      <p class="text-red-800 text-sm"><strong>🔐 Security Tip:</strong> Use a unique password for your student account and enable two-factor authentication for extra security!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Technical FAQ Item 2 -->
            <div class="faq-item bg-white rounded-2xl p-6 mb-4 shadow-lg">
              <button class="accordion-trigger w-full text-left flex justify-between items-center" data-keywords="portal not working error loading problem technical issue">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold">⚠️</span>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800">The portal isn't working properly. What should I do?</h3>
                </div>
                <svg class="accordion-icon w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="accordion-content mt-4">
                <div class="pl-16 pr-8">
                  <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                    <p class="text-gray-700 mb-4">Try these troubleshooting steps before contacting support:</p>
                    
                    <div class="space-y-4">
                      <div class="bg-white p-4 rounded-lg border border-blue-300">
                        <h4 class="font-semibold text-blue-800 mb-2">🔧 Quick Fixes:</h4>
                        <ol class="space-y-1 text-blue-700 text-sm">
                          <li>1. <strong>Refresh the page</strong> (Ctrl+F5 or Cmd+R)</li>
                          <li>2. <strong>Clear your browser cache</strong> and cookies</li>
                          <li>3. <strong>Try a different browser</strong> (Chrome, Firefox, Safari)</li>
                          <li>4. <strong>Disable browser extensions</strong> temporarily</li>
                          <li>5. <strong>Check your internet connection</strong></li>
                          <li>6. <strong>Try incognito/private browsing mode</strong></li>
                        </ol>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-green-300">
                        <h4 class="font-semibold text-green-800 mb-2">💻 Browser Requirements:</h4>
                        <div class="grid md:grid-cols-2 gap-3">
                          <div>
                            <p class="font-medium text-green-700 mb-1">Supported Browsers:</p>
                            <ul class="space-y-1 text-green-700 text-sm">
                              <li>• Chrome 90+ ✅</li>
                              <li>• Firefox 88+ ✅</li>
                              <li>• Safari 14+ ✅</li>
                              <li>• Edge 90+ ✅</li>
                            </ul>
                          </div>
                          <div>
                            <p class="font-medium text-green-700 mb-1">Required Settings:</p>
                            <ul class="space-y-1 text-green-700 text-sm">
                              <li>• JavaScript enabled</li>
                              <li>• Cookies enabled</li>
                              <li>• Pop-ups allowed for sti.edu.ph</li>
                              <li>• Minimum 1024x768 resolution</li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-red-300">
                        <h4 class="font-semibold text-red-800 mb-2">🚨 Common Error Messages:</h4>
                        <div class="space-y-2">
                          <div class="p-2 bg-red-50 rounded text-sm">
                            <strong class="text-red-700">"Session Expired"</strong> → Log out and log back in
                          </div>
                          <div class="p-2 bg-red-50 rounded text-sm">
                            <strong class="text-red-700">"Server Error 500"</strong> → Wait 5 minutes and try again
                          </div>
                          <div class="p-2 bg-red-50 rounded text-sm">
                            <strong class="text-red-700">"Page Not Found"</strong> → Check the URL or go to homepage
                          </div>
                          <div class="p-2 bg-red-50 rounded text-sm">
                            <strong class="text-red-700">"Access Denied"</strong> → Verify your account permissions
                          </div>
                        </div>
                      </div>
                      
                      <div class="bg-white p-4 rounded-lg border border-purple-300">
                        <h4 class="font-semibold text-purple-800 mb-2">📞 Still Need Help?</h4>
                        <div class="grid md:grid-cols-2 gap-3">
                          <div>
                            <p class="font-medium text-purple-700 mb-1">Contact Options:</p>
                            <ul class="space-y-1 text-purple-700 text-sm">
                              <li>• Phone: (02) 1234-5680</li>
                              <li>• Email: techsupport@sti.edu.ph</li>
                              <li>• Live Chat: Available 8 AM - 6 PM</li>
                              <li>• Campus IT Help Desk</li>
                            </ul>
                          </div>
                          <div>
                            <p class="font-medium text-purple-700 mb-1">Include This Info:</p>
                            <ul class="space-y-1 text-purple-700 text-sm">
                              <li>• Your student ID</li>
                              <li>• Browser and version</li>
                              <li>• Error message (screenshot)</li>
                              <li>• What you were trying to do</li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-100 rounded-lg">
                      <p class="text-yellow-800 text-sm"><strong>⏰ System Maintenance:</strong> Check our status page at status.sti.edu.ph for scheduled maintenance times.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- Contact Support Section -->
  <section class="py-20 bg-gradient-to-r from-purple-500 to-pink-600">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto text-center text-white">
        <h2 class="text-4xl font-bold mb-6">Still Have Questions?</h2>
        <p class="text-xl mb-8 text-white/90">
          Our support team is here to help you 24/7. Don't hesitate to reach out!
        </p>
        
        <div class="grid md:grid-cols-3 gap-6 mb-8">
          <div class="contact-card p-6 rounded-2xl transition-all duration-300">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Phone Support</h3>
            <p class="text-white/80 mb-2">(02) 1234-5678</p>
            <p class="text-white/60 text-sm">Mon-Fri: 8 AM - 6 PM</p>
          </div>
          
          <div class="contact-card p-6 rounded-2xl transition-all duration-300">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Email Support</h3>
            <p class="text-white/80 mb-2">support@sti.edu.ph</p>
            <p class="text-white/60 text-sm">Response within 24 hours</p>
          </div>
          
          <div class="contact-card p-6 rounded-2xl transition-all duration-300">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Live Chat</h3>
            <p class="text-white/80 mb-2">Available on portal</p>
            <p class="text-white/60 text-sm">8 AM - 6 PM daily</p>
          </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="contact.php" class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            Contact Support Team 📞
          </a>
          <a href="help-center.php" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition-all duration-300">
            Visit Help Center 🆘
          </a>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
      });
    });

    // Header scroll effect
    window.addEventListener('scroll', () => {
      const header = document.querySelector('header');
      if (window.scrollY > 100) {
        header.classList.add('shadow-xl');
      } else {
        header.classList.remove('shadow-xl');
      }
    });

    // Accordion functionality
    const accordionTriggers = document.querySelectorAll('.accordion-trigger');
    
    accordionTriggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const content = trigger.nextElementSibling;
        const icon = trigger.querySelector('.accordion-icon');
        const faqItem = trigger.closest('.faq-item');
        
        // Close other open accordions
        accordionTriggers.forEach(otherTrigger => {
          if (otherTrigger !== trigger) {
            const otherContent = otherTrigger.nextElementSibling;
            const otherIcon = otherTrigger.querySelector('.accordion-icon');
            const otherFaqItem = otherTrigger.closest('.faq-item');
            
            otherContent.classList.remove('active');
            otherIcon.classList.remove('rotated');
            otherFaqItem.classList.remove('active');
          }
        });
        
        // Toggle current accordion
        content.classList.toggle('active');
        icon.classList.toggle('rotated');
        faqItem.classList.toggle('active');
      });
    });

    // Category filtering
    const categoryTabs = document.querySelectorAll('.category-tab');
    const faqCategories = document.querySelectorAll('.faq-category');
    
    categoryTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const category = tab.dataset.category;
        
        // Update active tab
        categoryTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        // Show/hide categories
        if (category === 'all') {
          faqCategories.forEach(cat => cat.style.display = 'block');
        } else {
          faqCategories.forEach(cat => {
            if (cat.dataset.category === category) {
              cat.style.display = 'block';
            } else {
              cat.style.display = 'none';
            }
          });
        }
        
        // Close all accordions when switching categories
        accordionTriggers.forEach(trigger => {
          const content = trigger.nextElementSibling;
          const icon = trigger.querySelector('.accordion-icon');
          const faqItem = trigger.closest('.faq-item');
          
          content.classList.remove('active');
          icon.classList.remove('rotated');
          faqItem.classList.remove('active');
        });
      });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultCount = document.getElementById('resultCount');
    
    searchInput.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase().trim();
      
      if (searchTerm === '') {
        // Reset view
        searchResults.classList.add('hidden');
        faqCategories.forEach(cat => cat.style.display = 'block');
        accordionTriggers.forEach(trigger => {
          const faqItem = trigger.closest('.faq-item');
          faqItem.style.display = 'block';
          // Remove highlights
          const title = trigger.querySelector('h3');
          title.innerHTML = title.textContent;
        });
        
        // Reset category tabs
        categoryTabs.forEach(t => t.classList.remove('active'));
        categoryTabs[0].classList.add('active');
        return;
      }
      
      let matchCount = 0;
      
      // Show all categories during search
      faqCategories.forEach(cat => cat.style.display = 'block');
      
      accordionTriggers.forEach(trigger => {
        const title = trigger.querySelector('h3').textContent.toLowerCase();
        const keywords = trigger.dataset.keywords?.toLowerCase() || '';
        const content = trigger.nextElementSibling.textContent.toLowerCase();
        const faqItem = trigger.closest('.faq-item');
        
        if (title.includes(searchTerm) || keywords.includes(searchTerm) || content.includes(searchTerm)) {
          faqItem.style.display = 'block';
          matchCount++;
          
          // Highlight search term in title
          const titleElement = trigger.querySelector('h3');
          const titleText = titleElement.textContent;
          const regex = new RegExp(`(${searchTerm})`, 'gi');
          titleElement.innerHTML = titleText.replace(regex, '<span class="search-highlight">$1</span>');
        } else {
          faqItem.style.display = 'none';
        }
      });
      
      // Update search results
      resultCount.textContent = matchCount;
      searchResults.classList.remove('hidden');
      
      // Update category tabs for search
      categoryTabs.forEach(t => t.classList.remove('active'));
    });

    // Intersection Observer for animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0) translateX(0)';
        }
      });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.animate-fadeInUp, .animate-slideInLeft, .animate-slideInRight').forEach(el => {
      el.style.opacity = '0';
      if (el.classList.contains('animate-slideInLeft')) {
        el.style.transform = 'translateX(-50px)';
      } else if (el.classList.contains('animate-slideInRight')) {
        el.style.transform = 'translateX(50px)';
      } else {
        el.style.transform = 'translateY(30px)';
      }
      observer.observe(el);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          const headerHeight = 100;
          const targetPosition = target.offsetTop - headerHeight;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });

    // Auto-expand FAQ if coming from direct link
    window.addEventListener('load', () => {
      const hash = window.location.hash;
      if (hash) {
        const target = document.querySelector(hash);
        if (target && target.classList.contains('faq-item')) {
          const trigger = target.querySelector('.accordion-trigger');
          if (trigger) {
            trigger.click();
            setTimeout(() => {
              target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
          }
        }
      }
    });
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98628ad1d7810dcb',t:'MTc1OTA1NTE4NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
