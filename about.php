<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About | Guidance Portal</title>
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
        <a href="about.php" class="text-blue-600 font-semibold relative">
          About
          <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-600"></span>
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
        <a href="about.php" class="text-xl font-medium text-blue-600">About</a>
        <a href="courses.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Courses</a>
        <a href="contact.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="guidance-gradient pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    
    <div class="container mx-auto px-6 text-center text-white relative z-10">
      <div class="max-w-4xl mx-auto animate-fadeInUp">
        <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
          📚 About Our Office
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Empowering Students,
          <span class="block text-yellow-300">Shaping Futures</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto">
          Discover our mission, values, and commitment to providing exceptional guidance and support for every student's journey to success.
        </p>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main class="py-20">
    <!-- Mission, Services, Team Cards -->
    <section class="container mx-auto px-6 mb-20">
      <div class="text-center mb-16 animate-fadeInUp">
        <div class="inline-block bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-medium mb-4">
          🎯 What We Do
        </div>
        <h2 class="text-4xl font-bold gradient-text mb-4">Our Core Values</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          We are dedicated to fostering an environment where every student can thrive academically, personally, and emotionally
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Card 1: Our Mission -->
        <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white text-center animate-fadeInUp">
          <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 text-gray-800">Our Mission</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            To support students' personal, academic, and emotional well-being through comprehensive guidance and counseling services that inspire growth and success.
          </p>
          <div class="flex justify-center space-x-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            <span class="w-2 h-2 bg-blue-300 rounded-full"></span>
            <span class="w-2 h-2 bg-blue-200 rounded-full"></span>
          </div>
        </div>

        <!-- Card 2: Our Services -->
        <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white text-center animate-fadeInUp" style="animation-delay: 0.2s;">
          <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 text-gray-800">Our Services</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Comprehensive counseling, personalized consultations, interactive workshops, and specialized programs designed to foster a positive and supportive learning environment.
          </p>
          <div class="flex justify-center space-x-2">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            <span class="w-2 h-2 bg-green-300 rounded-full"></span>
            <span class="w-2 h-2 bg-green-200 rounded-full"></span>
          </div>
        </div>

        <!-- Card 3: Our Team -->
        <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white text-center animate-fadeInUp" style="animation-delay: 0.4s;">
          <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 text-gray-800">Our Team</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            A dedicated group of experienced counselors, educators, and support staff committed to guiding students toward academic excellence and personal growth.
          </p>
          <div class="flex justify-center space-x-2">
            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
            <span class="w-2 h-2 bg-purple-300 rounded-full"></span>
            <span class="w-2 h-2 bg-purple-200 rounded-full"></span>
          </div>
        </div>
      </div>
    </section>

    <!-- Vision Section -->
    <section class="container mx-auto px-6 mb-20">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="animate-slideInLeft">
          <div class="inline-block bg-purple-100 text-purple-600 px-4 py-2 rounded-full text-sm font-medium mb-4">
            🌟 Our Vision
          </div>
          <h3 class="text-4xl font-bold mb-6 gradient-text">Shaping Tomorrow's Leaders</h3>
          <p class="text-lg text-gray-600 mb-6 leading-relaxed">
            To be a trusted guidance office that empowers students to overcome challenges and reach their full potential academically and personally. We envision a future where every student has the tools, confidence, and support needed to succeed.
          </p>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white text-xl">🎯</span>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800">Student-Centered Approach</h4>
                <p class="text-gray-600 text-sm">Tailored guidance for individual needs and goals</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white text-xl">💡</span>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800">Innovation in Education</h4>
                <p class="text-gray-600 text-sm">Modern methods and technology-enhanced learning</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white text-xl">🤝</span>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800">Collaborative Support</h4>
                <p class="text-gray-600 text-sm">Working together with students, parents, and educators</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="animate-slideInRight">
          <div class="relative">
            <div class="bg-white rounded-3xl p-8 shadow-2xl hover-lift">
              <svg viewBox="0 0 400 300" class="w-full h-auto">
                <!-- Background -->
                <rect width="400" height="300" fill="#f8fafc" rx="20"/>
                
                <!-- Central circle (representing vision) -->
                <circle cx="200" cy="150" r="80" fill="url(#visionGradient)" stroke="#e2e8f0" stroke-width="2"/>
                
                <!-- Gradient definitions -->
                <defs>
                  <linearGradient id="visionGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#3b82f6"/>
                    <stop offset="100%" style="stop-color:#1e40af"/>
                  </linearGradient>
                </defs>
                
                <!-- Vision icon in center -->
                <text x="200" y="160" text-anchor="middle" fill="white" font-size="40">🌟</text>
                
                <!-- Surrounding elements -->
                <circle cx="120" cy="80" r="25" fill="#10b981" opacity="0.8"/>
                <text x="120" y="88" text-anchor="middle" fill="white" font-size="16">🎓</text>
                
                <circle cx="280" cy="80" r="25" fill="#f59e0b" opacity="0.8"/>
                <text x="280" y="88" text-anchor="middle" fill="white" font-size="16">📚</text>
                
                <circle cx="120" cy="220" r="25" fill="#ef4444" opacity="0.8"/>
                <text x="120" y="228" text-anchor="middle" fill="white" font-size="16">💡</text>
                
                <circle cx="280" cy="220" r="25" fill="#8b5cf6" opacity="0.8"/>
                <text x="280" y="228" text-anchor="middle" fill="white" font-size="16">🤝</text>
                
                <!-- Connecting lines -->
                <line x1="145" y1="95" x2="175" y2="125" stroke="#e2e8f0" stroke-width="2" opacity="0.6"/>
                <line x1="255" y1="95" x2="225" y2="125" stroke="#e2e8f0" stroke-width="2" opacity="0.6"/>
                <line x1="145" y1="205" x2="175" y2="175" stroke="#e2e8f0" stroke-width="2" opacity="0.6"/>
                <line x1="255" y1="205" x2="225" y2="175" stroke="#e2e8f0" stroke-width="2" opacity="0.6"/>
                
                <!-- Success metrics -->
                <rect x="50" y="260" width="60" height="20" fill="#10b981" rx="10"/>
                <text x="80" y="273" text-anchor="middle" fill="white" font-size="10" font-weight="bold">95% Success</text>
                
                <rect x="290" y="260" width="60" height="20" fill="#3b82f6" rx="10"/>
                <text x="320" y="273" text-anchor="middle" fill="white" font-size="10" font-weight="bold">5K+ Students</text>
              </svg>
            </div>
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
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98590a3360560dcb',t:'MTc1ODk1NTU0My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
