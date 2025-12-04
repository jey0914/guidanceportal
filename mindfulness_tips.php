<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mindfulness Tips &amp; Techniques</title>
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
        
        .tip-card {
            transition: all 0.3s ease;
        }
        
        .tip-card:hover {
            transform: translateY(-4px);
        }
        
        .breathing-circle {
            animation: breathe 4s ease-in-out infinite;
        }
        
        @keyframes breathe {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; }
        }
        
        .category-tab {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .category-tab:hover {
            transform: scale(1.05);
        }
        
        .category-tab.active {
            transform: scale(1.05);
        }
        
        .technique-step {
            position: relative;
            padding-left: 2.5rem;
        }
        
        .technique-step::before {
            content: '';
            position: absolute;
            left: 0.875rem;
            top: 2.5rem;
            bottom: -1rem;
            width: 2px;
            background: #e5e7eb;
        }
        
        .technique-step:last-child::before {
            display: none;
        }
        
        .expand-btn {
            transition: transform 0.3s ease;
        }
        
        .expand-btn.rotated {
            transform: rotate(180deg);
        }
    </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);"><!-- Navigation -->
   <nav style="background: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 1rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-lightbulb" style="font-size: 1.5rem; color: #06b6d4;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Mindfulness Tips &amp; Techniques</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; color: #4b5563; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s;"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1200px; margin: 0 auto; padding: 2rem;"><!-- Header Section -->
    <div style="background: white; border-radius: 1rem; padding: 2.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center;">
     <div class="breathing-circle" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4, #3b82f6); margin: 0 auto 1.5rem;"></div>
     <h2 id="pageSubtitle" style="font-size: 1.25rem; font-weight: 500; color: #6b7280; margin: 0;">Practical guides for daily mindfulness</h2>
    </div><!-- Category Tabs -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;"><button class="category-tab active" data-category="all" style="padding: 0.75rem 1.5rem; border: 2px solid #06b6d4; background: #06b6d4; color: white; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem;"> <i class="fas fa-th"></i> All Tips </button> <button class="category-tab" data-category="breathing" style="padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem;"> <i class="fas fa-wind"></i> Breathing </button> <button class="category-tab" data-category="meditation" style="padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem;"> <i class="fas fa-spa"></i> Meditation </button> <button class="category-tab" data-category="daily" style="padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem;"> <i class="fas fa-sun"></i> Daily Practice </button> <button class="category-tab" data-category="stress" style="padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem;"> <i class="fas fa-heart"></i> Stress Relief </button>
     </div>
    </div><!-- Tips Grid -->
    <div id="tipsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;"><!-- Tips will be rendered here -->
    </div>
   </main>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Mindfulness Tips & Techniques",
            page_subtitle: "Practical guides for daily mindfulness",
            background_color: "#a8edea",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#06b6d4",
            secondary_action_color: "#3b82f6",
            font_family: "Inter",
            font_size: 16
        };

        const tipsData = [
            {
                id: 1,
                title: "4-7-8 Breathing Technique",
                category: "breathing",
                icon: "fa-wind",
                description: "A simple breathing exercise to calm your nervous system and reduce anxiety.",
                steps: [
                    "Place the tip of your tongue against the ridge behind your upper teeth",
                    "Exhale completely through your mouth, making a whoosh sound",
                    "Close your mouth and inhale quietly through your nose for 4 counts",
                    "Hold your breath for 7 counts",
                    "Exhale completely through your mouth for 8 counts",
                    "Repeat this cycle 3-4 times"
                ],
                benefits: ["Reduces anxiety", "Improves sleep", "Lowers blood pressure", "Calms the mind"]
            },
            {
                id: 2,
                title: "Body Scan Meditation",
                category: "meditation",
                icon: "fa-person",
                description: "Bring awareness to each part of your body, releasing tension and promoting relaxation.",
                steps: [
                    "Lie down in a comfortable position",
                    "Close your eyes and take three deep breaths",
                    "Start at your toes, noticing any sensations",
                    "Slowly move your attention up through your feet, legs, torso",
                    "Continue to your arms, hands, neck, and head",
                    "Notice areas of tension without judgment",
                    "Breathe into tense areas and allow them to soften"
                ],
                benefits: ["Releases physical tension", "Increases body awareness", "Promotes deep relaxation", "Improves sleep quality"]
            },
            {
                id: 3,
                title: "Mindful Morning Routine",
                category: "daily",
                icon: "fa-sun",
                description: "Start your day with intention and presence to set a positive tone.",
                steps: [
                    "Wake up without immediately checking your phone",
                    "Take 5 deep breaths while still in bed",
                    "Set an intention for the day",
                    "Practice gentle stretching for 5 minutes",
                    "Drink a glass of water mindfully",
                    "Eat breakfast without distractions",
                    "Take a moment of gratitude before starting work"
                ],
                benefits: ["Reduces morning anxiety", "Increases energy", "Improves focus", "Creates positive momentum"]
            },
            {
                id: 4,
                title: "5-4-3-2-1 Grounding",
                category: "stress",
                icon: "fa-anchor",
                description: "A powerful technique to bring yourself back to the present moment during stress or anxiety.",
                steps: [
                    "Acknowledge 5 things you can see around you",
                    "Acknowledge 4 things you can touch or feel",
                    "Acknowledge 3 things you can hear",
                    "Acknowledge 2 things you can smell",
                    "Acknowledge 1 thing you can taste",
                    "Take a deep breath and notice how you feel"
                ],
                benefits: ["Stops panic attacks", "Grounds you in the present", "Reduces anxiety", "Increases sensory awareness"]
            },
            {
                id: 5,
                title: "Loving-Kindness Meditation",
                category: "meditation",
                icon: "fa-heart",
                description: "Cultivate compassion and positive feelings towards yourself and others.",
                steps: [
                    "Sit comfortably and close your eyes",
                    "Think of someone you love and wish them well",
                    "Repeat: 'May you be happy, healthy, safe, and at ease'",
                    "Direct these wishes toward yourself",
                    "Extend to a neutral person, then to someone difficult",
                    "Finally, extend to all beings everywhere",
                    "Sit quietly and notice the feelings that arise"
                ],
                benefits: ["Increases self-compassion", "Reduces negative emotions", "Improves relationships", "Boosts positive emotions"]
            },
            {
                id: 6,
                title: "Box Breathing",
                category: "breathing",
                icon: "fa-square",
                description: "A military technique used to maintain calm and focus under pressure.",
                steps: [
                    "Sit upright in a comfortable position",
                    "Exhale all the air from your lungs",
                    "Inhale slowly through your nose for 4 counts",
                    "Hold your breath for 4 counts",
                    "Exhale slowly through your mouth for 4 counts",
                    "Hold empty for 4 counts",
                    "Repeat for 5-10 minutes"
                ],
                benefits: ["Regulates stress response", "Improves concentration", "Balances nervous system", "Reduces blood pressure"]
            },
            {
                id: 7,
                title: "Mindful Walking",
                category: "daily",
                icon: "fa-walking",
                description: "Transform a simple walk into a meditation practice by bringing full awareness to movement.",
                steps: [
                    "Choose a quiet place to walk, even just 10-20 steps",
                    "Stand still and take three deep breaths",
                    "Begin walking slowly, noticing the sensation of each step",
                    "Feel your feet lifting, moving, and touching the ground",
                    "Notice your breath and body movement",
                    "When your mind wanders, gently return to the sensations",
                    "Walk for 10-20 minutes"
                ],
                benefits: ["Improves focus", "Reduces rumination", "Connects mind and body", "Energizes naturally"]
            },
            {
                id: 8,
                title: "Progressive Muscle Relaxation",
                category: "stress",
                icon: "fa-dumbbell",
                description: "Release physical tension by systematically tensing and relaxing muscle groups.",
                steps: [
                    "Lie down or sit in a comfortable position",
                    "Start with your feet: tense for 5 seconds, then release",
                    "Move to calves, thighs, buttocks, tensing and releasing",
                    "Continue with stomach, chest, arms, and hands",
                    "Tense your shoulders up to your ears, then drop",
                    "Finish with face muscles: scrunch, then release",
                    "Take a moment to enjoy the full-body relaxation"
                ],
                benefits: ["Releases muscle tension", "Reduces physical stress", "Improves body awareness", "Helps with insomnia"]
            },
            {
                id: 9,
                title: "Mindful Eating Practice",
                category: "daily",
                icon: "fa-utensils",
                description: "Transform meals into a meditation by eating with full awareness and appreciation.",
                steps: [
                    "Remove all distractions (phone, TV, reading)",
                    "Look at your food and appreciate its colors and presentation",
                    "Take a moment of gratitude before eating",
                    "Take a small bite and chew slowly",
                    "Notice the textures, flavors, and temperature",
                    "Put your utensil down between bites",
                    "Eat until satisfied, not stuffed"
                ],
                benefits: ["Improves digestion", "Increases satisfaction", "Prevents overeating", "Enhances taste perception"]
            },
            {
                id: 10,
                title: "One-Minute Breathing Space",
                category: "breathing",
                icon: "fa-clock",
                description: "A quick reset practice you can do anywhere, anytime you need to center yourself.",
                steps: [
                    "Stop what you're doing and sit comfortably",
                    "Close your eyes or soften your gaze",
                    "Notice your current experience: thoughts, feelings, sensations",
                    "Bring attention to your breathing",
                    "Follow each breath in and out for several cycles",
                    "Expand awareness to your whole body",
                    "Open your eyes and continue your day"
                ],
                benefits: ["Quick stress relief", "Improves decision-making", "Increases awareness", "Easy to practice anywhere"]
            },
            {
                id: 11,
                title: "Gratitude Journaling",
                category: "daily",
                icon: "fa-book",
                description: "End your day by reflecting on what you're grateful for to promote positive thinking.",
                steps: [
                    "Set aside 5-10 minutes before bed",
                    "Get a dedicated journal or notebook",
                    "Write down 3-5 things you're grateful for today",
                    "Be specific and detailed about why",
                    "Include small moments as well as big ones",
                    "Notice how you feel as you write",
                    "Review past entries occasionally to reflect"
                ],
                benefits: ["Boosts happiness", "Improves sleep", "Reduces depression", "Shifts focus to positive"]
            },
            {
                id: 12,
                title: "Visualization for Calm",
                category: "stress",
                icon: "fa-eye",
                description: "Use the power of imagination to create a peaceful mental sanctuary.",
                steps: [
                    "Sit or lie in a comfortable position",
                    "Close your eyes and take deep breaths",
                    "Imagine a place where you feel completely safe and peaceful",
                    "Engage all senses: what do you see, hear, smell, feel?",
                    "Spend 5-10 minutes exploring this peaceful place",
                    "Notice details and how your body responds",
                    "Slowly return to the present when ready"
                ],
                benefits: ["Reduces stress hormones", "Creates mental refuge", "Improves emotional regulation", "Enhances creativity"]
            }
        ];

        let currentCategory = 'all';
        let expandedTips = new Set();

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;

            document.getElementById('app').style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('pageSubtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.5}px`;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;
            document.getElementById('pageSubtitle').style.fontFamily = `${customFont}, sans-serif`;

            renderTips();
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
                        ["page_subtitle", config.page_subtitle || defaultConfig.page_subtitle]
                    ])
                });
            }

            setupEventListeners();
            renderTips();
        }

        function setupEventListeners() {
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', (e) => {
                    currentCategory = e.currentTarget.dataset.category;
                    updateActiveTab(e.currentTarget);
                    renderTips();
                });
            });
        }

        function updateActiveTab(activeTab) {
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;

            document.querySelectorAll('.category-tab').forEach(tab => {
                if (tab === activeTab) {
                    tab.classList.add('active');
                    tab.style.background = primaryColor;
                    tab.style.borderColor = primaryColor;
                    tab.style.color = 'white';
                } else {
                    tab.classList.remove('active');
                    tab.style.background = 'white';
                    tab.style.borderColor = '#e5e7eb';
                    tab.style.color = '#374151';
                }
            });
        }

        function renderTips() {
            const container = document.getElementById('tipsContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            const filteredTips = currentCategory === 'all' 
                ? tipsData 
                : tipsData.filter(tip => tip.category === currentCategory);

            container.innerHTML = filteredTips.map(tip => {
                const isExpanded = expandedTips.has(tip.id);

                return `
                    <div class="tip-card" style="background: ${cardColor}; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-family: ${customFont}, sans-serif;">
                        <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                            <div style="background: ${primaryColor}; color: white; width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas ${tip.icon}" style="font-size: ${baseFontSize * 1.25}px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; font-size: ${baseFontSize * 1.125}px; font-weight: 600; color: ${textColor};">${tip.title}</h3>
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.5;">${tip.description}</p>
                            </div>
                        </div>

                        <div id="details-${tip.id}" style="display: ${isExpanded ? 'block' : 'none'}; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #f3f4f6;">
                            <h4 style="margin: 0 0 1rem 0; font-size: ${baseFontSize * 0.9375}px; font-weight: 600; color: ${textColor};">Step-by-Step Guide:</h4>
                            ${tip.steps.map((step, index) => `
                                <div class="technique-step" style="margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="background: ${secondaryColor}; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 600; font-size: ${baseFontSize * 0.8125}px;">
                                            ${index + 1}
                                        </div>
                                        <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: ${textColor}; line-height: 1.5;">${step}</p>
                                    </div>
                                </div>
                            `).join('')}

                            <h4 style="margin: 1.5rem 0 0.75rem 0; font-size: ${baseFontSize * 0.9375}px; font-weight: 600; color: ${textColor};">Benefits:</h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                ${tip.benefits.map(benefit => `
                                    <span style="background: ${primaryColor}20; color: ${primaryColor}; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: ${baseFontSize * 0.8125}px; font-weight: 500;">
                                        <i class="fas fa-check" style="font-size: ${baseFontSize * 0.75}px;"></i> ${benefit}
                                    </span>
                                `).join('')}
                            </div>
                        </div>

                        <button onclick="toggleTip(${tip.id})" style="margin-top: 1rem; width: 100%; padding: 0.75rem; background: ${primaryColor}; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                            <span>${isExpanded ? 'Show Less' : 'Learn More'}</span>
                            <i id="icon-${tip.id}" class="fas fa-chevron-down expand-btn ${isExpanded ? 'rotated' : ''}" style="font-size: ${baseFontSize * 0.75}px;"></i>
                        </button>
                    </div>
                `;
            }).join('');
        }

        function toggleTip(tipId) {
            if (expandedTips.has(tipId)) {
                expandedTips.delete(tipId);
            } else {
                expandedTips.add(tipId);
            }
            renderTips();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a83a87192a40dcb',t:'MTc2NDc3MTEzNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>