<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final i18n Test - TNB Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .success { color: #080; background: #e6ffe6; padding: 10px; margin: 10px 0; }
        .error { color: #d00; background: #ffe6e6; padding: 10px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; margin: 10px 0; }
        .test-result { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .translation-demo { padding: 15px; background: #f9f9f9; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Final i18n Test -  Complete System Check</h1>
    
    <div id="test-results">
        <div class="test-result">Running tests...</div>
    </div>
    
    <div class="translation-demo">
        <h3>Translation Demo:</h3>
        <p data-i18n="branches.title">Original: branches.title</p>
        <p data-i18n="branches.subtitle">Original: branches.subtitle</p>
        <p data-i18n="branches.bangsaen_name">Original: branches.bangsaen_name</p>
    </div>
    
    <div>
        <button onclick="switchLanguage('th')">Thai</button>
        <button onclick="switchLanguage('en')">English</button>
        <button onclick="switchLanguage('zh')">Chinese</button>
        <button onclick="switchLanguage('jp')">Japanese</button>
    </div>
    
    <div id="debug-info"></div>

    <script src="../js/i18n.js"></script>
    <script>
        let testResults = [];
        
        function addResult(test, status, message) {
            testResults.push({ test, status, message });
            updateDisplay();
        }
        
        function updateDisplay() {
            const resultsDiv = document.getElementById('test-results');
            let html = '<h2>Test Results:</h2>';
            
            testResults.forEach(result => {
                const cssClass = result.status === 'SUCCESS' ? 'success' : 
                                result.status === 'ERROR' ? 'error' : 'warning';
                html += `<div class="${cssClass}">
                    <strong>${result.test}:</strong> ${result.message}
                </div>`;
            });
            
            resultsDiv.innerHTML = html;
        }
        
        async function runTests() {
            console.log('=== Starting Final i18n Tests ===');
            
            // Test 1: Script Loading
            try {
                if (window.tnbLang) {
                    addResult('Script Loading', 'SUCCESS', 'i18n.js loaded successfully');
                } else {
                    addResult('Script Loading', 'ERROR', 'i18n.js not loaded');
                    return;
                }
            } catch (e) {
                addResult('Script Loading', 'ERROR', e.message);
                return;
            }
            
            // Test 2: Language File Loading
            try {
                const result = await window.tnbLang.setLang('th');
                if (result) {
                    addResult('Language File', 'SUCCESS', 'Thai language file loaded');
                } else {
                    addResult('Language File', 'ERROR', 'Failed to load Thai language file');
                }
            } catch (e) {
                addResult('Language File', 'ERROR', e.message);
            }
            
            // Test 3: Translation Resolution
            try {
                const title = window.tnbLang.resolve('branches.title');
                if (title) {
                    addResult('Translation Resolution', 'SUCCESS', `branches.title = "${title}"`);
                } else {
                    addResult('Translation Resolution', 'ERROR', 'branches.title not found');
                }
            } catch (e) {
                addResult('Translation Resolution', 'ERROR', e.message);
            }
            
            // Test 4: DOM Application
            try {
                const element = document.querySelector('[data-i18n="branches.title"]');
                if (element && element.textContent !== 'Original: branches.title') {
                    addResult('DOM Application', 'SUCCESS', 'Translation applied to DOM');
                } else {
                    addResult('DOM Application', 'WARNING', 'Translation not yet applied to DOM');
                }
            } catch (e) {
                addResult('DOM Application', 'ERROR', e.message);
            }
            
            // Test 5: Debug Info
            try {
                if (window.tnbLangDebug) {
                    const debug = window.tnbLangDebug();
                    addResult('Debug Info', 'SUCCESS', `Current lang: ${debug.currentLang}, Cache keys: ${debug.cacheKeys.join(', ')}`);
                }
            } catch (e) {
                addResult('Debug Info', 'ERROR', e.message);
            }
        }
        
        function switchLanguage(lang) {
            console.log('Switching to:', lang);
            if (window.tnbLang && window.tnbLang.setLang) {
                window.tnbLang.setLang(lang).then(() => {
                    updateDebugInfo();
                });
            } else {
                alert('i18n system not available');
            }
        }
        
        function updateDebugInfo() {
            const debugDiv = document.getElementById('debug-info');
            if (window.tnbLangDebug) {
                const debug = window.tnbLangDebug();
                debugDiv.innerHTML = `
                    <h3>Current Debug Info:</h3>
                    <pre>${JSON.stringify(debug, null, 2)}</pre>
                `;
            }
        }
        
        // Auto-run tests
        setTimeout(() => {
            runTests();
            updateDebugInfo();
        }, 1000);
        
        // Update debug info every 3 seconds
        setInterval(updateDebugInfo, 3000);
    </script>
</body>
</html>
