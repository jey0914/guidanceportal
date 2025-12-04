<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coping Strategies - Student Wellness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .strategy-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .strategy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .mood-emoji {
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .mood-emoji:hover {
            transform: scale(1.2);
        }
        
        .mood-emoji.selected {
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            padding: 0.5rem;
        }
        
        .progress-bar {
            transition: width 0.5s ease;
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
                            <i class="fas fa-shield-heart text-xl text-blue-600"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Coping Strategies</h1>
                        <p class="text-blue-100 text-sm">Tools and techniques for managing stress and emotions</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Assessment Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Mood Tracker -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-smile text-yellow-500 mr-3"></i>
                    How are you feeling today?
                </h3>
                
                <div class="flex justify-center space-x-4 mb-6">
                    <span class="mood-emoji" data-mood="very-sad" onclick="selectMood('very-sad')">😢</span>
                    <span class="mood-emoji" data-mood="sad" onclick="selectMood('sad')">😔</span>
                    <span class="mood-emoji" data-mood="neutral" onclick="selectMood('neutral')">😐</span>
                    <span class="mood-emoji" data-mood="happy" onclick="selectMood('happy')">😊</span>
                    <span class="mood-emoji" data-mood="very-happy" onclick="selectMood('very-happy')">😄</span>
                </div>
                
                <div id="moodFeedback" class="text-center text-gray-600 mb-4 hidden">
                    <p id="moodMessage"></p>
                </div>
                
                <div class="bg-gray-100 rounded-lg p-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>This Week's Mood</span>
                        <span id="weeklyAverage">Not tracked yet</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-400 to-green-400 h-2 rounded-full progress-bar" style="width: 0%" id="moodProgress"></div>
                    </div>
                </div>
            </div>

            <!-- Stress Level Assessment -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-thermometer-half text-red-500 mr-3"></i>
                    Current Stress Level
                </h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rate your stress (1-10):</label>
                    <input type="range" min="1" max="10" value="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" id="stressSlider" oninput="updateStressLevel(this.value)">
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Low</span>
                        <span>Medium</span>
                        <span>High</span>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <div class="text-3xl font-bold mb-2" id="stressValue">5</div>
                    <div class="text-sm text-gray-600" id="stressLabel">Moderate Stress</div>
                </div>
                
                <div id="stressRecommendation" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <p class="text-sm text-blue-700">Try some breathing exercises or take a short walk to help manage your stress level.</p>
                </div>
            </div>
        </div>

        <!-- Quick Relief Tools -->
        <div class="strategy-card rounded-2xl p-6 mb-8 shadow-xl">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                <i class="fas fa-first-aid text-green-500 mr-3"></i>
                Quick Relief Tools
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="startBreathingExercise()" class="bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white p-4 rounded-xl transition-all duration-200 text-center">
                    <i class="fas fa-wind text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">4-7-8 Breathing</span>
                </button>
                
                <button onclick="startGroundingExercise()" class="bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white p-4 rounded-xl transition-all duration-200 text-center">
                    <i class="fas fa-anchor text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">5-4-3-2-1 Grounding</span>
                </button>
                
                <button onclick="startProgressiveMuscle()" class="bg-gradient-to-r from-purple-400 to-purple-600 hover:from-purple-500 hover:to-purple-700 text-white p-4 rounded-xl transition-all duration-200 text-center">
                    <i class="fas fa-dumbbell text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">Muscle Relaxation</span>
                </button>
                
                <button onclick="showEmergencyContacts()" class="bg-gradient-to-r from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white p-4 rounded-xl transition-all duration-200 text-center">
                    <i class="fas fa-phone text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">Emergency Help</span>
                </button>
            </div>
        </div>

        <!-- Coping Strategies Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Breathing Techniques -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lungs text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Breathing Techniques</h4>
                    <p class="text-gray-600 mb-4">Learn various breathing exercises to calm your mind and reduce anxiety instantly.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Box Breathing</span>
                            <button onclick="showTechnique('box-breathing')" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">4-7-8 Technique</span>
                            <button onclick="showTechnique('478-breathing')" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Belly Breathing</span>
                            <button onclick="showTechnique('belly-breathing')" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('breathing')" class="w-full bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Mindfulness -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Mindfulness</h4>
                    <p class="text-gray-600 mb-4">Practice present-moment awareness to reduce stress and improve focus.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Body Scan</span>
                            <button onclick="showTechnique('body-scan')" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Mindful Walking</span>
                            <button onclick="showTechnique('mindful-walking')" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">5-4-3-2-1 Grounding</span>
                            <button onclick="showTechnique('grounding')" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('mindfulness')" class="w-full bg-gradient-to-r from-green-400 to-teal-500 hover:from-green-500 hover:to-teal-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Physical Activities -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-running text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Physical Activities</h4>
                    <p class="text-gray-600 mb-4">Use movement and exercise to release tension and boost mood naturally.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Quick Stretches</span>
                            <button onclick="showTechnique('stretches')" class="text-orange-600 hover:text-orange-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Desk Exercises</span>
                            <button onclick="showTechnique('desk-exercises')" class="text-orange-600 hover:text-orange-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Walking Break</span>
                            <button onclick="showTechnique('walking')" class="text-orange-600 hover:text-orange-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('physical')" class="w-full bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Creative Expression -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-palette text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Creative Expression</h4>
                    <p class="text-gray-600 mb-4">Channel emotions through art, writing, and creative activities for healing.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Journaling</span>
                            <button onclick="showTechnique('journaling')" class="text-purple-600 hover:text-purple-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Art Therapy</span>
                            <button onclick="showTechnique('art-therapy')" class="text-purple-600 hover:text-purple-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Music & Movement</span>
                            <button onclick="showTechnique('music')" class="text-purple-600 hover:text-purple-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('creative')" class="w-full bg-gradient-to-r from-purple-400 to-pink-500 hover:from-purple-500 hover:to-pink-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Social Support -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Social Support</h4>
                    <p class="text-gray-600 mb-4">Connect with others and build a strong support network for emotional wellbeing.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Reach Out</span>
                            <button onclick="showTechnique('reach-out')" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Support Groups</span>
                            <button onclick="showTechnique('support-groups')" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Professional Help</span>
                            <button onclick="showTechnique('professional-help')" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('social')" class="w-full bg-gradient-to-r from-indigo-400 to-blue-500 hover:from-indigo-500 hover:to-blue-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Time Management -->
            <div class="strategy-card rounded-2xl p-6 shadow-xl">
                <div class="text-center">
                    <div class="h-16 w-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-3">Time Management</h4>
                    <p class="text-gray-600 mb-4">Organize your schedule and priorities to reduce stress and increase productivity.</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Priority Matrix</span>
                            <button onclick="showTechnique('priority-matrix')" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Pomodoro Technique</span>
                            <button onclick="showTechnique('pomodoro')" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">Break Planning</span>
                            <button onclick="showTechnique('break-planning')" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="openStrategyModal('time')" class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white py-3 rounded-lg transition-all duration-200">
                        Learn More
                    </button>
                </div>
            </div>
        </div>

        <!-- Emergency Resources -->
        <div class="strategy-card rounded-2xl p-6 shadow-xl">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                <i class="fas fa-life-ring text-red-500 mr-3"></i>
                Crisis Support & Emergency Resources
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <i class="fas fa-phone-alt text-3xl text-red-600 mb-3"></i>
                    <h4 class="font-semibold text-gray-800 mb-2">Crisis Hotline</h4>
                    <p class="text-sm text-gray-600 mb-3">24/7 support for mental health emergencies</p>
                    <a href="tel:988" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 inline-block">
                        Call 988
                    </a>
                </div>
                
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <i class="fas fa-comments text-3xl text-blue-600 mb-3"></i>
                    <h4 class="font-semibold text-gray-800 mb-2">Text Support</h4>
                    <p class="text-sm text-gray-600 mb-3">Text-based crisis counseling</p>
                    <a href="sms:741741" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 inline-block">
                        Text HOME to 741741
                    </a>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <i class="fas fa-user-md text-3xl text-green-600 mb-3"></i>
                    <h4 class="font-semibold text-gray-800 mb-2">Campus Counseling</h4>
                    <p class="text-sm text-gray-600 mb-3">Professional counseling services</p>
                    <button onclick="showCampusResources()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        Find Resources
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Strategy Modal -->
    <div id="strategyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800" id="modalTitle">Strategy Details</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Technique Modal -->
    <div id="techniqueModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="h-20 w-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-play text-3xl text-white" id="techniqueIcon"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4" id="techniqueTitle">Technique Guide</h3>
                <div id="techniqueContent" class="text-gray-600 mb-6">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <div class="flex space-x-4">
                    <button onclick="closeTechniqueModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg transition-all duration-200">
                        Close
                    </button>
                    <button onclick="startTechnique()" class="flex-1 bg-gradient-to-r from-blue-400 to-purple-500 hover:from-blue-500 hover:to-purple-600 text-white py-3 rounded-lg transition-all duration-200" id="startTechniqueBtn">
                        Start
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentMood = null;
        let moodHistory = JSON.parse(localStorage.getItem('moodHistory') || '[]');

        function selectMood(mood) {
            // Remove previous selection
            document.querySelectorAll('.mood-emoji').forEach(emoji => {
                emoji.classList.remove('selected');
            });
            
            // Add selection to clicked emoji
            document.querySelector(`[data-mood="${mood}"]`).classList.add('selected');
            currentMood = mood;
            
            // Save to history
            const today = new Date().toDateString();
            moodHistory = moodHistory.filter(entry => entry.date !== today);
            moodHistory.push({ date: today, mood: mood });
            localStorage.setItem('moodHistory', JSON.stringify(moodHistory));
            
            // Show feedback
            showMoodFeedback(mood);
            updateMoodProgress();
        }

        function showMoodFeedback(mood) {
            const messages = {
                'very-sad': "I'm sorry you're feeling this way. Remember, it's okay to not be okay. Consider reaching out for support.",
                'sad': "It sounds like you're having a tough time. Try some breathing exercises or talk to someone you trust.",
                'neutral': "You're doing okay today. This is a good time to practice some self-care activities.",
                'happy': "Great to hear you're feeling good! Keep up the positive momentum with healthy habits.",
                'very-happy': "Wonderful! You're feeling fantastic today. Share your positive energy with others!"
            };
            
            document.getElementById('moodMessage').textContent = messages[mood];
            document.getElementById('moodFeedback').classList.remove('hidden');
        }

        function updateMoodProgress() {
            if (moodHistory.length === 0) return;
            
            const moodValues = {
                'very-sad': 1,
                'sad': 2,
                'neutral': 3,
                'happy': 4,
                'very-happy': 5
            };
            
            const recentMoods = moodHistory.slice(-7); // Last 7 days
            const average = recentMoods.reduce((sum, entry) => sum + moodValues[entry.mood], 0) / recentMoods.length;
            const percentage = (average / 5) * 100;
            
            document.getElementById('moodProgress').style.width = percentage + '%';
            document.getElementById('weeklyAverage').textContent = average.toFixed(1) + '/5';
        }

        function updateStressLevel(value) {
            document.getElementById('stressValue').textContent = value;
            
            const labels = {
                1: 'Very Low', 2: 'Low', 3: 'Low', 4: 'Moderate', 5: 'Moderate',
                6: 'Moderate', 7: 'High', 8: 'High', 9: 'Very High', 10: 'Extreme'
            };
            
            const recommendations = {
                1: 'You\'re feeling great! Keep up your current self-care routine.',
                2: 'Low stress level. Perfect time for preventive self-care.',
                3: 'Manageable stress. Consider some light relaxation techniques.',
                4: 'Moderate stress. Try breathing exercises or a short walk.',
                5: 'Moderate stress. Time for some active stress management.',
                6: 'Elevated stress. Practice mindfulness or physical activity.',
                7: 'High stress. Use multiple coping strategies and consider support.',
                8: 'High stress. Prioritize stress relief and reach out for help.',
                9: 'Very high stress. Use emergency coping tools and seek support.',
                10: 'Extreme stress. Please use crisis resources and get immediate help.'
            };
            
            document.getElementById('stressLabel').textContent = labels[value] + ' Stress';
            document.getElementById('stressRecommendation').innerHTML = 
                `<p class="text-sm text-blue-700">${recommendations[value]}</p>`;
        }

        function startBreathingExercise() {
            showTechnique('478-breathing');
        }

        function startGroundingExercise() {
            showTechnique('grounding');
        }

        function startProgressiveMuscle() {
            showTechnique('progressive-muscle');
        }

        function showEmergencyContacts() {
            alert('Emergency Resources:\n\n• Crisis Text Line: Text HOME to 741741\n• National Suicide Prevention Lifeline: 988\n• Campus Counseling: Contact your student services\n\nIf you\'re in immediate danger, call 911.');
        }

        function showTechnique(technique) {
            const techniques = {
                'box-breathing': {
                    title: 'Box Breathing',
                    icon: 'fas fa-square',
                    content: 'Breathe in for 4 counts, hold for 4, breathe out for 4, hold for 4. Repeat this cycle 4-6 times.'
                },
                '478-breathing': {
                    title: '4-7-8 Breathing',
                    icon: 'fas fa-wind',
                    content: 'Inhale through your nose for 4 counts, hold your breath for 7 counts, exhale through your mouth for 8 counts. Repeat 3-4 times.'
                },
                'grounding': {
                    title: '5-4-3-2-1 Grounding',
                    icon: 'fas fa-anchor',
                    content: 'Name 5 things you can see, 4 things you can touch, 3 things you can hear, 2 things you can smell, and 1 thing you can taste.'
                },
                'progressive-muscle': {
                    title: 'Progressive Muscle Relaxation',
                    icon: 'fas fa-dumbbell',
                    content: 'Tense each muscle group for 5 seconds, then relax for 10 seconds. Start with your toes and work up to your head.'
                }
            };
            
            const tech = techniques[technique];
            if (tech) {
                document.getElementById('techniqueTitle').textContent = tech.title;
                document.getElementById('techniqueIcon').className = tech.icon + ' text-3xl text-white';
                document.getElementById('techniqueContent').textContent = tech.content;
                document.getElementById('techniqueModal').classList.remove('hidden');
                document.getElementById('techniqueModal').classList.add('flex');
            }
        }

        function closeTechniqueModal() {
            document.getElementById('techniqueModal').classList.add('hidden');
            document.getElementById('techniqueModal').classList.remove('flex');
        }

        function startTechnique() {
            alert('Great! Find a comfortable position and follow the instructions. Take your time and focus on your breathing.');
            closeTechniqueModal();
        }

        function openStrategyModal(strategy) {
            // This would open detailed information about each strategy category
            alert(`Opening detailed guide for ${strategy} strategies. This would contain comprehensive information and exercises.`);
        }

        function closeModal() {
            document.getElementById('strategyModal').classList.add('hidden');
            document.getElementById('strategyModal').classList.remove('flex');
        }

        function showCampusResources() {
            alert('Campus Counseling Resources:\n\n• Student Counseling Center\n• Mental Health Services\n• Peer Support Groups\n• Academic Advisors\n• Chaplain Services\n\nContact your student services office for specific contact information and appointment scheduling.');
        }

        // Initialize mood progress on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMoodProgress();
        });

        // Close modals when clicking outside
        document.getElementById('strategyModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.getElementById('techniqueModal').addEventListener('click', function(e) {
            if (e.target === this) closeTechniqueModal();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'992003e817d20dc9',t:'MTc2MTA0MTk1Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
