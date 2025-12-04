<?php
// privacy_policy.php
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.7;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100%;
            width: 100%;
        }
        
        * {
            box-sizing: border-box;
        }
        
        .page-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 3rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 900px;
            width: 100%;
            background: #ffffff;
            padding: 3.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 3px solid #f0f0f0;
        }
        
        .privacy-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }
        
        h1 {
            font-size: 2.75rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 0.75rem 0;
            letter-spacing: -0.5px;
        }
        
        .last-updated {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        .intro-text {
            background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #4facfe;
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
            color: #4b5563;
        }
        
        section {
            margin-bottom: 2.5rem;
        }
        
        .policy-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #ffffff;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .policy-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
            letter-spacing: -0.3px;
        }
        
        .policy-content {
            padding-left: 3.25rem;
            color: #4b5563;
            font-size: 1.05rem;
        }
        
        .policy-content p {
            margin: 0.75rem 0;
        }
        
        .highlight-box {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 1.25rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .highlight-box strong {
            color: #1e40af;
        }
        
        .footer-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 3px solid #f0f0f0;
            text-align: center;
        }
        
        .btn-back {
            display: inline-block;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #ffffff;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.5);
        }
        
        .contact-info {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 12px;
            text-align: left;
        }
        
        .contact-info h3 {
            font-size: 1.25rem;
            color: #1a1a2e;
            margin: 0 0 1rem 0;
        }
        
        .contact-info p {
            color: #6b7280;
            margin: 0.5rem 0;
        }
        
        .contact-info a {
            color: #4facfe;
            text-decoration: none;
            font-weight: 500;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        .data-rights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .data-right-card {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid #4facfe;
        }
        
        .data-right-card strong {
            color: #0369a1;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .page-wrapper {
                padding: 2rem 1rem;
            }
            
            .container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.25rem;
            }
            
            .policy-content {
                padding-left: 0;
                margin-top: 1rem;
            }
            
            .policy-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .policy-number {
                margin-bottom: 0.75rem;
            }
            
            .privacy-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <div class="page-wrapper">
   <div class="container">
    <div class="header">
     <div class="privacy-icon">
      🔒
     </div>
     <h1 id="main-title">Privacy Policy</h1>
     <p class="last-updated" id="last-updated">Last Updated: January 2025</p>
    </div>
    <div class="intro-text" id="intro-text">
     Your privacy is important to us. This Privacy Policy explains how we collect, use, protect, and share your personal information when you use our platform. By using our services, you agree to the practices described in this policy.
    </div>
    <section>
     <div class="policy-header"><span class="policy-number">1</span>
      <h2 id="section-1-title">Information Collection</h2>
     </div>
     <div class="policy-content">
      <p id="section-1-content">We collect personal information, such as name, email address, contact number, and relationship to the student, to provide our services effectively. This information is provided voluntarily when you create an account or interact with our platform.</p>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">2</span>
      <h2 id="section-2-title">Use of Information</h2>
     </div>
     <div class="policy-content">
      <p id="section-2-content">Your information is used to manage user accounts, communicate important updates, facilitate communication with educational institutions, and improve overall user experience. We use your data only for legitimate purposes related to the services we provide.</p>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">3</span>
      <h2 id="section-3-title">Data Sharing and Disclosure</h2>
     </div>
     <div class="policy-content">
      <div class="highlight-box"><strong>Your Privacy Matters:</strong> We do not sell or rent your personal information to third parties for marketing purposes.
      </div>
      <p id="section-3-content">Your data may be shared with authorized school personnel and administrators only for legitimate educational and administrative purposes. We may also disclose information when required by law or to protect the rights and safety of our users.</p>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">4</span>
      <h2 id="section-4-title">Data Security</h2>
     </div>
     <div class="policy-content">
      <p id="section-4-content">We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. This includes encryption, secure servers, and regular security audits. However, no method of transmission over the internet is 100% secure.</p>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">5</span>
      <h2 id="section-5-title">Your Rights</h2>
     </div>
     <div class="policy-content">
      <p id="section-5-content">You have the right to access, correct, update, or request deletion of your personal data at any time. You may also object to certain data processing activities or request data portability. To exercise these rights, please contact our support team.</p>
      <div class="data-rights-grid">
       <div class="data-right-card"><strong>Access</strong> Request a copy of your data
       </div>
       <div class="data-right-card"><strong>Correction</strong> Update inaccurate information
       </div>
       <div class="data-right-card"><strong>Deletion</strong> Request data removal
       </div>
       <div class="data-right-card"><strong>Portability</strong> Export your data
       </div>
      </div>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">6</span>
      <h2 id="section-6-title">Cookies and Tracking Technologies</h2>
     </div>
     <div class="policy-content">
      <p id="section-6-content">We may use cookies, web beacons, and similar tracking technologies to enhance your experience, analyze usage patterns, remember your preferences, and provide personalized content. You can control cookie settings through your browser preferences.</p>
     </div>
    </section>
    <section>
     <div class="policy-header"><span class="policy-number">7</span>
      <h2 id="section-7-title">Policy Updates</h2>
     </div>
     <div class="policy-content">
      <p id="section-7-content">This Privacy Policy may be updated periodically to reflect changes in our practices or legal requirements. Any significant updates will be posted on this page with a revised "Last Updated" date. We encourage you to review this policy regularly to stay informed about how we protect your information.</p>
     </div>
    </section>
    <div class="footer-section"><a href="parent_sign_up.php" class="btn-back" id="back-button">← Return to Sign Up</a>
    </div>
   </div>
  </div>
  <script>
        const defaultConfig = {
            background_gradient_start: "#4facfe",
            background_gradient_end: "#00f2fe",
            surface_color: "#ffffff",
            text_color: "#1a1a2e",
            primary_action_color: "#4facfe",
            font_family: "Inter",
            font_size: 16,
            main_title: "Privacy Policy",
            last_updated: "Last Updated: January 2025",
            intro_text: "Your privacy is important to us. This Privacy Policy explains how we collect, use, protect, and share your personal information when you use our platform. By using our services, you agree to the practices described in this policy.",
            section_1_title: "Information Collection",
            section_1_content: "We collect personal information, such as name, email address, contact number, and relationship to the student, to provide our services effectively. This information is provided voluntarily when you create an account or interact with our platform.",
            section_2_title: "Use of Information",
            section_2_content: "Your information is used to manage user accounts, communicate important updates, facilitate communication with educational institutions, and improve overall user experience. We use your data only for legitimate purposes related to the services we provide.",
            section_3_title: "Data Sharing and Disclosure",
            section_3_content: "Your data may be shared with authorized school personnel and administrators only for legitimate educational and administrative purposes. We may also disclose information when required by law or to protect the rights and safety of our users.",
            section_4_title: "Data Security",
            section_4_content: "We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. This includes encryption, secure servers, and regular security audits. However, no method of transmission over the internet is 100% secure.",
            section_5_title: "Your Rights",
            section_5_content: "You have the right to access, correct, update, or request deletion of your personal data at any time. You may also object to certain data processing activities or request data portability. To exercise these rights, please contact our support team.",
            section_6_title: "Cookies and Tracking Technologies",
            section_6_content: "We may use cookies, web beacons, and similar tracking technologies to enhance your experience, analyze usage patterns, remember your preferences, and provide personalized content. You can control cookie settings through your browser preferences.",
            section_7_title: "Policy Updates",
            section_7_content: "This Privacy Policy may be updated periodically to reflect changes in our practices or legal requirements. Any significant updates will be posted on this page with a revised \"Last Updated\" date. We encourage you to review this policy regularly to stay informed about how we protect your information.",
            contact_title: "Have Questions?",
            contact_description: "If you have questions or concerns about our Privacy Policy or how we handle your data, please reach out:",
            contact_email: "Email: privacy@example.com",
            back_button: "← Return to Sign Up"
        };

        async function onConfigChange(config) {
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontStack = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';

            document.body.style.background = `linear-gradient(135deg, ${config.background_gradient_start || defaultConfig.background_gradient_start} 0%, ${config.background_gradient_end || defaultConfig.background_gradient_end} 100%)`;
            document.body.style.fontFamily = `${customFont}, ${baseFontStack}`;
            document.body.style.fontSize = `${baseFontSize}px`;

            const container = document.querySelector('.container');
            if (container) {
                container.style.background = config.surface_color || defaultConfig.surface_color;
            }

            const textElements = document.querySelectorAll('h1, h2, .policy-content, .intro-text, .contact-info p, .contact-info h3, .last-updated');
            textElements.forEach(el => {
                if (el.tagName === 'H1') {
                    el.style.fontSize = `${baseFontSize * 2.75}px`;
                    el.style.color = config.text_color || defaultConfig.text_color;
                    el.style.fontFamily = `${customFont}, ${baseFontStack}`;
                } else if (el.tagName === 'H2') {
                    el.style.fontSize = `${baseFontSize * 1.5}px`;
                    el.style.color = config.text_color || defaultConfig.text_color;
                    el.style.fontFamily = `${customFont}, ${baseFontStack}`;
                } else if (el.classList.contains('contact-info') && el.tagName === 'H3') {
                    el.style.fontSize = `${baseFontSize * 1.25}px`;
                    el.style.color = config.text_color || defaultConfig.text_color;
                    el.style.fontFamily = `${customFont}, ${baseFontStack}`;
                } else {
                    el.style.fontFamily = `${customFont}, ${baseFontStack}`;
                }
            });

            const policyNumbers = document.querySelectorAll('.policy-number');
            policyNumbers.forEach(num => {
                num.style.background = `linear-gradient(135deg, ${config.primary_action_color || defaultConfig.primary_action_color} 0%, ${config.background_gradient_end || defaultConfig.background_gradient_end} 100%)`;
            });

            const btnBack = document.querySelector('.btn-back');
            if (btnBack) {
                btnBack.style.background = `linear-gradient(135deg, ${config.primary_action_color || defaultConfig.primary_action_color} 0%, ${config.background_gradient_end || defaultConfig.background_gradient_end} 100%)`;
            }

            const privacyIcon = document.querySelector('.privacy-icon');
            if (privacyIcon) {
                privacyIcon.style.background = `linear-gradient(135deg, ${config.primary_action_color || defaultConfig.primary_action_color} 0%, ${config.background_gradient_end || defaultConfig.background_gradient_end} 100%)`;
            }

            document.getElementById('main-title').textContent = config.main_title || defaultConfig.main_title;
            document.getElementById('last-updated').textContent = config.last_updated || defaultConfig.last_updated;
            document.getElementById('intro-text').textContent = config.intro_text || defaultConfig.intro_text;
            document.getElementById('section-1-title').textContent = config.section_1_title || defaultConfig.section_1_title;
            document.getElementById('section-1-content').textContent = config.section_1_content || defaultConfig.section_1_content;
            document.getElementById('section-2-title').textContent = config.section_2_title || defaultConfig.section_2_title;
            document.getElementById('section-2-content').textContent = config.section_2_content || defaultConfig.section_2_content;
            document.getElementById('section-3-title').textContent = config.section_3_title || defaultConfig.section_3_title;
            document.getElementById('section-3-content').textContent = config.section_3_content || defaultConfig.section_3_content;
            document.getElementById('section-4-title').textContent = config.section_4_title || defaultConfig.section_4_title;
            document.getElementById('section-4-content').textContent = config.section_4_content || defaultConfig.section_4_content;
            document.getElementById('section-5-title').textContent = config.section_5_title || defaultConfig.section_5_title;
            document.getElementById('section-5-content').textContent = config.section_5_content || defaultConfig.section_5_content;
            document.getElementById('section-6-title').textContent = config.section_6_title || defaultConfig.section_6_title;
            document.getElementById('section-6-content').textContent = config.section_6_content || defaultConfig.section_6_content;
            document.getElementById('section-7-title').textContent = config.section_7_title || defaultConfig.section_7_title;
            document.getElementById('section-7-content').textContent = config.section_7_content || defaultConfig.section_7_content;
            document.getElementById('contact-title').textContent = config.contact_title || defaultConfig.contact_title;
            document.getElementById('contact-description').textContent = config.contact_description || defaultConfig.contact_description;
            document.getElementById('contact-email').innerHTML = config.contact_email || defaultConfig.contact_email;
            document.getElementById('back-button').textContent = config.back_button || defaultConfig.back_button;
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [
                        {
                            get: () => config.background_gradient_start || defaultConfig.background_gradient_start,
                            set: (value) => {
                                config.background_gradient_start = value;
                                window.elementSdk.setConfig({ background_gradient_start: value });
                            }
                        },
                        {
                            get: () => config.background_gradient_end || defaultConfig.background_gradient_end,
                            set: (value) => {
                                config.background_gradient_end = value;
                                window.elementSdk.setConfig({ background_gradient_end: value });
                            }
                        },
                        {
                            get: () => config.surface_color || defaultConfig.surface_color,
                            set: (value) => {
                                config.surface_color = value;
                                window.elementSdk.setConfig({ surface_color: value });
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
                    ["main_title", config.main_title || defaultConfig.main_title],
                    ["last_updated", config.last_updated || defaultConfig.last_updated],
                    ["intro_text", config.intro_text || defaultConfig.intro_text],
                    ["section_1_title", config.section_1_title || defaultConfig.section_1_title],
                    ["section_1_content", config.section_1_content || defaultConfig.section_1_content],
                    ["section_2_title", config.section_2_title || defaultConfig.section_2_title],
                    ["section_2_content", config.section_2_content || defaultConfig.section_2_content],
                    ["section_3_title", config.section_3_title || defaultConfig.section_3_title],
                    ["section_3_content", config.section_3_content || defaultConfig.section_3_content],
                    ["section_4_title", config.section_4_title || defaultConfig.section_4_title],
                    ["section_4_content", config.section_4_content || defaultConfig.section_4_content],
                    ["section_5_title", config.section_5_title || defaultConfig.section_5_title],
                    ["section_5_content", config.section_5_content || defaultConfig.section_5_content],
                    ["section_6_title", config.section_6_title || defaultConfig.section_6_title],
                    ["section_6_content", config.section_6_content || defaultConfig.section_6_content],
                    ["section_7_title", config.section_7_title || defaultConfig.section_7_title],
                    ["section_7_content", config.section_7_content || defaultConfig.section_7_content],
                    ["contact_title", config.contact_title || defaultConfig.contact_title],
                    ["contact_description", config.contact_description || defaultConfig.contact_description],
                    ["contact_email", config.contact_email || defaultConfig.contact_email],
                    ["back_button", config.back_button || defaultConfig.back_button]
                ])
            });

            onConfigChange(window.elementSdk.config);
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a362707d4160dc9',t:'MTc2Mzk1ODQzMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>