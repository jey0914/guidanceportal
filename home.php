<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guidance Portal</title>
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

    @keyframes wave {
  0%, 40%, 100% { transform: scaleY(0.4); } 
  20% { transform: scaleY(1); }
}

.animate-wave {
  animation: wave 1.2s infinite ease-in-out;
}

    
    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
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
    
    .animate-pulse-custom {
      animation: pulse 2s ease-in-out infinite;
    }
    
    /* Gradient backgrounds */
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Modal animations */
    .modal-enter {
      opacity: 0;
      transform: translateY(-20px) scale(0.95);
    }
    .modal-enter-active {
      opacity: 1;
      transform: translateY(0) scale(1);
      transition: all 0.3s ease-out;
    }
    .modal-exit {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
    .modal-exit-active {
      opacity: 0;
      transform: translateY(-20px) scale(0.95);
      transition: all 0.2s ease-in;
    }
    
    /* Hover effects */
    .hover-lift {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
      <div class="flex items-center space-x-2">
        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
          <span class="text-white font-bold text-xl">📚</span>
        </div>
        <h1 class="text-2xl font-bold gradient-text">GuidancePortal</h1>
      </div>
      
      <!-- Desktop Navigation -->
      <nav class="hidden md:flex space-x-8">
        <a href="home.php" class="hover:text-purple-600 transition-colors font-medium">Home</a>
        <a href="about.php" class="hover:text-purple-600 transition-colors font-medium">About</a>
        <a href="courses" class="hover:text-purple-600 transition-colors font-medium">Courses</a>
        <a href="contact.php" class="hover:text-purple-600 transition-colors font-medium">Contact</a>
      </nav>
      
      
      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-purple-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 left-0 w-full h-full bg-white z-40 md:hidden">
      <div class="flex flex-col p-6 space-y-6 mt-16">
        <a href="home" class="text-xl font-medium hover:text-purple-600 transition-colors">Home</a>
        <a href="about" class="text-xl font-medium hover:text-purple-600 transition-colors">About</a>
        <a href="courses" class="text-xl font-medium hover:text-purple-600 transition-colors">Courses</a>
        <a href="contact" class="text-xl font-medium hover:text-purple-600 transition-colors">Contact</a>
        <button id="mobileGetStarted" class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold shadow-lg mt-4">
          Get Started 🚀
        </button>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="home" class="gradient-bg pt-32 pb-20 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    
    <div class="container mx-auto flex flex-col lg:flex-row items-center px-6 relative z-10">
      <div class="lg:w-1/2 space-y-8 text-white animate-fadeInUp">
        <h2 class="text-5xl lg:text-6xl font-bold leading-tight">
          We Listen,
          <span class="block text-yellow-300">We Guide, We care</span>
        </h2>
        <p class="text-xl text-white/90 leading-relaxed">
          Connect with counselors and mentors who are ready to support your journey academically, emotionally, and personally.
        </p>
        
        <!-- Stats -->
        <div class="flex flex-wrap gap-6 py-6">
          <div class="text-center">
            <div class="text-3xl font-bold text-yellow-300">10K+</div>
            <div class="text-sm text-white/80">Students</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-yellow-300">500+</div>
            <div class="text-sm text-white/80">Courses</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-yellow-300">98%</div>
            <div class="text-sm text-white/80">Success Rate</div>
          </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="inspire.php" 
   class="bg-white text-purple-600 px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
   Inspire Me ✨
</a>
          <button  onclick="window.open('https://www.youtube.com/embed/dMrCPz0FptA')" 
          class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition-all duration-300">
            Watch Now 🎥
          </button>
        </div>
      </div>

  <!-- Modal -->
<div id="videoModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl overflow-hidden shadow-xl w-11/12 max-w-3xl">
    <div class="relative">
      <iframe 
        class="w-full aspect-video" 
        src="https://www.youtube.com/embed/dMrCPz0FptA" 
        title="See How Guidance Works"
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
      <!-- Close Button -->
      <button 
        onclick="document.getElementById('videoModal').classList.add('hidden')" 
        class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full hover:bg-red-600">
        ✕
      </button>
    </div>
  </div>
</div>
      
   <div class="lg:w-1/2 mt-12 lg:mt-0 animate-fadeInUp" style="animation-delay: 0.3s;">
  <div class="relative">
    <!-- Hero illustration replaced with Lottie -->
    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 shadow-2xl hover-lift">
      <lottie-player 
        src="https://assets10.lottiefiles.com/packages/lf20_jcikwtux.json"  
        background="transparent"  
        speed="1"  
        style="width: 90%; height: 90%;"  
        loop  
        autoplay>
      </lottie-player>
    </div>

    <!-- Floating badges -->
    <div class="absolute -top-4 -right-4 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full font-bold shadow-lg animate-pulse-custom">
      ⭐ Top Rated
    </div>
    <div class="absolute -bottom-4 -left-4 bg-green-500 text-white px-4 py-2 rounded-full font-bold shadow-lg animate-float">
      🏆 Certified
    </div>
  </div>
</div>
</section>

  <!-- Features Section -->
  <section id="about" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h3 class="text-4xl font-bold gradient-text mb-4">Why Seek Guidance With Us?</h3>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          We are committed to helping students discover their strengths, overcome challenges, and build a brighter future.
        </p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 hover-lift">
          <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl">🎓</span>
          </div>
          <h4 class="text-xl font-bold mb-4">Professional Counselor</h4>
          <p class="text-gray-600">Talk to licensed and experienced guidance staff who are ready to listen and help.</p>
        </div>
        
        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover-lift">
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl">⚡</span>
          </div>
          <h4 class="text-xl font-bold mb-4">Confidential Counseling</h4>
          <p class="text-gray-600">Engage with hands-on projects and real-time feedback systems</p>
        </div>
        
        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 hover-lift">
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl">🏆</span>
          </div>
          <h4 class="text-xl font-bold mb-4">Career Guidance</h4>
          <p class="text-gray-600">Receive advice in choosing the right strand, course, or career path for your future.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
    <div class="container mx-auto px-6 text-center">
      <h3 class="text-4xl font-bold mb-6">Ready to Shape Your Future</h3>
      <p class="text-xl mb-8 text-gray-300 max-w-2xl mx-auto">
        Connect with our guidance services and start building the path toward your goals today.
      </p>
      <button id="ctaGetStarted" class="bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
        Get Started Now - It's Free! 🎉
      </button>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 py-16">
    <div class="container mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
              <span class="text-white font-bold">📚</span>
            </div>
            <h5 class="text-xl font-bold text-white">GuidancePortal</h5>
          </div>
          <p class="text-gray-400">Empowering learners worldwide with quality education and innovative learning experiences.</p>
        </div>
        
        <div>
          <h6 class="font-bold text-white mb-4">Quick Links</h6>
          <div class="space-y-2">
            <a href="about.php" class="block hover:text-white transition-colors">About Us</a>
            <a href="courses.php" class="block hover:text-white transition-colors">Courses</a>
            <a href="contact.php" class="block hover:text-white transition-colors">Contact</a>
          </div>
        </div>
        
        <div>
          <h6 class="font-bold text-white mb-4">Support</h6>
          <div class="space-y-2">
            <a href="help-center.php" class="block hover:text-white transition-colors">Help Center</a>
            <a href="privacy-policy.php" class="block hover:text-white transition-colors">Privacy Policy</a>
            <a href="#" class="block hover:text-white transition-colors">Terms of Service</a>
            <a href="FAQ.php" class="block hover:text-white transition-colors">FAQ</a>
          </div>
        </div>
        
        <div>
          <h6 class="font-bold text-white mb-4">Connect With Us</h6>
          <div class="flex space-x-4">
            <a href="#" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
              <span>📘</span>
            </a>
            <a href="#" class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center hover:bg-blue-500 transition-colors">
              <span>🐦</span>
            </a>
            <a href="#" class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center hover:bg-pink-700 transition-colors">
              <span>📷</span>
            </a>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-700 pt-8 text-center">
        <p>&copy; 2025 GuidancePortal. All Rights Reserved. Made with ❤️ for learners worldwide.</p>
      </div>
    </div>
  </footer>

  <div id="loadingSpinner" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
  <div class="flex space-x-1">
    <div class="w-3 h-10 bg-white animate-wave"></div>
    <div class="w-3 h-10 bg-white animate-wave [animation-delay:0.1s]"></div>
    <div class="w-3 h-10 bg-white animate-wave [animation-delay:0.2s]"></div>
    <div class="w-3 h-10 bg-white animate-wave [animation-delay:0.3s]"></div>
    <div class="w-3 h-10 bg-white animate-wave [animation-delay:0.4s]"></div>
  </div>
</div>


  <!-- Login Modal -->
  <div id="loginModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div id="modalContent" class="modal-enter bg-white p-8 rounded-3xl shadow-2xl max-w-md w-full text-center relative">
      <button id="closeModal" class="text-gray-400 hover:text-gray-600 absolute top-6 right-6 text-2xl transition-colors">&times;</button>
      
      <div class="mb-6">
        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-4">
          <span class="text-2xl">👋</span>
        </div>
        <h2 class="text-3xl font-bold mb-2 gradient-text">Welcome Back!</h2>
        <p class="text-gray-500">Choose your login type to continue your learning journey</p>
      </div>
      
      <div class="space-y-4">
         <a href="login.php"button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-3">
          <span class="text-xl">👨‍🎓</span>
          <span>Student Login</span>
  </a>
      
        
        <a href="parent_login.php" button class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-3">
          <span class="text-xl">👨‍👩‍👧‍👦</span>
          <span>Parent Login</span>
  </a>

  </div>

  <script>
    // Modal functionality
    const modal = document.getElementById('loginModal');
    const modalContent = document.getElementById('modalContent');
    const getStartedBtns = [
      document.getElementById('getStartedBtn'),
      document.getElementById('mobileGetStarted'),
      document.getElementById('heroGetStarted'),
      document.getElementById('ctaGetStarted')
    ];
    const closeModalBtn = document.getElementById('closeModal');

  // Open modal with loading spinner first
getStartedBtns.forEach(btn => {
  if (btn) {
    btn.addEventListener('click', () => {
      const spinner = document.getElementById('loadingSpinner');
      
      // Show spinner
      spinner.classList.remove('hidden');
      spinner.classList.add('flex');
      
      // After 2 seconds, hide spinner and show modal
      setTimeout(() => {
        spinner.classList.add('hidden');
        spinner.classList.remove('flex');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalContent.classList.remove('modal-exit-active');
        modalContent.classList.add('modal-enter-active');
      }, 2000); // delay 2s
    });
  }
});

    // Close modal
    function closeModal() {
      modalContent.classList.remove('modal-enter-active');
      modalContent.classList.add('modal-exit-active');
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modalContent.classList.remove('modal-exit-active');
        modalContent.classList.add('modal-enter');
      }, 200);
    }

    closeModalBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeModal();
      }
    });

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

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Add scroll effect to header
    window.addEventListener('scroll', () => {
      const header = document.querySelector('header');
      if (window.scrollY > 100) {
        header.classList.add('bg-white/98');
      } else {
        header.classList.remove('bg-white/98');
      }
    });
  </script>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9858b71a50f50dcb',t:'MTc1ODk1MjE0MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
