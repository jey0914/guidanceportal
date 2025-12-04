<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Self-Care Ideas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .idea-card {
            transition: all 0.3s ease;
        }

        .idea-card:hover {
            transform: translateY(-4px);
        }

        .category-badge {
            transition: all 0.2s ease;
        }

        .category-badge:hover {
            transform: scale(1.05);
        }

        .category-badge.active {
            transform: scale(1.05);
        }

        .done-overlay {
            transition: all 0.3s ease;
        }

        .expand-btn {
            transition: transform 0.3s ease;
        }

        .expand-btn.rotated {
            transform: rotate(180deg);
        }

        .floating-hearts {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            animation: floatUp 1s ease-out;
            pointer-events: none;
        }

        @keyframes floatUp {
            0% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-50px);
            }
        }

        .sparkle {
            animation: sparkle 0.6s ease;
        }

        @keyframes sparkle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
    </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%;"><!-- Navigation -->
   <nav class="gradient-bg" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); padding: 1.5rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-heart" style="font-size: 1.75rem; color: white;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.75rem; font-weight: 700; color: white;">Self-Care Ideas</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; color: white; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s; background: rgba(255, 255, 255, 0.2);"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1200px; margin: 0 auto; padding: 2rem; background: #f9fafb; min-height: 100%;"><!-- Header Section -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center;">
     <div style="display: inline-block; padding: 1rem 2rem; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 9999px; margin-bottom: 1rem;"><i class="fas fa-spa" style="font-size: 2rem; color: white;"></i>
     </div>
     <h2 id="pageSubtitle" style="font-size: 1.25rem; font-weight: 500; color: #6b7280; margin: 0;">Nurture yourself with these wellness activities</h2>
    </div><!-- Category Filter -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center;"><button class="category-badge active" data-category="all" style="padding: 0.625rem 1.25rem; border: 2px solid #667eea; background: #667eea; color: white; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-th"></i> <span id="filterAll">All Ideas</span> </button> <button class="category-badge" data-category="physical" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-running"></i> Physical </button> <button class="category-badge" data-category="mental" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-brain"></i> Mental </button> <button class="category-badge" data-category="emotional" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-heart"></i> Emotional </button> <button class="category-badge" data-category="social" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-users"></i> Social </button> <button class="category-badge" data-category="creative" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-palette"></i> Creative </button> <button class="category-badge" data-category="spiritual" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-om"></i> Spiritual </button>
     </div>
    </div><!-- Ideas Grid -->
    <div id="ideasContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;"><!-- Ideas will be rendered here -->
    </div>
   </main>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Self-Care Ideas",
            page_subtitle: "Nurture yourself with these wellness activities",
            filter_all: "All Ideas",
            background_color: "#667eea",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#667eea",
            secondary_action_color: "#764ba2",
            font_family: "Inter",
            font_size: 16
        };

        const selfCareIdeas = [
            {
                id: 1,
                title: "Morning Stretch Routine",
                category: "physical",
                icon: "fa-user-ninja",
                difficulty: "Easy",
                duration: "10 min",
                description: "Start your day with gentle stretches to awaken your body and increase flexibility.",
                details: "Spend 10 minutes doing simple stretches: neck rolls, shoulder shrugs, cat-cow pose, forward fold, and side stretches. Move slowly and breathe deeply. This releases tension and boosts circulation."
            },
            {
                id: 2,
                title: "Nature Walk",
                category: "physical",
                icon: "fa-tree",
                difficulty: "Easy",
                duration: "20-30 min",
                description: "Take a mindful walk outside, connecting with nature and fresh air.",
                details: "Walk in a park, forest, or any green space. Leave your phone behind or on silent. Notice the colors, sounds, and smells around you. Let nature restore your energy and calm your mind."
            },
            {
                id: 3,
                title: "Brain Dump Journaling",
                category: "mental",
                icon: "fa-pen",
                difficulty: "Easy",
                duration: "15 min",
                description: "Write down all your thoughts without judgment to clear mental clutter.",
                details: "Set a timer for 15 minutes and write continuously. Don't worry about grammar or structure. Just let everything flow onto the page. This helps organize thoughts and reduces mental overwhelm."
            },
            {
                id: 4,
                title: "Learn Something New",
                category: "mental",
                icon: "fa-book-open",
                difficulty: "Medium",
                duration: "30 min",
                description: "Challenge your brain by learning about a topic that interests you.",
                details: "Watch an educational video, read an article, or try a new skill tutorial. Choose something purely for enjoyment, not productivity. Learning stimulates your mind and builds confidence."
            },
            {
                id: 5,
                title: "Gratitude Practice",
                category: "emotional",
                icon: "fa-heart",
                difficulty: "Easy",
                duration: "5 min",
                description: "List three things you're grateful for today.",
                details: "Write or mentally note three specific things you appreciate. Be detailed: instead of 'my friend,' say 'my friend's thoughtful text this morning.' This shifts focus to positive aspects of life."
            },
            {
                id: 6,
                title: "Emotional Check-In",
                category: "emotional",
                icon: "fa-smile",
                difficulty: "Medium",
                duration: "10 min",
                description: "Sit quietly and identify your current emotions without judgment.",
                details: "Find a quiet space. Ask yourself: 'How am I feeling right now?' Name the emotions (sad, anxious, content, frustrated). Accept them without trying to change them. This builds emotional awareness."
            },
            {
                id: 7,
                title: "Call a Loved One",
                category: "social",
                icon: "fa-phone",
                difficulty: "Easy",
                duration: "15-30 min",
                description: "Reach out to someone you care about for meaningful connection.",
                details: "Call a friend or family member you've been thinking about. Have a real conversation beyond small talk. Share how you're doing and listen to them. Connection is vital for wellbeing."
            },
            {
                id: 8,
                title: "Host a Simple Gathering",
                category: "social",
                icon: "fa-users",
                difficulty: "Medium",
                duration: "2-3 hours",
                description: "Invite friends over for coffee, tea, or a simple meal.",
                details: "Keep it low-pressure: order takeout or make something simple. The goal is connection, not perfection. Share stories, laugh, and enjoy each other's company. Social bonds are healing."
            },
            {
                id: 9,
                title: "Doodle or Color",
                category: "creative",
                icon: "fa-pen-fancy",
                difficulty: "Easy",
                duration: "15-20 min",
                description: "Express yourself through simple drawing or coloring.",
                details: "No artistic skill needed! Grab paper and colored pencils or markers. Doodle abstract shapes, color in a coloring book, or draw whatever comes to mind. Creativity reduces stress."
            },
            {
                id: 10,
                title: "Try a New Recipe",
                category: "creative",
                icon: "fa-utensils",
                difficulty: "Medium",
                duration: "45-60 min",
                description: "Experiment in the kitchen with a recipe you've never tried.",
                details: "Choose something that sounds fun, not complicated. Follow along, be present with the process, and enjoy the sensory experience of cooking. Treat yourself to something delicious."
            },
            {
                id: 11,
                title: "Meditation Session",
                category: "spiritual",
                icon: "fa-om",
                difficulty: "Medium",
                duration: "10-20 min",
                description: "Sit in stillness and connect with your inner self.",
                details: "Find a quiet spot. Sit comfortably, close your eyes, and focus on your breath. When thoughts arise, gently return to your breath. Use a guided meditation app if helpful. Start with 5-10 minutes."
            },
            {
                id: 12,
                title: "Stargazing",
                category: "spiritual",
                icon: "fa-star",
                difficulty: "Easy",
                duration: "20-30 min",
                description: "Connect with the vastness of the universe under the night sky.",
                details: "Go outside on a clear night. Lie down or sit comfortably. Look at the stars and let yourself feel small in the best way. This perspective can be deeply grounding and peaceful."
            },
            {
                id: 13,
                title: "Digital Detox Hour",
                category: "mental",
                icon: "fa-mobile-alt",
                difficulty: "Medium",
                duration: "60 min",
                description: "Take a complete break from all screens and devices.",
                details: "Turn off phone, computer, and TV for one hour. Read a physical book, sit in nature, or do a hobby. Notice how you feel without constant digital stimulation. Mental clarity often follows."
            },
            {
                id: 14,
                title: "Bubble Bath",
                category: "physical",
                icon: "fa-bath",
                difficulty: "Easy",
                duration: "30 min",
                description: "Soak in warm water to relax muscles and mind.",
                details: "Fill the tub with warm water, add bath salts or bubbles if you have them. Light a candle, play soft music. Let the warmth ease tension. Stay for at least 20 minutes to fully relax."
            },
            {
                id: 15,
                title: "Dance Session",
                category: "physical",
                icon: "fa-music",
                difficulty: "Easy",
                duration: "15 min",
                description: "Move your body freely to music you love.",
                details: "Put on your favorite upbeat songs and dance like nobody's watching (because they're not!). Let your body move however it wants. This releases endorphins and lifts your mood instantly."
            },
            {
                id: 16,
                title: "Compliment Yourself",
                category: "emotional",
                icon: "fa-award",
                difficulty: "Easy",
                duration: "5 min",
                description: "Write down three things you like about yourself.",
                details: "Be specific and genuine. Include personality traits ('I'm a good listener'), accomplishments ('I finished that project'), and physical attributes. Read them aloud. Self-compassion is essential."
            },
            {
                id: 17,
                title: "Volunteer Time",
                category: "social",
                icon: "fa-hands-helping",
                difficulty: "Challenging",
                duration: "2-4 hours",
                description: "Give your time to help others in your community.",
                details: "Find a local charity, food bank, or community center. Spending time helping others creates connection and purpose. Even a few hours can make a difference and boost your own wellbeing."
            },
            {
                id: 18,
                title: "Write Poetry",
                category: "creative",
                icon: "fa-feather-alt",
                difficulty: "Medium",
                duration: "20-30 min",
                description: "Express your feelings and experiences through verse.",
                details: "Don't worry about 'being good' at poetry. Write about your day, your feelings, or observations. Play with rhythm and imagery. Poetry is a powerful emotional outlet and form of self-expression."
            },
            {
                id: 19,
                title: "Build a Playlist",
                category: "creative",
                icon: "fa-headphones",
                difficulty: "Easy",
                duration: "30 min",
                description: "Curate a collection of songs for a specific mood or moment.",
                details: "Create a playlist for relaxation, motivation, happiness, or processing emotions. Choose songs intentionally. Music is a powerful tool for regulating emotions and energy levels."
            },
            {
                id: 20,
                title: "Practice Affirmations",
                category: "spiritual",
                icon: "fa-comment-dots",
                difficulty: "Easy",
                duration: "5-10 min",
                description: "Speak positive statements about yourself and your life.",
                details: "Stand in front of a mirror or sit quietly. Repeat affirmations like 'I am enough,' 'I deserve peace,' 'I am growing.' Say them even if you don't fully believe them yet. Words shape reality."
            },
            {
                id: 21,
                title: "Puzzle Time",
                category: "mental",
                icon: "fa-puzzle-piece",
                difficulty: "Easy",
                duration: "30-60 min",
                description: "Engage your mind with a jigsaw puzzle, crossword, or sudoku.",
                details: "Choose a puzzle that's challenging but not frustrating. Working on puzzles is meditative, improves focus, and gives a sense of accomplishment. It's productive relaxation."
            },
            {
                id: 22,
                title: "Organize a Space",
                category: "mental",
                icon: "fa-box-open",
                difficulty: "Medium",
                duration: "30-60 min",
                description: "Declutter and organize one area of your home.",
                details: "Pick one drawer, shelf, or corner. Sort through items, discard what you don't need, organize what remains. A tidy space creates mental clarity and a sense of control and accomplishment."
            },
            {
                id: 23,
                title: "Yoga Practice",
                category: "physical",
                icon: "fa-yin-yang",
                difficulty: "Medium",
                duration: "20-45 min",
                description: "Flow through yoga poses to connect mind and body.",
                details: "Follow an online video or do your own sequence. Focus on breath and sensation. Yoga builds strength, flexibility, and mindfulness. Even 20 minutes can leave you feeling centered and renewed."
            },
            {
                id: 24,
                title: "Create a Vision Board",
                category: "spiritual",
                icon: "fa-images",
                difficulty: "Medium",
                duration: "60 min",
                description: "Visualize your goals and dreams through images and words.",
                details: "Gather magazines, photos, or print images. Create a collage representing your aspirations, values, and dreams. Display it where you'll see it daily. Visualization is a powerful manifestation tool."
            }
        ];

        let currentCategory = 'all';
        let expandedIdeas = new Set();
        let completedIdeas = new Set();

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;

            const navBar = document.querySelector('nav');
            navBar.style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('pageSubtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;
            document.getElementById('filterAll').textContent = config.filter_all || defaultConfig.filter_all;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.75}px`;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;
            document.getElementById('pageSubtitle').style.fontFamily = `${customFont}, sans-serif`;

            renderIdeas();
        }

        function initApp() {
            if (window.elementSdk) {
                window.elementSdk.init({
                    defaultConfig,
                    onConfigChange,
                    mapToCapabilities: (config) => ({
                        recolorables: [
                            {
                                get: () => config.background_color || defaultConfig.background_color,
                                set: (value) => {
                                    config.background_color = value;
                                    window.elementSdk.setConfig({ background_color: value });
                                }
                            },
                            {
                                get: () => config.card_color || defaultConfig.card_color,
                                set: (value) => {
                                    config.card_color = value;
                                    window.elementSdk.setConfig({ card_color: value });
                                }
                            },
                            {
                                get: () => config.text_color || defaultConfig.text_color,
                                set: (value) => {
                                    config.text_color = value;
                                    window.elementSdk.setConfig({ text_color: value });
                                }
                            },
                            {
                                get: () => config.primary_action_color || defaultConfig.primary_action_color,
                                set: (value) => {
                                    config.primary_action_color = value;
                                    window.elementSdk.setConfig({ primary_action_color: value });
                                }
                            },
                            {
                                get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
                                set: (value) => {
                                    config.secondary_action_color = value;
                                    window.elementSdk.setConfig({ secondary_action_color: value });
                                }
                            }
                        ],
                        borderables: [],
                        fontEditable: {
                            get: () => config.font_family || defaultConfig.font_family,
                            set: (value) => {
                                config.font_family = value;
                                window.elementSdk.setConfig({ font_family: value });
                            }
                        },
                        fontSizeable: {
                            get: () => config.font_size || defaultConfig.font_size,
                            set: (value) => {
                                config.font_size = value;
                                window.elementSdk.setConfig({ font_size: value });
                            }
                        }
                    }),
                    mapToEditPanelValues: (config) => new Map([
                        ["page_title", config.page_title || defaultConfig.page_title],
                        ["page_subtitle", config.page_subtitle || defaultConfig.page_subtitle],
                        ["filter_all", config.filter_all || defaultConfig.filter_all]
                    ])
                });
            }

            setupEventListeners();
            renderIdeas();
        }

        function setupEventListeners() {
            document.querySelectorAll('.category-badge').forEach(badge => {
                badge.addEventListener('click', (e) => {
                    currentCategory = e.currentTarget.dataset.category;
                    updateActiveBadge(e.currentTarget);
                    renderIdeas();
                });
            });
        }

        function updateActiveBadge(activeBadge) {
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;

            document.querySelectorAll('.category-badge').forEach(badge => {
                if (badge === activeBadge) {
                    badge.classList.add('active');
                    badge.style.background = primaryColor;
                    badge.style.borderColor = primaryColor;
                    badge.style.color = 'white';
                } else {
                    badge.classList.remove('active');
                    badge.style.background = 'white';
                    badge.style.borderColor = '#e5e7eb';
                    badge.style.color = '#374151';
                }
            });
        }

        function renderIdeas() {
            const container = document.getElementById('ideasContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            const filteredIdeas = currentCategory === 'all' 
                ? selfCareIdeas 
                : selfCareIdeas.filter(idea => idea.category === currentCategory);

            const difficultyColors = {
                'Easy': '#10b981',
                'Medium': '#f59e0b',
                'Challenging': '#ef4444'
            };

            container.innerHTML = filteredIdeas.map(idea => {
                const isExpanded = expandedIdeas.has(idea.id);
                const isCompleted = completedIdeas.has(idea.id);

                return `
                    <div class="idea-card" style="background: ${cardColor}; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); position: relative; opacity: ${isCompleted ? '0.7' : '1'}; font-family: ${customFont}, sans-serif;">
                        ${isCompleted ? `
                            <div class="done-overlay" style="position: absolute; top: 1rem; right: 1rem; background: ${primaryColor}; color: white; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: ${baseFontSize * 0.8125}px; font-weight: 600;">
                                <i class="fas fa-check"></i> Done
                            </div>
                        ` : ''}

                        <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                            <div style="background: linear-gradient(135deg, ${primaryColor}, ${secondaryColor}); color: white; width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas ${idea.icon}" style="font-size: ${baseFontSize * 1.25}px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; font-size: ${baseFontSize * 1.125}px; font-weight: 600; color: ${textColor};">${idea.title}</h3>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <span style="background: ${difficultyColors[idea.difficulty]}20; color: ${difficultyColors[idea.difficulty]}; padding: 0.25rem 0.625rem; border-radius: 0.25rem; font-size: ${baseFontSize * 0.75}px; font-weight: 600;">
                                        ${idea.difficulty}
                                    </span>
                                    <span style="background: ${primaryColor}20; color: ${primaryColor}; padding: 0.25rem 0.625rem; border-radius: 0.25rem; font-size: ${baseFontSize * 0.75}px; font-weight: 600;">
                                        <i class="fas fa-clock"></i> ${idea.duration}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p style="margin: 0 0 1rem 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.6;">${idea.description}</p>

                        ${isExpanded ? `
                            <div style="padding: 1rem; background: #f9fafb; border-radius: 0.5rem; margin-bottom: 1rem;">
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: ${textColor}; line-height: 1.6;">${idea.details}</p>
                            </div>
                        ` : ''}

                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="toggleIdea(${idea.id})" style="flex: 1; padding: 0.75rem; background: ${primaryColor}; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                <span>${isExpanded ? 'Show Less' : 'Learn More'}</span>
                                <i id="icon-${idea.id}" class="fas fa-chevron-down expand-btn ${isExpanded ? 'rotated' : ''}" style="font-size: ${baseFontSize * 0.75}px;"></i>
                            </button>
                            <button onclick="toggleComplete(${idea.id})" style="padding: 0.75rem 1rem; background: ${isCompleted ? '#6b7280' : secondaryColor}; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                <i class="fas ${isCompleted ? 'fa-undo' : 'fa-check'}"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleIdea(ideaId) {
            if (expandedIdeas.has(ideaId)) {
                expandedIdeas.delete(ideaId);
            } else {
                expandedIdeas.add(ideaId);
            }
            renderIdeas();
        }

        function toggleComplete(ideaId) {
            if (completedIdeas.has(ideaId)) {
                completedIdeas.delete(ideaId);
            } else {
                completedIdeas.add(ideaId);
            }
            renderIdeas();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a83bb5230570dcb',t:'MTc2NDc3MTkwOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>