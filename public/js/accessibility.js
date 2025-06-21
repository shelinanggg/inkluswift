// accessibility.js - Tempatkan file ini di folder JS Anda
document.addEventListener('DOMContentLoaded', function() {
    // Sisipkan toolbar aksesibilitas
    insertAccessibilityToolbar();
    
    // Fungsi untuk menyisipkan toolbar
    function insertAccessibilityToolbar() {
        const toolbarHTML = `
            <!-- Accessibility Toggle Button -->
            <button class="toggle-toolbar" aria-label="Toggle Accessibility Toolbar">
                <i class="fas fa-universal-access"></i>
            </button>

            <!-- Accessibility Toolbar -->
            <div class="accessibility-toolbar" style="display: none;">
                <h3>Alat Aksesibilitas</h3>
                <button class="accessibility-btn" id="text-to-speech">
                    <i class="fas fa-volume-up"></i> Text to Speech
                </button>
                <button class="accessibility-btn" id="text-size">
                    <i class="fas fa-text-height"></i> Besarkan Teks
                </button>
                <button class="accessibility-btn" id="high-contrast">
                    <i class="fas fa-adjust"></i> Mode Kontras Tinggi
                </button>
                <button class="accessibility-btn" id="grayscale">
                    <i class="fas fa-tint-slash"></i> Mode Grayscale
                </button>
                <button class="accessibility-btn" id="dyslexia-font">
                    <i class="fas fa-font"></i> Font untuk Disleksia
                </button>
                <button class="accessibility-btn" id="reset-all">
                    <i class="fas fa-undo"></i> Reset Pengaturan
                </button>
            </div>
        `;
        
        const accessibilityDiv = document.createElement('div');
        accessibilityDiv.id = 'accessibility-wrapper';
        accessibilityDiv.innerHTML = toolbarHTML;
        document.body.appendChild(accessibilityDiv);
        
        const style = document.createElement('style');
        style.textContent = `
            /* Accessibility Toolbar Styles */
            .accessibility-toolbar {
                position: fixed;
                top: 20px;
                right: 50px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                z-index: 1000;
                padding: 15px;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .accessibility-toolbar h3 {
                margin-bottom: 10px;
                text-align: center;
                color: #FF5040;
            }

            .accessibility-btn {
                padding: 8px 12px;
                background-color: #f5f5f5;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 10px;
                transition: all 0.2s;
            }

            .accessibility-btn:hover {
                background-color: #FF5040;
                color: white;
            }

            .toggle-toolbar {
                position: fixed;
                top: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                background-color: #FF5040;
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                z-index: 1001;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            }
        `;
        document.head.appendChild(style);
        
        initAccessibilityFeatures();
    }
    
    function initAccessibilityFeatures() {
        const toggleButton = document.querySelector('.toggle-toolbar');
        const toolbar = document.querySelector('.accessibility-toolbar');
        
        if (toggleButton && toolbar) {
            toggleButton.addEventListener('click', function() {
                toolbar.style.display = toolbar.style.display === 'none' ? 'flex' : 'none';
            });
        }
        
        let textToSpeechMode = false;
        const textToSpeechBtn = document.getElementById('text-to-speech');
        if (textToSpeechBtn) {
            textToSpeechBtn.addEventListener('click', function () {
                textToSpeechMode = !textToSpeechMode;
                alert(textToSpeechMode 
                    ? 'Mode Text-to-Speech aktif. Pilih teks untuk dibacakan.' 
                    : 'Mode Text-to-Speech dimatikan.');
            });
        }

        document.addEventListener('selectionchange', function () {
            if (!textToSpeechMode) return;

            const selectedText = window.getSelection().toString().trim();
            if (selectedText.length > 0) {
                const speech = new SpeechSynthesisUtterance(selectedText);
                speech.lang = 'id-ID';
                window.speechSynthesis.cancel();
                window.speechSynthesis.speak(speech);
            }
        });
        
        const textSizeBtn = document.getElementById('text-size');
        if (textSizeBtn) {
            textSizeBtn.addEventListener('click', function() {
                document.body.classList.toggle('large-text');
                if (!document.getElementById('textSize')) {
                    const style = document.createElement('style');
                    style.id = 'textSize';
                    style.innerHTML = `
                        body.large-text {
                            font-size: 120% !important;
                        }
                        body.large-text p, body.large-text div, body.large-text span, 
                        body.large-text h1, body.large-text h2, body.large-text h3, 
                        body.large-text h4, body.large-text h5, body.large-text h6, 
                        body.large-text input, body.large-text button, body.large-text a {
                            font-size: 120% !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                saveAccessibilitySettings();
            });
        }

        const highContrastBtn = document.getElementById('high-contrast');
        if (highContrastBtn) {
            highContrastBtn.addEventListener('click', function() {
                document.body.classList.toggle('high-contrast');
                if (!document.getElementById('highContrast')) {
                    const style = document.createElement('style');
                    style.id = 'highContrast';
                    style.innerHTML = `
                        body.high-contrast {
                            background-color: black !important;
                            color: #FFD700 !important;
                        }
                        body.high-contrast * {
                            background-color: black !important;
                            color: #FFD700 !important;
                            border-color: #FFD700 !important;
                        }
                        body.high-contrast p, body.high-contrast div, body.high-contrast span,
                        body.high-contrast h1, body.high-contrast h2, body.high-contrast h3,
                        body.high-contrast h4, body.high-contrast h5, body.high-contrast h6,
                        body.high-contrast li, body.high-contrast td, body.high-contrast th {
                            color: #FFD700 !important;
                        }
                        body.high-contrast a {
                            color: #FFFF00 !important;
                        }
                        body.high-contrast button, body.high-contrast .btn {
                            background-color: #333 !important;
                            color: #FFD700 !important;
                            border: 1px solid #FFD700 !important;
                        }
                        body.high-contrast input, body.high-contrast textarea, body.high-contrast select {
                            background-color: #333 !important;
                            color: #FFD700 !important;
                            border: 1px solid #FFD700 !important;
                        }
                        body.high-contrast .left-panel {
                            background-color: #333 !important;
                        }
                        body.high-contrast ::placeholder {
                            color: #FFD700 !important;
                            opacity: 0.7;
                        }
                    `;
                    document.head.appendChild(style);
                }
                saveAccessibilitySettings();
            });
        }

        const grayscaleBtn = document.getElementById('grayscale');
        if (grayscaleBtn) {
            grayscaleBtn.addEventListener('click', function() {
                document.body.classList.toggle('grayscale');
                if (!document.getElementById('grayscaleStyle')) {
                    const style = document.createElement('style');
                    style.id = 'grayscaleStyle';
                    style.innerHTML = `
                        body.grayscale *:not(#accessibility-wrapper):not(.toggle-toolbar):not(#accessibility-wrapper *):not(.toggle-toolbar *) {
                            filter: grayscale(100%) !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                saveAccessibilitySettings();
            });
        }

        const dyslexiaFontBtn = document.getElementById('dyslexia-font');
        if (dyslexiaFontBtn) {
            dyslexiaFontBtn.addEventListener('click', function() {
                document.body.classList.toggle('dyslexia-font');
                if (!document.getElementById('dyslexiaFont')) {
                    const style = document.createElement('style');
                    style.id = 'dyslexiaFont';
                    style.innerHTML = `
                        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap');
                        body.dyslexia-font, 
                        body.dyslexia-font p, body.dyslexia-font div, body.dyslexia-font span,
                        body.dyslexia-font h1, body.dyslexia-font h2, body.dyslexia-font h3,
                        body.dyslexia-font h4, body.dyslexia-font h5, body.dyslexia-font h6,
                        body.dyslexia-font button, body.dyslexia-font input, body.dyslexia-font textarea,
                        body.dyslexia-font a, body.dyslexia-font li, body.dyslexia-font td, body.dyslexia-font th {
                            font-family: 'Lexend', sans-serif !important;
                            line-height: 1.5 !important;
                            letter-spacing: 0.5px !important;
                        }
                        /* Jaga agar icon FontAwesome tetap menggunakan font aslinya */
                        body.dyslexia-font .fas, body.dyslexia-font .far, body.dyslexia-font .fab,
                        body.dyslexia-font .fa, body.dyslexia-font [class*="fa-"] {
                            font-family: "Font Awesome 5 Free", "Font Awesome 5 Pro", "Font Awesome 5 Brands", "FontAwesome" !important;
                            line-height: 1 !important;
                            letter-spacing: normal !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                saveAccessibilitySettings();
            });
        }

        const resetAllBtn = document.getElementById('reset-all');
        if (resetAllBtn) {
            resetAllBtn.addEventListener('click', function() {
                document.body.classList.remove('large-text', 'high-contrast', 'grayscale', 'dyslexia-font');
                toolbar.style.display = 'none';
                saveAccessibilitySettings();
            });
        }

        loadAccessibilitySettings();
    }

    function saveAccessibilitySettings() {
        const settings = {
            largeText: document.body.classList.contains('large-text'),
            highContrast: document.body.classList.contains('high-contrast'),
            grayscale: document.body.classList.contains('grayscale'),
            dyslexiaFont: document.body.classList.contains('dyslexia-font')
        };
        localStorage.setItem('accessibilitySettings', JSON.stringify(settings));
    }

    function loadAccessibilitySettings() {
        const settings = JSON.parse(localStorage.getItem('accessibilitySettings'));
        if (settings) {
            if (settings.largeText) document.body.classList.add('large-text');
            if (settings.highContrast) document.body.classList.add('high-contrast');
            if (settings.grayscale) document.body.classList.add('grayscale');
            if (settings.dyslexiaFont) document.body.classList.add('dyslexia-font');
        }
    }
});