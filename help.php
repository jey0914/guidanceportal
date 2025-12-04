<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guidance Services: GET &amp; SUPPORT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    /* Enhanced Sidebar styling */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 300px;
      height: 100vh;
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      color: white;
      z-index: 1000;
      box-shadow: 8px 0 32px rgba(0, 0, 0, 0.3);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-header {
      padding: 32px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
      animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    
    .sidebar-header h2 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-header .logo-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
      animation: pulse 2s ease-in-out infinite;
    }
    
    .sidebar-header .subtitle {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 8px;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-nav {
      padding: 24px 0;
      height: calc(100vh - 140px);
      overflow-y: auto;
    }
    
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .sidebar li {
      margin-bottom: 8px;
      padding: 0 16px;
    }
    
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 16px;
      position: relative;
      font-weight: 500;
      overflow: hidden;
    }
    
    .sidebar a::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .sidebar a:hover::before {
      opacity: 1;
    }
    
    .sidebar a:hover {
      color: white;
      transform: translateX(8px) scale(1.02);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar a.active {
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      color: white;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
      transform: translateX(4px);
    }
    
    .sidebar a.active::after {
      content: '';
      position: absolute;
      right: -16px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 24px;
      background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 2px;
    }
    
    .sidebar a i {
      width: 24px;
      margin-right: 16px;
      font-size: 1.2rem;
      text-align: center;
    }
  </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="/_sdk/element_sdk.js" type="text/javascript"></script>
 </head>
 <body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen"><!-- Sidebar -->
  <div class="sidebar">
   <div class="sidebar-header">
    <h2>
     <div class="logo-icon"><i class="fas fa-graduation-cap"></i>
     </div> Guidance Portal</h2>
    <div class="subtitle">
     Help &amp; Support Center
    </div>
   </div>
   <nav class="sidebar-nav">
    <ul>
     <li><a href="dashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
     <li><a href="student_records.php"><i class="fas fa-clipboard-check"></i>Attendance</a></li>
     <li><a href="appointments.php"><i class="fas fa-calendar-check"></i>Appointments</a></li>
     <li><a href="reports.php"><i class="fas fa-file-alt"></i>Reports</a></li>
     <li><a href="settings.php"><i class="fas fa-cog"></i>Settings</a></li>
     <li><a href="help.php" class="active"><i class="fas fa-question-circle"></i>Help &amp; Support</a></li>
    </ul>
   </nav>

  </div><!-- Main Content -->
  <div class="ml-80 p-8">
   <div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-bold text-center mb-12 text-blue-600">Guidance Services: GET &amp; SUPPORT</h1>
    <div class="grid md:grid-cols-2 gap-8">

    <!-- Section 1 --> 
     <a href="academic.html" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
      <div class="flex items-center mb-6">
       <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4"><i class="fas fa-book-open text-green-600 text-xl"></i>
       </div>
       <h2 class="text-2xl font-bold text-green-600">Academic Support (GET)</h2>
      </div>
      <ul class="list-disc list-inside space-y-3 text-gray-700 text-lg">
       <li>Study habits coaching</li>
       <li>Time management workshops</li>
       <li>Tutorial sessions / peer mentoring</li>
       <li>Exam prep and strategies</li>
      </ul></a> 

      <!-- Section 2 -->
        <a href="career.html" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
      <div class="flex items-center mb-6">
       <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4"><i class="fas fa-briefcase text-green-600 text-xl"></i>
       </div>
       <h2 class="text-2xl font-bold text-green-600">Career &amp; Personal Development (GET)</h2>
      </div>
      <ul class="list-disc list-inside space-y-3 text-gray-700 text-lg">
       <li>Career guidance / course planning</li>
       <li>Personality &amp; interest assessments</li>
       <li>Goal setting &amp; motivation workshops</li>
       <li>Leadership and life skills training</li>
      </ul></a> 
      
      <!-- Section 3 --> 
       
      <a href="emotional.html" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
      <div class="flex items-center mb-6">
       <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4"><i class="fas fa-heart text-purple-600 text-xl"></i>
       </div>
       <h2 class="text-2xl font-bold text-purple-600">Emotional &amp; Mental Health Support (SUPPORT)</h2>
      </div>
      <ul class="list-disc list-inside space-y-3 text-gray-700 text-lg">
       <li>Individual counseling sessions</li>
       <li>Stress and anxiety management</li>
       <li>Grief &amp; trauma support</li>
       <li>Referral to psychologists or social workers</li>
      </ul></a> 
      
      <!-- Section 4 -->
       <a href="behavioral.html" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
      <div class="flex items-center mb-6">
       <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4"><i class="fas fa-users text-purple-600 text-xl"></i>
       </div>
       <h2 class="text-2xl font-bold text-purple-600">Behavioral &amp; Peer Support (SUPPORT)</h2>
      </div>
      <ul class="list-disc list-inside space-y-3 text-gray-700 text-lg">
       <li>Conflict resolution &amp; mediation</li>
       <li>Peer support groups</li>
       <li>Anti-bullying guidance</li>
       <li>Guidance on discipline and decision-making</li>
      </ul></a>
    </div>
   </div>
  </div>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9979e88ac2560dc9',t:'MTc2MTk4NDU0OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>