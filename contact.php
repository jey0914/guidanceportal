<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact | Guidance Portal</title>
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
    
    /* Form animations */
    .form-group {
      position: relative;
    }
    
    .form-input {
      transition: all 0.3s ease;
    }
    
    .form-input:focus {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
    }
    
    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .mobile-menu.active {
      transform: translateX(0);
    }
    
    /* Success message animation */
    .success-message {
      transform: translateY(-20px);
      opacity: 0;
      transition: all 0.3s ease;
    }
    
    .success-message.show {
      transform: translateY(0);
      opacity: 1;
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
        <a href="contact.php" class="text-blue-600 font-semibold relative">
          Contact
          <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-600"></span>
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
        <a href="contact.php" class="text-xl font-medium text-blue-600">Contact</a>
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
          📞 Get In Touch
        </div>
        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Let's Connect &
          <span class="block text-yellow-300">Start Your Journey</span>
        </h1>
        <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto">
          Have questions about our guidance services? Need personalized support? We're here to help you succeed. Reach out to us today!
        </p>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main class="py-20">
    <!-- Contact Information Section -->
    <section class="container mx-auto px-6 mb-20">
      <div class="mb-16 animate-fadeInUp">
        <div class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-medium mb-4 inline-block">
          📞 Contact Information
        </div>
        <h2 class="text-4xl font-bold gradient-text mb-4">How to Reach Us</h2>
        <p class="text-xl text-gray-600 max-w-3xl">
          We're here to support you every step of the way. Get in touch with us through any of these convenient methods.
        </p>
      </div>

      <div class="max-w-6xl mx-auto">
        <!-- Contact Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8 mb-12">
          <!-- Email Card -->
          <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white animate-fadeInUp">
            <div class="flex items-start space-x-6">
              <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Email Us</h3>
                <p class="text-blue-600 font-semibold text-lg">guidance@gmail.com</p>
                <p class="text-gray-500">We reply within 24 hours</p>
              </div>
            </div>
          </div>

          <!-- Phone Card -->
          <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white animate-fadeInUp" style="animation-delay: 0.1s;">
            <div class="flex items-start space-x-6">
              <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-500 rounded-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <div class="text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Call Us</h3>
                <p class="text-green-600 font-semibold text-lg">(02) 1234-5678</p>
                <p class="text-gray-500">Mon-Fri, 8AM-5PM</p>
              </div>
            </div>
          </div>

          <!-- Office Hours Card -->
          <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white animate-fadeInUp" style="animation-delay: 0.2s;">
            <div class="flex items-start space-x-6">
              <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Office Hours</h3>
                <p class="text-purple-600 font-semibold text-lg">Monday to Friday</p>
                <p class="text-gray-500">8:00 AM - 5:00 PM</p>
              </div>
            </div>
          </div>

          <!-- Address Card -->
          <div class="card-hover hover-lift p-8 border-2 border-gray-100 rounded-3xl shadow-lg bg-white animate-fadeInUp" style="animation-delay: 0.3s;">
            <div class="flex items-start space-x-6">
              <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Visit Us</h3>
                <p class="text-orange-600 font-semibold text-lg">STI College Rosario</p>
                <p class="text-gray-500">Guidance Office, 1st Floor</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Map Section -->
<div class="bg-white rounded-3xl p-8 shadow-2xl hover-lift animate-fadeInUp" style="animation-delay: 0.4s;">
  <h3 class="text-2xl font-bold mb-6 gradient-text">Find Us Here</h3>

  <!-- Replace this div with the iframe -->
  <div class="rounded-2xl overflow-hidden h-80">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.340794228541!2d120.85608451117348!3d14.407507585998124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33962cf1a44c17cd%3A0xb4bcc61646bedc65!2sSTI%20College%20-%20Rosario!5e0!3m2!1sen!2sph!4v1759067611094!5m2!1sen!2sph" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</div>


    <!-- FAQ Section -->
    <section class="bg-white py-16">
      <div class="container mx-auto px-6">
        <div class="mb-12">
          <div class="bg-purple-100 text-purple-600 px-4 py-2 rounded-full text-sm font-medium mb-4 inline-block">
            ❓ Frequently Asked Questions
          </div>
          <h3 class="text-3xl font-bold gradient-text mb-4">Common Questions</h3>
          <p class="text-gray-600 max-w-3xl">
            Find quick answers to the most common questions about our guidance services
          </p>
        </div>
        
        <div class="max-w-4xl space-y-4">
          <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-colors">
            <h4 class="font-semibold text-gray-800 mb-2">How can I schedule an appointment?</h4>
            <p class="text-gray-600">You can schedule an appointment by calling us, sending an email, or filling out the contact form above. We'll respond within 24 hours to confirm your appointment.</p>
          </div>
          
          <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-colors">
            <h4 class="font-semibold text-gray-800 mb-2">What services do you offer?</h4>
            <p class="text-gray-600">We offer academic guidance, career counseling, personal development support, study skills training, and mental health resources for students.</p>
          </div>
          
          <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-colors">
            <h4 class="font-semibold text-gray-800 mb-2">Is counseling confidential?</h4>
            <p class="text-gray-600">Yes, all counseling sessions are strictly confidential. We follow professional ethics and only share information with your explicit consent or in cases required by law.</p>
          </div>
          
          <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-colors">
            <h4 class="font-semibold text-gray-800 mb-2">Do you charge for guidance services?</h4>
            <p class="text-gray-600">Basic guidance services are free for all enrolled students. Some specialized programs may have fees, which will be clearly communicated beforehand.</p>
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
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9859341c52c90dc9',t:'MTc1ODk1NzI2MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
