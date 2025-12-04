<?php
// wellness_library.php
// Enhanced Wellness Library Page

// Enhanced articles with more detailed content
$articles = [
    [
        "id" => 1,
        "title" => "Stress Less, Smile More",
        "category" => "Mental Health",
        "excerpt" => "Feeling overwhelmed? Try 5-minute resets: deep breaths, stretches, and little breaks make a big difference!",
        "content" => "Stress is a natural part of student life, but it doesn't have to control you. Simple techniques like the 4-7-8 breathing method, progressive muscle relaxation, and mindful walking can significantly reduce stress levels. Remember, taking breaks isn't lazy—it's essential for your mental health and academic performance.",
        "readTime" => "3 min read",
        "tags" => ["stress", "breathing", "relaxation"],
        "icon" => "fas fa-heart",
        "color" => "from-pink-500 to-rose-500",
        "image" => "🧘‍♀️"
    ],
    [
        "id" => 2,
        "title" => "Study Smarter, Not Harder",
        "category" => "Study Tips",
        "excerpt" => "Instead of cramming, try the Pomodoro Technique: 25 mins focus, 5 mins break. Boost your productivity!",
        "content" => "The Pomodoro Technique revolutionizes how you approach studying. Work in focused 25-minute intervals followed by 5-minute breaks. After 4 cycles, take a longer 15-30 minute break. This method improves concentration, reduces mental fatigue, and makes large tasks feel manageable.",
        "readTime" => "4 min read",
        "tags" => ["productivity", "focus", "time-management"],
        "icon" => "fas fa-brain",
        "color" => "from-blue-500 to-indigo-500",
        "image" => "📚"
    ],
    [
        "id" => 3,
        "title" => "Mood Boosters",
        "category" => "Wellness",
        "excerpt" => "Music, short walks, or doodling can lift your mood instantly. Small joys matter!",
        "content" => "Your mood affects everything—your energy, focus, and relationships. Simple activities like listening to upbeat music, taking a 10-minute walk, practicing gratitude, or engaging in creative activities can instantly improve your emotional state. Keep a 'mood toolkit' of activities that work for you.",
        "readTime" => "2 min read",
        "tags" => ["happiness", "activities", "self-care"],
        "icon" => "fas fa-smile",
        "color" => "from-yellow-500 to-orange-500",
        "image" => "🌟"
    ],
    [
        "id" => 4,
        "title" => "Quick Self-Check",
        "category" => "Reflection",
        "excerpt" => "Take 2 minutes to answer: How's your stress today? Write down 3 things that made you smile this week.",
        "content" => "Regular self-reflection is crucial for mental wellness. Ask yourself: How am I feeling today? What's working well? What needs attention? Journaling, even for just 5 minutes daily, can help you process emotions, track patterns, and celebrate small wins.",
        "readTime" => "3 min read",
        "tags" => ["reflection", "journaling", "awareness"],
        "icon" => "fas fa-mirror",
        "color" => "from-purple-500 to-violet-500",
        "image" => "🪞"
    ],
    [
        "id" => 5,
        "title" => "Mindful Moments",
        "category" => "Mindfulness",
        "excerpt" => "Pause, breathe, notice your surroundings for 60 seconds. Little mindfulness exercises calm the mind.",
        "content" => "Mindfulness doesn't require hours of meditation. Try the 5-4-3-2-1 technique: notice 5 things you see, 4 you can touch, 3 you hear, 2 you smell, and 1 you taste. This grounds you in the present moment and reduces anxiety.",
        "readTime" => "2 min read",
        "tags" => ["mindfulness", "present-moment", "grounding"],
        "icon" => "fas fa-leaf",
        "color" => "from-green-500 to-emerald-500",
        "image" => "🍃"
    ],
    [
        "id" => 6,
        "title" => "Sleep Better Tonight",
        "category" => "Sleep Health",
        "excerpt" => "Quality sleep improves memory, mood, and focus. Create a bedtime routine that works for you.",
        "content" => "Good sleep is the foundation of wellness. Establish a consistent sleep schedule, create a relaxing bedtime routine, limit screen time before bed, and keep your room cool and dark. Your brain consolidates memories and processes emotions during sleep—don't skimp on it!",
        "readTime" => "4 min read",
        "tags" => ["sleep", "routine", "health"],
        "icon" => "fas fa-moon",
        "color" => "from-indigo-500 to-purple-500",
        "image" => "🌙"
    ],
    [
        "id" => 7,
        "title" => "Building Resilience",
        "category" => "Personal Growth",
        "excerpt" => "Bounce back stronger from challenges with these practical resilience-building strategies.",
        "content" => "Resilience is like a muscle—it gets stronger with practice. Focus on what you can control, maintain perspective during tough times, build strong relationships, and view challenges as opportunities to grow. Remember, setbacks are temporary, but the skills you develop are permanent.",
        "readTime" => "5 min read",
        "tags" => ["resilience", "growth", "challenges"],
        "icon" => "fas fa-mountain",
        "color" => "from-teal-500 to-cyan-500",
        "image" => "⛰️"
    ],
    [
        "id" => 8,
        "title" => "Social Connections",
        "category" => "Relationships",
        "excerpt" => "Strong relationships are key to happiness. Learn how to build and maintain meaningful connections.",
        "content" => "Humans are social beings, and quality relationships significantly impact mental health. Practice active listening, show genuine interest in others, be vulnerable when appropriate, and invest time in relationships that matter. Quality trumps quantity every time.",
        "readTime" => "3 min read",
        "tags" => ["relationships", "social", "connection"],
        "icon" => "fas fa-users",
        "color" => "from-rose-500 to-pink-500",
        "image" => "👥"
    ]
];

