<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Help Center | Guidance Portal</title>
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
    
    @keyframes slideDown {
      from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
      }
    }
    
    @keyframes slideUp {
      from {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
      }
      to {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
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
    
    .help-gradient {
      background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
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
    
    /* Accordion styles */
    .accordion-item {
      transition: all 0.3s ease;
    }
    
    .accordion-header {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .accordion-header:hover {
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }
    
    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease, padding 0.3s ease;
    }
    
    .accordion-content.active {
      max-height: 2000px;
      padding: 1.5rem;
    }
    
    .accordion-icon {
      transition: transform 0.3s ease;
    }
    
    .accordion-icon.rotated {
      transform: rotate(180deg);
    }
    
    /* Help icons */
    .help-icon {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
    
    /* Step indicators */
    .step-indicator {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    /* Search box */
    .search-box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
    }
    
    /* Category cards */
    .category-card {
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    
    .category-card:hover {
      border-color: #10b981;
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
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
        <h1 class="text-2xl font-bold gradient-text">GuidancePortal</h1>
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
          courses
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
        <a href="courses.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Programs</a>
        <a href="contact.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="help-gradient pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full animate-pulse-custom"></div>
    
    <div class="container mx-auto px-6 text-center text-white relative z-10">
      <div class="max-w-4xl mx-auto animate-fadeInUp">
        <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
          🆘 Support Center
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Help
          <span class="block text-yellow-300">Center</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto mb-8">
          Find answers to your questions and get the support you need to make the most of your guidance experience.
        </p>
        

  <!-- Quick Actions -->
  <section class="py-12 -mt-10 relative z-10">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto">
        <div class="grid md:grid-cols-4 gap-4">
          <a href="#getting-started" class="category-card bg-white p-6 rounded-2xl shadow-lg text-center hover-lift">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Getting Started</h3>
            <p class="text-sm text-gray-600 mt-1">Portal basics</p>
          </a>
          
          <a href="#appointments" class="category-card bg-white p-6 rounded-2xl shadow-lg text-center hover-lift">
            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Appointments</h3>
            <p class="text-sm text-gray-600 mt-1">Booking & scheduling</p>
          </a>
          
          <a href="#technical-help" class="category-card bg-white p-6 rounded-2xl shadow-lg text-center hover-lift">
            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Technical Help</h3>
            <p class="text-sm text-gray-600 mt-1">Login & troubleshooting</p>
          </a>
          
          <a href="#wellness-resources" class="category-card bg-white p-6 rounded-2xl shadow-lg text-center hover-lift">
            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Wellness Resources</h3>
            <p class="text-sm text-gray-600 mt-1">Support & tips</p>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Help Content -->
  <main class="py-20">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto">
        
        <!-- Getting Started Section -->
        <section id="getting-started" class="mb-16 animate-fadeInUp">
          <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100">
            <div class="flex items-center mb-8">
              <div class="help-icon w-16 h-16 rounded-2xl flex items-center justify-center mr-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-4xl font-bold gradient-text mb-2">Getting Started</h2>
                <p class="text-gray-600 text-lg">Learn how to navigate and use the guidance portal effectively</p>
              </div>
            </div>
            
            <!-- Accordion Items -->
            <div class="space-y-4">
              <!-- First Time Setup -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('setup')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                      <span class="text-white font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">First Time Setup</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="setup" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Step-by-Step Setup Guide:</h4>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Create Your Account</h5>
                          <p class="text-gray-600 text-sm">Visit the registration page and fill out your personal information using your student ID number.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Verify Your Email</h5>
                          <p class="text-gray-600 text-sm">Check your email for a verification link and click it to activate your account.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Complete Your Profile</h5>
                          <p class="text-gray-600 text-sm">Add your program details, contact information, and emergency contacts.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Take the Initial Assessment</h5>
                          <p class="text-gray-600 text-sm">Complete the welcome survey to help us understand your needs and goals.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-xl mt-6">
                      <p class="text-blue-800 text-sm">
                        <strong>💡 Tip:</strong> Keep your student ID handy during registration. If you encounter any issues, contact our support team at guidance@sti.edu.ph
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Navigating the Portal -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('navigation')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                      <span class="text-white font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Navigating the Portal</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="navigation" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Portal Features Overview:</h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                      <div class="p-4 bg-green-50 rounded-xl">
                        <h5 class="font-semibold text-green-800 mb-2">📊 Dashboard</h5>
                        <p class="text-green-700 text-sm">View your upcoming appointments, recent activities, and important notifications.</p>
                      </div>
                      <div class="p-4 bg-blue-50 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">📅 Appointments</h5>
                        <p class="text-blue-700 text-sm">Schedule, reschedule, or cancel your guidance counseling sessions.</p>
                      </div>
                      <div class="p-4 bg-purple-50 rounded-xl">
                        <h5 class="font-semibold text-purple-800 mb-2">📋 Assessments</h5>
                        <p class="text-purple-700 text-sm">Take career assessments and view your results and recommendations.</p>
                      </div>
                      <div class="p-4 bg-orange-50 rounded-xl">
                        <h5 class="font-semibold text-orange-800 mb-2">📚 Resources</h5>
                        <p class="text-orange-700 text-sm">Access study materials, career guides, and wellness resources.</p>
                      </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl mt-6">
                      <p class="text-green-800 text-sm">
                        <strong>🎯 Quick Tip:</strong> Use the search bar at the top of any page to quickly find specific features or information.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Understanding Your Dashboard -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('dashboard')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                      <span class="text-white font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Understanding Your Dashboard</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="dashboard" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <p class="text-gray-700">Your dashboard is your central for all guidance activities. Here's what each section means:</p>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h10a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Quick Actions</h5>
                          <p class="text-gray-600 text-sm">One-click access to book appointments, take assessments, or contact counselors.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Progress Tracking</h5>
                          <p class="text-gray-600 text-sm">Visual charts showing your academic progress and goal achievements.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h10a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Recent Activity</h5>
                          <p class="text-gray-600 text-sm">Timeline of your recent sessions, completed assessments, and updates.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Appointments Section -->
        <section id="appointments" class="mb-16 animate-fadeInUp">
          <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100">
            <div class="flex items-center mb-8">
              <div class="help-icon w-16 h-16 rounded-2xl flex items-center justify-center mr-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-4xl font-bold gradient-text mb-2">Appointments</h2>
                <p class="text-gray-600 text-lg">Everything you need to know about booking and managing appointments</p>
              </div>
            </div>
            
            <!-- Appointment Accordion Items -->
            <div class="space-y-4">
              <!-- Booking Appointments -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('booking')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Booking New Appointments</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="booking" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">How to Book an Appointment:</h4>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Navigate to Appointments</h5>
                          <p class="text-gray-600 text-sm">Click on "Appointments" in the main menu or use the "Book Appointment" button on your dashboard.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Choose Appointment Type</h5>
                          <p class="text-gray-600 text-sm">Select from Academic Counseling, Career Guidance, Personal Counseling, or Crisis Support.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Select Date and Time</h5>
                          <p class="text-gray-600 text-sm">Choose from available time slots. Green slots are available, red slots are booked.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Provide Details</h5>
                          <p class="text-gray-600 text-sm">Briefly describe the reason for your appointment to help the counselor prepare.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">5</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Confirm Booking</h5>
                          <p class="text-gray-600 text-sm">Review your details and click "Confirm Appointment." You'll receive an email confirmation.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4 mt-6">
                      <div class="bg-green-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-green-800 mb-2">📅 Available Times</h5>
                        <ul class="text-green-700 text-sm space-y-1">
                          <li>• Monday-Friday: 8:00 AM - 5:00 PM</li>
                          <li>• Saturday: 8:00 AM - 12:00 PM</li>
                          <li>• Emergency slots available 24/7</li>
                        </ul>
                      </div>
                      <div class="bg-blue-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">⏰ Booking Rules</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Book at least 24 hours in advance</li>
                          <li>• Sessions are 45-60 minutes long</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Rescheduling -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('rescheduling')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Rescheduling Appointments</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="rescheduling" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <p class="text-gray-700">Need to change your appointment time? Here's how to reschedule:</p>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Go to My Appointments</h5>
                          <p class="text-gray-600 text-sm">Find your upcoming appointment in the "My Appointments" section.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Click "Reschedule"</h5>
                          <p class="text-gray-600 text-sm">Click the "Reschedule" button next to your appointment.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Choose New Time</h5>
                          <p class="text-gray-600 text-sm">Select a new available time slot from the calendar.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Confirm Changes</h5>
                          <p class="text-gray-600 text-sm">Review and confirm your new appointment time.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="bg-orange-50 p-4 rounded-xl">
                      <p class="text-orange-800 text-sm">
                        <strong>⚠️ Important:</strong> Please reschedule at least 4 hours before your original appointment time. Late rescheduling may result in appointment fees.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Canceling -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('canceling')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Canceling Appointments</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="canceling" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <p class="text-gray-700">If you need to cancel an appointment, please follow these steps:</p>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Access Your Appointments</h5>
                          <p class="text-gray-600 text-sm">Go to "My Appointments" to view your scheduled sessions.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Select Cancel</h5>
                          <p class="text-gray-600 text-sm">Click the "Cancel" button next to the appointment you want to cancel.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Provide Reason (Optional)</h5>
                          <p class="text-gray-600 text-sm">You can optionally provide a reason for cancellation to help us improve our services.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Confirm Cancellation</h5>
                          <p class="text-gray-600 text-sm">Confirm your cancellation. You'll receive an email confirmation.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4 mt-6">
                      <div class="bg-red-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-red-800 mb-2">⏰ Cancellation Policy</h5>
                        <ul class="text-red-700 text-sm space-y-1">
                          <li>• Cancel at least 4 hours in advance</li>
                          <li>• Late cancellations may incur fees</li>
                          <li>• No-shows count as missed appointments</li>
                        </ul>
                      </div>
                      <div class="bg-blue-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">📞 Need Help?</h5>
                        <p class="text-blue-700 text-sm">
                          If you can't cancel online, call us at (02) 1234-5678 or email guidance@sti.edu.ph
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Technical Help Section -->
        <section id="technical-help" class="mb-16 animate-fadeInUp">
          <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100">
            <div class="flex items-center mb-8">
              <div class="help-icon w-16 h-16 rounded-2xl flex items-center justify-center mr-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-4xl font-bold gradient-text mb-2">Technical Help</h2>
                <p class="text-gray-600 text-lg">Solutions for login issues, password problems, and system errors</p>
              </div>
            </div>
            
            <!-- Technical Help Accordion Items -->
            <div class="space-y-4">
              <!-- Login Problems -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('login-problems')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Login Problems</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="login-problems" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Common Login Issues & Solutions:</h4>
                    
                    <div class="space-y-4">
                      <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                        <h5 class="font-semibold text-red-800 mb-2">❌ "Invalid Username or Password"</h5>
                        <ul class="text-red-700 text-sm space-y-1">
                          <li>• Double-check your student ID number (username)</li>
                          <li>• Ensure Caps Lock is off</li>
                          <li>• Try typing your password in a text editor first</li>
                          <li>• Clear your browser cache and cookies</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                        <h5 class="font-semibold text-orange-800 mb-2">⚠️ "Account Locked"</h5>
                        <ul class="text-orange-700 text-sm space-y-1">
                          <li>• Wait 15 minutes before trying again</li>
                          <li>• Contact IT support if still locked</li>
                          <li>• Verify your account status with the registrar</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <h5 class="font-semibold text-blue-800 mb-2">🔄 "Session Expired"</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Close all browser tabs and restart</li>
                          <li>• Clear browser data for our site</li>
                          <li>• Try logging in from an incognito window</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <h5 class="font-semibold text-purple-800 mb-2">🌐 Browser Issues</h5>
                        <ul class="text-purple-700 text-sm space-y-1">
                          <li>• Use Chrome, Firefox, or Edge (latest versions)</li>
                          <li>• Disable browser extensions temporarily</li>
                          <li>• Enable JavaScript and cookies</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl mt-6">
                      <p class="text-green-800 text-sm">
                        <strong>💡 Quick Fix:</strong> Try the "Forgot Password" link if you're still having trouble. It's often the fastest solution!
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Password Reset -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('password-reset')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Password Reset</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="password-reset" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">How to Reset Your Password:</h4>
                    
                    <div class="space-y-3">
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Go to Login Page</h5>
                          <p class="text-gray-600 text-sm">Visit the login page and click "Forgot Password?" below the login form.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Enter Your Information</h5>
                          <p class="text-gray-600 text-sm">Provide your student ID number and registered email address.</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Check Your Email</h5>
                          <p class="text-gray-600 text-sm">Look for a password reset email (check spam folder if needed).</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Click Reset Link</h5>
                          <p class="text-gray-600 text-sm">Click the link in the email (valid for 24 hours).</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start space-x-4">
                        <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                          <span class="text-white font-bold text-sm">5</span>
                        </div>
                        <div>
                          <h5 class="font-semibold text-gray-800">Create New Password</h5>
                          <p class="text-gray-600 text-sm">Enter and confirm your new password following the security requirements.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4 mt-6">
                      <div class="bg-blue-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">🔐 Password Requirements</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• At least 8 characters long</li>
                          <li>• Include uppercase and lowercase letters</li>
                          <li>• Include at least one number</li>
                          <li>• Include at least one special character</li>
                        </ul>
                      </div>
                      <div class="bg-yellow-50 p-4 rounded-xl">
                        <h5 class="font-semibold text-yellow-800 mb-2">⚠️ Troubleshooting</h5>
                        <ul class="text-yellow-700 text-sm space-y-1">
                          <li>• Email not received? Check spam folder</li>
                          <li>• Link expired? Request a new reset</li>
                          <li>• Still having issues? Contact IT support</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- System Errors -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('system-errors')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">System Errors</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="system-errors" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Common System Errors & Solutions:</h4>
                    
                    <div class="space-y-4">
                      <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                        <h5 class="font-semibold text-red-800 mb-2">🚫 "Page Not Found" (404 Error)</h5>
                        <ul class="text-red-700 text-sm space-y-1">
                          <li>• Check the URL for typos</li>
                          <li>• Go back to the homepage and navigate again</li>
                          <li>• Clear browser cache and refresh</li>
                          <li>• Try accessing the page later</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                        <h5 class="font-semibold text-orange-800 mb-2">⚡ "Server Error" (500 Error)</h5>
                        <ul class="text-orange-700 text-sm space-y-1">
                          <li>• Wait a few minutes and try again</li>
                          <li>• Check our status page for maintenance updates</li>
                          <li>• Contact IT support if error persists</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <h5 class="font-semibold text-blue-800 mb-2">🐌 Slow Loading Pages</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Check your internet connection</li>
                          <li>• Close unnecessary browser tabs</li>
                          <li>• Try a different browser</li>
                          <li>• Restart your router if needed</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <h5 class="font-semibold text-purple-800 mb-2">📱 Mobile App Issues</h5>
                        <ul class="text-purple-700 text-sm space-y-1">
                          <li>• Update the app to the latest version</li>
                          <li>• Restart the app completely</li>
                          <li>• Check your mobile data/WiFi connection</li>
                          <li>• Reinstall the app if problems persist</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl mt-6">
                      <h5 class="font-semibold text-gray-800 mb-2">🛠️ General Troubleshooting Steps</h5>
                      <ol class="text-gray-700 text-sm space-y-1 list-decimal list-inside">
                        <li>Refresh the page (Ctrl+F5 or Cmd+Shift+R)</li>
                        <li>Clear browser cache and cookies</li>
                        <li>Try incognito/private browsing mode</li>
                        <li>Disable browser extensions temporarily</li>
                        <li>Try a different browser or device</li>
                        <li>Check your internet connection</li>
                        <li>Contact support if issue persists</li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Wellness Resources Section -->
        <section id="wellness-resources" class="mb-16 animate-fadeInUp">
          <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100">
            <div class="flex items-center mb-8">
              <div class="help-icon w-16 h-16 rounded-2xl flex items-center justify-center mr-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-4xl font-bold gradient-text mb-2">Wellness Resources</h2>
                <p class="text-gray-600 text-lg">Support materials and tips for stress management and student wellbeing</p>
              </div>
            </div>
            
            <!-- Wellness Accordion Items -->
            <div class="space-y-4">
              <!-- Stress Management -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('stress-management')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Stress Management Tips</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="stress-management" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Effective Stress Management Techniques:</h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                      <div class="p-4 bg-pink-50 rounded-xl">
                        <h5 class="font-semibold text-pink-800 mb-2">🧘‍♀️ Mindfulness & Relaxation</h5>
                        <ul class="text-pink-700 text-sm space-y-1">
                          <li>• Deep breathing exercises (4-7-8 technique)</li>
                          <li>• Progressive muscle relaxation</li>
                          <li>• Meditation apps (Headspace, Calm)</li>
                          <li>• Mindful walking or nature breaks</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-blue-50 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">📅 Time Management</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Use a planner or digital calendar</li>
                          <li>• Break large tasks into smaller steps</li>
                          <li>• Set realistic deadlines and goals</li>
                          <li>• Practice the Pomodoro Technique</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-green-50 rounded-xl">
                        <h5 class="font-semibold text-green-800 mb-2">💪 Physical Wellness</h5>
                        <ul class="text-green-700 text-sm space-y-1">
                          <li>• Regular exercise (even 15 minutes helps)</li>
                          <li>• Maintain a consistent sleep schedule</li>
                          <li>• Eat nutritious meals regularly</li>
                          <li>• Stay hydrated throughout the day</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-purple-50 rounded-xl">
                        <h5 class="font-semibold text-purple-800 mb-2">🤝 Social Support</h5>
                        <ul class="text-purple-700 text-sm space-y-1">
                          <li>• Talk to friends, family, or counselors</li>
                          <li>• Join study groups or clubs</li>
                          <li>• Participate in campus activities</li>
                          <li>• Don't hesitate to ask for help</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-xl mt-6">
                      <h5 class="font-semibold text-yellow-800 mb-2">⚠️ When to Seek Professional Help</h5>
                      <p class="text-yellow-700 text-sm mb-2">Contact a counselor if you experience:</p>
                      <ul class="text-yellow-700 text-sm space-y-1">
                        <li>• Persistent feelings of overwhelm or anxiety</li>
                        <li>• Difficulty sleeping or changes in appetite</li>
                        <li>• Loss of interest in activities you usually enjoy</li>
                        <li>• Thoughts of self-harm or suicide</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Student Support Services -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('support-services')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Student Support Services</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="support-services" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Available Support Services:</h4>
                    
                    <div class="space-y-4">
                      <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <h5 class="font-semibold text-blue-800 mb-2">🎯 Academic Support</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Tutoring services for difficult subjects</li>
                          <li>• Study skills workshops</li>
                          <li>• Time management coaching</li>
                          <li>• Academic planning and goal setting</li>
                        </ul>
                        <p class="text-blue-600 text-sm mt-2"><strong>Contact:</strong> academic-support@sti.edu.ph</p>
                      </div>
                      
                      <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                        <h5 class="font-semibold text-green-800 mb-2">💰 Financial Aid</h5>
                        <ul class="text-green-700 text-sm space-y-1">
                          <li>• Scholarship application assistance</li>
                          <li>• Financial planning guidance</li>
                          <li>• Emergency financial support</li>
                          <li>• Work-study program information</li>
                        </ul>
                        <p class="text-green-600 text-sm mt-2"><strong>Contact:</strong> financial-aid@sti.edu.ph</p>
                      </div>
                      
                      <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <h5 class="font-semibold text-purple-800 mb-2">🏥 Health & Wellness</h5>
                        <ul class="text-purple-700 text-sm space-y-1">
                          <li>• Campus health clinic services</li>
                          <li>• Mental health counseling</li>
                          <li>• Wellness workshops and seminars</li>
                          <li>• Peer support groups</li>
                        </ul>
                        <p class="text-purple-600 text-sm mt-2"><strong>Contact:</strong> health-services@sti.edu.ph</p>
                      </div>
                      
                      <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                        <h5 class="font-semibold text-orange-800 mb-2">🎓 Career Services</h5>
                        <ul class="text-orange-700 text-sm space-y-1">
                          <li>• Resume and cover letter assistance</li>
                          <li>• Interview preparation workshops</li>
                          <li>• Job placement assistance</li>
                          <li>• Internship opportunities</li>
                        </ul>
                        <p class="text-orange-600 text-sm mt-2"><strong>Contact:</strong> career-services@sti.edu.ph</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Crisis Resources -->
              <div class="accordion-item border border-gray-200 rounded-xl overflow-hidden">
                <div class="accordion-header bg-gray-50 p-6 flex items-center justify-between" onclick="toggleAccordion('crisis-resources')">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Crisis Resources</h3>
                  </div>
                  <svg class="accordion-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div id="crisis-resources" class="accordion-content bg-white">
                  <div class="space-y-4">
                    <div class="bg-red-50 p-4 rounded-xl border-2 border-red-200">
                      <h4 class="text-lg font-semibold text-red-800 mb-3">🚨 Emergency Contacts</h4>
                      <p class="text-red-700 text-sm mb-3">If you or someone you know is in immediate danger, contact emergency services immediately.</p>
                      
                      <div class="grid md:grid-cols-2 gap-4">
                        <div>
                          <h5 class="font-semibold text-red-800 mb-2">Immediate Emergency</h5>
                          <ul class="text-red-700 text-sm space-y-1">
                            <li>• Emergency Services: <strong>911</strong></li>
                            <li>• Campus Security: <strong>(02) 1234-5679</strong></li>
                            <li>• Campus Crisis Line: <strong>(02) 1234-5680</strong></li>
                          </ul>
                        </div>
                        <div>
                          <h5 class="font-semibold text-red-800 mb-2">Crisis Hotlines</h5>
                          <ul class="text-red-700 text-sm space-y-1">
                            <li>• National Suicide Prevention: <strong>1-800-273-8255</strong></li>
                            <li>• Crisis Text Line: Text <strong>HOME</strong> to <strong>741741</strong></li>
                            <li>• Mental Health Crisis: <strong>1-800-950-6264</strong></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                      <div class="p-4 bg-blue-50 rounded-xl">
                        <h5 class="font-semibold text-blue-800 mb-2">📞 24/7 Support Lines</h5>
                        <ul class="text-blue-700 text-sm space-y-1">
                          <li>• Student Crisis Support: Available 24/7</li>
                          <li>• Anonymous reporting hotline</li>
                          <li>• Peer support chat services</li>
                          <li>• Online crisis counseling</li>
                        </ul>
                      </div>
                      
                      <div class="p-4 bg-green-50 rounded-xl">
                        <h5 class="font-semibold text-green-800 mb-2">🏥 Local Resources</h5>
                        <ul class="text-green-700 text-sm space-y-1">
                          <li>• Rosario General Hospital Emergency</li>
                          <li>• Local mental health clinics</li>
                          <li>• Community support centers</li>
                          <li>• Religious and spiritual counselors</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-xl">
                      <h5 class="font-semibold text-yellow-800 mb-2">🤝 How to Help a Friend in Crisis</h5>
                      <ul class="text-yellow-700 text-sm space-y-1">
                        <li>• Listen without judgment and take their concerns seriously</li>
                        <li>• Encourage them to seek professional help</li>
                        <li>• Stay with them or ensure they're not alone</li>
                        <li>• Contact emergency services if there's immediate danger</li>
                        <li>• Follow up and continue to show support</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
  </main>

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
    function toggleAccordion(id) {
      const content = document.getElementById(id);
      const header = content.previousElementSibling;
      const icon = header.querySelector('.accordion-icon');
      
      // Close all other accordions
      document.querySelectorAll('.accordion-content').forEach(item => {
        if (item.id !== id) {
          item.classList.remove('active');
          const otherIcon = item.previousElementSibling.querySelector('.accordion-icon');
          otherIcon.classList.remove('rotated');
        }
      });
      
      // Toggle current accordion
      content.classList.toggle('active');
      icon.classList.toggle('rotated');
    }

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const accordionItems = document.querySelectorAll('.accordion-item');
      
      accordionItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === '') {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
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

    // Smooth scrolling for category cards
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
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9862393290040dcb',t:'MTc1OTA1MTg0MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
