<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Resources - Comprehensive Support Materials</title>
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
        
        .resource-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .resource-card:hover {
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
        
        .crisis-banner {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.9; }
        }
        
        .category-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .emergency-card {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .support-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .therapy-card {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }
        
        .wellness-card {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .search-highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 4px;
        }
        
        .filter-active {
            background: #3b82f6;
            color: white;
        }
        
        .resource-item {
            transition: all 0.2s ease;
        }
        
        .resource-item:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        .availability-indicator {
            animation: blink 2s ease-in-out infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.5; }
        }
    </style>
</head>

<body class="gradient-bg min-h-full">
    <!-- Crisis Banner -->
    <div class="crisis-banner text-white py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center space-x-4">
                <i class="fas fa-exclamation-triangle text-xl"></i>
                <span class="font-semibold">Crisis Support Available 24/7</span>
                <span class="text-sm">•</span>
                <span class="text-sm">National Suicide Prevention Lifeline: 988</span>
                <span class="text-sm">•</span>
                <span class="text-sm">Crisis Text Line: Text HOME to 741741</span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-4">
                    <div class="floating">
                        <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-brain text-2xl text-purple-600"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Mental Health Resources</h1>
                        <p class="text-purple-100">Comprehensive support materials • 30+ Resources • 24/7 Access</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-white text-center">
                        <div class="availability-indicator h-3 w-3 bg-green-400 rounded-full mx-auto mb-1"></div>
                        <div class="text-xs">24/7 Available</div>
                    </div>
                    <button onclick="showEmergencyModal()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-all duration-200 font-semibold">
                        <i class="fas fa-phone mr-2"></i>Emergency Help
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Access Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="emergency-card rounded-2xl p-6 shadow-xl text-center cursor-pointer" onclick="showTab('crisis')">
                <i class="fas fa-phone-alt text-3xl mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Crisis Support</h3>
                <p class="text-sm opacity-90">Immediate help available 24/7</p>
            </div>
            
            <div class="support-card rounded-2xl p-6 shadow-xl text-center cursor-pointer" onclick="showTab('counseling')">
                <i class="fas fa-comments text-3xl mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Counseling Services</h3>
                <p class="text-sm opacity-90">Professional therapy options</p>
            </div>
            
            <div class="therapy-card rounded-2xl p-6 shadow-xl text-center cursor-pointer" onclick="showTab('selfhelp')">
                <i class="fas fa-heart text-3xl mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Self-Help Tools</h3>
                <p class="text-sm opacity-90">Coping strategies & techniques</p>
            </div>
            
            <div class="wellness-card rounded-2xl p-6 shadow-xl text-center cursor-pointer" onclick="showTab('wellness')">
                <i class="fas fa-leaf text-3xl mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Wellness Resources</h3>
                <p class="text-sm opacity-90">Preventive mental health care</p>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="resource-card rounded-2xl p-6 shadow-xl mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search resources, topics, or keywords..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               oninput="filterResources()">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterByCategory('all')" class="filter-btn filter-active px-4 py-2 rounded-lg transition-all duration-200" data-category="all">All</button>
                    <button onclick="filterByCategory('crisis')" class="filter-btn px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all duration-200" data-category="crisis">Crisis</button>
                    <button onclick="filterByCategory('therapy')" class="filter-btn px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all duration-200" data-category="therapy">Therapy</button>
                    <button onclick="filterByCategory('support')" class="filter-btn px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all duration-200" data-category="support">Support</button>
                    <button onclick="filterByCategory('wellness')" class="filter-btn px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all duration-200" data-category="wellness">Wellness</button>
                </div>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="resource-card rounded-2xl shadow-xl">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="showTab('crisis')" class="tab-button py-4 px-2 border-b-2 border-blue-500 text-blue-600 font-medium text-sm active" id="crisisTab">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Crisis Support
                    </button>
                    <button onclick="showTab('counseling')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="counselingTab">
                        <i class="fas fa-user-md mr-2"></i>Counseling
                    </button>
                    <button onclick="showTab('selfhelp')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="selfhelpTab">
                        <i class="fas fa-tools mr-2"></i>Self-Help
                    </button>
                    <button onclick="showTab('wellness')" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" id="wellnessTab">
                        <i class="fas fa-spa mr-2"></i>Wellness
                    </button>
                </nav>
            </div>

            <!-- Crisis Support Tab -->
            <div id="crisisContent" class="tab-content p-6">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Crisis Support Resources</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Immediate help is available 24/7. You are not alone, and support is just a call or text away.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="crisisResources">
                    <!-- Crisis resources will be populated here -->
                </div>
            </div>

            <!-- Counseling Tab -->
            <div id="counselingContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Professional Counseling Services</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Connect with licensed mental health professionals for ongoing support and therapy.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="counselingResources">
                    <!-- Counseling resources will be populated here -->
                </div>
            </div>

            <!-- Self-Help Tab -->
            <div id="selfhelpContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Self-Help Tools & Techniques</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Evidence-based strategies and tools you can use to support your mental health journey.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="selfhelpResources">
                    <!-- Self-help resources will be populated here -->
                </div>
            </div>

            <!-- Wellness Tab -->
            <div id="wellnessContent" class="tab-content p-6 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Wellness & Prevention Resources</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Proactive resources to maintain and improve your mental health and overall well-being.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="wellnessResources">
                    <!-- Wellness resources will be populated here -->
                </div>
            </div>
        </div>
    </main>

    <!-- Emergency Modal -->
    <div id="emergencyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="h-16 w-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Emergency Mental Health Support</h3>
                <div class="space-y-4 text-left">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-semibold text-red-800 mb-2">Immediate Crisis Support</h4>
                        <p class="text-red-700 text-sm mb-2">National Suicide Prevention Lifeline</p>
                        <p class="text-red-800 font-bold text-lg">988</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">Crisis Text Line</h4>
                        <p class="text-blue-700 text-sm mb-2">Text HOME to</p>
                        <p class="text-blue-800 font-bold text-lg">741741</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-800 mb-2">Emergency Services</h4>
                        <p class="text-green-700 text-sm mb-2">Call for immediate emergency</p>
                        <p class="text-green-800 font-bold text-lg">911</p>
                    </div>
                </div>
                <button onclick="closeEmergencyModal()" class="mt-6 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Resource Detail Modal -->
    <div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
            <div id="resourceModalContent">
                <!-- Resource details will be populated here -->
            </div>
            <button onclick="closeResourceModal()" class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-all duration-200">
                Close
            </button>
        </div>
    </div>

    <script>
        // Mental health resources data
        const resources = {
            crisis: [
                {
                    name: "National Suicide Prevention Lifeline",
                    description: "24/7 crisis support for people in suicidal crisis or emotional distress",
                    contact: "988",
                    availability: "24/7",
                    type: "Phone",
                    category: "crisis",
                    icon: "fas fa-phone",
                    details: "Free and confidential support for people in distress, prevention and crisis resources for you or your loved ones, and best practices for professionals."
                },
                {
                    name: "Crisis Text Line",
                    description: "Free, 24/7 support for those in crisis via text message",
                    contact: "Text HOME to 741741",
                    availability: "24/7",
                    type: "Text",
                    category: "crisis",
                    icon: "fas fa-comment",
                    details: "Crisis Text Line provides free, 24/7, confidential support via text message to people in crisis when they dial 741741."
                },
                {
                    name: "SAMHSA National Helpline",
                    description: "Treatment referral and information service",
                    contact: "1-800-662-4357",
                    availability: "24/7",
                    type: "Phone",
                    category: "crisis",
                    icon: "fas fa-info-circle",
                    details: "Free, confidential, 24/7 treatment referral and information service for individuals and families facing mental and/or substance use disorders."
                },
                {
                    name: "National Domestic Violence Hotline",
                    description: "Support for domestic violence survivors",
                    contact: "1-800-799-7233",
                    availability: "24/7",
                    type: "Phone",
                    category: "crisis",
                    icon: "fas fa-shield-alt",
                    details: "Confidential support from trained advocates who can provide crisis intervention, safety planning, information and referrals."
                },
                {
                    name: "Trans Lifeline",
                    description: "Crisis support for transgender people",
                    contact: "877-565-8860",
                    availability: "24/7",
                    type: "Phone",
                    category: "crisis",
                    icon: "fas fa-heart",
                    details: "Peer support service run by trans people, for trans and questioning callers."
                },
                {
                    name: "Veterans Crisis Line",
                    description: "Crisis support for veterans and their families",
                    contact: "1-800-273-8255",
                    availability: "24/7",
                    type: "Phone",
                    category: "crisis",
                    icon: "fas fa-flag-usa",
                    details: "Free, confidential support for Veterans in crisis and their families and friends."
                }
            ],
            therapy: [
                {
                    name: "BetterHelp",
                    description: "Online therapy platform with licensed therapists",
                    contact: "betterhelp.com",
                    availability: "Flexible",
                    type: "Online",
                    category: "therapy",
                    icon: "fas fa-laptop",
                    details: "Professional counseling done securely online. Get matched with a licensed therapist and start communicating in under 24 hours."
                },
                {
                    name: "Talkspace",
                    description: "Text-based therapy with licensed professionals",
                    contact: "talkspace.com",
                    availability: "Flexible",
                    type: "Online",
                    category: "therapy",
                    icon: "fas fa-comments",
                    details: "Online therapy that fits your schedule. Text, audio, and video messaging with licensed therapists."
                },
                {
                    name: "Psychology Today",
                    description: "Directory to find local therapists and counselors",
                    contact: "psychologytoday.com",
                    availability: "Varies",
                    type: "Directory",
                    category: "therapy",
                    icon: "fas fa-search",
                    details: "Find therapists, psychiatrists, support groups, and treatment centers in your area."
                },
                {
                    name: "Open Path Collective",
                    description: "Affordable therapy sessions ($30-$60)",
                    contact: "openpathcollective.org",
                    availability: "Varies",
                    type: "In-person/Online",
                    category: "therapy",
                    icon: "fas fa-dollar-sign",
                    details: "A nonprofit network of mental health professionals providing affordable therapy."
                },
                {
                    name: "NAMI Support Groups",
                    description: "Peer support groups for mental health",
                    contact: "nami.org",
                    availability: "Weekly",
                    type: "Group",
                    category: "therapy",
                    icon: "fas fa-users",
                    details: "Free support groups led by trained facilitators who have lived experience with mental health conditions."
                },
                {
                    name: "7 Cups",
                    description: "Free emotional support and counseling",
                    contact: "7cups.com",
                    availability: "24/7",
                    type: "Online",
                    category: "therapy",
                    icon: "fas fa-coffee",
                    details: "Free, anonymous, and confidential conversations with trained active listeners."
                }
            ],
            support: [
                {
                    name: "Mental Health America",
                    description: "Mental health advocacy and resources",
                    contact: "mhanational.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-globe",
                    details: "Leading community-based nonprofit dedicated to addressing the needs of those living with mental illness."
                },
                {
                    name: "NAMI (National Alliance on Mental Illness)",
                    description: "Education, support, and advocacy",
                    contact: "nami.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-hands-helping",
                    details: "The nation's largest grassroots mental health organization dedicated to building better lives."
                },
                {
                    name: "Depression and Bipolar Support Alliance",
                    description: "Peer support for mood disorders",
                    contact: "dbsalliance.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-balance-scale",
                    details: "Leading patient-directed organization focusing on mood disorders."
                },
                {
                    name: "Anxiety and Depression Association",
                    description: "Resources for anxiety and depression",
                    contact: "adaa.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-brain",
                    details: "Leading nonprofit organization dedicated to the prevention, treatment, and cure of anxiety, depression, and related disorders."
                },
                {
                    name: "International OCD Foundation",
                    description: "Support for OCD and related disorders",
                    contact: "iocdf.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-sync",
                    details: "Resources, support groups, and treatment information for OCD and related disorders."
                },
                {
                    name: "National Eating Disorders Association",
                    description: "Support for eating disorders",
                    contact: "nationaleatingdisorders.org",
                    availability: "Always",
                    type: "Website",
                    category: "support",
                    icon: "fas fa-utensils",
                    details: "Leading nonprofit organization supporting individuals and families affected by eating disorders."
                }
            ],
            wellness: [
                {
                    name: "Headspace",
                    description: "Meditation and mindfulness app",
                    contact: "headspace.com",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-brain",
                    details: "Guided meditation, sleep stories, and mindfulness exercises for mental wellness."
                },
                {
                    name: "Calm",
                    description: "Sleep, meditation, and relaxation app",
                    contact: "calm.com",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-leaf",
                    details: "Sleep stories, guided meditations, and relaxation techniques for better mental health."
                },
                {
                    name: "Insight Timer",
                    description: "Free meditation app with community",
                    contact: "insighttimer.com",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-clock",
                    details: "Free meditation app with thousands of guided meditations and a supportive community."
                },
                {
                    name: "Sanvello",
                    description: "Anxiety and mood tracking app",
                    contact: "sanvello.com",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-chart-line",
                    details: "Track your mood, practice coping techniques, and access professional support."
                },
                {
                    name: "MindShift",
                    description: "Anxiety management app",
                    contact: "anxietybc.com/mindshift",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-mobile-alt",
                    details: "Free app designed to help teens and young adults cope with anxiety."
                },
                {
                    name: "PTSD Coach",
                    description: "Self-management tool for PTSD symptoms",
                    contact: "ptsd.va.gov/apps",
                    availability: "Always",
                    type: "App",
                    category: "wellness",
                    icon: "fas fa-shield-alt",
                    details: "Provides tools to help manage symptoms of PTSD and connect with support."
                },
                {
                    name: "Rethink Mental Health",
                    description: "Mental health education and resources",
                    contact: "rethink.org",
                    availability: "Always",
                    type: "Website",
                    category: "wellness",
                    icon: "fas fa-lightbulb",
                    details: "Information, advice, and support for anyone affected by mental health problems."
                },
                {
                    name: "Mental Health First Aid",
                    description: "Training to help others in mental health crises",
                    contact: "mentalhealthfirstaid.org",
                    availability: "Scheduled",
                    type: "Training",
                    category: "wellness",
                    icon: "fas fa-first-aid",
                    details: "Learn how to identify, understand and respond to signs of mental illnesses and substance use disorders."
                }
            ]
        };

        let currentFilter = 'all';
        let allResources = [];

        // Initialize all resources array
        function initializeResources() {
            allResources = [
                ...resources.crisis,
                ...resources.therapy,
                ...resources.support,
                ...resources.wellness
            ];
        }

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

            // Load resources for the tab
            loadTabResources(tabName);
        }

        // Load resources for specific tab
        function loadTabResources(tabName) {
            let resourceList = [];
            
            switch(tabName) {
                case 'crisis':
                    resourceList = resources.crisis;
                    break;
                case 'counseling':
                    resourceList = resources.therapy;
                    break;
                case 'selfhelp':
                    resourceList = [...resources.support, ...resources.wellness.slice(0, 4)];
                    break;
                case 'wellness':
                    resourceList = resources.wellness;
                    break;
            }

            const container = document.getElementById(tabName === 'counseling' ? 'counselingResources' : 
                                                   tabName === 'selfhelp' ? 'selfhelpResources' : 
                                                   tabName + 'Resources');
            
            displayResources(resourceList, container);
        }

        // Display resources in container
        function displayResources(resourceList, container) {
            container.innerHTML = '';

            resourceList.forEach(resource => {
                const div = document.createElement('div');
                div.className = 'resource-item bg-white rounded-xl p-6 shadow-lg border border-gray-200 cursor-pointer';
                div.onclick = () => showResourceDetail(resource);
                
                const availabilityColor = resource.availability === '24/7' ? 'text-green-600' : 'text-blue-600';
                
                div.innerHTML = `
                    <div class="flex items-start justify-between mb-4">
                        <div class="category-icon h-12 w-12 rounded-full flex items-center justify-center">
                            <i class="${resource.icon} text-white text-xl"></i>
                        </div>
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">${resource.type}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">${resource.name}</h3>
                    <p class="text-gray-600 text-sm mb-4">${resource.description}</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                            <span class="${availabilityColor} font-medium">${resource.availability}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            <span class="text-gray-700">${resource.contact}</span>
                        </div>
                    </div>
                `;
                
                container.appendChild(div);
            });
        }

        // Show resource detail modal
        function showResourceDetail(resource) {
            const modal = document.getElementById('resourceModal');
            const content = document.getElementById('resourceModalContent');
            
            const availabilityColor = resource.availability === '24/7' ? 'text-green-600' : 'text-blue-600';
            
            content.innerHTML = `
                <div class="text-center mb-6">
                    <div class="category-icon h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="${resource.icon} text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">${resource.name}</h3>
                    <p class="text-gray-600">${resource.description}</p>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Contact Information</h4>
                        <p class="text-gray-700">${resource.contact}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Availability</h4>
                        <p class="${availabilityColor} font-medium">${resource.availability}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2">About This Resource</h4>
                        <p class="text-gray-700">${resource.details}</p>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close resource modal
        function closeResourceModal() {
            document.getElementById('resourceModal').classList.add('hidden');
            document.getElementById('resourceModal').classList.remove('flex');
        }

        // Show emergency modal
        function showEmergencyModal() {
            document.getElementById('emergencyModal').classList.remove('hidden');
            document.getElementById('emergencyModal').classList.add('flex');
        }

        // Close emergency modal
        function closeEmergencyModal() {
            document.getElementById('emergencyModal').classList.add('hidden');
            document.getElementById('emergencyModal').classList.remove('flex');
        }

        // Filter by category
        function filterByCategory(category) {
            currentFilter = category;
            
            // Update filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('filter-active');
                btn.classList.add('bg-gray-200', 'hover:bg-gray-300');
            });
            
            const activeBtn = document.querySelector(`[data-category="${category}"]`);
            activeBtn.classList.add('filter-active');
            activeBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300');
            
            filterResources();
        }

        // Filter resources based on search and category
        function filterResources() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            let filteredResources = allResources;
            
            // Filter by category
            if (currentFilter !== 'all') {
                filteredResources = filteredResources.filter(resource => 
                    resource.category === currentFilter
                );
            }
            
            // Filter by search term
            if (searchTerm) {
                filteredResources = filteredResources.filter(resource =>
                    resource.name.toLowerCase().includes(searchTerm) ||
                    resource.description.toLowerCase().includes(searchTerm) ||
                    resource.details.toLowerCase().includes(searchTerm)
                );
            }
            
            // Update current tab display
            const activeTab = document.querySelector('.tab-button.active').id.replace('Tab', '');
            const container = document.getElementById(activeTab === 'counseling' ? 'counselingResources' : 
                                                   activeTab === 'selfhelp' ? 'selfhelpResources' : 
                                                   activeTab + 'Resources');
            
            displayResources(filteredResources, container);
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            initializeResources();
            showTab('crisis');
            
            // Close modals when clicking outside
            document.getElementById('emergencyModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEmergencyModal();
                }
            });
            
            document.getElementById('resourceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeResourceModal();
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99201c0780540dc9',t:'MTc2MTA0Mjk0MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
