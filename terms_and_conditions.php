<?php
// terms_and_conditions.php
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms and Conditions</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.7;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
            color: #4b5563;
        }
        
        section {
            margin-bottom: 2.5rem;
        }
        
        .term-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .term-header {
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
        
        .term-content {
            padding-left: 3.25rem;
            color: #4b5563;
            font-size: 1.05rem;
        }
        
        .term-content p {
            margin: 0.75rem 0;
        }
        
        .highlight-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1.25rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .highlight-box strong {
            color: #92400e;
        }
        
        .footer-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 3px solid #f0f0f0;
            text-align: center;
        }
        
        .btn-back {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
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
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
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
            
            .term-content {
                padding-left: 0;
                margin-top: 1rem;
            }
            
            .term-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .term-number {
                margin-bottom: 0.75rem;
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
     <h1 id="main-title">Terms and Conditions</h1>
     <p class="last-updated" id="last-updated">Last Updated: January 2025</p>
    </div>
    <div class="intro-text" id="intro-text">
     Please read these terms and conditions carefully before using our platform. By creating an account, you acknowledge that you have read, understood, and agree to be bound by these terms.
    </div>
    <section>
     <div class="term-header"><span class="term-number">1</span>
      <h2 id="term-1-title">Acceptance of Terms</h2>
     </div>
     <div class="term-content">
      <p id="term-1-content">By creating an account and using this platform, you agree to comply with these Terms and Conditions. If you do not agree with any part of these terms, you should not use this service. Your continued use of the platform constitutes acceptance of these terms.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">2</span>
      <h2 id="term-2-title">Eligibility</h2>
     </div>
     <div class="term-content">
      <p id="term-2-content">This service is intended for parents or guardians of students. You must be at least 18 years old to register and use this platform. By registering, you represent and warrant that you meet these eligibility requirements.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">3</span>
      <h2 id="term-3-title">Account Responsibility</h2>
     </div>
     <div class="term-content">
      <p id="term-3-content">You are responsible for maintaining the confidentiality of your account information, including your username and password. You agree to notify us immediately of any unauthorized access or use of your account. Any activity under your account is your responsibility, and you will be held accountable for all actions taken.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">4</span>
      <h2 id="term-4-title">Acceptable Use</h2>
     </div>
     <div class="term-content">
      <p id="term-4-content">You agree to use the platform for lawful purposes only. You shall not engage in any activity that may disrupt, harm, or compromise the platform or its users. This includes, but is not limited to, transmitting harmful code, attempting unauthorized access, or harassing other users.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">5</span>
      <h2 id="term-5-title">Intellectual Property</h2>
     </div>
     <div class="term-content">
      <p id="term-5-content">All content on this platform, including text, graphics, logos, and software, is the property of the platform or its licensors unless otherwise stated. You may not copy, reproduce, distribute, or create derivative works from any content without explicit written permission.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">6</span>
      <h2 id="term-6-title">Privacy and Data Protection</h2>
     </div>
     <div class="term-content">
      <p id="term-6-content">We are committed to protecting your privacy and personal information. Your use of the platform is also governed by our Privacy Policy, which explains how we collect, use, and safeguard your data. Please review our Privacy Policy to understand our practices.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">7</span>
      <h2 id="term-7-title">Limitation of Liability</h2>
     </div>
     <div class="term-content">
      <div class="highlight-box"><strong>Important Notice:</strong> The platform is provided "as is" without warranties of any kind, either express or implied.
      </div>
      <p id="term-7-content">We are not liable for any direct, indirect, incidental, consequential, or punitive damages arising from your use or inability to use the platform. This includes, but is not limited to, damages for loss of profits, data, or other intangible losses.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">8</span>
      <h2 id="term-8-title">Modifications to Terms</h2>
     </div>
     <div class="term-content">
      <p id="term-8-content">We reserve the right to update, modify, or replace these Terms and Conditions at any time at our sole discretion. Changes will be posted on this page with an updated "Last Updated" date. Your continued use of the platform after any changes constitutes acceptance of the new terms.</p>
     </div>
    </section>
    <section>
     <div class="term-header"><span class="term-number">9</span>
      <h2 id="term-9-title">Termination</h2>
     </div>
     <div class="term-content">
      <p id="term-9-content">We reserve the right to terminate or suspend your account and access to the platform at our discretion, without notice, for conduct that we believe violates these Terms and Conditions or is harmful to other users, us, or third parties, or for any other reason.</p>
     </div>
    </section>
    <div class="footer-section"><a href="parent_signup.php" class="btn-back" id="back-button">← Return to Sign Up</a>
    </div>
   </div>
  </div>
  <script>
        const defaultConfig = {
            background_gradient_start: "#667eea",
            background_gradient_end: "#764ba2",
            surface_color: "#ffffff",
            text_color: "#1a1a2e",
            primary_action_color: "#667eea",
            secondary_action_color: "#764ba2",
            font_family: "Inter",
            font_size: 16,
            main_title: "Terms and Conditions",
            last_updated: "Last Updated: January 2025",
            intro_text: "Please read these terms and conditions carefully before using our platform. By creating an account, you acknowledge that you have read, understood, and agree to be bound by these terms.",
            term_1_title: "Acceptance of Terms",
            term_1_content: "By creating an account and using this platform, you agree to comply with these Terms and Conditions. If you do not agree with any part of these terms, you should not use this service. Your continued use of the platform constitutes acceptance of these terms.",
            term_2_title: "Eligibility",
            term_2_content: "This service is intended for parents or guardians of students. You must be at least 18 years old to register and use this platform. By registering, you represent and warrant that you meet these eligibility requirements.",
            term_3_title: "Account Responsibility",
            term_3_content: "You are responsible for maintaining the confidentiality of your account information, including your username and password. You agree to notify us immediately of any unauthorized access or use of your account. Any activity under your account is your responsibility, and you will be held accountable for all actions taken.",
            term_4_title: "Acceptable Use",
            term_4_content: "You agree to use the platform for lawful purposes only. You shall not engage in any activity that may disrupt, harm, or compromise the platform or its users. This includes, but is not limited to, transmitting harmful code, attempting unauthorized access, or harassing other users.",
            term_5_title: "Intellectual Property",
            term_5_content: "All content on this platform, including text, graphics, logos, and software, is the property of the platform or its licensors unless otherwise stated. You may not copy, reproduce, distribute, or create derivative works from any content without explicit written permission.",
            term_6_title: "Privacy and Data Protection",
            term_6_content: "We are committed to protecting your privacy and personal information. Your use of the platform is also governed by our Privacy Policy, which explains how we collect, use, and safeguard your data. Please review our Privacy Policy to understand our practices.",
            term_7_content: "We are not liable for any direct, indirect, incidental, consequential, or punitive damages arising from your use or inability to use the platform. This includes, but is not limited to, damages for loss of profits, data, or other intangible losses.",
            term_8_title: "Modifications to Terms",
            term_8_content: "We reserve the right to update, modify, or replace these Terms and Conditions at any time at our sole discretion. Changes will be posted on this page with an updated \"Last Updated\" date. Your continued use of the platform after any changes constitutes acceptance of the new terms.",
            term_9_title: "Termination",
            term_9_content: "We reserve the right to terminate or suspend your account and access to the platform at our discretion, without notice, for conduct that we believe violates these Terms and Conditions or is harmful to other users, us, or third parties, or for any other reason.",
            contact_title: "Questions or Concerns?",
            contact_description: "If you have any questions about these Terms and Conditions, please contact us:",
            contact_email: "Email: support@example.com",
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

            const textElements = document.querySelectorAll('h1, h2, .term-content, .intro-text, .contact-info p, .contact-info h3, .last-updated');
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

            const termNumbers = document.querySelectorAll('.term-number');
            termNumbers.forEach(num => {
                num.style.background = `linear-gradient(135deg, ${config.primary_action_color || defaultConfig.primary_action_color} 0%, ${config.secondary_action_color || defaultConfig.secondary_action_color} 100%)`;
            });

            const btnBack = document.querySelector('.btn-back');
            if (btnBack) {
                btnBack.style.background = `linear-gradient(135deg, ${config.primary_action_color || defaultConfig.primary_action_color} 0%, ${config.secondary_action_color || defaultConfig.secondary_action_color} 100%)`;
            }

            document.getElementById('main-title').textContent = config.main_title || defaultConfig.main_title;
            document.getElementById('last-updated').textContent = config.last_updated || defaultConfig.last_updated;
            document.getElementById('intro-text').textContent = config.intro_text || defaultConfig.intro_text;
            document.getElementById('term-1-title').textContent = config.term_1_title || defaultConfig.term_1_title;
            document.getElementById('term-1-content').textContent = config.term_1_content || defaultConfig.term_1_content;
            document.getElementById('term-2-title').textContent = config.term_2_title || defaultConfig.term_2_title;
            document.getElementById('term-2-content').textContent = config.term_2_content || defaultConfig.term_2_content;
            document.getElementById('term-3-title').textContent = config.term_3_title || defaultConfig.term_3_title;
            document.getElementById('term-3-content').textContent = config.term_3_content || defaultConfig.term_3_content;
            document.getElementById('term-4-title').textContent = config.term_4_title || defaultConfig.term_4_title;
            document.getElementById('term-4-content').textContent = config.term_4_content || defaultConfig.term_4_content;
            document.getElementById('term-5-title').textContent = config.term_5_title || defaultConfig.term_5_title;
            document.getElementById('term-5-content').textContent = config.term_5_content || defaultConfig.term_5_content;
            document.getElementById('term-6-title').textContent = config.term_6_title || defaultConfig.term_6_title;
            document.getElementById('term-6-content').textContent = config.term_6_content || defaultConfig.term_6_content;
            document.getElementById('term-7-content').textContent = config.term_7_content || defaultConfig.term_7_content;
            document.getElementById('term-8-title').textContent = config.term_8_title || defaultConfig.term_8_title;
            document.getElementById('term-8-content').textContent = config.term_8_content || defaultConfig.term_8_content;
            document.getElementById('term-9-title').textContent = config.term_9_title || defaultConfig.term_9_title;
            document.getElementById('term-9-content').textContent = config.term_9_content || defaultConfig.term_9_content;
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
                    ["term_1_title", config.term_1_title || defaultConfig.term_1_title],
                    ["term_1_content", config.term_1_content || defaultConfig.term_1_content],
                    ["term_2_title", config.term_2_title || defaultConfig.term_2_title],
                    ["term_2_content", config.term_2_content || defaultConfig.term_2_content],
                    ["term_3_title", config.term_3_title || defaultConfig.term_3_title],
                    ["term_3_content", config.term_3_content || defaultConfig.term_3_content],
                    ["term_4_title", config.term_4_title || defaultConfig.term_4_title],
                    ["term_4_content", config.term_4_content || defaultConfig.term_4_content],
                    ["term_5_title", config.term_5_title || defaultConfig.term_5_title],
                    ["term_5_content", config.term_5_content || defaultConfig.term_5_content],
                    ["term_6_title", config.term_6_title || defaultConfig.term_6_title],
                    ["term_6_content", config.term_6_content || defaultConfig.term_6_content],
                    ["term_7_content", config.term_7_content || defaultConfig.term_7_content],
                    ["term_8_title", config.term_8_title || defaultConfig.term_8_title],
                    ["term_8_content", config.term_8_content || defaultConfig.term_8_content],
                    ["term_9_title", config.term_9_title || defaultConfig.term_9_title],
                    ["term_9_content", config.term_9_content || defaultConfig.term_9_content],
                    ["contact_title", config.contact_title || defaultConfig.contact_title],
                    ["contact_description", config.contact_description || defaultConfig.contact_description],
                    ["contact_email", config.contact_email || defaultConfig.contact_email],
                    ["back_button", config.back_button || defaultConfig.back_button]
                ])
            });

            onConfigChange(window.elementSdk.config);
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a361873e3a40dc9',t:'MTc2Mzk1NzgzNS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>