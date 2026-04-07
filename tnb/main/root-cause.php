<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Cause Analysis - TNB i18n</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .step { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .success { background: #e6ffe6; color: #080; }
        .error { background: #ffe6e6; color: #d00; }
        .warning { background: #fff3cd; color: #856404; }
        .info { background: #e3f2fd; color: #1976d2; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .test-result { margin: 10px 0; padding: 10px; }
        .demo-area { padding: 15px; background: #f9f9f9; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Root Cause Analysis - TNB i18n System</h1>
    
    <div class="step info">
        <h2>Step 1: Basic Environment Check</h2>
        <div id="env-info">Checking...</div>
    </div>
    
    <div class="step">
        <h2>Step 2: Script Loading Test</h2>
        <button onclick="testScriptLoading()">Test Script Loading</button>
        <div id="script-results"></div>
    </div>
    
    <div class="step">
        <h2>Step 3: Language File Path Test</h2>
        <button onclick="testLanguagePaths()">Test All Paths</button>
        <div id="path-results"></div>
    </div>
    
    <div class="step">
        <h2>Step 4: i18n System Initialization Test</h2>
        <button onclick="testI18nInit()">Test i18n Init</button>
        <div id="init-results"></div>
    </div>
    
    <div class="step">
        <h2>Step 5: Translation Application Test</h2>
        <button onclick="testTranslationApply()">Test Translation Apply</button>
        <div id="apply-results"></div>
    </div>
    
    <div class="demo-area">
        <h3>Translation Demo Area:</h3>
        <p data-i18n="branches.title">Original: branches.title</p>
        <p data-i18n="branches.subtitle">Original: branches.subtitle</p>
        <p data-i18n="branches.bangsaen_name">Original: branches.bangsaen_name</p>
    </div>
    
    <div class="step">
        <h2>Step 6: Manual Language Switch Test</h2>
        <button onclick="manualSwitch('th')">Switch to Thai</button>
        <button onclick="manualSwitch('en')">Switch to English</button>
        <button onclick="manualSwitch('zh')">Switch to Chinese</button>
        <button onclick="manualSwitch('jp')">Switch to Japanese</button>
        <div id="switch-results"></div>
    </div>
    
    <div class="step">
        <h2>Step 7: Complete System Test</h2>
        <button onclick="runCompleteTest()">Run Complete Test</button>
        <div id="complete-results"></div>
    </div>

    <script>
        let testLog = [];
        
        function log(message, type = 'info') {
            testLog.push({ message, type, timestamp: new Date().toISOString() });
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
        
        function showResults(divId, results) {
            const div = document.getElementById(divId);
            let html = '';
            results.forEach(result => {
                const cssClass = result.type === 'success' ? 'success' : 
                                result.type === 'error' ? 'error' : 
                                result.type === 'warning' ? 'warning' : 'info';
                html += `<div class="test-result ${cssClass}">${result.message}</div>`;
            });
            div.innerHTML = html;
        }
        
        // Step 1: Environment Check
        function checkEnvironment() {
            const envDiv = document.getElementById('env-info');
            const info = {
                'URL': window.location.href,
                'Protocol': window.location.protocol,
                'Hostname': window.location.hostname,
                'Pathname': window.location.pathname,
                'Search': window.location.search,
                'Hash': window.location.hash
            };
            
            let html = '<h4>Environment Info:</h4>';
            for (const [key, value] of Object.entries(info)) {
                html += `<div><strong>${key}:</strong> ${value}</div>`;
            }
            
            if (window.location.hostname === 'localhost') {
                html += '<div class="success">Running on localhost - Good for testing</div>';
            } else {
                html += '<div class="warning">Not on localhost - May have CORS issues</div>';
            }
            
            envDiv.innerHTML = html;
            log('Environment check completed');
        }
        
        // Step 2: Script Loading Test
        function testScriptLoading() {
            const results = [];
            
            // Test i18n.js accessibility
            fetch('../js/i18n.js')
                .then(response => {
                    if (response.ok) {
                        results.push({ message: 'i18n.js is accessible via HTTP', type: 'success' });
                        log('i18n.js accessible via HTTP', 'success');
                        
                        // Try to load the script
                        return loadScript('../js/i18n.js');
                    } else {
                        results.push({ message: `i18n.js HTTP error: ${response.status}`, type: 'error' });
                        log(`i18n.js HTTP error: ${response.status}`, 'error');
                    }
                })
                .then(() => {
                    setTimeout(() => {
                        if (window.tnbLang) {
                            results.push({ message: 'window.tnbLang is available', type: 'success' });
                            log('window.tnbLang is available', 'success');
                        } else {
                            results.push({ message: 'window.tnbLang is NOT available', type: 'error' });
                            log('window.tnbLang is NOT available', 'error');
                        }
                        showResults('script-results', results);
                    }, 1000);
                })
                .catch(error => {
                    results.push({ message: `i18n.js fetch error: ${error.message}`, type: 'error' });
                    log(`i18n.js fetch error: ${error.message}`, 'error');
                    showResults('script-results', results);
                });
        }
        
        function loadScript(src) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
        
        // Step 3: Language File Path Test
        function testLanguagePaths() {
            const results = [];
            const paths = [
                '../lang/th.json',
                './lang/th.json',
                '/Allweb-main/tnb/lang/th.json',
                '/tnb/lang/th.json',
                '../../lang/th.json'
            ];
            
            let completed = 0;
            paths.forEach(path => {
                fetch(path)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error(`HTTP ${response.status}`);
                    })
                    .then(data => {
                        results.push({ 
                            message: `${path}: SUCCESS - branches.title = "${data.branches?.title || 'Not found'}"`, 
                            type: 'success' 
                        });
                        log(`Path success: ${path}`, 'success');
                    })
                    .catch(error => {
                        results.push({ message: `${path}: FAILED - ${error.message}`, type: 'error' });
                        log(`Path failed: ${path} - ${error.message}`, 'error');
                    })
                    .finally(() => {
                        completed++;
                        if (completed === paths.length) {
                            showResults('path-results', results);
                        }
                    });
            });
        }
        
        // Step 4: i18n System Initialization Test
        function testI18nInit() {
            const results = [];
            
            if (!window.tnbLang) {
                results.push({ message: 'window.tnbLang not available - load script first', type: 'error' });
                showResults('init-results', results);
                return;
            }
            
            results.push({ message: 'window.tnbLang is available', type: 'success' });
            
            // Check initialization
            if (window.tnbLang._initialized) {
                results.push({ message: 'i18n system is initialized', type: 'success' });
            } else {
                results.push({ message: 'i18n system is NOT initialized', type: 'warning' });
            }
            
            // Check supported languages
            const supported = window.tnbLang.getSupportedLangs();
            results.push({ message: `Supported languages: ${supported.join(', ')}`, type: 'info' });
            
            // Try to set language
            window.tnbLang.setLang('th').then(() => {
                results.push({ message: 'Successfully set language to Thai', type: 'success' });
                
                // Test translation resolution
                const title = window.tnbLang.resolve('branches.title');
                if (title) {
                    results.push({ message: `Translation resolved: branches.title = "${title}"`, type: 'success' });
                } else {
                    results.push({ message: 'Translation NOT resolved: branches.title', type: 'error' });
                }
                
                showResults('init-results', results);
            }).catch(error => {
                results.push({ message: `Failed to set language: ${error.message}`, type: 'error' });
                showResults('init-results', results);
            });
        }
        
        // Step 5: Translation Application Test
        function testTranslationApply() {
            const results = [];
            
            if (!window.tnbLang) {
                results.push({ message: 'i18n system not available', type: 'error' });
                showResults('apply-results', results);
                return;
            }
            
            // Set language first
            window.tnbLang.setLang('th').then(() => {
                results.push({ message: 'Language set to Thai', type: 'success' });
                
                // Check DOM elements
                const elements = document.querySelectorAll('[data-i18n]');
                results.push({ message: `Found ${elements.length} elements with data-i18n`, type: 'info' });
                
                elements.forEach((el, index) => {
                    const key = el.getAttribute('data-i18n');
                    const originalText = el.textContent;
                    results.push({ 
                        message: `Element ${index + 1}: ${key} = "${originalText}"`, 
                        type: 'info' 
                    });
                });
                
                // Check if translations were applied
                setTimeout(() => {
                    const titleElement = document.querySelector('[data-i18n="branches.title"]');
                    if (titleElement && titleElement.textContent !== 'Original: branches.title') {
                        results.push({ message: 'Translation APPLIED to DOM', type: 'success' });
                    } else {
                        results.push({ message: 'Translation NOT applied to DOM', type: 'error' });
                    }
                    showResults('apply-results', results);
                }, 1000);
            });
        }
        
        // Step 6: Manual Language Switch
        function manualSwitch(lang) {
            const results = [];
            
            if (!window.tnbLang) {
                results.push({ message: 'i18n system not available', type: 'error' });
                showResults('switch-results', results);
                return;
            }
            
            window.tnbLang.setLang(lang).then(() => {
                results.push({ message: `Switched to ${lang}`, type: 'success' });
                
                const title = window.tnbLang.resolve('branches.title');
                results.push({ message: `Current translation: ${title}`, type: 'info' });
                
                showResults('switch-results', results);
            }).catch(error => {
                results.push({ message: `Failed to switch: ${error.message}`, type: 'error' });
                showResults('switch-results', results);
            });
        }
        
        // Step 7: Complete System Test
        function runCompleteTest() {
            const results = [];
            
            results.push({ message: 'Starting complete system test...', type: 'info' });
            
            // Test 1: Script loading
            fetch('../js/i18n.js')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return loadScript('../js/i18n.js');
                })
                .then(() => {
                    results.push({ message: 'Script loaded successfully', type: 'success' });
                    
                    // Test 2: i18n system
                    setTimeout(() => {
                        if (window.tnbLang) {
                            results.push({ message: 'i18n system available', type: 'success' });
                            
                            // Test 3: Language loading
                            window.tnbLang.setLang('th').then(() => {
                                results.push({ message: 'Thai language loaded', type: 'success' });
                                
                                // Test 4: Translation
                                const title = window.tnbLang.resolve('branches.title');
                                if (title && title !== 'Original: branches.title') {
                                    results.push({ message: 'Translation working: ' + title, type: 'success' });
                                    results.push({ message: 'SYSTEM IS WORKING CORRECTLY!', type: 'success' });
                                } else {
                                    results.push({ message: 'Translation NOT working', type: 'error' });
                                    results.push({ message: 'SYSTEM HAS ISSUES', type: 'error' });
                                }
                                
                                showResults('complete-results', results);
                            }).catch(error => {
                                results.push({ message: 'Language loading failed: ' + error.message, type: 'error' });
                                showResults('complete-results', results);
                            });
                        } else {
                            results.push({ message: 'i18n system NOT available', type: 'error' });
                            showResults('complete-results', results);
                        }
                    }, 1000);
                })
                .catch(error => {
                    results.push({ message: 'Script loading failed: ' + error.message, type: 'error' });
                    showResults('complete-results', results);
                });
        }
        
        // Auto-run environment check
        document.addEventListener('DOMContentLoaded', function() {
            checkEnvironment();
        });
    </script>
</body>
</html>
