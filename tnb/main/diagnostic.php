<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i18n Diagnostic - TNB Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .debug-info { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 3px; }
        .error { background: #ffe6e6; color: #d00; }
        .success { background: #e6ffe6; color: #080; }
        .warning { background: #fff3cd; color: #856404; }
        pre { background: #f8f9fa; padding: 10px; overflow-x: auto; }
        button { padding: 8px 16px; margin: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>i18n System Diagnostic Tool</h1>
    
    <div class="test-section">
        <h2>Step 1: Basic Environment Check</h2>
        <div id="env-check">Checking...</div>
    </div>
    
    <div class="test-section">
        <h2>Step 2: Language File Accessibility</h2>
        <div id="file-check">Checking...</div>
        <button onclick="testLanguageFile('th')">Test Thai File</button>
        <button onclick="testLanguageFile('en')">Test English File</button>
        <button onclick="testLanguageFile('zh')">Test Chinese File</button>
        <button onclick="testLanguageFile('jp')">Test Japanese File</button>
    </div>
    
    <div class="test-section">
        <h2>Step 3: Script Loading Check</h2>
        <div id="script-check">Checking...</div>
    </div>
    
    <div class="test-section">
        <h2>Step 4: i18n System Check</h2>
        <div id="i18n-check">Checking...</div>
        <button onclick="testI18nSystem()">Test i18n System</button>
    </div>
    
    <div class="test-section">
        <h2>Step 5: Translation Application Test</h2>
        <div id="translation-test">
            <p data-i18n="branches.title">Original: branches.title</p>
            <p data-i18n="branches.subtitle">Original: branches.subtitle</p>
            <p data-i18n="branches.bangsaen_name">Original: branches.bangsaen_name</p>
        </div>
        <button onclick="applyTranslations()">Apply Translations</button>
        <button onclick="testAllLanguages()">Test All Languages</button>
    </div>
    
    <div class="test-section">
        <h2>Step 6: Manual Debug Info</h2>
        <div id="debug-output">Click buttons above to see debug information</div>
    </div>

    <script>
        // Step 1: Environment Check
        function checkEnvironment() {
            const envDiv = document.getElementById('env-check');
            const info = {
                'Current URL': window.location.href,
                'Protocol': window.location.protocol,
                'Hostname': window.location.hostname,
                'Port': window.location.port,
                'Pathname': window.location.pathname,
                'User Agent': navigator.userAgent,
                'Language': navigator.language
            };
            
            let html = '<table border="1" style="width: 100%; border-collapse: collapse;">';
            for (const [key, value] of Object.entries(info)) {
                html += `<tr><td><strong>${key}</strong></td><td>${value}</td></tr>`;
            }
            html += '</table>';
            
            // Check if we're on localhost
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                html += '<div class="success">Running on localhost - Good for testing</div>';
            } else {
                html += '<div class="warning">Not on localhost - might have CORS issues</div>';
            }
            
            envDiv.innerHTML = html;
        }
        
        // Step 2: Language File Test
        function testLanguageFile(lang) {
            console.log(`Testing language file: ${lang}`);
            const url = `../lang/${lang}.json`;
            
            fetch(url)
                .then(response => {
                    console.log(`Response status for ${lang}:`, response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`Data loaded for ${lang}:`, Object.keys(data));
                    const hasBranches = data.branches && typeof data.branches === 'object';
                    const title = data.branches?.title || 'Not found';
                    
                    document.getElementById('file-check').innerHTML = `
                        <div class="success">Successfully loaded ${lang}.json</div>
                        <div>Keys: ${Object.keys(data).join(', ')}</div>
                        <div>Has branches: ${hasBranches}</div>
                        <div>branches.title: ${title}</div>
                    `;
                })
                .catch(error => {
                    console.error(`Error loading ${lang}:`, error);
                    document.getElementById('file-check').innerHTML = `
                        <div class="error">Failed to load ${lang}.json: ${error.message}</div>
                        <div>URL attempted: ${url}</div>
                    `;
                });
        }
        
        // Step 3: Script Loading Check
        function checkScriptLoading() {
            const scriptDiv = document.getElementById('script-check');
            let html = '';
            
            // Check if i18n.js is accessible
            fetch('../js/i18n.js')
                .then(response => {
                    if (response.ok) {
                        html += '<div class="success">i18n.js is accessible</div>';
                    } else {
                        html += `<div class="error">i18n.js not accessible: ${response.status}</div>`;
                    }
                    
                    // Check if script.js is accessible
                    return fetch('../js/script.js');
                })
                .then(response => {
                    if (response.ok) {
                        html += '<div class="success">script.js is accessible</div>';
                    } else {
                        html += `<div class="error">script.js not accessible: ${response.status}</div>`;
                    }
                    
                    scriptDiv.innerHTML = html;
                })
                .catch(error => {
                    scriptDiv.innerHTML = `<div class="error">Script loading error: ${error.message}</div>`;
                });
        }
        
        // Step 4: i18n System Check
        function testI18nSystem() {
            const i18nDiv = document.getElementById('i18n-check');
            
            // Load i18n script dynamically
            const script = document.createElement('script');
            script.src = '../js/i18n.js';
            script.onload = function() {
                setTimeout(() => {
                    if (window.tnbLang) {
                        i18nDiv.innerHTML = `
                            <div class="success">i18n script loaded</div>
                            <div>Initialized: ${window.tnbLang._initialized}</div>
                            <div>Current lang: ${window.tnbLang.getCurrentLang()}</div>
                            <div>Supported: ${window.tnbLang.getSupportedLangs().join(', ')}</div>
                        `;
                        
                        if (window.tnbLangDebug) {
                            const debug = window.tnbLangDebug();
                            i18nDiv.innerHTML += `<pre>${JSON.stringify(debug, null, 2)}</pre>`;
                        }
                    } else {
                        i18nDiv.innerHTML = '<div class="error">i18n script loaded but window.tnbLang not found</div>';
                    }
                }, 1000);
            };
            script.onerror = function() {
                i18nDiv.innerHTML = '<div class="error">Failed to load i18n script</div>';
            };
            document.head.appendChild(script);
        }
        
        // Step 5: Translation Application
        function applyTranslations() {
            if (window.tnbLang && window.tnbLang.setLang) {
                window.tnbLang.setLang('th');
                setTimeout(() => {
                    const elements = document.querySelectorAll('[data-i18n]');
                    let html = '<h4>Translation Results:</h4>';
                    elements.forEach(el => {
                        const key = el.getAttribute('data-i18n');
                        html += `<div>${key}: "${el.textContent}"</div>`;
                    });
                    document.getElementById('debug-output').innerHTML = html;
                }, 500);
            } else {
                document.getElementById('debug-output').innerHTML = '<div class="error">i18n system not available</div>';
            }
        }
        
        function testAllLanguages() {
            const languages = ['th', 'en', 'zh', 'jp'];
            let html = '<h4>All Language Tests:</h4>';
            
            languages.forEach(lang => {
                if (window.tnbLang && window.tnbLang.setLang) {
                    window.tnbLang.setLang(lang);
                    const title = window.tnbLang.resolve('branches.title');
                    html += `<div>${lang}: ${title || 'Not found'}</div>`;
                }
            });
            
            document.getElementById('debug-output').innerHTML = html;
        }
        
        // Initialize all checks
        document.addEventListener('DOMContentLoaded', function() {
            checkEnvironment();
            checkScriptLoading();
        });
    </script>
</body>
</html>
