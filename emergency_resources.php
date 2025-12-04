<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emergency Resources</title>
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

        .resource-card {
            transition: all 0.3s ease;
        }

        .resource-card:hover {
            transform: translateY(-2px);
        }

        .hotline-card {
            transition: all 0.3s ease;
        }

        .hotline-card:hover {
            transform: scale(1.02);
        }

        .expand-btn {
            transition: transform 0.3s ease;
        }

        .expand-btn.rotated {
            transform: rotate(180deg);
        }

        .pulse-dot {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .checkbox-item {
            transition: all 0.2s ease;
        }

        .checkbox-item:hover {
            background: rgba(102, 126, 234, 0.05);
        }
    </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%;"><!-- Navigation -->
   <nav class="gradient-bg" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); padding: 1.5rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-shield-heart" style="font-size: 1.75rem; color: white;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.75rem; font-weight: 700; color: white;">Emergency Resources</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; color: white; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s; background: rgba(255, 255, 255, 0.2);"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1200px; margin: 0 auto; padding: 2rem; background: #f9fafb; min-height: 100%;"><!-- Alert Banner -->
    <div style="background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; align-items: start; gap: 1rem;">
      <div style="flex-shrink: 0;">
       <div class="pulse-dot" style="width: 12px; height: 12px; background: #ef4444; border-radius: 50%; margin-top: 0.375rem;"></div>
      </div>
      <div>
       <h3 id="pageSubtitle" style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 700; color: #991b1b;">Immediate help and support services</h3>
       <p style="margin: 0; font-size: 0.875rem; color: #7f1d1d; line-height: 1.6;">If you're in immediate danger, call <strong>911</strong>. These resources are available 24/7 and are free and confidential. You are not alone.</p>
      </div>
     </div>
    </div><!-- Crisis Hotlines Section -->
    <div style="margin-bottom: 2rem;">
     <h2 id="crisisSectionTitle" style="font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0 0 1.5rem 0;">Crisis Hotlines</h2>
     <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;"><!-- 988 Suicide & Crisis Lifeline -->
      <div class="hotline-card" style="background: white; border: 3px solid #ef4444; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;"><i class="fas fa-phone-volume" style="font-size: 1.5rem; color: #ef4444;"></i>
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1f2937;">Suicide &amp; Crisis Lifeline</h3>
       </div>
       <p style="margin: 0 0 1rem 0; font-size: 2rem; font-weight: 700; color: #ef4444;">988</p>
       <p style="margin: 0 0 1rem 0; font-size: 0.875rem; color: #6b7280; line-height: 1.6;">Free, confidential support 24/7 for people in distress, prevention and crisis resources.</p><a href="tel:988" style="display: block; width: 100%; padding: 0.75rem; background: #ef4444; color: white; text-align: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: 0.875rem;"> <i class="fas fa-phone"></i> Call Now </a>
      </div><!-- Crisis Text Line -->
      <div class="hotline-card" style="background: white; border: 3px solid #3b82f6; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;"><i class="fas fa-comments" style="font-size: 1.5rem; color: #3b82f6;"></i>
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1f2937;">Crisis Text Line</h3>
       </div>
       <p style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 700; color: #3b82f6;">Text HOME to 741741</p>
       <p style="margin: 0 0 1rem 0; font-size: 0.875rem; color: #6b7280; line-height: 1.6;">Free 24/7 support via text message. Trained crisis counselors available.</p><a href="sms:741741?body=HOME" style="display: block; width: 100%; padding: 0.75rem; background: #3b82f6; color: white; text-align: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: 0.875rem;"> <i class="fas fa-comment"></i> Text Now </a>
      </div><!-- SAMHSA National Helpline -->
      <div class="hotline-card" style="background: white; border: 3px solid #10b981; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;"><i class="fas fa-hand-holding-heart" style="font-size: 1.5rem; color: #10b981;"></i>
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1f2937;">SAMHSA Helpline</h3>
       </div>
       <p style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 700; color: #10b981;">1-800-662-4357</p>
       <p style="margin: 0 0 1rem 0; font-size: 0.875rem; color: #6b7280; line-height: 1.6;">Mental health and substance abuse treatment referral and information service.</p><a href="tel:1-800-662-4357" style="display: block; width: 100%; padding: 0.75rem; background: #10b981; color: white; text-align: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: 0.875rem;"> <i class="fas fa-phone"></i> Call Now </a>
      </div><!-- Emergency Services -->
      <div class="hotline-card" style="background: white; border: 3px solid #f59e0b; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
       <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;"><i class="fas fa-ambulance" style="font-size: 1.5rem; color: #f59e0b;"></i>
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1f2937;">Emergency Services</h3>
       </div>
       <p style="margin: 0 0 1rem 0; font-size: 2rem; font-weight: 700; color: #f59e0b;">911</p>
       <p style="margin: 0 0 1rem 0; font-size: 0.875rem; color: #6b7280; line-height: 1.6;">For immediate danger, medical emergencies, or threats to safety.</p><a href="tel:911" style="display: block; width: 100%; padding: 0.75rem; background: #f59e0b; color: white; text-align: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: 0.875rem;"> <i class="fas fa-phone"></i> Call 911 </a>
      </div>
     </div>
    </div><!-- Specialized Support -->
    <div style="margin-bottom: 2rem;">
     <h2 id="specializedSectionTitle" style="font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0 0 1.5rem 0;">Specialized Support</h2>
     <div id="specializedContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1rem;"><!-- Specialized resources will be rendered here -->
     </div>
    </div><!-- Warning Signs -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
      <div style="background: #fef3c7; width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;"><i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: #f59e0b;"></i>
      </div>
      <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">When to Reach Out</h2>
     </div>
     <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Thoughts of self-harm</strong> or feeling like life isn't worth living</p>
      </div>
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Overwhelming anxiety</strong> or panic that won't subside</p>
      </div>
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Unable to care</strong> for yourself or daily activities</p>
      </div>
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Substance abuse</strong> that's out of control</p>
      </div>
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Experiencing abuse</strong> or unsafe living situation</p>
      </div>
      <div style="padding: 1rem; background: #fef3c7; border-radius: 0.5rem; border-left: 4px solid #f59e0b;">
       <p style="margin: 0; font-size: 0.875rem; color: #78350f; line-height: 1.6;"><strong>Extreme mood changes</strong> or disconnection from reality</p>
      </div>
     </div>
    </div><!-- Safety Plan -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
      <div style="background: #dbeafe; width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;"><i class="fas fa-clipboard-check" style="font-size: 1.5rem; color: #3b82f6;"></i>
      </div>
      <h2 id="safetyPlanTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Create a Safety Plan</h2>
     </div>
     <p style="margin: 0 0 1.5rem 0; font-size: 0.875rem; color: #6b7280; line-height: 1.6;">A safety plan helps you prepare for difficult moments. Check off the steps you've completed:</p>
     <div id="safetyPlanContainer"><!-- Safety plan items will be rendered here -->
     </div>
    </div><!-- International Resources -->
    <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
      <div style="background: #e0e7ff; width: 50px; height: 50px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;"><i class="fas fa-globe" style="font-size: 1.5rem; color: #6366f1;"></i>
      </div>
      <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">International Resources</h2>
     </div>
     <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🇨🇦 Canada</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">1-833-456-4566</p>
      </div>
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🇬🇧 UK</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">116 123 (Samaritans)</p>
      </div>
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🇦🇺 Australia</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">13 11 14 (Lifeline)</p>
      </div>
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🇳🇿 New Zealand</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">1737</p>
      </div>
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🇮🇪 Ireland</p>
       <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">116 123 (Samaritans)</p>
      </div>
      <div style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem;">
       <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">🌍 More Countries</p><a href="https://findahelpline.com" target="_blank" rel="noopener noreferrer" style="font-size: 0.875rem; color: #3b82f6; text-decoration: none;">findahelpline.com</a>
      </div>
     </div>
    </div>
   </main>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Emergency Resources",
            page_subtitle: "Immediate help and support services",
            crisis_section_title: "Crisis Hotlines",
            specialized_section_title: "Specialized Support",
            safety_plan_title: "Create a Safety Plan",
            background_color: "#667eea",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#667eea",
            secondary_action_color: "#3b82f6",
            font_family: "Inter",
            font_size: 16
        };

        const specializedResources = [
            {
                id: 1,
                title: "Domestic Violence Hotline",
                icon: "fa-home-heart",
                color: "#8b5cf6",
                phone: "1-800-799-7233",
                description: "Support for those experiencing domestic violence or abuse.",
                details: "24/7 support, safety planning, and resources for victims of domestic violence and their families. Chat available at thehotline.org"
            },
            {
                id: 2,
                title: "LGBTQ+ Youth Support",
                icon: "fa-rainbow",
                color: "#ec4899",
                phone: "1-866-488-7386",
                description: "Trevor Project for LGBTQ+ youth in crisis.",
                details: "Crisis intervention and suicide prevention for LGBTQ+ young people. Also available via text (678678) and chat at thetrevorproject.org"
            },
            {
                id: 3,
                title: "Veterans Crisis Line",
                icon: "fa-flag-usa",
                color: "#059669",
                phone: "988 (Press 1)",
                description: "24/7 support for veterans and their families.",
                details: "Confidential help for veterans, service members, National Guard, Reserve members, and their families. Text 838255."
            },
            {
                id: 4,
                title: "Sexual Assault Hotline",
                icon: "fa-hands-helping",
                color: "#dc2626",
                phone: "1-800-656-4673",
                description: "RAINN National Sexual Assault Hotline.",
                details: "Free, confidential support for survivors of sexual assault and their loved ones. Online chat available at rainn.org"
            },
            {
                id: 5,
                title: "Teen Line",
                icon: "fa-user-friends",
                color: "#f59e0b",
                phone: "1-800-852-8336",
                description: "Teens helping teens through tough times.",
                details: "Call or text TEEN to 839863. Hours: 6pm-10pm PST daily. Teen-to-teen support for those struggling."
            },
            {
                id: 6,
                title: "Disaster Distress Helpline",
                icon: "fa-cloud-sun",
                color: "#0ea5e9",
                phone: "1-800-985-5990",
                description: "Support for disaster-related emotional distress.",
                details: "24/7 crisis counseling for those affected by natural disasters or human-caused events. Text TalkWithUs to 66746."
            }
        ];

        const safetyPlanSteps = [
            "Identify warning signs that a crisis might be developing",
            "List internal coping strategies (activities you can do alone)",
            "Identify people who can distract you in a healthy way",
            "List people you can ask for help during a crisis",
            "Write down professional contacts and crisis hotlines",
            "Remove or secure means of self-harm from your environment"
        ];

        let expandedResources = new Set();
        let checkedSafetySteps = new Set();

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;
            const textColor = config.text_color || defaultConfig.text_color;

            const navBar = document.querySelector('nav');
            navBar.style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('pageSubtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;
            document.getElementById('crisisSectionTitle').textContent = config.crisis_section_title || defaultConfig.crisis_section_title;
            document.getElementById('specializedSectionTitle').textContent = config.specialized_section_title || defaultConfig.specialized_section_title;
            document.getElementById('safetyPlanTitle').textContent = config.safety_plan_title || defaultConfig.safety_plan_title;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.75}px`;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;
            document.getElementById('pageTitle').style.color = 'white';

            const allHeadings = document.querySelectorAll('h2, h3');
            allHeadings.forEach(heading => {
                heading.style.fontFamily = `${customFont}, sans-serif`;
                if (heading.tagName === 'H2') {
                    heading.style.color = textColor;
                }
            });

            renderSpecializedResources();
            renderSafetyPlan();
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
                        ["crisis_section_title", config.crisis_section_title || defaultConfig.crisis_section_title],
                        ["specialized_section_title", config.specialized_section_title || defaultConfig.specialized_section_title],
                        ["safety_plan_title", config.safety_plan_title || defaultConfig.safety_plan_title]
                    ])
                });
            }

            renderSpecializedResources();
            renderSafetyPlan();
        }

        function renderSpecializedResources() {
            const container = document.getElementById('specializedContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            container.innerHTML = specializedResources.map(resource => {
                const isExpanded = expandedResources.has(resource.id);

                return `
                    <div class="resource-card" style="background: ${cardColor}; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 2px solid ${resource.color}; font-family: ${customFont}, sans-serif;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="background: ${resource.color}; color: white; width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas ${resource.icon}" style="font-size: ${baseFontSize * 1}px;"></i>
                            </div>
                            <h3 style="margin: 0; font-size: ${baseFontSize * 1}px; font-weight: 600; color: ${textColor};">${resource.title}</h3>
                        </div>

                        <p style="margin: 0 0 0.75rem 0; font-size: ${baseFontSize * 1.125}px; font-weight: 700; color: ${resource.color};">${resource.phone}</p>
                        <p style="margin: 0 0 1rem 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.6;">${resource.description}</p>

                        ${isExpanded ? `
                            <div style="padding: 1rem; background: #f9fafb; border-radius: 0.5rem; margin-bottom: 1rem;">
                                <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: ${textColor}; line-height: 1.6;">${resource.details}</p>
                            </div>
                        ` : ''}

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="tel:${resource.phone}" style="flex: 1; padding: 0.75rem; background: ${resource.color}; color: white; text-align: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                <i class="fas fa-phone"></i> Call
                            </a>
                            <button onclick="toggleResource(${resource.id})" style="padding: 0.75rem 1rem; background: #f3f4f6; color: ${textColor}; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                <i id="icon-${resource.id}" class="fas fa-chevron-down expand-btn ${isExpanded ? 'rotated' : ''}" style="font-size: ${baseFontSize * 0.875}px;"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleResource(resourceId) {
            if (expandedResources.has(resourceId)) {
                expandedResources.delete(resourceId);
            } else {
                expandedResources.add(resourceId);
            }
            renderSpecializedResources();
        }

        function renderSafetyPlan() {
            const container = document.getElementById('safetyPlanContainer');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            container.innerHTML = safetyPlanSteps.map((step, index) => {
                const stepId = `step-${index}`;
                const isChecked = checkedSafetySteps.has(stepId);

                return `
                    <div class="checkbox-item" onclick="toggleSafetyStep('${stepId}')" style="padding: 1rem; border-radius: 0.5rem; cursor: pointer; display: flex; align-items: start; gap: 1rem; margin-bottom: 0.75rem; border: 2px solid ${isChecked ? primaryColor : '#e5e7eb'}; font-family: ${customFont}, sans-serif; ${isChecked ? `background: ${primaryColor}10;` : ''}">
                        <div style="width: 24px; height: 24px; border: 2px solid ${isChecked ? primaryColor : '#d1d5db'}; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; background: ${isChecked ? primaryColor : 'white'}; flex-shrink: 0; margin-top: 0.125rem;">
                            ${isChecked ? `<i class="fas fa-check" style="color: white; font-size: ${baseFontSize * 0.75}px;"></i>` : ''}
                        </div>
                        <span style="font-size: ${baseFontSize * 0.9375}px; color: ${textColor}; line-height: 1.6; ${isChecked ? 'text-decoration: line-through; opacity: 0.7;' : ''}">
                            ${step}
                        </span>
                    </div>
                `;
            }).join('');
        }

        function toggleSafetyStep(stepId) {
            if (checkedSafetySteps.has(stepId)) {
                checkedSafetySteps.delete(stepId);
            } else {
                checkedSafetySteps.add(stepId);
            }
            renderSafetyPlan();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a83c1a3f1500dcb',t:'MTc2NDc3MjE2OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>