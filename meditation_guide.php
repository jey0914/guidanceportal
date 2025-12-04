<?php
session_start();
include("db.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meditation Guide - Student Wellness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .breathing-animation {
            animation: breathe 4s ease-in-out infinite;
        }
        
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .meditation-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 0.35s;
        }
        
        .wave {
            animation: wave 2s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.5); }
        }
    </style>
</head>

<body class="gradient-bg min-h-full">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="floating">
                        <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-lotus text-xl text-purple-600"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Meditation Guide</h1>
                        <p class="text-purple-100 text-sm">Find your inner peace and balance</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <div class="floating mb-6">
                <div class="breathing-animation mx-auto h-24 w-24 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-om text-4xl text-white"></i>
                </div>
            </div>
            <h2 class="text-4xl font-bold text-white mb-4">Welcome to Your Meditation Journey</h2>
            <p class="text-purple-100 text-lg max-w-2xl mx-auto">
                Take a moment to breathe, relax, and reconnect with yourself. Choose from our guided meditation sessions designed specifically for students.
            </p>
        </div>

        <!-- Quick Start Breathing Exercise -->
        <div class="meditation-card rounded-2xl p-8 mb-8 shadow-xl">
            <div class="text-center">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-wind text-blue-500 mr-3"></i>
                    Quick Breathing Exercise
                </h3>
                
                <div class="relative mb-8">
                    <div class="mx-auto w-48 h-48 rounded-full border-8 border-blue-200 flex items-center justify-center relative" id="breathingCircle">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-2" id="breathingText">Breathe In</div>
                            <div class="text-lg text-gray-600" id="breathingCount">4</div>
                        </div>
                        
                        <!-- Progress Ring -->
                        <svg class="absolute inset-0 w-full h-full progress-ring">
                            <circle class="progress-ring-circle" stroke="#3b82f6" stroke-width="8" fill="transparent" r="45%" cx="50%" cy="50%" id="progressCircle"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button onclick="startBreathing()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-play"></i>
                        <span id="breathingButton">Start Breathing</span>
                    </button>
                    <button onclick="resetBreathing()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Meditation Sessions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Stress Relief -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-red-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Stress Relief</h4>
                    <p class="text-gray-600 mb-4">Release tension and anxiety with this calming 10-minute session.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>10 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>Beginner</span>
                    </div>
                    <button onclick="startMeditation('stress')" class="w-full bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>

            <!-- Focus & Concentration -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bullseye text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Focus & Concentration</h4>
                    <p class="text-gray-600 mb-4">Enhance your mental clarity and concentration for better studying.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>15 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>Intermediate</span>
                    </div>
                    <button onclick="startMeditation('focus')" class="w-full bg-gradient-to-r from-blue-400 to-indigo-500 hover:from-blue-500 hover:to-indigo-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>

            <!-- Sleep Preparation -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-moon text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Sleep Preparation</h4>
                    <p class="text-gray-600 mb-4">Wind down and prepare your mind for restful sleep.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>20 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>All levels</span>
                    </div>
                    <button onclick="startMeditation('sleep')" class="w-full bg-gradient-to-r from-purple-400 to-indigo-500 hover:from-purple-500 hover:to-indigo-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>

            <!-- Anxiety Relief -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Anxiety Relief</h4>
                    <p class="text-gray-600 mb-4">Calm your mind and reduce anxiety with gentle guidance.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>12 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>Beginner</span>
                    </div>
                    <button onclick="startMeditation('anxiety')" class="w-full bg-gradient-to-r from-green-400 to-teal-500 hover:from-green-500 hover:to-teal-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>

            <!-- Confidence Building -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Confidence Building</h4>
                    <p class="text-gray-600 mb-4">Build self-confidence and positive self-image through meditation.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>18 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>Intermediate</span>
                    </div>
                    <button onclick="startMeditation('confidence')" class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>

            <!-- Mindful Study -->
            <div class="meditation-card rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-teal-400 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-brain text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Mindful Study</h4>
                    <p class="text-gray-600 mb-4">Prepare your mind for effective and mindful studying sessions.</p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-4">
                        <i class="fas fa-clock"></i>
                        <span>8 minutes</span>
                        <span>•</span>
                        <i class="fas fa-signal"></i>
                        <span>All levels</span>
                    </div>
                    <button onclick="startMeditation('study')" class="w-full bg-gradient-to-r from-teal-400 to-cyan-500 hover:from-teal-500 hover:to-cyan-600 text-white py-3 rounded-lg transition-all duration-200">
                        Start Session
                    </button>
                </div>
            </div>
        </div>

        <!-- Meditation Tips -->
        <div class="meditation-card rounded-2xl p-8 shadow-xl">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                <i class="fas fa-lightbulb text-yellow-500 mr-3"></i>
                Meditation Tips for Students
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Start Small</h4>
                        <p class="text-gray-600 text-sm">Begin with just 5-10 minutes daily. Consistency is more important than duration.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Find Your Space</h4>
                        <p class="text-gray-600 text-sm">Choose a quiet, comfortable spot where you won't be disturbed.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Be Consistent</h4>
                        <p class="text-gray-600 text-sm">Try to meditate at the same time each day to build a healthy habit.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-heart text-orange-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Be Patient</h4>
                        <p class="text-gray-600 text-sm">Don't judge yourself. Meditation is a practice that improves over time.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Meditation Modal -->
    <div id="meditationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="h-20 w-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-play text-3xl text-white" id="modalIcon"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4" id="modalTitle">Meditation Session</h3>
                <p class="text-gray-600 mb-6" id="modalDescription">Prepare yourself for a peaceful meditation session.</p>
                
                <div class="mb-6">
                    <div class="text-4xl font-bold text-purple-600 mb-2" id="sessionTimer">00:00</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-400 to-pink-500 h-2 rounded-full transition-all duration-1000" style="width: 0%" id="sessionProgress"></div>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button onclick="closeModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg transition-all duration-200">
                        Close
                    </button>
                    <button onclick="toggleSession()" class="flex-1 bg-gradient-to-r from-purple-400 to-pink-500 hover:from-purple-500 hover:to-pink-600 text-white py-3 rounded-lg transition-all duration-200" id="sessionButton">
                        Start
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let breathingInterval;
        let breathingActive = false;
        let sessionInterval;
        let sessionActive = false;
        let sessionTime = 0;
        let totalSessionTime = 0;

        function startBreathing() {
            if (breathingActive) {
                stopBreathing();
                return;
            }

            breathingActive = true;
            document.getElementById('breathingButton').innerHTML = '<i class="fas fa-pause"></i><span>Stop</span>';
            
            let phase = 'inhale'; // inhale, hold, exhale, hold
            let count = 4;
            let progress = 0;
            
            const circle = document.getElementById('progressCircle');
            const text = document.getElementById('breathingText');
            const countElement = document.getElementById('breathingCount');
            
            breathingInterval = setInterval(() => {
                countElement.textContent = count;
                
                // Update progress ring
                const offset = 283 - (progress / 100) * 283;
                circle.style.strokeDashoffset = offset;
                
                count--;
                progress += 25;
                
                if (count < 0) {
                    switch(phase) {
                        case 'inhale':
                            phase = 'hold1';
                            text.textContent = 'Hold';
                            count = 4;
                            break;
                        case 'hold1':
                            phase = 'exhale';
                            text.textContent = 'Breathe Out';
                            count = 4;
                            break;
                        case 'exhale':
                            phase = 'hold2';
                            text.textContent = 'Hold';
                            count = 4;
                            break;
                        case 'hold2':
                            phase = 'inhale';
                            text.textContent = 'Breathe In';
                            count = 4;
                            progress = 0;
                            break;
                    }
                }
            }, 1000);
        }

        function stopBreathing() {
            breathingActive = false;
            clearInterval(breathingInterval);
            document.getElementById('breathingButton').innerHTML = '<i class="fas fa-play"></i><span>Start Breathing</span>';
        }

        function resetBreathing() {
            stopBreathing();
            document.getElementById('breathingText').textContent = 'Breathe In';
            document.getElementById('breathingCount').textContent = '4';
            document.getElementById('progressCircle').style.strokeDashoffset = 283;
        }

        function startMeditation(type) {
            const sessions = {
                stress: {
                    title: 'Stress Relief Meditation',
                    description: 'Let go of tension and find your calm center.',
                    duration: 10,
                    icon: 'fas fa-heart'
                },
                focus: {
                    title: 'Focus & Concentration',
                    description: 'Sharpen your mind for better learning.',
                    duration: 15,
                    icon: 'fas fa-bullseye'
                },
                sleep: {
                    title: 'Sleep Preparation',
                    description: 'Prepare your mind for restful sleep.',
                    duration: 20,
                    icon: 'fas fa-moon'
                },
                anxiety: {
                    title: 'Anxiety Relief',
                    description: 'Find peace and calm your worried mind.',
                    duration: 12,
                    icon: 'fas fa-leaf'
                },
                confidence: {
                    title: 'Confidence Building',
                    description: 'Build inner strength and self-belief.',
                    duration: 18,
                    icon: 'fas fa-star'
                },
                study: {
                    title: 'Mindful Study Preparation',
                    description: 'Prepare your mind for focused learning.',
                    duration: 8,
                    icon: 'fas fa-brain'
                }
            };

            const session = sessions[type];
            document.getElementById('modalTitle').textContent = session.title;
            document.getElementById('modalDescription').textContent = session.description;
            document.getElementById('modalIcon').className = session.icon + ' text-3xl text-white';
            
            totalSessionTime = session.duration * 60; // Convert to seconds
            sessionTime = 0;
            
            updateSessionDisplay();
            document.getElementById('meditationModal').classList.remove('hidden');
            document.getElementById('meditationModal').classList.add('flex');
        }

        function closeModal() {
            if (sessionActive) {
                clearInterval(sessionInterval);
                sessionActive = false;
            }
            document.getElementById('meditationModal').classList.add('hidden');
            document.getElementById('meditationModal').classList.remove('flex');
            document.getElementById('sessionButton').textContent = 'Start';
        }

        function toggleSession() {
            if (sessionActive) {
                clearInterval(sessionInterval);
                sessionActive = false;
                document.getElementById('sessionButton').textContent = 'Resume';
            } else {
                sessionActive = true;
                document.getElementById('sessionButton').textContent = 'Pause';
                
                sessionInterval = setInterval(() => {
                    sessionTime++;
                    updateSessionDisplay();
                    
                    if (sessionTime >= totalSessionTime) {
                        clearInterval(sessionInterval);
                        sessionActive = false;
                        document.getElementById('sessionButton').textContent = 'Complete';
                        
                        // Show completion message
                        setTimeout(() => {
                            alert('Meditation session complete! Well done on taking time for your wellbeing.');
                            closeModal();
                        }, 1000);
                    }
                }, 1000);
            }
        }

        function updateSessionDisplay() {
            const minutes = Math.floor(sessionTime / 60);
            const seconds = sessionTime % 60;
            const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            document.getElementById('sessionTimer').textContent = timeString;
            
            const progress = (sessionTime / totalSessionTime) * 100;
            document.getElementById('sessionProgress').style.width = progress + '%';
        }

        // Close modal when clicking outside
        document.getElementById('meditationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'991ffbfdb5490dc9',t:'MTc2MTA0MTYyOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
