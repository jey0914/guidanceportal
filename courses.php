<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Programs & Services | Guidance Portal</title>
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
    
    @keyframes shimmer {
      0% {
        background-position: -200px 0;
      }
      100% {
        background-position: calc(200px + 100%) 0;
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
    
    /* Service card gradients */
    .career-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .counseling-gradient {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .workshop-gradient {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .development-gradient {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .special-gradient {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .resources-gradient {
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    
    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .mobile-menu.active {
      transform: translateX(0);
    }
    
    /* Shimmer effect */
    .shimmer {
      background: linear-gradient(
        90deg,
        #f0f0f0 0px,
        #e0e0e0 40px,
        #f0f0f0 80px
      );
      background-size: 200px;
      animation: shimmer 2s infinite;
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
        <a href="courses.php" class="text-blue-600 font-semibold relative">
          Courses
          <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-600"></span>
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
        <a href="courses.php" class="text-xl font-medium text-blue-600">Programs</a>
        <a href="contact.php" class="text-xl font-medium hover:text-blue-600 transition-colors">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="guidance-gradient pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full animate-pulse-custom"></div>
    
    <div class="container mx-auto px-6 text-center text-white relative z-10">
      <div class="max-w-4xl mx-auto animate-fadeInUp">
        <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
          📚 Our Programs & Services
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Comprehensive
          <span class="block text-yellow-300">Guidance Programs</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto">
          Discover our wide range of guidance services designed to support your academic journey, personal growth, and career development.
        </p>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main class="py-20">
    <!-- Programs Overview -->
    <section class="container mx-auto px-6 mb-20">
      <div class="mb-16 animate-fadeInUp">
        <div class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-medium mb-4 inline-block">
          🎯 What We Offer
        </div>
        <h2 class="text-4xl font-bold gradient-text mb-4">Our Core Programs</h2>
        <p class="text-xl text-gray-600 max-w-3xl">
          We provide comprehensive guidance services tailored to meet the diverse needs of our students at every stage of their academic journey.
        </p>
      </div>

      <!-- Service Cards Grid -->
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        
        <!-- Career Guidance Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp">
          <div class="career-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Career Guidance</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Expert guidance in career exploration, course selection, and strategic decision-making to help you build a successful future path.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-medium">Career Planning</span>
            <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-medium">Course Selection</span>
          </div>
        </div>

        <!-- Counseling Services Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp" style="animation-delay: 0.1s;">
          <div class="counseling-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Counseling Services</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Professional individual and group counseling sessions addressing academic challenges, personal concerns, and social development.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-sm font-medium">Individual Sessions</span>
            <span class="bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-sm font-medium">Group Therapy</span>
          </div>
        </div>

        <!-- Workshops & Seminars Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp" style="animation-delay: 0.2s;">
          <div class="workshop-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Workshops & Seminars</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Interactive sessions covering stress management, effective study habits, time management, and personal growth strategies.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-cyan-100 text-cyan-600 px-3 py-1 rounded-full text-sm font-medium">Study Skills</span>
            <span class="bg-cyan-100 text-cyan-600 px-3 py-1 rounded-full text-sm font-medium">Stress Management</span>
          </div>
        </div>

        <!-- Student Development Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp" style="animation-delay: 0.3s;">
          <div class="development-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Student Development</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Leadership training programs, peer mentoring opportunities, and team-building activities to boost confidence and collaboration skills.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-medium">Leadership</span>
            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-medium">Mentoring</span>
          </div>
        </div>

        <!-- Special Programs Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp" style="animation-delay: 0.4s;">
          <div class="special-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Special Programs</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Targeted initiatives including anti-bullying campaigns, mental health awareness programs, and comprehensive values formation activities.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-medium">Mental Health</span>
            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-medium">Values Formation</span>
          </div>
        </div>

        <!-- Student Resources Card -->
        <div class="card-hover hover-lift bg-white rounded-3xl p-8 shadow-xl border-2 border-gray-100 animate-fadeInUp" style="animation-delay: 0.5s;">
          <div class="resources-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">Student Resources</h3>
          <p class="text-gray-600 leading-relaxed mb-6">
            Comprehensive access to FAQs, learning guides, study materials, and digital resources to support your academic and personal development.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="bg-teal-100 text-teal-600 px-3 py-1 rounded-full text-sm font-medium">Study Guides</span>
            <span class="bg-teal-100 text-teal-600 px-3 py-1 rounded-full text-sm font-medium">Digital Resources</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-white py-16">
      <div class="container mx-auto px-6">
        <div class="text-center mb-12">
          <div class="inline-block bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-medium mb-4">
            📊 Our Impact
          </div>
          <h3 class="text-3xl font-bold gradient-text mb-4">Making a Difference</h3>
          <p class="text-gray-600 max-w-2xl mx-auto">
            See how our guidance programs have positively impacted students' lives and academic success
          </p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-8 max-w-4xl mx-auto">
          <div class="text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">500+</div>
            <p class="text-gray-600">Students Guided</p>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-green-600 mb-2">95%</div>
            <p class="text-gray-600">Success Rate</p>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-purple-600 mb-2">50+</div>
            <p class="text-gray-600">Workshops Conducted</p>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-orange-600 mb-2">24/7</div>
            <p class="text-gray-600">Support Available</p>
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

    // Counter animation for statistics
    const animateCounters = () => {
      const counters = document.querySelectorAll('.text-4xl');
      counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
          if (current < target) {
            current += increment;
            counter.textContent = Math.ceil(current) + (counter.textContent.includes('%') ? '%' : '+');
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target + (counter.textContent.includes('%') ? '%' : '+');
          }
        };
        
        updateCounter();
      });
    };

    // Trigger counter animation when statistics section is visible
    const statsSection = document.querySelector('.bg-white');
    const statsObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounters();
          statsObserver.unobserve(entry.target);
        }
      });
    });
    
    if (statsSection) {
      statsObserver.observe(statsSection);
    }
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'985939ef21ab0dcb',t:'MTc1ODk1NzQ5OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
