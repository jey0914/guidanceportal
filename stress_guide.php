<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Management Guide - Comprehensive Relief Techniques</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .breathing-circle {
            animation: breathe 4s ease-in-out infinite;
        }
        
        @keyframes breathe {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
        }
        
        .technique-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .technique-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .stress-meter {
            background: linear-gradient(90deg, #10b981 0%, #f59e0b 50%, #ef4444 100%);
            height: 8px;
            border-radius: 4px;
        }
        
        .quick-relief-bg {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }
        
        .technique-expanded {
            max-height: 1000px;
            opacity: 1;
        }
        
        .technique-collapsed {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-full">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="floating-element mb-8">
                    <i class="fas fa-leaf text-6xl text-green-300 opacity-80"></i>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    Stress Management Guide
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100">
                    Comprehensive stress relief techniques for a calmer, healthier you
                </p>
                
                <div class="flex flex-wrap justify-center gap-8 mb-12">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-yellow-300">8</div>
                        <div class="text-sm text-indigo-100">Techniques</div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-green-300">5min</div>
                        <div class="text-sm text-indigo-100">Quick Relief</div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-pink-300">24/7</div>
                        <div class="text-sm text-indigo-100">Available</div>
                    </div>
                </div>
                
                <button onclick="scrollToTechniques()" class="bg-white text-indigo-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-indigo-50 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-down mr-2"></i>
                    Start Your Journey
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stress Assessment -->
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-16">
            <h2 class="text-3xl font-bold text-center mb-8 gradient-text">
                <i class="fas fa-chart-line mr-3"></i>
                Quick Stress Assessment
            </h2>
            
            <div class="mb-6">
                <label class="block text-lg font-medium mb-4">How stressed do you feel right now? (1-10)</label>
                <input type="range" id="stressLevel" min="1" max="10" value="5" 
                       class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                       onchange="updateStressLevel(this.value)">
                <div class="flex justify-between text-sm text-gray-500 mt-2">
                    <span>1 - Very Calm</span>
                    <span id="stressValue" class="font-bold text-indigo-600">5 - Moderate</span>
                    <span>10 - Very Stressed</span>
                </div>
            </div>
            
            <div class="stress-meter mb-6"></div>
            
            <div id="stressRecommendation" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-blue-800">Based on your stress level, we recommend starting with breathing exercises and mindfulness techniques.</p>
            </div>
        </div>
    </div>

    <!-- 8 Stress Management Techniques -->
    <div id="techniques-section" class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-4xl font-bold text-center mb-4 gradient-text">
            8 Proven Stress Management Techniques
        </h2>
        <p class="text-xl text-gray-600 text-center mb-16">
            Evidence-based methods to help you manage and reduce stress effectively
        </p>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8">
            <!-- Technique 1: Deep Breathing -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-lungs text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Deep Breathing</h3>
                        <p class="text-gray-600">Instant calm through controlled breathing</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('breathing')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Try Guided Breathing
                </button>
                
                <div id="breathing-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-blue-50 rounded-2xl p-6">
                        <div class="text-center mb-6">
                            <div class="breathing-circle w-24 h-24 bg-blue-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-circle text-blue-600 text-2xl"></i>
                            </div>
                            <p class="text-blue-800 font-medium">Follow the circle: Inhale as it grows, exhale as it shrinks</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                <p>Sit comfortably with your back straight</p>
                            </div>
                            <div class="flex items-center">
                                <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                                <p>Inhale slowly through your nose for 4 counts</p>
                            </div>
                            <div class="flex items-center">
                                <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                                <p>Hold your breath for 4 counts</p>
                            </div>
                            <div class="flex items-center">
                                <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                                <p>Exhale slowly through your mouth for 6 counts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 2: Progressive Muscle Relaxation -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-dumbbell text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Progressive Muscle Relaxation</h3>
                        <p class="text-gray-600">Release tension throughout your body</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('muscle')" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Start Muscle Relaxation
                </button>
                
                <div id="muscle-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-green-50 rounded-2xl p-6">
                        <h4 class="font-bold text-green-800 mb-4">15-Minute Full Body Routine</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-head-side-virus text-green-600 mr-3"></i>
                                <p><strong>Face & Head:</strong> Tense for 5 seconds, then relax</p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-hand-paper text-green-600 mr-3"></i>
                                <p><strong>Arms & Hands:</strong> Make fists, tense arms, then release</p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-heart text-green-600 mr-3"></i>
                                <p><strong>Chest & Shoulders:</strong> Lift shoulders to ears, then drop</p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-running text-green-600 mr-3"></i>
                                <p><strong>Legs & Feet:</strong> Point toes, tense legs, then relax</p>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-green-100 rounded-xl">
                            <p class="text-green-800 text-sm"><strong>Tip:</strong> Focus on the contrast between tension and relaxation. This helps you recognize and release stress in your body.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 3: Mindfulness Meditation -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-om text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Mindfulness Meditation</h3>
                        <p class="text-gray-600">Present moment awareness practice</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('mindfulness')" class="w-full bg-purple-600 text-white py-3 rounded-xl font-semibold hover:bg-purple-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Begin Meditation
                </button>
                
                <div id="mindfulness-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-purple-50 rounded-2xl p-6">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-purple-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-clock text-purple-600 text-xl"></i>
                            </div>
                            <div class="flex justify-center space-x-4 mb-4">
                                <button onclick="startTimer(5)" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">5 min</button>
                                <button onclick="startTimer(10)" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">10 min</button>
                                <button onclick="startTimer(15)" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">15 min</button>
                            </div>
                            <div id="timer-display" class="text-2xl font-bold text-purple-800 mb-4">Select Duration</div>
                        </div>
                        
                        <div class="space-y-3">
                            <p><strong>Simple Mindfulness Steps:</strong></p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center"><i class="fas fa-check text-purple-600 mr-2"></i>Find a quiet, comfortable position</li>
                                <li class="flex items-center"><i class="fas fa-check text-purple-600 mr-2"></i>Close your eyes or soften your gaze</li>
                                <li class="flex items-center"><i class="fas fa-check text-purple-600 mr-2"></i>Focus on your natural breathing</li>
                                <li class="flex items-center"><i class="fas fa-check text-purple-600 mr-2"></i>When mind wanders, gently return to breath</li>
                                <li class="flex items-center"><i class="fas fa-check text-purple-600 mr-2"></i>Be kind to yourself throughout</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 4: Physical Exercise -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-orange-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-running text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Physical Exercise</h3>
                        <p class="text-gray-600">Move your body to reduce stress</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('exercise')" class="w-full bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Quick Exercise Routines
                </button>
                
                <div id="exercise-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-orange-50 rounded-2xl p-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-orange-800 mb-3">5-Minute Desk Exercises</h4>
                                <ul class="space-y-2 text-sm">
                                    <li>• Neck rolls (30 seconds)</li>
                                    <li>• Shoulder shrugs (30 seconds)</li>
                                    <li>• Seated spinal twists (1 minute)</li>
                                    <li>• Calf raises (1 minute)</li>
                                    <li>• Deep breathing (2 minutes)</li>
                                </ul>
                            </div>
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-orange-800 mb-3">10-Minute Energy Boost</h4>
                                <ul class="space-y-2 text-sm">
                                    <li>• Jumping jacks (2 minutes)</li>
                                    <li>• Push-ups (2 minutes)</li>
                                    <li>• Squats (2 minutes)</li>
                                    <li>• Planks (2 minutes)</li>
                                    <li>• Stretching (2 minutes)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-orange-100 rounded-xl">
                            <p class="text-orange-800 text-sm"><strong>Benefits:</strong> Exercise releases endorphins, improves mood, and reduces cortisol levels naturally.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 5: Time Management -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-clock text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Time Management</h3>
                        <p class="text-gray-600">Organize your day to reduce overwhelm</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('time')" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Time Management Tools
                </button>
                
                <div id="time-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-indigo-50 rounded-2xl p-6">
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-indigo-800 mb-3">Priority Matrix</h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="bg-red-100 p-2 rounded text-center">
                                        <strong>Urgent + Important</strong><br>Do First
                                    </div>
                                    <div class="bg-yellow-100 p-2 rounded text-center">
                                        <strong>Important + Not Urgent</strong><br>Schedule
                                    </div>
                                    <div class="bg-blue-100 p-2 rounded text-center">
                                        <strong>Urgent + Not Important</strong><br>Delegate
                                    </div>
                                    <div class="bg-gray-100 p-2 rounded text-center">
                                        <strong>Not Urgent + Not Important</strong><br>Eliminate
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-indigo-800 mb-3">Pomodoro Technique</h4>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <p>25 min work → 5 min break</p>
                                        <p>Repeat 4 times → Long break</p>
                                    </div>
                                    <button onclick="startPomodoro()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                                        Start Timer
                                    </button>
                                </div>
                                <div id="pomodoro-timer" class="text-center text-2xl font-bold text-indigo-800 mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 6: Social Support -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-pink-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-users text-2xl text-pink-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Social Support</h3>
                        <p class="text-gray-600">Connect with others for emotional relief</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('social')" class="w-full bg-pink-600 text-white py-3 rounded-xl font-semibold hover:bg-pink-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Build Your Support Network
                </button>
                
                <div id="social-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-pink-50 rounded-2xl p-6">
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-pink-800 mb-3">Ways to Connect</h4>
                                <div class="grid md:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-pink-600 mr-2"></i>
                                        <span>Call a trusted friend</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-coffee text-pink-600 mr-2"></i>
                                        <span>Meet for coffee/tea</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-pink-600 mr-2"></i>
                                        <span>Join support groups</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-heart text-pink-600 mr-2"></i>
                                        <span>Share your feelings</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-pink-800 mb-3">Emergency Contacts</h4>
                                <div class="space-y-2 text-sm">
                                    <p><strong>Crisis Text Line:</strong> Text HOME to 741741</p>
                                    <p><strong>National Suicide Prevention:</strong> 988</p>
                                    <p><strong>SAMHSA Helpline:</strong> 1-800-662-4357</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 7: Healthy Lifestyle -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-leaf text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Healthy Lifestyle</h3>
                        <p class="text-gray-600">Build stress-resistant daily habits</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('lifestyle')" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Healthy Habits Guide
                </button>
                
                <div id="lifestyle-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-green-50 rounded-2xl p-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-green-800 mb-3">
                                    <i class="fas fa-moon mr-2"></i>Sleep Hygiene
                                </h4>
                                <ul class="space-y-1 text-sm">
                                    <li>• 7-9 hours nightly</li>
                                    <li>• Consistent sleep schedule</li>
                                    <li>• Cool, dark room</li>
                                    <li>• No screens 1hr before bed</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-green-800 mb-3">
                                    <i class="fas fa-apple-alt mr-2"></i>Nutrition
                                </h4>
                                <ul class="space-y-1 text-sm">
                                    <li>• Balanced meals</li>
                                    <li>• Limit caffeine/alcohol</li>
                                    <li>• Stay hydrated</li>
                                    <li>• Omega-3 rich foods</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-green-800 mb-3">
                                    <i class="fas fa-water mr-2"></i>Hydration
                                </h4>
                                <ul class="space-y-1 text-sm">
                                    <li>• 8 glasses daily</li>
                                    <li>• Start day with water</li>
                                    <li>• Herbal teas count</li>
                                    <li>• Monitor urine color</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-green-800 mb-3">
                                    <i class="fas fa-ban mr-2"></i>Limit Stressors
                                </h4>
                                <ul class="space-y-1 text-sm">
                                    <li>• Reduce news consumption</li>
                                    <li>• Set boundaries</li>
                                    <li>• Declutter space</li>
                                    <li>• Practice saying "no"</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technique 8: Professional Help -->
            <div class="technique-card bg-white bg-opacity-80 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-teal-100 p-4 rounded-2xl mr-4">
                        <i class="fas fa-user-md text-2xl text-teal-600"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Professional Help</h3>
                        <p class="text-gray-600">When to seek expert guidance</p>
                    </div>
                </div>
                
                <button onclick="toggleTechnique('professional')" class="w-full bg-teal-600 text-white py-3 rounded-xl font-semibold hover:bg-teal-700 transition-colors mb-4">
                    <i class="fas fa-play mr-2"></i>
                    Find Professional Support
                </button>
                
                <div id="professional-content" class="technique-collapsed transition-all duration-500">
                    <div class="bg-teal-50 rounded-2xl p-6">
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-teal-800 mb-3">When to Seek Help</h4>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-1"></i>
                                        <span>Stress interferes with daily activities</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-1"></i>
                                        <span>Physical symptoms persist</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-1"></i>
                                        <span>Self-help techniques aren't working</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-2 mt-1"></i>
                                        <span>Thoughts of self-harm</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl">
                                <h4 class="font-bold text-teal-800 mb-3">Types of Professionals</h4>
                                <div class="grid md:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p><strong>Therapists/Counselors:</strong></p>
                                        <p>Talk therapy, coping strategies</p>
                                    </div>
                                    <div>
                                        <p><strong>Psychiatrists:</strong></p>
                                        <p>Medical treatment, medication</p>
                                    </div>
                                    <div>
                                        <p><strong>Primary Care Doctors:</strong></p>
                                        <p>Initial assessment, referrals</p>
                                    </div>
                                    <div>
                                        <p><strong>Support Groups:</strong></p>
                                        <p>Peer support, shared experiences</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-teal-100 p-4 rounded-xl">
                                <p class="text-teal-800 text-sm">
                                    <strong>Remember:</strong> Seeking help is a sign of strength, not weakness. Professional support can provide you with personalized strategies and tools.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Relief Section -->
    <div class="quick-relief-bg py-16">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                    5-Minute Quick Relief
                </h2>
                <p class="text-xl text-gray-600">
                    Emergency stress busters when you need immediate relief
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white bg-opacity-90 rounded-3xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        <i class="fas fa-wind text-blue-500 mr-2"></i>
                        Emergency Breathing
                    </h3>
                    
                    <div class="text-center mb-6">
                        <div class="w-32 h-32 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center breathing-circle">
                            <div class="w-16 h-16 bg-blue-500 rounded-full"></div>
                        </div>
                        <p class="text-gray-600">4-7-8 Breathing Technique</p>
                    </div>
                    
                    <div class="space-y-3 text-center">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <span class="font-bold text-blue-800">Inhale</span> for 4 counts
                        </div>
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <span class="font-bold text-blue-800">Hold</span> for 7 counts
                        </div>
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <span class="font-bold text-blue-800">Exhale</span> for 8 counts
                        </div>
                    </div>
                    
                    <button onclick="startEmergencyBreathing()" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        Start Emergency Breathing
                    </button>
                </div>
                
                <div class="bg-white bg-opacity-90 rounded-3xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        <i class="fas fa-magic text-purple-500 mr-2"></i>
                        Instant Stress Busters
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="bg-purple-50 p-4 rounded-xl flex items-center">
                            <i class="fas fa-smile text-purple-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-semibold">Smile & Laugh</p>
                                <p class="text-sm text-gray-600">Even forced smiles release endorphins</p>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-xl flex items-center">
                            <i class="fas fa-seedling text-green-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-semibold">5-4-3-2-1 Grounding</p>
                                <p class="text-sm text-gray-600">5 things you see, 4 hear, 3 feel, 2 smell, 1 taste</p>
                            </div>
                        </div>
                        
                        <div class="bg-orange-50 p-4 rounded-xl flex items-center">
                            <i class="fas fa-snowflake text-orange-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-semibold">Cold Water</p>
                                <p class="text-sm text-gray-600">Splash face or hold ice cubes</p>
                            </div>
                        </div>
                        
                        <div class="bg-pink-50 p-4 rounded-xl flex items-center">
                            <i class="fas fa-music text-pink-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-semibold">Calming Music</p>
                                <p class="text-sm text-gray-600">Listen to your favorite relaxing song</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Tips Section -->
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">
                <i class="fas fa-lightbulb mr-2"></i>
                Daily Stress Management Tip
            </h3>
            <p id="dailyTip" class="text-lg mb-6">
                Start your day with 5 minutes of deep breathing to set a calm tone for the entire day.
            </p>
            <button onclick="getNewTip()" class="bg-white text-indigo-600 px-6 py-3 rounded-full font-semibold hover:bg-indigo-50 transition-colors">
                Get New Tip
            </button>
        </div>
    </div>

    <script>
        // Global variables
        let currentTimer = null;
        let pomodoroTimer = null;
        
        // Stress level assessment
        function updateStressLevel(value) {
            const stressValue = document.getElementById('stressValue');
            const recommendation = document.getElementById('stressRecommendation');
            
            let level, color, advice;
            
            if (value <= 3) {
                level = 'Low - Feeling Good';
                color = 'text-green-600';
                advice = 'Great! Maintain your current stress management practices. Consider preventive techniques like regular exercise and mindfulness.';
            } else if (value <= 6) {
                level = 'Moderate - Some Stress';
                color = 'text-yellow-600';
                advice = 'You\'re experiencing moderate stress. Try breathing exercises, short walks, or the 5-4-3-2-1 grounding technique.';
            } else if (value <= 8) {
                level = 'High - Significant Stress';
                color = 'text-orange-600';
                advice = 'High stress levels detected. Focus on immediate relief techniques like deep breathing, progressive muscle relaxation, or reach out to someone you trust.';
            } else {
                level = 'Very High - Overwhelming';
                color = 'text-red-600';
                advice = 'You\'re experiencing very high stress. Please prioritize self-care and consider speaking with a mental health professional. Use emergency breathing techniques now.';
            }
            
            stressValue.textContent = `${value} - ${level}`;
            stressValue.className = `font-bold ${color}`;
            recommendation.innerHTML = `<p class="text-blue-800">${advice}</p>`;
        }
        
        // Toggle technique content
        function toggleTechnique(techniqueId) {
            const content = document.getElementById(`${techniqueId}-content`);
            const isExpanded = content.classList.contains('technique-expanded');
            
            // Close all other techniques
            document.querySelectorAll('[id$="-content"]').forEach(el => {
                el.classList.remove('technique-expanded');
                el.classList.add('technique-collapsed');
            });
            
            // Toggle current technique
            if (!isExpanded) {
                content.classList.remove('technique-collapsed');
                content.classList.add('technique-expanded');
            }
        }
        
        // Timer functions
        function startTimer(minutes) {
            if (currentTimer) clearInterval(currentTimer);
            
            let timeLeft = minutes * 60;
            const display = document.getElementById('timer-display');
            
            function updateDisplay() {
                const mins = Math.floor(timeLeft / 60);
                const secs = timeLeft % 60;
                display.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(currentTimer);
                    display.textContent = 'Session Complete!';
                    showNotification('Meditation session complete! Well done.', 'success');
                }
                timeLeft--;
            }
            
            updateDisplay();
            currentTimer = setInterval(updateDisplay, 1000);
        }
        
        // Pomodoro timer
        function startPomodoro() {
            if (pomodoroTimer) clearInterval(pomodoroTimer);
            
            let timeLeft = 25 * 60; // 25 minutes
            const display = document.getElementById('pomodoro-timer');
            
            function updateDisplay() {
                const mins = Math.floor(timeLeft / 60);
                const secs = timeLeft % 60;
                display.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(pomodoroTimer);
                    display.textContent = 'Break Time!';
                    showNotification('Pomodoro complete! Take a 5-minute break.', 'success');
                }
                timeLeft--;
            }
            
            updateDisplay();
            pomodoroTimer = setInterval(updateDisplay, 1000);
        }
        
        // Emergency breathing guide
        function startEmergencyBreathing() {
            showNotification('Starting 4-7-8 breathing exercise. Follow the prompts.', 'info');
            
            let step = 0;
            const steps = ['Inhale for 4...', 'Hold for 7...', 'Exhale for 8...'];
            const durations = [4000, 7000, 8000];
            
            function nextStep() {
                if (step < steps.length) {
                    showNotification(steps[step], 'info');
                    setTimeout(nextStep, durations[step]);
                    step++;
                } else {
                    showNotification('Breathing exercise complete! Repeat as needed.', 'success');
                }
            }
            
            nextStep();
        }
        
        // Daily tips
        const dailyTips = [
            "Start your day with 5 minutes of deep breathing to set a calm tone.",
            "Take regular breaks every hour to stretch and reset your mind.",
            "Practice gratitude by writing down 3 things you're thankful for daily.",
            "Limit caffeine intake, especially in the afternoon and evening.",
            "Create a bedtime routine to improve sleep quality and reduce stress.",
            "Spend time in nature, even if it's just a few minutes outside.",
            "Practice the 'two-minute rule' - if something takes less than 2 minutes, do it now.",
            "Use positive self-talk to counter negative thoughts and stress.",
            "Stay hydrated throughout the day to maintain energy and focus.",
            "Connect with friends or family regularly for emotional support."
        ];
        
        function getNewTip() {
            const tipElement = document.getElementById('dailyTip');
            const randomTip = dailyTips[Math.floor(Math.random() * dailyTips.length)];
            tipElement.textContent = randomTip;
        }
        
        // Scroll to techniques
        function scrollToTechniques() {
            document.getElementById('techniques-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }
        
        // Notification system
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300`;
            
            // Set background color based on type
            switch(type) {
                case 'success':
                    notification.classList.add('bg-green-500');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500');
                    break;
                case 'warning':
                    notification.classList.add('bg-yellow-500');
                    break;
                default:
                    notification.classList.add('bg-blue-500');
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set random daily tip on load
            getNewTip();
            
            // Add smooth scrolling to all anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98ed580c048b0dcb',t:'MTc2MDUxMDYyNS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
