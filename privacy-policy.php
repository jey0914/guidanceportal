<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Privacy Policy | Guidance Portal</title>
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
    
    .privacy-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
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
    
    /* Table of contents styles */
    .toc-link {
      transition: all 0.3s ease;
    }
    
    .toc-link:hover {
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
      color: white;
      transform: translateX(8px);
    }
    
    /* Section highlight */
    .section-highlight {
      border-left: 4px solid #3b82f6;
      background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, transparent 100%);
    }
    
    /* Privacy icons */
    .privacy-icon {
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
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
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
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
        <a href="courses.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Courses</a>
        <a href="contact.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="privacy-gradient pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full animate-pulse-custom"></div>
    
    <div class="container mx-auto px-6 text-center text-white relative z-10">
      <div class="max-w-4xl mx-auto animate-fadeInUp">
        <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
          🔒 Privacy & Security
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Privacy
          <span class="block text-yellow-300">Policy</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto mb-6">
          Your privacy and data security are our top priorities. Learn how we collect, use, and protect your personal information.
        </p>
  </section>

  <!-- Main Content -->
  <main class="py-20">
    <div class="container mx-auto px-6">
      <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-4 gap-12">
          
          <!-- Table of Contents - Sidebar -->
          <div class="lg:col-span-1">
            <div class="sticky top-32">
              <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-100">
                <h3 class="text-xl font-bold gradient-text mb-6 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                  </svg>
                  Table of Contents
                </h3>
                <nav class="space-y-2">
                  <a href="#overview" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">1. Overview</a>
                  <a href="#information-collection" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">2. Information We Collect</a>
                  <a href="#information-use" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">3. How We Use Information</a>
                  <a href="#information-sharing" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">4. Information Sharing</a>
                  <a href="#data-security" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">5. Data Security</a>
                  <a href="#student-rights" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">6. Student Rights</a>
                  <a href="#cookies" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">7. Cookies & Tracking</a>
                  <a href="#third-party" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">8. Third-Party Services</a>
                  <a href="#data-retention" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">9. Data Retention</a>
                  <a href="#policy-changes" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">10. Policy Changes</a>
                  <a href="#contact-info" class="toc-link block px-4 py-2 rounded-xl text-gray-600 hover:text-white transition-all">11. Contact Information</a>
                </nav>
              </div>
            </div>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-xl border-2 border-gray-100">
              
              <!-- Overview Section -->
              <section id="overview" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">1. Overview</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-4">
                    Welcome to GuidanceHub's Privacy Policy. This document explains how STI College Rosario's Guidance Services ("we," "our," or "us") collects, uses, protects, and shares information about students, parents, and visitors who use our guidance services and website.
                  </p>
                  <p class="mb-4">
                    We are committed to protecting your privacy and maintaining the confidentiality of your personal information in accordance with educational privacy laws, including the Family Educational Rights and Privacy Act (FERPA) and applicable data protection regulations.
                  </p>
                  <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl">
                    <p class="text-blue-800 font-medium">
                      <strong>Important:</strong> By using our services or website, you acknowledge that you have read and understood this Privacy Policy.
                    </p>
                  </div>
                </div>
              </section>

              <!-- Information Collection Section -->
              <section id="information-collection" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">2. Information We Collect</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">Personal Information</h3>
                  <ul class="list-disc pl-6 mb-6 space-y-2">
                    <li>Student name, ID number, and contact information</li>
                    <li>Academic records and educational history</li>
                    <li>Parent/guardian contact information</li>
                    <li>Emergency contact details</li>
                    <li>Demographic information (age, grade level, program of study)</li>
                  </ul>
                  
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">Guidance-Related Information</h3>
                  <ul class="list-disc pl-6 mb-6 space-y-2">
                    <li>Counseling session notes and records</li>
                    <li>Career assessment results</li>
                    <li>Academic progress and performance data</li>
                    <li>Personal development goals and plans</li>
                    <li>Mental health and wellness information (when applicable)</li>
                  </ul>
                  
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">Technical Information</h3>
                  <ul class="list-disc pl-6 mb-4 space-y-2">
                    <li>IP address and device information</li>
                    <li>Browser type and version</li>
                    <li>Website usage patterns and preferences</li>
                    <li>Login credentials and session data</li>
                  </ul>
                </div>
              </section>

              <!-- Information Use Section -->
              <section id="information-use" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">3. How We Use Information</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-4">We use collected information for the following educational purposes:</p>
                  
                  <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                      <h4 class="font-semibold text-green-800 mb-2">Academic Support</h4>
                      <ul class="text-green-700 text-sm space-y-1">
                        <li>• Providing personalized guidance services</li>
                        <li>• Tracking academic progress</li>
                        <li>• Developing intervention strategies</li>
                      </ul>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                      <h4 class="font-semibold text-blue-800 mb-2">Career Development</h4>
                      <ul class="text-blue-700 text-sm space-y-1">
                        <li>• Career counseling and planning</li>
                        <li>• Skills assessment and development</li>
                        <li>• Job placement assistance</li>
                      </ul>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                      <h4 class="font-semibold text-purple-800 mb-2">Personal Development</h4>
                      <ul class="text-purple-700 text-sm space-y-1">
                        <li>• Mental health and wellness support</li>
                        <li>• Personal counseling services</li>
                        <li>• Crisis intervention when needed</li>
                      </ul>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                      <h4 class="font-semibold text-orange-800 mb-2">Communication</h4>
                      <ul class="text-orange-700 text-sm space-y-1">
                        <li>• Scheduling appointments</li>
                        <li>• Sending important updates</li>
                        <li>• Parent/guardian communication</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Information Sharing Section -->
              <section id="information-sharing" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">4. Information Sharing</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl mb-6">
                    <p class="text-red-800 font-medium">
                      <strong>Confidentiality Promise:</strong> We maintain strict confidentiality and only share information when legally required or with explicit consent.
                    </p>
                  </div>
                  
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">When We May Share Information:</h3>
                  <ul class="list-disc pl-6 mb-6 space-y-2">
                    <li><strong>With Your Consent:</strong> When you explicitly authorize information sharing</li>
                    <li><strong>Educational Need:</strong> With teachers and staff who have legitimate educational interests</li>
                    <li><strong>Parent/Guardian Rights:</strong> With parents/guardians as permitted by FERPA</li>
                    <li><strong>Legal Requirements:</strong> When required by law or court order</li>
                    <li><strong>Safety Concerns:</strong> To protect health and safety in emergency situations</li>
                    <li><strong>Professional Consultation:</strong> With other qualified professionals for case consultation (anonymized when possible)</li>
                  </ul>
                  
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">We Never Share Information For:</h3>
                  <ul class="list-disc pl-6 space-y-2">
                    <li>Commercial or marketing purposes</li>
                    <li>Unauthorized third-party access</li>
                    <li>Non-educational purposes without consent</li>
                  </ul>
                </div>
              </section>

              <!-- Data Security Section -->
              <section id="data-security" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">5. Data Security</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-6">We implement comprehensive security measures to protect your personal information:</p>
                  
                  <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                      <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                      </div>
                      <h4 class="font-semibold text-gray-800">Encryption</h4>
                      <p class="text-sm text-gray-600">Data encrypted in transit and at rest</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                      <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                      </div>
                      <h4 class="font-semibold text-gray-800">Access Control</h4>
                      <p class="text-sm text-gray-600">Restricted access to authorized personnel only</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                      <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                      </div>
                      <h4 class="font-semibold text-gray-800">Secure Storage</h4>
                      <p class="text-sm text-gray-600">Protected servers and backup systems</p>
                    </div>
                  </div>
                  
                  <h3 class="text-xl font-semibold text-gray-800 mb-3">Additional Security Measures:</h3>
                  <ul class="list-disc pl-6 space-y-2">
                    <li>Regular security audits and updates</li>
                    <li>Staff training on privacy and security protocols</li>
                    <li>Secure physical storage of paper records</li>
                    <li>Multi-factor authentication for system access</li>
                    <li>Regular data backup and recovery procedures</li>
                  </ul>
                </div>
              </section>

              <!-- Student Rights Section -->
              <section id="student-rights" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">6. Your Rights</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-6">As a student or parent/guardian, you have the following rights regarding your personal information:</p>
                  
                  <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-xl">
                      <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold text-sm">1</span>
                      </div>
                      <div>
                        <h4 class="font-semibold text-blue-800 mb-1">Right to Access</h4>
                        <p class="text-blue-700 text-sm">Request copies of your personal information and guidance records</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-xl">
                      <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold text-sm">2</span>
                      </div>
                      <div>
                        <h4 class="font-semibold text-green-800 mb-1">Right to Correction</h4>
                        <p class="text-green-700 text-sm">Request corrections to inaccurate or incomplete information</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 bg-purple-50 rounded-xl">
                      <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold text-sm">3</span>
                      </div>
                      <div>
                        <h4 class="font-semibold text-purple-800 mb-1">Right to Consent</h4>
                        <p class="text-purple-700 text-sm">Control how your information is shared (within legal limits)</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 bg-orange-50 rounded-xl">
                      <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold text-sm">4</span>
                      </div>
                      <div>
                        <h4 class="font-semibold text-orange-800 mb-1">Right to Complaint</h4>
                        <p class="text-orange-700 text-sm">File complaints about privacy practices with appropriate authorities</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl mt-6">
                    <p class="text-yellow-800">
                      <strong>Note:</strong> Some rights may be limited by educational laws and regulations. Contact our Guidance Office for specific questions about your rights.
                    </p>
                  </div>
                </div>
              </section>

              <!-- Cookies Section -->
              <section id="cookies" class="section-highlight p-6 rounded-2xl mb-12 animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">7. Cookies & Tracking</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-4">Our website uses cookies and similar technologies to enhance your experience:</p>
                  
                  <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-xl">
                      <h4 class="font-semibold text-gray-800 mb-2">Essential Cookies</h4>
                      <p class="text-gray-600 text-sm mb-2">Required for basic website functionality</p>
                      <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Session management</li>
                        <li>• Security features</li>
                        <li>• User preferences</li>
                      </ul>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                      <h4 class="font-semibold text-gray-800 mb-2">Analytics Cookies</h4>
                      <p class="text-gray-600 text-sm mb-2">Help us improve our services</p>
                      <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Usage statistics</li>
                        <li>• Performance monitoring</li>
                        <li>• User behavior analysis</li>
                      </ul>
                    </div>
                  </div>
                  
                  <p class="mb-4">You can control cookie settings through your browser preferences. Note that disabling certain cookies may affect website functionality.</p>
                </div>
              </section>

              <!-- Contact Information Section -->
              <section id="contact-info" class="section-highlight p-6 rounded-2xl animate-fadeInUp">
                <div class="flex items-center mb-6">
                  <div class="privacy-icon w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold gradient-text">11. Contact Information</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                  <p class="mb-6">If you have questions about this Privacy Policy or our privacy practices, please contact us:</p>
                  
                  <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-6 rounded-xl">
                      <h4 class="font-semibold text-blue-800 mb-4">Guidance Office</h4>
                      <div class="space-y-3 text-blue-700">
                        <div class="flex items-center">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <span>2nd Floor, STI College Rosario</span>
                        </div>
                        <div class="flex items-center">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                          </svg>
                          <span>(02) 1234-5678</span>
                        </div>
                        <div class="flex items-center">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                          </svg>
                          <span>guidance@sti.edu.ph</span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-xl">
                      <h4 class="font-semibold text-green-800 mb-4">Office Hours</h4>
                      <div class="space-y-2 text-green-700">
                        <div class="flex justify-between">
                          <span>Monday - Friday:</span>
                          <span class="font-medium">8:00 AM - 5:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                          <span>Saturday:</span>
                          <span class="font-medium">8:00 AM - 12:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                          <span>Sunday:</span>
                          <span class="font-medium">Closed</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-green-200">
                          <p class="text-sm">Emergency support available 24/7</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- CTA Section -->
  <section class="py-20 guidance-gradient text-white relative overflow-hidden">
    <div class="absolute top-10 right-10 w-24 h-24 bg-white/5 rounded-full animate-float"></div>
    <div class="absolute bottom-10 left-10 w-16 h-16 bg-white/10 rounded-full animate-float" style="animation-delay: 1s;"></div>
    
    <div class="container mx-auto px-6 text-center relative z-10">
      <div class="max-w-3xl mx-auto">
        <h3 class="text-4xl lg:text-5xl font-bold mb-6">Questions About Privacy?</h3>
        <p class="text-xl mb-8 text-white/90 leading-relaxed">
          Our team is here to address any concerns about your privacy and data protection. Don't hesitate to reach out.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <a href="contact.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            Contact Us 📞
          </a>
          <a href="help-center.php" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">
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

    // Smooth scrolling for table of contents
    document.querySelectorAll('.toc-link').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
          const headerHeight = 100; // Account for fixed header
          const targetPosition = targetElement.offsetTop - headerHeight;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });

    // Highlight current section in table of contents
    window.addEventListener('scroll', () => {
      const sections = document.querySelectorAll('section[id]');
      const tocLinks = document.querySelectorAll('.toc-link');
      
      let currentSection = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        const sectionHeight = section.offsetHeight;
        
        if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
          currentSection = section.getAttribute('id');
        }
      });
      
      tocLinks.forEach(link => {
        link.classList.remove('bg-blue-600', 'text-white');
        if (link.getAttribute('href') === `#${currentSection}`) {
          link.classList.add('bg-blue-600', 'text-white');
        }
      });
    });
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98617a9351710dcd',t:'MTc1OTA0NDAzMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
