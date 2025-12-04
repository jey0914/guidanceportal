<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Stress Assessment Test</title>
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
        
        .question-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .option-button {
            transition: all 0.2s ease;
        }
        
        .option-button:hover {
            transform: scale(1.02);
        }
        
        .option-selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.02);
        }
        
        .progress-bar {
            transition: width 0.5s ease;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .stress-meter {
            background: linear-gradient(90deg, #10b981 0%, #f59e0b 50%, #ef4444 100%);
            height: 12px;
            border-radius: 6px;
        }
        
        .result-card {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .category-score {
            transition: all 0.3s ease;
        }
        
        .category-score:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-full">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-6xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="floating-element mb-6">
                    <i class="fas fa-brain text-5xl text-blue-300 opacity-80"></i>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Comprehensive Stress Test
                </h1>
                <p class="text-lg md:text-xl mb-8 text-indigo-100">
                    Discover your stress levels across different life areas and get personalized recommendations
                </p>
                
                <div class="flex flex-wrap justify-center gap-6 mb-8">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-300">25</div>
                        <div class="text-xs text-indigo-100">Questions</div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-green-300">5min</div>
                        <div class="text-xs text-indigo-100">Duration</div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-pink-300">Free</div>
                        <div class="text-xs text-indigo-100">Assessment</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Container -->
    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Progress Bar -->
        <div id="progress-container" class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-600">Progress</span>
                <span id="progress-text" class="text-sm font-medium text-indigo-600">0 / 25</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div id="progress-bar" class="progress-bar bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <!-- Welcome Screen -->
        <div id="welcome-screen" class="question-card bg-white bg-opacity-90 rounded-3xl p-8 shadow-lg text-center">
            <div class="mb-6">
                <i class="fas fa-clipboard-check text-6xl text-indigo-500 mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Ready to Begin?</h2>
                <p class="text-lg text-gray-600 mb-6">
                    This comprehensive assessment will evaluate your stress levels across 5 key areas:
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4 mb-8">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <i class="fas fa-briefcase text-blue-600 text-xl mb-2"></i>
                    <h3 class="font-semibold text-blue-800">Work & Career</h3>
                    <p class="text-sm text-blue-600">Job pressure, workload, satisfaction</p>
                </div>
                <div class="bg-green-50 p-4 rounded-xl">
                    <i class="fas fa-heart text-green-600 text-xl mb-2"></i>
                    <h3 class="font-semibold text-green-800">Relationships</h3>
                    <p class="text-sm text-green-600">Family, friends, social connections</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl mb-2"></i>
                    <h3 class="font-semibold text-purple-800">Financial</h3>
                    <p class="text-sm text-purple-600">Money management, security</p>
                </div>
                <div class="bg-orange-50 p-4 rounded-xl">
                    <i class="fas fa-running text-orange-600 text-xl mb-2"></i>
                    <h3 class="font-semibold text-orange-800">Health & Lifestyle</h3>
                    <p class="text-sm text-orange-600">Physical health, habits, wellness</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
                <p class="text-yellow-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Note:</strong> This assessment is for educational purposes and doesn't replace professional medical advice.
                </p>
            </div>
            
            <button onclick="startTest()" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-play mr-2"></i>
                Start Assessment
            </button>
        </div>

        <!-- Question Screen -->
        <div id="question-screen" class="hidden">
            <div class="question-card bg-white bg-opacity-90 rounded-3xl p-8 shadow-lg">
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span id="category-badge" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            Work & Career
                        </span>
                        <span id="question-number" class="text-gray-500 font-medium">Question 1 of 25</span>
                    </div>
                    
                    <h2 id="question-text" class="text-2xl font-bold text-gray-800 mb-6">
                        How often do you feel overwhelmed by your workload?
                    </h2>
                </div>
                
                <div id="options-container" class="space-y-3 mb-8">
                    <!-- Options will be populated by JavaScript -->
                </div>
                
                <div class="flex justify-between">
                    <button id="prev-btn" onclick="previousQuestion()" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Previous
                    </button>
                    
                    <button id="next-btn" onclick="nextQuestion()" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Next
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Screen -->
        <div id="results-screen" class="hidden">
            <div class="result-card bg-white bg-opacity-90 rounded-3xl p-8 shadow-lg">
                <div class="text-center mb-8">
                    <i class="fas fa-chart-pie text-6xl text-indigo-500 mb-4"></i>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Your Stress Assessment Results</h2>
                    <p class="text-lg text-gray-600">
                        Here's your comprehensive stress analysis across all life areas
                    </p>
                </div>
                
                <!-- Overall Score -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 mb-8 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Overall Stress Level</h3>
                    <div class="relative">
                        <div class="stress-meter mb-4"></div>
                        <div id="overall-score" class="text-4xl font-bold gradient-text mb-2">0</div>
                        <div id="overall-level" class="text-lg text-gray-600 font-medium">Calculating...</div>
                    </div>
                </div>
                
                <!-- Category Scores -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div id="work-score" class="category-score bg-blue-50 p-6 rounded-2xl">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-briefcase text-blue-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-blue-800">Work & Career</h4>
                        </div>
                        <div class="text-2xl font-bold text-blue-600 mb-2">0/20</div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-blue-600 mt-2">Low stress</p>
                    </div>
                    
                    <div id="relationship-score" class="category-score bg-green-50 p-6 rounded-2xl">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-heart text-green-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-green-800">Relationships</h4>
                        </div>
                        <div class="text-2xl font-bold text-green-600 mb-2">0/20</div>
                        <div class="w-full bg-green-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-green-600 mt-2">Low stress</p>
                    </div>
                    
                    <div id="financial-score" class="category-score bg-purple-50 p-6 rounded-2xl">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-purple-800">Financial</h4>
                        </div>
                        <div class="text-2xl font-bold text-purple-600 mb-2">0/20</div>
                        <div class="w-full bg-purple-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-purple-600 mt-2">Low stress</p>
                    </div>
                    
                    <div id="health-score" class="category-score bg-orange-50 p-6 rounded-2xl">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-running text-orange-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-orange-800">Health & Lifestyle</h4>
                        </div>
                        <div class="text-2xl font-bold text-orange-600 mb-2">0/20</div>
                        <div class="w-full bg-orange-200 rounded-full h-2">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-orange-600 mt-2">Low stress</p>
                    </div>
                </div>
                
                <!-- Recommendations -->
                <div id="recommendations" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Personalized Recommendations
                    </h3>
                    <div id="recommendations-content">
                        <!-- Recommendations will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-center gap-4">
                    <button onclick="retakeTest()" class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Retake Test
                    </button>
                    
                    <button onclick="saveResults()" class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Save Results
                    </button>
                    
                    <button onclick="shareResults()" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-share mr-2"></i>
                        Share Results
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Test data structure
        const testQuestions = [
            // Work & Career (5 questions)
            {
                category: 'work',
                categoryName: 'Work & Career',
                categoryColor: 'blue',
                question: 'How often do you feel overwhelmed by your workload?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Always', value: 4 }
                ]
            },
            {
                category: 'work',
                categoryName: 'Work & Career',
                categoryColor: 'blue',
                question: 'How satisfied are you with your current job or career path?',
                options: [
                    { text: 'Very satisfied', value: 0 },
                    { text: 'Satisfied', value: 1 },
                    { text: 'Neutral', value: 2 },
                    { text: 'Dissatisfied', value: 3 },
                    { text: 'Very dissatisfied', value: 4 }
                ]
            },
            {
                category: 'work',
                categoryName: 'Work & Career',
                categoryColor: 'blue',
                question: 'How often do you worry about job security or career advancement?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Constantly', value: 4 }
                ]
            },
            {
                category: 'work',
                categoryName: 'Work & Career',
                categoryColor: 'blue',
                question: 'How well do you maintain work-life balance?',
                options: [
                    { text: 'Excellent balance', value: 0 },
                    { text: 'Good balance', value: 1 },
                    { text: 'Fair balance', value: 2 },
                    { text: 'Poor balance', value: 3 },
                    { text: 'No balance at all', value: 4 }
                ]
            },
            {
                category: 'work',
                categoryName: 'Work & Career',
                categoryColor: 'blue',
                question: 'How often do you experience conflict with colleagues or supervisors?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Very often', value: 4 }
                ]
            },
            
            // Relationships (5 questions)
            {
                category: 'relationships',
                categoryName: 'Relationships',
                categoryColor: 'green',
                question: 'How satisfied are you with your close relationships?',
                options: [
                    { text: 'Very satisfied', value: 0 },
                    { text: 'Satisfied', value: 1 },
                    { text: 'Neutral', value: 2 },
                    { text: 'Dissatisfied', value: 3 },
                    { text: 'Very dissatisfied', value: 4 }
                ]
            },
            {
                category: 'relationships',
                categoryName: 'Relationships',
                categoryColor: 'green',
                question: 'How often do you feel lonely or isolated?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Always', value: 4 }
                ]
            },
            {
                category: 'relationships',
                categoryName: 'Relationships',
                categoryColor: 'green',
                question: 'How well do you communicate with family members?',
                options: [
                    { text: 'Very well', value: 0 },
                    { text: 'Well', value: 1 },
                    { text: 'Adequately', value: 2 },
                    { text: 'Poorly', value: 3 },
                    { text: 'Very poorly', value: 4 }
                ]
            },
            {
                category: 'relationships',
                categoryName: 'Relationships',
                categoryColor: 'green',
                question: 'How often do you have conflicts in your relationships?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Very often', value: 4 }
                ]
            },
            {
                category: 'relationships',
                categoryName: 'Relationships',
                categoryColor: 'green',
                question: 'How supported do you feel by your social network?',
                options: [
                    { text: 'Very supported', value: 0 },
                    { text: 'Supported', value: 1 },
                    { text: 'Somewhat supported', value: 2 },
                    { text: 'Unsupported', value: 3 },
                    { text: 'Very unsupported', value: 4 }
                ]
            },
            
            // Financial (5 questions)
            {
                category: 'financial',
                categoryName: 'Financial',
                categoryColor: 'purple',
                question: 'How often do you worry about your financial situation?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Constantly', value: 4 }
                ]
            },
            {
                category: 'financial',
                categoryName: 'Financial',
                categoryColor: 'purple',
                question: 'How comfortable are you with your current income level?',
                options: [
                    { text: 'Very comfortable', value: 0 },
                    { text: 'Comfortable', value: 1 },
                    { text: 'Getting by', value: 2 },
                    { text: 'Struggling', value: 3 },
                    { text: 'Very struggling', value: 4 }
                ]
            },
            {
                category: 'financial',
                categoryName: 'Financial',
                categoryColor: 'purple',
                question: 'How prepared do you feel for unexpected expenses?',
                options: [
                    { text: 'Very prepared', value: 0 },
                    { text: 'Prepared', value: 1 },
                    { text: 'Somewhat prepared', value: 2 },
                    { text: 'Unprepared', value: 3 },
                    { text: 'Very unprepared', value: 4 }
                ]
            },
            {
                category: 'financial',
                categoryName: 'Financial',
                categoryColor: 'purple',
                question: 'How confident are you about your retirement planning?',
                options: [
                    { text: 'Very confident', value: 0 },
                    { text: 'Confident', value: 1 },
                    { text: 'Somewhat confident', value: 2 },
                    { text: 'Not confident', value: 3 },
                    { text: 'Very worried', value: 4 }
                ]
            },
            {
                category: 'financial',
                categoryName: 'Financial',
                categoryColor: 'purple',
                question: 'How often do financial concerns affect your sleep or daily activities?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Always', value: 4 }
                ]
            },
            
            // Health & Lifestyle (5 questions)
            {
                category: 'health',
                categoryName: 'Health & Lifestyle',
                categoryColor: 'orange',
                question: 'How would you rate your overall physical health?',
                options: [
                    { text: 'Excellent', value: 0 },
                    { text: 'Good', value: 1 },
                    { text: 'Fair', value: 2 },
                    { text: 'Poor', value: 3 },
                    { text: 'Very poor', value: 4 }
                ]
            },
            {
                category: 'health',
                categoryName: 'Health & Lifestyle',
                categoryColor: 'orange',
                question: 'How often do you engage in regular physical exercise?',
                options: [
                    { text: 'Daily', value: 0 },
                    { text: 'Several times a week', value: 1 },
                    { text: 'Once a week', value: 2 },
                    { text: 'Rarely', value: 3 },
                    { text: 'Never', value: 4 }
                ]
            },
            {
                category: 'health',
                categoryName: 'Health & Lifestyle',
                categoryColor: 'orange',
                question: 'How well do you sleep at night?',
                options: [
                    { text: 'Very well', value: 0 },
                    { text: 'Well', value: 1 },
                    { text: 'Adequately', value: 2 },
                    { text: 'Poorly', value: 3 },
                    { text: 'Very poorly', value: 4 }
                ]
            },
            {
                category: 'health',
                categoryName: 'Health & Lifestyle',
                categoryColor: 'orange',
                question: 'How often do you feel physically tired or exhausted?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Always', value: 4 }
                ]
            },
            {
                category: 'health',
                categoryName: 'Health & Lifestyle',
                categoryColor: 'orange',
                question: 'How well do you manage your daily stress?',
                options: [
                    { text: 'Very well', value: 0 },
                    { text: 'Well', value: 1 },
                    { text: 'Adequately', value: 2 },
                    { text: 'Poorly', value: 3 },
                    { text: 'Very poorly', value: 4 }
                ]
            },
            
            // General Stress (5 questions)
            {
                category: 'general',
                categoryName: 'General Wellbeing',
                categoryColor: 'indigo',
                question: 'How often do you feel anxious or worried?',
                options: [
                    { text: 'Never', value: 0 },
                    { text: 'Rarely', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Often', value: 3 },
                    { text: 'Always', value: 4 }
                ]
            },
            {
                category: 'general',
                categoryName: 'General Wellbeing',
                categoryColor: 'indigo',
                question: 'How often do you feel like you have control over your life?',
                options: [
                    { text: 'Always', value: 0 },
                    { text: 'Often', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Rarely', value: 3 },
                    { text: 'Never', value: 4 }
                ]
            },
            {
                category: 'general',
                categoryName: 'General Wellbeing',
                categoryColor: 'indigo',
                question: 'How satisfied are you with your life overall?',
                options: [
                    { text: 'Very satisfied', value: 0 },
                    { text: 'Satisfied', value: 1 },
                    { text: 'Neutral', value: 2 },
                    { text: 'Dissatisfied', value: 3 },
                    { text: 'Very dissatisfied', value: 4 }
                ]
            },
            {
                category: 'general',
                categoryName: 'General Wellbeing',
                categoryColor: 'indigo',
                question: 'How often do you feel hopeful about the future?',
                options: [
                    { text: 'Always', value: 0 },
                    { text: 'Often', value: 1 },
                    { text: 'Sometimes', value: 2 },
                    { text: 'Rarely', value: 3 },
                    { text: 'Never', value: 4 }
                ]
            },
            {
                category: 'general',
                categoryName: 'General Wellbeing',
                categoryColor: 'indigo',
                question: 'How often do you take time for activities you enjoy?',
                options: [
                    { text: 'Daily', value: 0 },
                    { text: 'Several times a week', value: 1 },
                    { text: 'Once a week', value: 2 },
                    { text: 'Rarely', value: 3 },
                    { text: 'Never', value: 4 }
                ]
            }
        ];
        
        // Test state
        let currentQuestionIndex = 0;
        let answers = [];
        let testStarted = false;
        
        // Initialize test
        function startTest() {
            testStarted = true;
            currentQuestionIndex = 0;
            answers = [];
            
            document.getElementById('welcome-screen').classList.add('hidden');
            document.getElementById('question-screen').classList.remove('hidden');
            document.getElementById('progress-container').classList.remove('hidden');
            
            displayQuestion();
        }
        
        // Display current question
        function displayQuestion() {
            const question = testQuestions[currentQuestionIndex];
            const questionNumber = currentQuestionIndex + 1;
            
            // Update question info
            document.getElementById('question-number').textContent = `Question ${questionNumber} of ${testQuestions.length}`;
            document.getElementById('question-text').textContent = question.question;
            
            // Update category badge
            const badge = document.getElementById('category-badge');
            badge.textContent = question.categoryName;
            badge.className = `bg-${question.categoryColor}-100 text-${question.categoryColor}-800 px-3 py-1 rounded-full text-sm font-medium`;
            
            // Update progress
            const progress = (questionNumber / testQuestions.length) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = `${questionNumber} / ${testQuestions.length}`;
            
            // Display options
            const optionsContainer = document.getElementById('options-container');
            optionsContainer.innerHTML = '';
            
            question.options.forEach((option, index) => {
                const button = document.createElement('button');
                button.className = 'option-button w-full p-4 text-left bg-gray-50 hover:bg-gray-100 rounded-xl border-2 border-transparent transition-all duration-200';
                button.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 mr-4 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-transparent"></div>
                        </div>
                        <span class="font-medium">${option.text}</span>
                    </div>
                `;
                
                button.onclick = () => selectOption(index, button);
                optionsContainer.appendChild(button);
            });
            
            // Update navigation buttons
            document.getElementById('prev-btn').disabled = currentQuestionIndex === 0;
            document.getElementById('next-btn').disabled = true;
            
            // Pre-select if answer exists
            if (answers[currentQuestionIndex] !== undefined) {
                const selectedIndex = answers[currentQuestionIndex];
                const buttons = optionsContainer.children;
                selectOption(selectedIndex, buttons[selectedIndex]);
            }
        }
        
        // Select option
        function selectOption(index, button) {
            // Remove previous selection
            document.querySelectorAll('.option-button').forEach(btn => {
                btn.classList.remove('option-selected');
                btn.classList.add('bg-gray-50', 'hover:bg-gray-100');
                const circle = btn.querySelector('.w-3');
                circle.classList.remove('bg-indigo-600');
                circle.classList.add('bg-transparent');
            });
            
            // Add selection to clicked button
            button.classList.add('option-selected');
            button.classList.remove('bg-gray-50', 'hover:bg-gray-100');
            const circle = button.querySelector('.w-3');
            circle.classList.add('bg-white');
            circle.classList.remove('bg-transparent');
            
            // Store answer
            answers[currentQuestionIndex] = index;
            
            // Enable next button
            document.getElementById('next-btn').disabled = false;
        }
        
        // Navigate to next question
        function nextQuestion() {
            if (currentQuestionIndex < testQuestions.length - 1) {
                currentQuestionIndex++;
                displayQuestion();
            } else {
                showResults();
            }
        }
        
        // Navigate to previous question
        function previousQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                displayQuestion();
            }
        }
        
        // Calculate and show results
        function showResults() {
            document.getElementById('question-screen').classList.add('hidden');
            document.getElementById('progress-container').classList.add('hidden');
            document.getElementById('results-screen').classList.remove('hidden');
            
            // Calculate scores by category
            const scores = {
                work: 0,
                relationships: 0,
                financial: 0,
                health: 0,
                general: 0
            };
            
            // Calculate category scores
            testQuestions.forEach((question, index) => {
                const answerValue = testQuestions[index].options[answers[index]].value;
                scores[question.category] += answerValue;
            });
            
            // Calculate overall score
            const totalScore = Object.values(scores).reduce((sum, score) => sum + score, 0);
            const maxScore = testQuestions.length * 4; // 4 is max value per question
            const overallPercentage = Math.round((totalScore / maxScore) * 100);
            
            // Update overall score display
            document.getElementById('overall-score').textContent = `${overallPercentage}%`;
            
            // Determine overall stress level
            let overallLevel, levelColor;
            if (overallPercentage <= 25) {
                overallLevel = 'Low Stress - You\'re managing well!';
                levelColor = 'text-green-600';
            } else if (overallPercentage <= 50) {
                overallLevel = 'Moderate Stress - Some areas need attention';
                levelColor = 'text-yellow-600';
            } else if (overallPercentage <= 75) {
                overallLevel = 'High Stress - Consider stress management strategies';
                levelColor = 'text-orange-600';
            } else {
                overallLevel = 'Very High Stress - Seek professional support';
                levelColor = 'text-red-600';
            }
            
            document.getElementById('overall-level').textContent = overallLevel;
            document.getElementById('overall-level').className = `text-lg font-medium ${levelColor}`;
            
            // Update category scores
            updateCategoryScore('work', scores.work, 'blue');
            updateCategoryScore('relationships', scores.relationships, 'green');
            updateCategoryScore('financial', scores.financial, 'purple');
            updateCategoryScore('health', scores.health, 'orange');
            
            // Generate recommendations
            generateRecommendations(scores, overallPercentage);
        }
        
        // Update individual category score
        function updateCategoryScore(category, score, color) {
            const maxCategoryScore = 20; // 5 questions * 4 max points
            const percentage = Math.round((score / maxCategoryScore) * 100);
            
            const scoreElement = document.getElementById(`${category}-score`);
            const scoreText = scoreElement.querySelector('.text-2xl');
            const progressBar = scoreElement.querySelector(`.bg-${color}-600`);
            const levelText = scoreElement.querySelector('.text-sm');
            
            scoreText.textContent = `${score}/${maxCategoryScore}`;
            progressBar.style.width = `${percentage}%`;
            
            let level;
            if (percentage <= 25) level = 'Low stress';
            else if (percentage <= 50) level = 'Moderate stress';
            else if (percentage <= 75) level = 'High stress';
            else level = 'Very high stress';
            
            levelText.textContent = level;
        }
        
        // Generate personalized recommendations
        function generateRecommendations(scores, overallPercentage) {
            const recommendationsContainer = document.getElementById('recommendations-content');
            let recommendations = [];
            
            // Overall recommendations based on stress level
            if (overallPercentage > 75) {
                recommendations.push({
                    icon: 'fas fa-exclamation-triangle',
                    color: 'red',
                    title: 'Immediate Action Needed',
                    text: 'Your stress levels are very high. Consider speaking with a mental health professional, your doctor, or a counselor as soon as possible.'
                });
            }
            
            // Category-specific recommendations
            if (scores.work > 15) {
                recommendations.push({
                    icon: 'fas fa-briefcase',
                    color: 'blue',
                    title: 'Work Stress Management',
                    text: 'Consider discussing workload with your supervisor, setting better boundaries, or exploring time management techniques. Take regular breaks and practice saying no to non-essential tasks.'
                });
            }
            
            if (scores.relationships > 15) {
                recommendations.push({
                    icon: 'fas fa-heart',
                    color: 'green',
                    title: 'Relationship Support',
                    text: 'Focus on improving communication skills, spending quality time with loved ones, and consider couples or family counseling if conflicts persist. Join social groups to expand your support network.'
                });
            }
            
            if (scores.financial > 15) {
                recommendations.push({
                    icon: 'fas fa-dollar-sign',
                    color: 'purple',
                    title: 'Financial Wellness',
                    text: 'Create a budget, build an emergency fund, and consider speaking with a financial advisor. Look into debt management strategies and explore additional income sources if needed.'
                });
            }
            
            if (scores.health > 15) {
                recommendations.push({
                    icon: 'fas fa-running',
                    color: 'orange',
                    title: 'Health & Lifestyle',
                    text: 'Prioritize regular exercise, improve sleep hygiene, eat a balanced diet, and practice stress-reduction techniques like meditation or yoga. Schedule regular health check-ups.'
                });
            }
            
            if (scores.general > 15) {
                recommendations.push({
                    icon: 'fas fa-brain',
                    color: 'indigo',
                    title: 'Mental Wellbeing',
                    text: 'Practice mindfulness, engage in hobbies you enjoy, set realistic goals, and maintain a positive outlook. Consider therapy or counseling to develop better coping strategies.'
                });
            }
            
            // General recommendations for everyone
            recommendations.push({
                icon: 'fas fa-leaf',
                color: 'green',
                title: 'Daily Stress Management',
                text: 'Practice deep breathing exercises, maintain a regular sleep schedule, stay hydrated, limit caffeine, and make time for activities you enjoy. Remember that small daily changes can make a big difference.'
            });
            
            // Render recommendations
            recommendationsContainer.innerHTML = recommendations.map(rec => `
                <div class="bg-white p-4 rounded-xl mb-4 border-l-4 border-${rec.color}-400">
                    <div class="flex items-start">
                        <i class="${rec.icon} text-${rec.color}-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-bold text-${rec.color}-800 mb-2">${rec.title}</h4>
                            <p class="text-gray-700 text-sm">${rec.text}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        // Retake test
        function retakeTest() {
            currentQuestionIndex = 0;
            answers = [];
            
            document.getElementById('results-screen').classList.add('hidden');
            document.getElementById('welcome-screen').classList.remove('hidden');
            document.getElementById('progress-container').classList.add('hidden');
        }
        
        // Save results
        function saveResults() {
            const results = {
                date: new Date().toLocaleDateString(),
                overallScore: document.getElementById('overall-score').textContent,
                overallLevel: document.getElementById('overall-level').textContent,
                answers: answers
            };
            
            const dataStr = JSON.stringify(results, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `stress_assessment_${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            showNotification('Results saved successfully!', 'success');
        }
        
        // Share results
        function shareResults() {
            const overallScore = document.getElementById('overall-score').textContent;
            const overallLevel = document.getElementById('overall-level').textContent;
            
            const shareText = `I just completed a comprehensive stress assessment! My overall stress level is ${overallScore}. ${overallLevel}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'My Stress Assessment Results',
                    text: shareText,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(shareText).then(() => {
                    showNotification('Results copied to clipboard!', 'success');
                });
            }
        }
        
        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300`;
            
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
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98ed675b26920dcb',t:'MTc2MDUxMTI1Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
