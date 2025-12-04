<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Tips Archive</title>
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

        .tip-card {
            transition: all 0.3s ease;
        }

        .tip-card:hover {
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

        .expand-btn {
            transition: transform 0.3s ease;
        }

        .expand-btn.rotated {
            transform: rotate(180deg);
        }

        .bookmark-btn {
            transition: all 0.2s ease;
        }

        .bookmark-btn:hover {
            transform: scale(1.1);
        }

        .bookmark-btn.bookmarked {
            animation: bookmarkPop 0.3s ease;
        }

        @keyframes bookmarkPop {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .search-input {
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
    </style>
 
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%;"><!-- Navigation -->
   <nav class="gradient-bg" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); padding: 1.5rem 2rem;">
    <div style="max-width: 1400px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-lightbulb" style="font-size: 1.75rem; color: white;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.75rem; font-weight: 700; color: white;">Daily Tips Archive</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; color: white; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s; background: rgba(255, 255, 255, 0.2);"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: #f9fafb; min-height: 100%;"><!-- Header Section -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center;">
     <div style="display: inline-block; padding: 1rem 2rem; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 9999px; margin-bottom: 1rem;"><i class="fas fa-book-open" style="font-size: 2rem; color: white;"></i>
     </div>
     <h2 id="pageSubtitle" style="font-size: 1.25rem; font-weight: 500; color: #6b7280; margin: 0;">Your complete collection of wellness wisdom</h2>
    </div><!-- Stats Bar -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
      <div style="text-align: center;">
       <p style="margin: 0 0 0.25rem 0; font-size: 2rem; font-weight: 700; color: #667eea;" id="totalTips">0</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280; font-weight: 500;">Total Tips</p>
      </div>
      <div style="text-align: center;">
       <p style="margin: 0 0 0.25rem 0; font-size: 2rem; font-weight: 700; color: #ec4899;" id="bookmarkedCount">0</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280; font-weight: 500;">Bookmarked</p>
      </div>
      <div style="text-align: center;">
       <p style="margin: 0 0 0.25rem 0; font-size: 2rem; font-weight: 700; color: #10b981;" id="filteredCount">0</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280; font-weight: 500;">Showing</p>
      </div>
     </div>
    </div><!-- Search Bar -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-search" style="font-size: 1.25rem; color: #9ca3af;"></i> <input type="text" id="searchInput" class="search-input" style="flex: 1; border: none; font-size: 1rem; color: #1f2937; background: transparent;">
     </div>
    </div><!-- Category Filter -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center;"><button class="category-badge active" data-category="all" style="padding: 0.625rem 1.25rem; border: 2px solid #667eea; background: #667eea; color: white; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-th"></i> <span id="allCategories">All Categories</span> </button> <button class="category-badge" data-category="mindfulness" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-brain"></i> Mindfulness </button> <button class="category-badge" data-category="physical" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-running"></i> Physical Health </button> <button class="category-badge" data-category="emotional" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-heart"></i> Emotional Wellness </button> <button class="category-badge" data-category="sleep" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-moon"></i> Sleep </button> <button class="category-badge" data-category="nutrition" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-apple-alt"></i> Nutrition </button> <button class="category-badge" data-category="productivity" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-tasks"></i> Productivity </button> <button class="category-badge" data-category="relationships" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-users"></i> Relationships </button> <button class="category-badge" data-category="stress" style="padding: 0.625rem 1.25rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; cursor: pointer;"> <i class="fas fa-spa"></i> Stress Management </button>
     </div>
    </div><!-- View Toggle -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem;"><button id="showAllBtn" class="view-toggle active" style="padding: 0.75rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;"> <i class="fas fa-th"></i> All Tips </button> <button id="showBookmarkedBtn" class="view-toggle" style="padding: 0.75rem 1.5rem; background: white; color: #374151; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;"> <i class="fas fa-bookmark"></i> Bookmarked Only </button>
    </div><!-- Tips Grid -->
    <div id="tipsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;"><!-- Tips will be rendered here -->
    </div><!-- No Results Message -->
    <div id="noResults" style="display: none; text-align: center; padding: 4rem 2rem;"><i class="fas fa-search" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
     <p style="font-size: 1.25rem; font-weight: 600; color: #6b7280; margin: 0 0 0.5rem 0;">No tips found</p>
     <p style="font-size: 0.875rem; color: #9ca3af; margin: 0;">Try adjusting your search or filter criteria</p>
    </div>
   </main>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Daily Tips Archive",
            page_subtitle: "Your complete collection of wellness wisdom",
            search_placeholder: "Search tips...",
            all_categories: "All Categories",
            background_color: "#667eea",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#667eea",
            secondary_action_color: "#764ba2",
            font_family: "Inter",
            font_size: 16
        };

        const tips = [
            // Mindfulness Tips
            { id: 1, category: "mindfulness", title: "5-Minute Morning Meditation", difficulty: "Easy", description: "Start your day with clarity and calm through brief meditation.", details: "Sit comfortably, close your eyes, and focus on your breath for 5 minutes. Count breaths 1-10 and repeat. Notice thoughts without judgment and gently return focus to breathing." },
            { id: 2, category: "mindfulness", title: "Body Scan Technique", difficulty: "Medium", description: "Release physical tension through systematic body awareness.", details: "Lie down and mentally scan from toes to head. Notice sensations in each body part without changing anything. Spend 20-30 seconds on each area. This builds mind-body connection." },
            { id: 3, category: "mindfulness", title: "Mindful Walking Practice", difficulty: "Easy", description: "Turn daily walks into moving meditation sessions.", details: "Walk slowly and deliberately. Feel each foot contact the ground. Notice the rhythm of your steps. Observe surroundings without judgment. Start with 10 minutes." },
            { id: 4, category: "mindfulness", title: "Breath Counting Exercise", difficulty: "Easy", description: "Anchor your attention with simple breath counting.", details: "Inhale and mentally count 'one', exhale and count 'two'. Continue to 10, then restart. When mind wanders, gently restart at one. Practice for 5-10 minutes daily." },
            { id: 5, category: "mindfulness", title: "Loving-Kindness Meditation", difficulty: "Medium", description: "Cultivate compassion for yourself and others.", details: "Silently repeat: 'May I be happy, may I be healthy, may I be safe.' Then extend these wishes to loved ones, acquaintances, and all beings. Practice for 10 minutes." },
            { id: 6, category: "mindfulness", title: "Single-Tasking Challenge", difficulty: "Medium", description: "Break the multitasking habit for better focus.", details: "Choose one task and give it full attention for 25 minutes. No email, phone, or switching. Notice the urge to multitask without acting on it. Quality over quantity." },
            { id: 7, category: "mindfulness", title: "Gratitude Reflection", difficulty: "Easy", description: "Notice three good things before bed each night.", details: "Reflect on three positive moments from your day, no matter how small. Write them down or mentally note them. This rewires your brain to notice the good." },
            { id: 8, category: "mindfulness", title: "RAIN Technique for Emotions", difficulty: "Advanced", description: "Navigate difficult emotions with mindful awareness.", details: "Recognize the emotion, Allow it to be there, Investigate with kindness, Nurture yourself with compassion. This creates space between you and the emotion." },

            // Physical Health Tips
            { id: 9, category: "physical", title: "Morning Stretching Routine", difficulty: "Easy", description: "Wake up your body with 10 minutes of gentle stretches.", details: "Do neck rolls, shoulder circles, cat-cow pose, hamstring stretches, and side bends. Move slowly, breathe deeply, and never force a stretch. Consistency matters more than intensity." },
            { id: 10, category: "physical", title: "Walk After Meals", difficulty: "Easy", description: "Take a 10-15 minute walk after eating to aid digestion.", details: "A gentle post-meal walk stabilizes blood sugar, aids digestion, and boosts mood. Even a short walk around the block counts. Make it a daily habit after lunch or dinner." },
            { id: 11, category: "physical", title: "Desk Exercise Breaks", difficulty: "Easy", description: "Combat sitting with movement breaks every hour.", details: "Set a timer for every 50 minutes. Stand up, do 10 squats, 10 desk push-ups, neck stretches, and walk around. This prevents stiffness and boosts energy." },
            { id: 12, category: "physical", title: "Strength Training Basics", difficulty: "Medium", description: "Build muscle with bodyweight exercises 3x per week.", details: "Do push-ups, squats, lunges, and planks. Start with 2 sets of 10 reps each. Rest 1 day between sessions. Progressive overload: gradually increase reps or sets weekly." },
            { id: 13, category: "physical", title: "Proper Hydration Habit", difficulty: "Easy", description: "Drink water consistently throughout the day.", details: "Aim for half your body weight in ounces daily. Start your day with 16oz water. Keep a water bottle visible. Set hourly reminders. Proper hydration boosts energy and focus." },
            { id: 14, category: "physical", title: "Posture Check-Ins", difficulty: "Easy", description: "Set reminders to assess and correct your posture.", details: "Every hour, check: shoulders back, spine neutral, feet flat on floor, screen at eye level. Hold for 30 seconds. Over time, good posture becomes automatic." },
            { id: 15, category: "physical", title: "Mobility Drills", difficulty: "Medium", description: "Improve joint health with daily mobility work.", details: "Do hip circles, ankle rotations, thoracic spine twists, and shoulder circles. 5 minutes daily. This prevents injury and improves movement quality in daily life." },
            { id: 16, category: "physical", title: "Active Commute Strategy", difficulty: "Medium", description: "Turn travel time into exercise time.", details: "Bike or walk part of your commute. Park farther away. Take stairs instead of elevators. Get off the bus one stop early. Small changes add up to significant activity." },

            // Emotional Wellness Tips
            { id: 17, category: "emotional", title: "Emotion Journaling", difficulty: "Easy", description: "Write about your feelings for 10 minutes daily.", details: "Describe emotions without judgment. Note what triggered them. No need for perfect grammar—just honest expression. This creates emotional clarity and self-awareness." },
            { id: 18, category: "emotional", title: "Self-Compassion Practice", difficulty: "Medium", description: "Treat yourself with the kindness you'd show a friend.", details: "When self-critical, pause. Ask: 'Would I say this to a friend?' Replace harsh thoughts with understanding. Remember: everyone struggles and makes mistakes." },
            { id: 19, category: "emotional", title: "Name Your Emotions", difficulty: "Easy", description: "Label feelings precisely to regulate them better.", details: "Instead of 'bad,' identify: anxious, disappointed, overwhelmed, or lonely. Specific labels activate the thinking brain, reducing emotional intensity. 'Name it to tame it.'" },
            { id: 20, category: "emotional", title: "Create Joy Anchors", difficulty: "Easy", description: "Build small moments of joy into your routine.", details: "Choose 3 activities that reliably bring joy: favorite song, coffee ritual, sunset watching. Schedule them daily. These anchors provide emotional stability during tough times." },
            { id: 21, category: "emotional", title: "Boundary Setting Practice", difficulty: "Medium", description: "Protect your energy by saying 'no' when needed.", details: "Identify one area where you overextend. Practice saying 'no' politely but firmly. You don't need elaborate excuses. 'I'm not available' is complete. Start small." },
            { id: 22, category: "emotional", title: "Emotion Wheel Exercise", difficulty: "Medium", description: "Expand your emotional vocabulary for better regulation.", details: "Use an emotion wheel to identify nuanced feelings. Instead of just 'angry,' you might feel betrayed, frustrated, or disrespected. Precision helps you address root causes." },
            { id: 23, category: "emotional", title: "Celebrate Small Wins", difficulty: "Easy", description: "Acknowledge daily accomplishments, no matter how minor.", details: "End each day listing 3 things you accomplished. Include basics: made your bed, drank water, showed up. This builds self-efficacy and counters negative bias." },
            { id: 24, category: "emotional", title: "Future Self Letter", difficulty: "Medium", description: "Write a letter to your future self with compassion.", details: "Write to yourself 6 months from now. Share current struggles, hopes, and encouragement. Save it and read later. This builds perspective and self-compassion." },

            // Sleep Tips
            { id: 25, category: "sleep", title: "Consistent Sleep Schedule", difficulty: "Easy", description: "Go to bed and wake up at the same time daily.", details: "Choose a realistic bedtime and wake time. Stick to it even on weekends. Your circadian rhythm thrives on consistency. After 2 weeks, you'll sleep better naturally." },
            { id: 26, category: "sleep", title: "Screen Curfew", difficulty: "Medium", description: "No screens 1 hour before bedtime.", details: "Blue light suppresses melatonin. Set a phone alarm for screen curfew. Read a physical book, stretch, or journal instead. Keep devices outside the bedroom." },
            { id: 27, category: "sleep", title: "4-7-8 Breathing for Sleep", difficulty: "Easy", description: "Use this breathing pattern to fall asleep faster.", details: "Inhale through nose for 4 counts, hold for 7, exhale through mouth for 8. Repeat 4 times. This activates the parasympathetic nervous system for sleep." },
            { id: 28, category: "sleep", title: "Cool Bedroom Environment", difficulty: "Easy", description: "Keep your room between 60-67°F for optimal sleep.", details: "Your body temperature drops during sleep. A cool room facilitates this. Use breathable bedding, crack a window, or adjust the thermostat. Cool equals better sleep." },
            { id: 29, category: "sleep", title: "Morning Sunlight Exposure", difficulty: "Easy", description: "Get 10 minutes of natural light within an hour of waking.", details: "Morning light sets your circadian clock. Go outside or open curtains. Even cloudy day light helps. This improves nighttime melatonin production for better sleep." },
            { id: 30, category: "sleep", title: "Progressive Muscle Relaxation", difficulty: "Medium", description: "Release physical tension to prepare for sleep.", details: "Tense each muscle group for 5 seconds, then release. Start with toes, move up to face. Notice the contrast between tension and relaxation. This signals your body it's time to rest." },
            { id: 31, category: "sleep", title: "Caffeine Cutoff Time", difficulty: "Easy", description: "No caffeine after 2 PM for better sleep quality.", details: "Caffeine has a 5-6 hour half-life. That afternoon coffee still affects evening sleep. Switch to herbal tea or water after lunch. Sleep quality will improve within days." },
            { id: 32, category: "sleep", title: "Wind-Down Routine", difficulty: "Medium", description: "Create a 30-minute pre-sleep ritual.", details: "Same activities each night signal sleep time to your brain. Try: dim lights, gentle stretches, reading, or meditation. Consistency is more important than specific activities." },

            // Nutrition Tips
            { id: 33, category: "nutrition", title: "Eat the Rainbow", difficulty: "Easy", description: "Include 5 different colored fruits/vegetables daily.", details: "Each color provides different nutrients and antioxidants. Red tomatoes, orange carrots, green spinach, blue berries, purple cabbage. Variety ensures comprehensive nutrition." },
            { id: 34, category: "nutrition", title: "Protein at Every Meal", difficulty: "Easy", description: "Include protein source at breakfast, lunch, and dinner.", details: "Protein stabilizes blood sugar and keeps you full longer. Eggs, Greek yogurt, chicken, fish, beans, tofu. Aim for 20-30g per meal. This prevents energy crashes." },
            { id: 35, category: "nutrition", title: "Meal Prep Sunday", difficulty: "Medium", description: "Dedicate 2 hours weekly to prepare healthy meals.", details: "Choose 2-3 recipes, cook in batches, portion into containers. Having ready-to-eat healthy meals prevents poor food choices during busy weekdays. Start with just lunches." },
            { id: 36, category: "nutrition", title: "Mindful Eating Practice", difficulty: "Medium", description: "Eat without distractions, savoring each bite.", details: "No phone, TV, or computer while eating. Chew thoroughly. Notice flavors, textures, and satisfaction signals. This improves digestion and prevents overeating." },
            { id: 37, category: "nutrition", title: "Healthy Snack Prep", difficulty: "Easy", description: "Pre-portion nutritious snacks for the week.", details: "Cut vegetables, portion nuts, prepare fruit. Store in grab-and-go containers. When hunger strikes, healthy options are as convenient as junk food. You'll eat what's accessible." },
            { id: 38, category: "nutrition", title: "Fiber Focus", difficulty: "Easy", description: "Aim for 25-35g of fiber daily from whole foods.", details: "Add beans, whole grains, vegetables, and fruits. Fiber improves digestion, stabilizes blood sugar, and promotes fullness. Increase gradually and drink plenty of water." },
            { id: 39, category: "nutrition", title: "Smart Grocery Shopping", difficulty: "Easy", description: "Shop the perimeter and avoid processed center aisles.", details: "Fresh produce, meat, and dairy are on store edges. Center aisles contain processed foods. Make a list, shop after eating, stick to whole foods. Your cart reveals your health." },
            { id: 40, category: "nutrition", title: "Balanced Plate Method", difficulty: "Easy", description: "Fill half your plate with vegetables at each meal.", details: "1/2 plate vegetables, 1/4 protein, 1/4 whole grains. This simple visual ensures balanced nutrition without counting calories. Sustainable and effective for most people." },

            // Productivity Tips
            { id: 41, category: "productivity", title: "Pomodoro Technique", difficulty: "Easy", description: "Work in 25-minute focused bursts with 5-minute breaks.", details: "Set timer for 25 minutes of deep work, then take 5-minute break. After 4 rounds, take a 15-30 minute break. This maintains focus and prevents burnout." },
            { id: 42, category: "productivity", title: "Morning Prioritization", difficulty: "Easy", description: "Identify your 3 most important tasks each morning.", details: "Before checking email, list 3 must-do tasks. Complete these before anything else. This ensures your energy goes to what matters most. Everything else is secondary." },
            { id: 43, category: "productivity", title: "Time Blocking", difficulty: "Medium", description: "Schedule specific time blocks for different task types.", details: "Assign time blocks to activities: emails 9-10am, deep work 10-12pm, meetings 2-4pm. Treat these like appointments. This creates structure and reduces decision fatigue." },
            { id: 44, category: "productivity", title: "Two-Minute Rule", difficulty: "Easy", description: "If a task takes under 2 minutes, do it immediately.", details: "Quick emails, putting things away, brief calls—do them now. This prevents small tasks from accumulating and overwhelming you. Immediate action saves mental energy." },
            { id: 45, category: "productivity", title: "Batch Similar Tasks", difficulty: "Medium", description: "Group similar activities and complete them together.", details: "Answer all emails at once, make all calls consecutively, do all errands in one trip. Batching reduces context switching and improves efficiency significantly." },
            { id: 46, category: "productivity", title: "Energy Management", difficulty: "Medium", description: "Schedule demanding work during your peak energy hours.", details: "Identify when you're most alert (morning, afternoon, evening). Schedule creative and complex work then. Save admin tasks for low-energy times. Work with your natural rhythms." },
            { id: 47, category: "productivity", title: "Weekly Review Ritual", difficulty: "Medium", description: "Spend 30 minutes each week reviewing and planning.", details: "Review what worked, what didn't, and lessons learned. Plan upcoming week's priorities. This creates momentum and prevents you from drifting aimlessly through weeks." },
            { id: 48, category: "productivity", title: "Single Inbox Strategy", difficulty: "Advanced", description: "Process email to zero daily using sort-delete-do-defer.", details: "Check email 2-3x daily. Sort into folders, delete unnecessary, do quick tasks, defer others to task list. Inbox zero reduces mental clutter and increases clarity." },

            // Relationship Tips
            { id: 49, category: "relationships", title: "Active Listening Practice", difficulty: "Medium", description: "Focus fully on understanding, not just responding.", details: "Make eye contact, put phone away, don't interrupt. Reflect back what you heard: 'So you're saying...' Ask clarifying questions. Most conflicts stem from poor listening." },
            { id: 50, category: "relationships", title: "Express Appreciation Daily", difficulty: "Easy", description: "Tell someone you appreciate them every day.", details: "Be specific: 'I appreciate how you listened to me today.' Text, call, or say it in person. Regular appreciation strengthens all relationships. Make it a non-negotiable habit." },
            { id: 51, category: "relationships", title: "Quality Time Blocks", difficulty: "Easy", description: "Schedule undivided attention time with loved ones.", details: "30 minutes of phone-free, distraction-free connection. Talk, walk, play, or just be together. Consistency matters more than duration. Quality over quantity always wins." },
            { id: 52, category: "relationships", title: "Repair After Conflict", difficulty: "Medium", description: "Address disagreements within 24 hours.", details: "Acknowledge hurt, take responsibility for your part, express desire to reconnect. Don't let conflicts fester. Quick repair prevents resentment from building over time." },
            { id: 53, category: "relationships", title: "Ask Better Questions", difficulty: "Easy", description: "Move beyond 'how was your day' conversations.", details: "Ask: 'What was the best part of your day?' 'What's on your mind?' 'How are you really doing?' Open-ended questions invite deeper sharing and connection." },
            { id: 54, category: "relationships", title: "Relationship Check-Ins", difficulty: "Medium", description: "Monthly conversations about relationship health.", details: "Discuss what's working, what needs attention, and shared goals. This prevents small issues from becoming big ones. Proactive maintenance strengthens all relationships." },
            { id: 55, category: "relationships", title: "Show Love Languages", difficulty: "Easy", description: "Express affection in ways your loved ones prefer.", details: "Learn their love language: words, quality time, gifts, acts of service, or physical touch. Show love in THEIR language, not just yours. This ensures they feel loved." },
            { id: 56, category: "relationships", title: "Boundaries with Compassion", difficulty: "Medium", description: "Set limits while maintaining connection.", details: "Use 'I' statements: 'I need...' not 'You should...' Explain your boundary and its importance. Offer alternatives when possible. Clear boundaries create healthier relationships." },

            // Stress Management Tips
            { id: 57, category: "stress", title: "Box Breathing Technique", difficulty: "Easy", description: "Calm your nervous system with structured breathing.", details: "Inhale 4 counts, hold 4, exhale 4, hold 4. Repeat 5 times. This activates your calming parasympathetic nervous system. Use anytime you feel stressed." },
            { id: 58, category: "stress", title: "Worry Time Scheduling", difficulty: "Medium", description: "Contain anxious thoughts to a specific daily window.", details: "Set aside 15 minutes daily for worrying. When worries arise throughout the day, postpone them to worry time. This prevents anxiety from hijacking your entire day." },
            { id: 59, category: "stress", title: "Nature Therapy", difficulty: "Easy", description: "Spend 20 minutes in nature daily for stress relief.", details: "Walk in a park, sit under a tree, or tend a garden. Nature reduces cortisol and blood pressure. You don't need a forest—any green space works." },
            { id: 60, category: "stress", title: "Progressive Relaxation", difficulty: "Medium", description: "Release tension by systematically tensing and relaxing muscles.", details: "Tense each muscle group 5 seconds, release, notice difference. Start with feet, end with face. This teaches your body what relaxation feels like and reduces chronic tension." },
            { id: 61, category: "stress", title: "Stress Journal", difficulty: "Easy", description: "Track stress triggers to identify patterns.", details: "Note what stressed you, intensity (1-10), and how you responded. After 2 weeks, patterns emerge. Awareness is the first step to managing stress effectively." },
            { id: 62, category: "stress", title: "Laughter Breaks", difficulty: "Easy", description: "Watch or read something funny for 10 minutes daily.", details: "Laughter reduces stress hormones and boosts mood. Find comedy that works for you: stand-up, sitcoms, memes, funny podcasts. Schedule it like any other wellness practice." },
            { id: 63, category: "stress", title: "Stress Reset Ritual", difficulty: "Easy", description: "Create a 5-minute routine to shift out of stress.", details: "When overwhelmed, stop. Take 5 deep breaths, splash face with cold water, step outside, stretch. This interrupts the stress response and resets your nervous system." },
            { id: 64, category: "stress", title: "Delegation Practice", difficulty: "Advanced", description: "Identify tasks you can delegate or eliminate entirely.", details: "List all responsibilities. Mark: must do yourself, could delegate, could eliminate. Start delegating one task. Perfectionism keeps us stressed. Done is better than perfect." }
        ];

        let currentCategory = 'all';
        let searchQuery = '';
        let expandedTips = new Set();
        let bookmarkedTips = new Set();
        let showBookmarkedOnly = false;

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;
            const searchPlaceholder = config.search_placeholder || defaultConfig.search_placeholder;

            const navBar = document.querySelector('nav');
            navBar.style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('pageSubtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;
            document.getElementById('searchInput').placeholder = searchPlaceholder;
            document.getElementById('allCategories').textContent = config.all_categories || defaultConfig.all_categories;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.75}px`;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;
            document.getElementById('pageSubtitle').style.fontFamily = `${customFont}, sans-serif`;

            renderTips();
            updateStats();
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
                        ["search_placeholder", config.search_placeholder || defaultConfig.search_placeholder],
                        ["all_categories", config.all_categories || defaultConfig.all_categories]
                    ])
                });
            }

            setupEventListeners();
            renderTips();
            updateStats();
        }

        function setupEventListeners() {
            // Category filter
            document.querySelectorAll('.category-badge').forEach(badge => {
                badge.addEventListener('click', (e) => {
                    currentCategory = e.currentTarget.dataset.category;
                    updateActiveBadge(e.currentTarget);
                    renderTips();
                    updateStats();
                });
            });

            // Search
            document.getElementById('searchInput').addEventListener('input', (e) => {
                searchQuery = e.target.value.toLowerCase();
                renderTips();
                updateStats();
            });

            // View toggles
            document.getElementById('showAllBtn').addEventListener('click', () => {
                showBookmarkedOnly = false;
                updateViewToggle();
                renderTips();
                updateStats();
            });

            document.getElementById('showBookmarkedBtn').addEventListener('click', () => {
                showBookmarkedOnly = true;
                updateViewToggle();
                renderTips();
                updateStats();
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

        function updateViewToggle() {
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;

            const allBtn = document.getElementById('showAllBtn');
            const bookmarkedBtn = document.getElementById('showBookmarkedBtn');

            if (showBookmarkedOnly) {
                bookmarkedBtn.style.background = primaryColor;
                bookmarkedBtn.style.color = 'white';
                bookmarkedBtn.style.border = 'none';
                allBtn.style.background = 'white';
                allBtn.style.color = '#374151';
                allBtn.style.border = '2px solid #e5e7eb';
            } else {
                allBtn.style.background = primaryColor;
                allBtn.style.color = 'white';
                allBtn.style.border = 'none';
                bookmarkedBtn.style.background = 'white';
                bookmarkedBtn.style.color = '#374151';
                bookmarkedBtn.style.border = '2px solid #e5e7eb';
            }
        }

        function updateStats() {
            const filteredTips = getFilteredTips();
            document.getElementById('totalTips').textContent = tips.length;
            document.getElementById('bookmarkedCount').textContent = bookmarkedTips.size;
            document.getElementById('filteredCount').textContent = filteredTips.length;
        }

        function getFilteredTips() {
            let filtered = tips;

            // Category filter
            if (currentCategory !== 'all') {
                filtered = filtered.filter(tip => tip.category === currentCategory);
            }

            // Search filter
            if (searchQuery) {
                filtered = filtered.filter(tip => 
                    tip.title.toLowerCase().includes(searchQuery) ||
                    tip.description.toLowerCase().includes(searchQuery) ||
                    tip.details.toLowerCase().includes(searchQuery)
                );
            }

            // Bookmarked filter
            if (showBookmarkedOnly) {
                filtered = filtered.filter(tip => bookmarkedTips.has(tip.id));
            }

            return filtered;
        }

        function renderTips() {
            const container = document.getElementById('tipsContainer');
            const noResults = document.getElementById('noResults');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            const filteredTips = getFilteredTips();

            if (filteredTips.length === 0) {
                container.style.display = 'none';
                noResults.style.display = 'block';
                return;
            }

            container.style.display = 'grid';
            noResults.style.display = 'none';

            const categoryColors = {
                mindfulness: '#8b5cf6',
                physical: '#10b981',
                emotional: '#ec4899',
                sleep: '#6366f1',
                nutrition: '#f59e0b',
                productivity: '#3b82f6',
                relationships: '#ef4444',
                stress: '#14b8a6'
            };

            const difficultyColors = {
                'Easy': '#10b981',
                'Medium': '#f59e0b',
                'Advanced': '#ef4444'
            };

            container.innerHTML = filteredTips.map(tip => {
                const isExpanded = expandedTips.has(tip.id);
                const isBookmarked = bookmarkedTips.has(tip.id);
                const categoryColor = categoryColors[tip.category];

                return `
                    <div class="tip-card" style="background: ${cardColor}; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-left: 4px solid ${categoryColor}; position: relative; font-family: ${customFont}, sans-serif;">
                        <button 
                            onclick="toggleBookmark(${tip.id})" 
                            class="bookmark-btn ${isBookmarked ? 'bookmarked' : ''}"
                            style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; cursor: pointer; font-size: ${baseFontSize * 1.25}px; color: ${isBookmarked ? '#f59e0b' : '#d1d5db'};"
                        >
                            <i class="fas fa-bookmark"></i>
                        </button>

                        <h3 style="margin: 0 0 0.75rem 0; font-size: ${baseFontSize * 1.125}px; font-weight: 700; color: ${textColor}; padding-right: 2rem;">${tip.title}</h3>

                        <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <span style="background: ${categoryColor}20; color: ${categoryColor}; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: ${baseFontSize * 0.75}px; font-weight: 600; text-transform: capitalize;">
                                ${tip.category}
                            </span>
                            <span style="background: ${difficultyColors[tip.difficulty]}20; color: ${difficultyColors[tip.difficulty]}; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: ${baseFontSize * 0.75}px; font-weight: 600;">
                                ${tip.difficulty}
                            </span>
                        </div>

                        <p style="margin: 0 0 1rem 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.6;">${tip.description}</p>

                        ${isExpanded ? `
                            <div style="padding: 1rem; background: #f9fafb; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 3px solid ${categoryColor};">
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: ${textColor}; line-height: 1.7; font-weight: 500;">${tip.details}</p>
                            </div>
                        ` : ''}

                        <button onclick="toggleTip(${tip.id})" style="width: 100%; padding: 0.75rem; background: ${primaryColor}; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                            <span>${isExpanded ? 'Show Less' : 'Show Details'}</span>
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

        function toggleBookmark(tipId) {
            if (bookmarkedTips.has(tipId)) {
                bookmarkedTips.delete(tipId);
            } else {
                bookmarkedTips.add(tipId);
            }
            renderTips();
            updateStats();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a83d1b171640dcb',t:'MTc2NDc3MjgyNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>