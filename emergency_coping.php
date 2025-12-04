<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emergency Coping</title>
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
        
        .breathing-circle-active {
            animation: breatheActive 8s ease-in-out infinite;
        }
        
        @keyframes breatheActive {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.4); }
            50% { transform: scale(1); }
            75% { transform: scale(1); }
        }
        
        .pulse-ring {
            animation: pulse 2s ease-out infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        .tip-card {
            transition: all 0.3s ease;
        }
        
        .tip-card:hover {
            transform: translateY(-2px);
        }
        
        .checkbox-item {
            transition: all 0.2s ease;
        }
        
        .checkbox-item:hover {
            background: rgba(6, 182, 212, 0.05);
        }
        
        .expand-btn {
            transition: transform 0.3s ease;
        }
        
        .expand-btn.rotated {
            transform: rotate(180deg);
        }

        .toast {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
            z-index: 60;
            animation: slideIn 0.3s ease;
            display: none;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%; background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);"><!-- Navigation -->
   <nav style="background: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 1rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-life-ring" style="font-size: 1.5rem; color: #3b82f6;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Emergency Coping</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; color: #4b5563; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s;"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1200px; margin: 0 auto; padding: 2rem;"><!-- Alert Banner -->
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.5rem; padding: 1rem 1.5rem; margin-bottom: 2rem;">
     <div style="display: flex; align-items: start; gap: 1rem;"><i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 1.25rem; margin-top: 0.125rem;"></i>
      <div>
       <h3 id="pageSubtitle" style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 600; color: #78350f;">Quick techniques for difficult moments</h3>
       <p style="margin: 0; font-size: 0.875rem; color: #92400e;">If you're in crisis, please reach out to the resources below or call emergency services.</p>
      </div>
     </div>
    </div><!-- Emergency Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;"><!-- Breathing Exercise -->
     <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
      <h2 id="breathingTitle" style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 700; color: #1f2937; text-align: center;">Breathing Exercise</h2>
      <div style="position: relative; width: 200px; height: 200px; margin: 0 auto 1.5rem;">
       <div id="breathingCircle" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #06b6d4);"></div>
       <div id="breathingText" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 1.125rem; font-weight: 700; text-align: center; z-index: 10;">
        Start
       </div>
      </div><button id="breathingBtn" style="width: 100%; padding: 0.875rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 1rem;"> <i class="fas fa-play"></i> Start Breathing </button>
      <p style="margin: 1rem 0 0 0; text-align: center; font-size: 0.875rem; color: #6b7280;">4 seconds in, hold 4, 4 seconds out</p>
     </div><!-- 5-4-3-2-1 Grounding -->
     <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
      <h2 id="groundingTitle" style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 700; color: #1f2937;">5-4-3-2-1 Grounding</h2>
      <div id="groundingContainer"><!-- Grounding items will be rendered here -->
      </div><button id="resetGroundingBtn" style="width: 100%; padding: 0.75rem; background: #06b6d4; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.875rem; margin-top: 1rem;"> <i class="fas fa-redo"></i> Reset </button>
     </div>
    </div><!-- Crisis Support -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <h2 id="hotlineTitle" style="margin: 0 0 1.5rem 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Crisis Support</h2>
     <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
      <div style="border: 2px solid #ef4444; border-radius: 0.75rem; padding: 1.25rem;">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;"><i class="fas fa-phone" style="color: #ef4444; font-size: 1.25rem;"></i>
        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1f2937;">Suicide Prevention</h3>
       </div>
       <p style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 700; color: #ef4444;">988</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">24/7 Crisis Lifeline</p>
      </div>
      <div style="border: 2px solid #3b82f6; border-radius: 0.75rem; padding: 1.25rem;">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;"><i class="fas fa-comments" style="color: #3b82f6; font-size: 1.25rem;"></i>
        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1f2937;">Crisis Text Line</h3>
       </div>
       <p style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 700; color: #3b82f6;">Text HOME to 741741</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">Free, 24/7 support</p>
      </div>
      <div style="border: 2px solid #10b981; border-radius: 0.75rem; padding: 1.25rem;">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;"><i class="fas fa-hospital" style="color: #10b981; font-size: 1.25rem;"></i>
        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1f2937;">Emergency</h3>
       </div>
       <p style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 700; color: #10b981;">911</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">Immediate danger</p>
      </div>
     </div>
    </div><!-- Quick Coping Strategies -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <h2 id="quickTipsTitle" style="margin: 0 0 1.5rem 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Quick Coping Strategies</h2>
     <div id="tipsContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;"><!-- Tips will be rendered here -->
     </div>
    </div>
   </main><!-- Toast Notification -->
   <div id="toast" class="toast">
    <div style="display: flex; align-items: center; gap: 0.75rem;"><i id="toastIcon" class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
     <p id="toastMessage" style="margin: 0; color: #374151; font-weight: 500;"></p>
    </div>
   </div>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Emergency Coping",
            page_subtitle: "Quick techniques for difficult moments",
            breathing_title: "Breathing Exercise",
            grounding_title: "5-4-3-2-1 Grounding",
            hotline_section_title: "Crisis Support",
            quick_tips_title: "Quick Coping Strategies",
            background_color: "#dbeafe",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#3b82f6",
            secondary_action_color: "#06b6d4",
            font_family: "Inter",
            font_size: 16
        };

        const groundingItems = [
            { count: 5, text: "things you can see", icon: "fa-eye" },
            { count: 4, text: "things you can touch", icon: "fa-hand-paper" },
            { count: 3, text: "things you can hear", icon: "fa-ear" },
            { count: 2, text: "things you can smell", icon: "fa-nose" },
            { count: 1, text: "thing you can taste", icon: "fa-mouth" }
        ];

        const copingTips = [
            {
                id: 1,
                title: "Cold Water",
                icon: "fa-tint",
                quick: "Splash cold water on your face or hold ice cubes",
                details: "The cold activates your dive reflex, instantly calming your nervous system. Hold ice in your hands, splash your face, or take a cold shower."
            },
            {
                id: 2,
                title: "Muscle Tension",
                icon: "fa-dumbbell",
                quick: "Tense all muscles for 10 seconds, then release",
                details: "Progressive muscle relaxation releases physical tension. Squeeze every muscle in your body as tight as you can, hold for 10 seconds, then let go completely."
            },
            {
                id: 3,
                title: "Name It",
                icon: "fa-tag",
                quick: "Label your emotions: 'I feel...'",
                details: "Naming emotions reduces their intensity. Say out loud: 'I feel anxious/scared/overwhelmed.' This simple act engages your thinking brain and reduces emotional reactivity."
            },
            {
                id: 4,
                title: "Safe Space",
                icon: "fa-home",
                quick: "Visualize your safest, most peaceful place",
                details: "Close your eyes and imagine a place where you feel completely safe. Engage all your senses - what do you see, hear, smell, and feel there?"
            },
            {
                id: 5,
                title: "Move Your Body",
                icon: "fa-running",
                quick: "Jump, shake, dance - release the energy",
                details: "Physical movement processes stress hormones. Do jumping jacks, shake your arms and legs, dance wildly, or go for a fast walk."
            },
            {
                id: 6,
                title: "Distraction Box",
                icon: "fa-box",
                quick: "Focus intensely on an object for 2 minutes",
                details: "Pick any object and describe it in extreme detail: color, texture, weight, purpose, origin. This interrupts panic and grounds you in the present."
            }
        ];

        let breathingActive = false;
        let breathingInterval = null;
        let groundingChecked = new Set();
        let expandedTips = new Set();

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;
            const textColor = config.text_color || defaultConfig.text_color;

            document.getElementById('app').style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('pageSubtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;
            document.getElementById('breathingTitle').textContent = config.breathing_title || defaultConfig.breathing_title;
            document.getElementById('groundingTitle').textContent = config.grounding_title || defaultConfig.grounding_title;
            document.getElementById('hotlineTitle').textContent = config.hotline_section_title || defaultConfig.hotline_section_title;
            document.getElementById('quickTipsTitle').textContent = config.quick_tips_title || defaultConfig.quick_tips_title;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.5}px`;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;
            document.getElementById('pageTitle').style.color = textColor;

            renderGrounding();
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
                        ["page_subtitle", config.page_subtitle || defaultConfig.page_subtitle],
                        ["breathing_title", config.breathing_title || defaultConfig.breathing_title],
                        ["grounding_title", config.grounding_title || defaultConfig.grounding_title],
                        ["hotline_section_title", config.hotline_section_title || defaultConfig.hotline_section_title],
                        ["quick_tips_title", config.quick_tips_title || defaultConfig.quick_tips_title]
                    ])
                });
            }

            setupEventListeners();
            renderGrounding();
            renderTips();
        }

        function setupEventListeners() {
            document.getElementById('breathingBtn').addEventListener('click', toggleBreathing);
            document.getElementById('resetGroundingBtn').addEventListener('click', resetGrounding);
        }

        function toggleBreathing() {
            const btn = document.getElementById('breathingBtn');
            const circle = document.getElementById('breathingCircle');
            const text = document.getElementById('breathingText');

            if (breathingActive) {
                breathingActive = false;
                clearInterval(breathingInterval);
                circle.classList.remove('breathing-circle-active');
                btn.innerHTML = '<i class="fas fa-play"></i> Start Breathing';
                text.textContent = 'Start';
            } else {
                breathingActive = true;
                circle.classList.add('breathing-circle-active');
                btn.innerHTML = '<i class="fas fa-stop"></i> Stop';
                
                let phase = 0;
                const phases = ['Breathe In', 'Hold', 'Breathe Out', 'Hold'];
                const durations = [4000, 4000, 4000, 4000];
                
                function updatePhase() {
                    text.textContent = phases[phase];
                    phase = (phase + 1) % 4;
                }
                
                updatePhase();
                breathingInterval = setInterval(updatePhase, 4000);
            }
        }

        function renderGrounding() {
            const container = document.getElementById('groundingContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            container.innerHTML = groundingItems.map((item, index) => {
                const itemId = `grounding-${index}`;
                const isChecked = groundingChecked.has(itemId);

                return `
                    <div class="checkbox-item" onclick="toggleGroundingItem('${itemId}')" style="padding: 0.875rem; border-radius: 0.5rem; cursor: pointer; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; font-family: ${customFont}, sans-serif; ${isChecked ? `background: ${primaryColor}20;` : ''}">
                        <div style="width: 24px; height: 24px; border: 2px solid ${isChecked ? primaryColor : '#d1d5db'}; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; background: ${isChecked ? primaryColor : 'white'}; flex-shrink: 0;">
                            ${isChecked ? `<i class="fas fa-check" style="color: white; font-size: ${baseFontSize * 0.75}px;"></i>` : ''}
                        </div>
                        <i class="fas ${item.icon}" style="color: ${primaryColor}; font-size: ${baseFontSize * 1}px;"></i>
                        <span style="font-size: ${baseFontSize * 0.9375}px; color: ${textColor}; ${isChecked ? 'text-decoration: line-through; opacity: 0.6;' : ''}">
                            <strong>${item.count}</strong> ${item.text}
                        </span>
                    </div>
                `;
            }).join('');
        }

        function toggleGroundingItem(itemId) {
            if (groundingChecked.has(itemId)) {
                groundingChecked.delete(itemId);
            } else {
                groundingChecked.add(itemId);
                
                if (groundingChecked.size === groundingItems.length) {
                    showToast('Great job! You completed the grounding exercise.', 'success');
                }
            }
            renderGrounding();
        }

        function resetGrounding() {
            groundingChecked.clear();
            renderGrounding();
            showToast('Grounding exercise reset', 'success');
        }

        function renderTips() {
            const container = document.getElementById('tipsContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            container.innerHTML = copingTips.map(tip => {
                const isExpanded = expandedTips.has(tip.id);

                return `
                    <div class="tip-card" style="background: ${cardColor}; border-radius: 0.75rem; padding: 1.25rem; border: 2px solid #f3f4f6; font-family: ${customFont}, sans-serif;">
                        <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 0.75rem;">
                            <div style="background: ${primaryColor}; color: white; width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas ${tip.icon}" style="font-size: ${baseFontSize * 1}px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; font-size: ${baseFontSize * 1}px; font-weight: 600; color: ${textColor};">${tip.title}</h3>
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.5;">${tip.quick}</p>
                            </div>
                        </div>

                        ${isExpanded ? `
                            <div style="padding-top: 0.75rem; border-top: 2px solid #f3f4f6; margin-top: 0.75rem;">
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: ${textColor}; line-height: 1.6;">${tip.details}</p>
                            </div>
                        ` : ''}

                        <button onclick="toggleTip(${tip.id})" style="margin-top: 0.75rem; width: 100%; padding: 0.5rem; background: ${primaryColor}; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: ${baseFontSize * 0.8125}px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-family: ${customFont}, sans-serif;">
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

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const messageEl = document.getElementById('toastMessage');

            icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-info-circle';
            icon.style.color = type === 'success' ? '#10b981' : '#3b82f6';
            messageEl.textContent = message;

            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a83af86c7a20dcb',t:'MTc2NDc3MTQyNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>