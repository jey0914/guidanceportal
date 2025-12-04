<?php
session_start();
include("db.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Self-Care Planner - Student Wellness</title>
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
        
        .wellness-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .wellness-card:hover {
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
        
        .activity-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .activity-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }
        
        .activity-item.completed {
            background: rgba(34, 197, 94, 0.1);
            border-left: 4px solid #22c55e;
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            stroke-dasharray: 251;
            stroke-dashoffset: 251;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .streak-badge {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .calendar-day {
            min-height: 100px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .calendar-day:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        .calendar-day.has-activities {
            background: rgba(34, 197, 94, 0.1);
        }
        
        .drag-over {
            background: rgba(59, 130, 246, 0.2) !important;
            border: 2px dashed #3b82f6 !important;
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
                            <i class="fas fa-heart text-xl text-purple-600"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Self-Care Planner</h1>
                        <p class="text-purple-100 text-sm">Personalized wellness activities and custom plans</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-white text-center">
                        <div class="text-lg font-bold" id="wellnessScore">0</div>
                        <div class="text-xs">Wellness Score</div>
                    </div>
                    <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-user"></i>
                        <span>Student Portal</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Today's Progress -->
            <div class="wellness-card rounded-2xl p-6 shadow-xl text-center">
                <div class="relative w-20 h-20 mx-auto mb-4">
                    <svg class="w-full h-full progress-ring">
                        <circle class="progress-ring-circle" stroke="#3b82f6" stroke-width="4" fill="transparent" r="40" cx="50%" cy="50%" id="todayProgress"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-bold text-blue-600" id="todayPercentage">0%</span>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-800">Today's Progress</h3>
                <p class="text-sm text-gray-600" id="todayActivities">0/0 activities</p>
            </div>

            <!-- Current Streak -->
            <div class="wellness-card rounded-2xl p-6 shadow-xl text-center">
                <div class="streak-badge h-16 w-16 bg-gradient-to-r from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-fire text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Current Streak</h3>
                <p class="text-2xl font-bold text-orange-500" id="currentStreak">0</p>
                <p class="text-sm text-gray-600">days</p>
            </div>

            <!-- This Week -->
            <div class="wellness-card rounded-2xl p-6 shadow-xl text-center">
                <div class="h-16 w-16 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-week text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-800">This Week</h3>
                <p class="text-2xl font-bold text-green-500" id="weeklyActivities">0</p>
                <p class="text-sm text-gray-600">activities completed</p>
            </div>

            <!-- Achievements -->
            <div class="wellness-card rounded-2xl p-6 shadow-xl text-center">
                <div class="h-16 w-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Achievements</h3>
                <p class="text-2xl font-bold text-purple-500" id="totalBadges">0</p>
                <p class="text-sm text-gray-600">badges earned</p>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="wellness-card rounded-2xl shadow-xl mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="showTab('assessment')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm active" id="assessmentTab">
                        <i class="fas fa-clipboard-list mr-2"></i>Assessment
                    </button>
                    <button onclick="showTab('tracker')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="trackerTab">
                        <i class="fas fa-check-circle mr-2"></i>Daily Tracker
                    </button>
                    <button onclick="showTab('planner')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="plannerTab">
                        <i class="fas fa-calendar-alt mr-2"></i>Weekly Planner
                    </button>
                    <button onclick="showTab('activities')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="activitiesTab">
                        <i class="fas fa-list mr-2"></i>Activity Library
                    </button>
                </nav>
            </div>

            <!-- Assessment Tab -->
            <div id="assessmentContent" class="tab-content p-6">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Wellness Assessment</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Answer a few questions to get personalized self-care recommendations tailored to your current needs and lifestyle.</p>
                </div>

                <div class="max-w-2xl mx-auto">
                    <div class="space-y-6" id="assessmentQuestions">
                        <!-- Question 1 -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">How would you rate your current stress level?</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="stress" value="low" class="mr-3">
                                    <span>Low - I feel calm and in control</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stress" value="moderate" class="mr-3">
                                    <span>Moderate - Some stress but manageable</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stress" value="high" class="mr-3">
                                    <span>High - Feeling overwhelmed frequently</span>
                                </label>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">How many hours of sleep do you typically get?</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="sleep" value="insufficient" class="mr-3">
                                    <span>Less than 6 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="sleep" value="adequate" class="mr-3">
                                    <span>6-8 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="sleep" value="optimal" class="mr-3">
                                    <span>8+ hours</span>
                                </label>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">How often do you engage in physical activity?</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="exercise" value="rarely" class="mr-3">
                                    <span>Rarely or never</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="exercise" value="sometimes" class="mr-3">
                                    <span>1-2 times per week</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="exercise" value="regular" class="mr-3">
                                    <span>3+ times per week</span>
                                </label>
                            </div>
                        </div>

                        <!-- Question 4 -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">How connected do you feel to friends and family?</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="social" value="isolated" class="mr-3">
                                    <span>Often feel lonely or isolated</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="social" value="moderate" class="mr-3">
                                    <span>Sometimes feel connected</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="social" value="connected" class="mr-3">
                                    <span>Feel well-connected and supported</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <button onclick="generateRecommendations()" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-8 py-3 rounded-lg transition-all duration-200 font-semibold">
                            Get My Personalized Plan
                        </button>
                    </div>

                    <div id="recommendations" class="mt-8 hidden">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Your Personalized Recommendations</h3>
                        <div id="recommendationsList" class="space-y-4">
                            <!-- Recommendations will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Tracker Tab -->
            <div id="trackerContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daily Self-Care Tracker</h2>
                    <p class="text-gray-600">Track your daily wellness activities and build healthy habits</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Today's Activities -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Activities</h3>
                        <div class="space-y-3" id="todayActivitiesList">
                            <!-- Activities will be populated here -->
                        </div>
                        
                        <button onclick="addCustomActivity()" class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>Add Custom Activity
                        </button>
                    </div>

                    <!-- Progress Overview -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Overview</h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Physical Wellness</span>
                                <span class="text-sm text-gray-600" id="physicalProgress">0/3</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="physicalBar"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Mental Wellness</span>
                                <span class="text-sm text-gray-600" id="mentalProgress">0/3</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="mentalBar"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Emotional Wellness</span>
                                <span class="text-sm text-gray-600" id="emotionalProgress">0/3</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="emotionalBar"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Social Wellness</span>
                                <span class="text-sm text-gray-600" id="socialProgress">0/3</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="socialBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Planner Tab -->
            <div id="plannerContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Weekly Self-Care Planner</h2>
                    <p class="text-gray-600">Drag activities from the library to plan your week</p>
                </div>

                <div class="grid grid-cols-7 gap-2 mb-6">
                    <div class="text-center font-semibold text-gray-700 p-2">Sun</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Mon</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Tue</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Wed</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Thu</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Fri</div>
                    <div class="text-center font-semibold text-gray-700 p-2">Sat</div>
                </div>

                <div class="grid grid-cols-7 gap-2" id="weeklyCalendar">
                    <!-- Calendar days will be populated here -->
                </div>
            </div>

            <!-- Activity Library Tab -->
            <div id="activitiesContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Self-Care Activity Library</h2>
                    <p class="text-gray-600">25+ wellness activities organized by category</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Physical Wellness -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-orange-400 to-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-dumbbell text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Physical Wellness</h3>
                        </div>
                        <div class="space-y-2" id="physicalActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>

                    <!-- Mental Wellness -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-brain text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Mental Wellness</h3>
                        </div>
                        <div class="space-y-2" id="mentalActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>

                    <!-- Emotional Wellness -->
                    <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-6 border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-heart text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Emotional Wellness</h3>
                        </div>
                        <div class="space-y-2" id="emotionalActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>

                    <!-- Social Wellness -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Social Wellness</h3>
                        </div>
                        <div class="space-y-2" id="socialActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>

                    <!-- Creative Wellness -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-palette text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Creative Wellness</h3>
                        </div>
                        <div class="space-y-2" id="creativeActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>

                    <!-- Spiritual Wellness -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-om text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Spiritual Wellness</h3>
                        </div>
                        <div class="space-y-2" id="spiritualActivities">
                            <!-- Activities will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Activity Modal -->
    <div id="addActivityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Add Custom Activity</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity Name</label>
                    <input type="text" id="customActivityName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Take a nature walk">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="customActivityCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="physical">Physical Wellness</option>
                        <option value="mental">Mental Wellness</option>
                        <option value="emotional">Emotional Wellness</option>
                        <option value="social">Social Wellness</option>
                        <option value="creative">Creative Wellness</option>
                        <option value="spiritual">Spiritual Wellness</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" id="customActivityDuration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="15" min="1" max="180">
                </div>
            </div>
            <div class="flex space-x-4 mt-6">
                <button onclick="closeAddActivityModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-all duration-200">
                    Cancel
                </button>
                <button onclick="saveCustomActivity()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-all duration-200">
                    Add Activity
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data storage
        let activities = {
            physical: [
                { name: '10-minute walk', duration: 10, icon: 'fas fa-walking' },
                { name: 'Stretching routine', duration: 15, icon: 'fas fa-child' },
                { name: 'Yoga session', duration: 30, icon: 'fas fa-praying-hands' },
                { name: 'Dance break', duration: 10, icon: 'fas fa-music' },
                { name: 'Drink water', duration: 1, icon: 'fas fa-tint' },
                { name: 'Healthy snack', duration: 5, icon: 'fas fa-apple-alt' },
                { name: 'Posture check', duration: 2, icon: 'fas fa-user-check' }
            ],
            mental: [
                { name: 'Meditation', duration: 15, icon: 'fas fa-om' },
                { name: 'Reading', duration: 30, icon: 'fas fa-book' },
                { name: 'Puzzle solving', duration: 20, icon: 'fas fa-puzzle-piece' },
                { name: 'Learning something new', duration: 25, icon: 'fas fa-graduation-cap' },
                { name: 'Mindful breathing', duration: 5, icon: 'fas fa-wind' },
                { name: 'Brain training games', duration: 15, icon: 'fas fa-gamepad' }
            ],
            emotional: [
                { name: 'Gratitude journaling', duration: 10, icon: 'fas fa-pen' },
                { name: 'Mood check-in', duration: 5, icon: 'fas fa-smile' },
                { name: 'Positive affirmations', duration: 5, icon: 'fas fa-heart' },
                { name: 'Emotional release', duration: 15, icon: 'fas fa-cloud-rain' },
                { name: 'Self-compassion practice', duration: 10, icon: 'fas fa-hands-helping' },
                { name: 'Mindful observation', duration: 10, icon: 'fas fa-eye' }
            ],
            social: [
                { name: 'Call a friend', duration: 20, icon: 'fas fa-phone' },
                { name: 'Send a kind message', duration: 5, icon: 'fas fa-comment-heart' },
                { name: 'Join a group activity', duration: 60, icon: 'fas fa-users' },
                { name: 'Practice active listening', duration: 15, icon: 'fas fa-ear-listen' },
                { name: 'Express appreciation', duration: 5, icon: 'fas fa-thumbs-up' },
                { name: 'Social media break', duration: 30, icon: 'fas fa-mobile-alt' }
            ],
            creative: [
                { name: 'Draw or sketch', duration: 20, icon: 'fas fa-pencil-alt' },
                { name: 'Write creatively', duration: 25, icon: 'fas fa-feather-alt' },
                { name: 'Listen to music', duration: 15, icon: 'fas fa-headphones' },
                { name: 'Craft project', duration: 45, icon: 'fas fa-cut' },
                { name: 'Photography', duration: 30, icon: 'fas fa-camera' },
                { name: 'Sing or hum', duration: 10, icon: 'fas fa-microphone' }
            ],
            spiritual: [
                { name: 'Reflection time', duration: 15, icon: 'fas fa-mountain' },
                { name: 'Nature connection', duration: 20, icon: 'fas fa-leaf' },
                { name: 'Prayer or meditation', duration: 15, icon: 'fas fa-praying-hands' },
                { name: 'Values reflection', duration: 10, icon: 'fas fa-compass' },
                { name: 'Mindful eating', duration: 20, icon: 'fas fa-utensils' },
                { name: 'Spiritual reading', duration: 25, icon: 'fas fa-book-open' }
            ]
        };

        let dailyProgress = JSON.parse(localStorage.getItem('dailyProgress') || '{}');
        let weeklyPlan = JSON.parse(localStorage.getItem('weeklyPlan') || '{}');
        let streakData = JSON.parse(localStorage.getItem('streakData') || '{"current": 0, "longest": 0, "lastDate": null}');

        // Tab management
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + 'Content').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + 'Tab');
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');

            // Initialize tab-specific content
            if (tabName === 'tracker') {
                initializeDailyTracker();
            } else if (tabName === 'planner') {
                initializeWeeklyPlanner();
            } else if (tabName === 'activities') {
                initializeActivityLibrary();
            }
        }

        // Assessment functions
        function generateRecommendations() {
            const stress = document.querySelector('input[name="stress"]:checked')?.value;
            const sleep = document.querySelector('input[name="sleep"]:checked')?.value;
            const exercise = document.querySelector('input[name="exercise"]:checked')?.value;
            const social = document.querySelector('input[name="social"]:checked')?.value;

            if (!stress || !sleep || !exercise || !social) {
                alert('Please answer all questions to get your personalized recommendations.');
                return;
            }

            const recommendations = [];

            // Generate recommendations based on answers
            if (stress === 'high') {
                recommendations.push({
                    category: 'Stress Management',
                    activities: ['Meditation', 'Mindful breathing', 'Yoga session', 'Nature connection'],
                    priority: 'High Priority'
                });
            }

            if (sleep === 'insufficient') {
                recommendations.push({
                    category: 'Sleep Hygiene',
                    activities: ['Evening routine', 'Digital detox before bed', 'Relaxation exercises'],
                    priority: 'High Priority'
                });
            }

            if (exercise === 'rarely') {
                recommendations.push({
                    category: 'Physical Activity',
                    activities: ['10-minute walk', 'Stretching routine', 'Dance break'],
                    priority: 'Medium Priority'
                });
            }

            if (social === 'isolated') {
                recommendations.push({
                    category: 'Social Connection',
                    activities: ['Call a friend', 'Join a group activity', 'Send a kind message'],
                    priority: 'High Priority'
                });
            }

            // Always include general wellness
            recommendations.push({
                category: 'General Wellness',
                activities: ['Gratitude journaling', 'Drink water', 'Positive affirmations'],
                priority: 'Daily Practice'
            });

            displayRecommendations(recommendations);
        }

        function displayRecommendations(recommendations) {
            const container = document.getElementById('recommendationsList');
            container.innerHTML = '';

            recommendations.forEach(rec => {
                const div = document.createElement('div');
                div.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4';
                div.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold text-gray-800">${rec.category}</h4>
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">${rec.priority}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        ${rec.activities.map(activity => 
                            `<span class="text-sm bg-white px-2 py-1 rounded border">${activity}</span>`
                        ).join('')}
                    </div>
                `;
                container.appendChild(div);
            });

            document.getElementById('recommendations').classList.remove('hidden');
        }

        // Daily tracker functions
        function initializeDailyTracker() {
            const today = new Date().toDateString();
            if (!dailyProgress[today]) {
                dailyProgress[today] = {
                    physical: [],
                    mental: [],
                    emotional: [],
                    social: [],
                    creative: [],
                    spiritual: []
                };
            }

            updateTodayActivitiesList();
            updateProgressBars();
            updateStats();
        }

        function updateTodayActivitiesList() {
            const container = document.getElementById('todayActivitiesList');
            container.innerHTML = '';

            // Add sample activities for each category
            const sampleActivities = [
                { name: '10-minute walk', category: 'physical', icon: 'fas fa-walking' },
                { name: 'Meditation', category: 'mental', icon: 'fas fa-om' },
                { name: 'Gratitude journaling', category: 'emotional', icon: 'fas fa-pen' },
                { name: 'Call a friend', category: 'social', icon: 'fas fa-phone' },
                { name: 'Draw or sketch', category: 'creative', icon: 'fas fa-pencil-alt' },
                { name: 'Reflection time', category: 'spiritual', icon: 'fas fa-mountain' }
            ];

            const today = new Date().toDateString();
            const todayProgress = dailyProgress[today] || {};

            sampleActivities.forEach(activity => {
                const isCompleted = todayProgress[activity.category]?.includes(activity.name);
                const div = document.createElement('div');
                div.className = `activity-item p-3 rounded-lg border ${isCompleted ? 'completed' : 'bg-white'}`;
                div.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="${activity.icon} text-gray-600 mr-3"></i>
                            <span class="font-medium">${activity.name}</span>
                        </div>
                        <button onclick="toggleActivity('${activity.name}', '${activity.category}')" 
                                class="w-6 h-6 rounded-full border-2 ${isCompleted ? 'bg-green-500 border-green-500' : 'border-gray-300'} flex items-center justify-center">
                            ${isCompleted ? '<i class="fas fa-check text-white text-xs"></i>' : ''}
                        </button>
                    </div>
                `;
                container.appendChild(div);
            });
        }

        function toggleActivity(activityName, category) {
            const today = new Date().toDateString();
            if (!dailyProgress[today]) {
                dailyProgress[today] = { physical: [], mental: [], emotional: [], social: [], creative: [], spiritual: [] };
            }

            const categoryActivities = dailyProgress[today][category] || [];
            const index = categoryActivities.indexOf(activityName);

            if (index > -1) {
                categoryActivities.splice(index, 1);
            } else {
                categoryActivities.push(activityName);
            }

            dailyProgress[today][category] = categoryActivities;
            localStorage.setItem('dailyProgress', JSON.stringify(dailyProgress));

            updateTodayActivitiesList();
            updateProgressBars();
            updateStats();
            updateStreak();
        }

        function updateProgressBars() {
            const today = new Date().toDateString();
            const todayProgress = dailyProgress[today] || {};

            const categories = ['physical', 'mental', 'emotional', 'social'];
            categories.forEach(category => {
                const completed = (todayProgress[category] || []).length;
                const total = 3; // Target activities per category
                const percentage = Math.min((completed / total) * 100, 100);

                document.getElementById(category + 'Progress').textContent = `${completed}/${total}`;
                document.getElementById(category + 'Bar').style.width = percentage + '%';
            });
        }

        function updateStats() {
            const today = new Date().toDateString();
            const todayProgress = dailyProgress[today] || {};

            // Today's progress
            let totalCompleted = 0;
            let totalTarget = 12; // 3 per category × 4 categories

            Object.values(todayProgress).forEach(categoryActivities => {
                totalCompleted += (categoryActivities || []).length;
            });

            const todayPercentage = Math.round((totalCompleted / totalTarget) * 100);
            document.getElementById('todayPercentage').textContent = todayPercentage + '%';
            document.getElementById('todayActivities').textContent = `${totalCompleted}/${totalTarget} activities`;

            // Update progress ring
            const circumference = 2 * Math.PI * 40;
            const offset = circumference - (todayPercentage / 100) * circumference;
            document.getElementById('todayProgress').style.strokeDashoffset = offset;

            // Weekly activities
            const weekStart = new Date();
            weekStart.setDate(weekStart.getDate() - weekStart.getDay());
            let weeklyTotal = 0;

            for (let i = 0; i < 7; i++) {
                const date = new Date(weekStart);
                date.setDate(date.getDate() + i);
                const dateString = date.toDateString();
                
                if (dailyProgress[dateString]) {
                    Object.values(dailyProgress[dateString]).forEach(categoryActivities => {
                        weeklyTotal += (categoryActivities || []).length;
                    });
                }
            }

            document.getElementById('weeklyActivities').textContent = weeklyTotal;

            // Wellness score (simplified calculation)
            const wellnessScore = Math.min(Math.round((weeklyTotal / 7) * 10), 100);
            document.getElementById('wellnessScore').textContent = wellnessScore;
        }

        function updateStreak() {
            const today = new Date().toDateString();
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            const yesterdayString = yesterday.toDateString();

            const todayProgress = dailyProgress[today] || {};
            let todayCompleted = 0;
            Object.values(todayProgress).forEach(categoryActivities => {
                todayCompleted += (categoryActivities || []).length;
            });

            if (todayCompleted >= 3) { // Minimum activities for streak
                if (streakData.lastDate === yesterdayString || streakData.lastDate === today) {
                    if (streakData.lastDate !== today) {
                        streakData.current += 1;
                        streakData.lastDate = today;
                    }
                } else {
                    streakData.current = 1;
                    streakData.lastDate = today;
                }
                
                streakData.longest = Math.max(streakData.longest, streakData.current);
            }

            document.getElementById('currentStreak').textContent = streakData.current;
            localStorage.setItem('streakData', JSON.stringify(streakData));
        }

        // Weekly planner functions
        function initializeWeeklyPlanner() {
            const calendar = document.getElementById('weeklyCalendar');
            calendar.innerHTML = '';

            const today = new Date();
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());

            for (let i = 0; i < 7; i++) {
                const date = new Date(startOfWeek);
                date.setDate(startOfWeek.getDate() + i);
                const dateString = date.toDateString();

                const dayDiv = document.createElement('div');
                dayDiv.className = 'calendar-day p-2 rounded-lg';
                dayDiv.setAttribute('data-date', dateString);
                dayDiv.innerHTML = `
                    <div class="text-sm font-medium text-gray-700 mb-2">${date.getDate()}</div>
                    <div class="planned-activities space-y-1" id="activities-${dateString}">
                        <!-- Planned activities will appear here -->
                    </div>
                `;

                // Add drag and drop functionality
                dayDiv.addEventListener('dragover', handleDragOver);
                dayDiv.addEventListener('drop', handleDrop);

                calendar.appendChild(dayDiv);
            }

            loadWeeklyPlan();
        }

        function loadWeeklyPlan() {
            Object.keys(weeklyPlan).forEach(date => {
                const container = document.getElementById(`activities-${date}`);
                if (container) {
                    container.innerHTML = '';
                    weeklyPlan[date].forEach(activity => {
                        const activityDiv = document.createElement('div');
                        activityDiv.className = 'text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded';
                        activityDiv.textContent = activity.name;
                        container.appendChild(activityDiv);
                    });
                }
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.currentTarget.classList.add('drag-over');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
            
            const activityData = JSON.parse(e.dataTransfer.getData('text/plain'));
            const date = e.currentTarget.getAttribute('data-date');
            
            if (!weeklyPlan[date]) {
                weeklyPlan[date] = [];
            }
            
            weeklyPlan[date].push(activityData);
            localStorage.setItem('weeklyPlan', JSON.stringify(weeklyPlan));
            
            loadWeeklyPlan();
        }

        // Activity library functions
        function initializeActivityLibrary() {
            Object.keys(activities).forEach(category => {
                const container = document.getElementById(category + 'Activities');
                container.innerHTML = '';

                activities[category].forEach(activity => {
                    const div = document.createElement('div');
                    div.className = 'activity-item p-2 rounded border bg-white cursor-move';
                    div.draggable = true;
                    div.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="${activity.icon} text-gray-600 mr-2"></i>
                                <span class="text-sm font-medium">${activity.name}</span>
                            </div>
                            <span class="text-xs text-gray-500">${activity.duration}min</span>
                        </div>
                    `;

                    div.addEventListener('dragstart', (e) => {
                        e.dataTransfer.setData('text/plain', JSON.stringify(activity));
                    });

                    container.appendChild(div);
                });
            });
        }

        // Custom activity functions
        function addCustomActivity() {
            document.getElementById('addActivityModal').classList.remove('hidden');
            document.getElementById('addActivityModal').classList.add('flex');
        }

        function closeAddActivityModal() {
            document.getElementById('addActivityModal').classList.add('hidden');
            document.getElementById('addActivityModal').classList.remove('flex');
            
            // Clear form
            document.getElementById('customActivityName').value = '';
            document.getElementById('customActivityDuration').value = '';
        }

        function saveCustomActivity() {
            const name = document.getElementById('customActivityName').value.trim();
            const category = document.getElementById('customActivityCategory').value;
            const duration = parseInt(document.getElementById('customActivityDuration').value);

            if (!name || !duration) {
                alert('Please fill in all fields.');
                return;
            }

            const newActivity = {
                name: name,
                duration: duration,
                icon: 'fas fa-star'
            };

            activities[category].push(newActivity);
            
            closeAddActivityModal();
            
            if (document.getElementById('activitiesContent').classList.contains('hidden') === false) {
                initializeActivityLibrary();
            }
            
            alert('Custom activity added successfully!');
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            showTab('assessment');
            updateStats();
            
            // Close modal when clicking outside
            document.getElementById('addActivityModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddActivityModal();
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99200e94c3130dc9',t:'MTc2MTA0MjM4OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