$categories = array_unique(array_column($articles, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Library - GuidanceHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .top-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 1rem 0;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .nav-logo {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .content {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="100%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
            opacity: 0.3;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }
        
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-input {
            width: 100%;
            padding: 1rem 1.5rem 1rem 3rem;
            border-radius: 50px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            font-size: 1.1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
        
        .filter-tabs {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem 0;
        }
        
        .filter-tab {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .filter-tab:hover, .filter-tab.active {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .article-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }
        
        .article-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .article-header {
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .article-emoji {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .article-category {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }
        
        .article-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        
        .article-excerpt {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .read-time {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .article-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            background: #f1f5f9;
            color: #64748b;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .read-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 2rem;
        }
        
        .modal-content {
            background: white;
            border-radius: 24px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .modal-header {
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .modal-body {
            padding: 2rem;
            line-height: 1.7;
            color: #374151;
        }
        
        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            background: #e2e8f0;
            transform: scale(1.1);
        }
        
        .stats-section {
            background: white;
            margin: 2rem;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1.5rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #64748b;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }
            
            .nav-links {
                gap: 1rem;
            }
            
            .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .articles-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            
            .hero-section {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Top Navigation -->
<nav class="top-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <div class="nav-logo">
                <i class="fas fa-book-heart text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Wellness Library</h2>
                <p class="text-sm text-purple-200">Your Mental Health Resource Center</p>
            </div>
        </div>
        
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="floating-elements">
            <div class="floating-element" style="left: 10%; animation-delay: 0s;">📚</div>
            <div class="floating-element" style="left: 20%; animation-delay: 2s;">🧘‍♀️</div>
            <div class="floating-element" style="left: 30%; animation-delay: 4s;">🌟</div>
            <div class="floating-element" style="left: 40%; animation-delay: 6s;">💡</div>
            <div class="floating-element" style="left: 50%; animation-delay: 8s;">🌱</div>
            <div class="floating-element" style="left: 60%; animation-delay: 10s;">❤️</div>
            <div class="floating-element" style="left: 70%; animation-delay: 12s;">🎯</div>
            <div class="floating-element" style="left: 80%; animation-delay: 14s;">✨</div>
        </div>
        
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <h1 class="text-5xl font-bold mb-4">📖 Wellness Library</h1>
            <p class="text-xl mb-8 opacity-90">Your personal collection of mental health resources, study tips, and wellness guides</p>
            
            <!-- Search Bar -->
            <div class="search-container mb-8">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" class="search-input" placeholder="Search articles, tips, or topics..." id="searchInput">
            </div>
            
            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterArticles('all')">All Articles</button>
                <?php foreach($categories as $category): ?>
                <button class="filter-tab" onclick="filterArticles('<?= strtolower(str_replace(' ', '-', $category)) ?>')"><?= $category ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <h3 class="text-2xl font-bold text-center mb-6 text-gray-800">📊 Your Wellness Journey</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number text-blue-600"><?= count($articles) ?></div>
                <div class="stat-label">Articles Available</div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-green-600"><?= count($categories) ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-purple-600">15</div>
                <div class="stat-label">Minutes Daily Reading</div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-orange-600">92%</div>
                <div class="stat-label">Student Satisfaction</div>
            </div>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="articles-grid" id="articlesGrid">
        <?php foreach($articles as $article): ?>
        <div class="article-card" data-category="<?= strtolower(str_replace(' ', '-', $article['category'])) ?>">
            <div class="article-header">
                <span class="article-emoji"><?= $article['image'] ?></span>
                <div class="article-category bg-gradient-to-r <?= $article['color'] ?>">
                    <i class="<?= $article['icon'] ?>"></i>
                    <?= $article['category'] ?>
                </div>
                <h3 class="article-title"><?= $article['title'] ?></h3>
                <p class="article-excerpt"><?= $article['excerpt'] ?></p>
                
                <div class="article-meta">
                    <div class="read-time">
                        <i class="fas fa-clock"></i>
                        <?= $article['readTime'] ?>
                    </div>
                </div>
                
                <div class="article-tags">
                    <?php foreach($article['tags'] as $tag): ?>
                    <span class="tag">#<?= $tag ?></span>
                    <?php endforeach; ?>
                </div>
                
                <a href="#" class="read-more-btn bg-gradient-to-r <?= $article['color'] ?>" onclick="openModal(<?= $article['id'] ?>)">
                    <i class="fas fa-book-open"></i>
                    Read Full Article
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Article Modal -->
<div class="modal" id="articleModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-header">
            <div id="modalCategory" class="article-category mb-4"></div>
            <h2 id="modalTitle" class="text-3xl font-bold text-gray-800"></h2>
            <div class="flex items-center gap-4 mt-4 text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock"></i>
                    <span id="modalReadTime"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>Popular Article</span>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p id="modalContent" class="text-lg leading-relaxed"></p>
            
            <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">💡 Quick Action Steps:</h4>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Try one technique from this article today</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Share what you learned with a friend</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Schedule time to practice regularly</span>
                    </li>
                </ul>
            </div>
            
            <div class="mt-6 text-center">
                <button onclick="closeModal()" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                    <i class="fas fa-heart mr-2"></i>
                    Thanks for Reading!
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const articles = <?= json_encode($articles) ?>;
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const articleCards = document.querySelectorAll('.article-card');
        
        articleCards.forEach(card => {
            const title = card.querySelector('.article-title').textContent.toLowerCase();
            const excerpt = card.querySelector('.article-excerpt').textContent.toLowerCase();
            const tags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
            
            const matches = title.includes(searchTerm) || 
                          excerpt.includes(searchTerm) || 
                          tags.some(tag => tag.includes(searchTerm));
            
            card.style.display = matches ? 'block' : 'none';
        });
    });
    
    // Filter functionality
    function filterArticles(category) {
        const articleCards = document.querySelectorAll('.article-card');
        const filterTabs = document.querySelectorAll('.filter-tab');
        
        // Update active tab
        filterTabs.forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filter articles
        articleCards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.5s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Modal functionality
    function openModal(articleId) {
        const article = articles.find(a => a.id === articleId);
        if (!article) return;
        
        document.getElementById('modalCategory').textContent = article.category;
        document.getElementById('modalCategory').className = `article-category bg-gradient-to-r ${article.color}`;
        document.getElementById('modalTitle').textContent = article.title;
        document.getElementById('modalReadTime').textContent = article.readTime;
        document.getElementById('modalContent').textContent = article.content;
        
        document.getElementById('articleModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('articleModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    document.getElementById('articleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    // Add fade-in animation to articles
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all article cards
    document.querySelectorAll('.article-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'989dedbfd20e25e5',t:'MTc1OTY3Nzg5Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
