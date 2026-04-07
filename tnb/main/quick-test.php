<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick i18n Test - TNB Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .error { background: #ffe6e6; color: #d00; padding: 10px; }
        .success { background: #e6ffe6; color: #080; padding: 10px; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; }
        button { padding: 8px 16px; margin: 5px; cursor: pointer; }
        pre { background: #f8f9fa; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Quick i18n Test - 30 Second Diagnosis</h1>
    
    <div class="test-section">
        <h2>Step 1:  Basic Path Test</h2>
        <div id="path-test">Testing...</div>
        <button onclick="testPaths()">Test All Paths</button>
    </div>
    
    <div class="test-section">
        <h2>Step 2:  Language File Test</h2>
        <div id="file-test">Testing...</div>
        <button onclick="testLanguageFiles()">Test Language Files</button>
    </div>
    
    <div class="test-section">
        <h2>Step 3:  Script Test</h2>
        <div id="script-test">Testing...</div>
        <button onclick="testScripts()">Test Scripts</button>
    </div>
    
    <div class="test-section">
        <h2>Step 4:  i18n Function Test</h2>
        <div id="i18n-test">Testing...</div>
        <button onclick="testI18nFunction()">Test i18n</button>
    </div>
    
    <div class="test-section">
        <h2>Step 5:  Manual Translation Test</h2>
        <div id="manual-test">
            <p data-i18n="branches.title">Original: branches.title</p>
            <p data-i18n="branches.subtitle">Original: branches.subtitle</p>
        </div>
        <button onclick="manualTranslate()">Manual Translate</button>
    </div>
    
    <div class="test-section">
        <h2>Auto-Test Results</h2>
        <div id="auto-results">Running auto-tests...</div>
    </div>

    <script>
        let testResults = {};
        
        // Auto-run all tests
        function runAllTests() {
            console.log('=== Starting Auto-Tests ===');
            testPaths();
            setTimeout(() => testLanguageFiles(), 1000);
            setTimeout(() => testScripts(), 2000);
            setTimeout(() => testI18nFunction(), 3000);
            setTimeout(() => showSummary(), 4000);
        }
        
        function testPaths() {
            const paths = [
                '../lang/th.json',
                './lang/th.json', 
                '/lang/th.json',
                '../../lang/th.json'
            ];
            
            let html = '<h4>Testing Paths:</h4>';
            let promises = [];
            
            paths.forEach(path => {
                const promise = fetch(path)
                    .then(response => {
                        const status = response.ok ? 'SUCCESS' : 'FAIL';
                        html += `<div>${path}: <span class="${response.ok ? 'success' : 'error'}">${status} (${response.status})</span></div>`;
                        testResults[path] = { status: response.status, ok: response.ok };
                    })
                    .catch(error => {
                        html += `<div>${path}: <span class="error">ERROR - ${error.message}</span></div>`;
                        testResults[path] = { error: error.message };
                    });
                promises.push(promise);
            });
            
            Promise.allSettled(promises).then(() => {
                document.getElementById('path-test').innerHTML = html;
            });
        }
        
        function testLanguageFiles() {
            const languages = ['th', 'en', 'zh', 'jp'];
            let html = '<h4>Testing Language Files:</h4>';
            
            languages.forEach(lang => {
                fetch(`../lang/${lang}.json`)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error(`HTTP ${response.status}`);
                    })
                    .then(data => {
                        const hasBranches = data.branches && data.branches.title;
                        html += `<div>${lang}.json: <span class="success">OK - branches.title: "${data.branches?.title || 'Not found'}"</span></div>`;
                    })
                    .catch(error => {
                        html += `<div>${lang}.json: <span class="error">FAIL - ${error.message}</span></div>`;
                    });
            });
            
            document.getElementById('file-test').innerHTML = html;
        }
        
        function testScripts() {
            let html = '<h4>Testing Scripts:</h4>';
            
            // Test i18n.js
            fetch('../js/i18n.js')
                .then(response => {
                    html += `<div>i18n.js: <span class="${response.ok ? 'success' : 'error'}">${response.ok ? 'OK' : 'FAIL'} (${response.status})</span></div>`;
                    
                    // Load and test i18n
                    if (response.ok) {
                        return loadI18nScript();
                    }
                })
                .catch(error => {
                    html += `<div>i18n.js: <span class="error">ERROR - ${error.message}</span></div>`;
                });
            
            // Test script.js
            fetch('../js/script.js')
                .then(response => {
                    html += `<div>script.js: <span class="${response.ok ? 'success' : 'error'}">${response.ok ? 'OK' : 'FAIL'} (${response.status})</span></div>`;
                })
                .catch(error => {
                    html += `<div>script.js: <span class="error">ERROR - ${error.message}</span></div>`;
                });
            
            document.getElementById('script-test').innerHTML = html;
        }
        
        function loadI18nScript() {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = '../js/i18n.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
        
        function testI18nFunction() {
            let html = '<h4>Testing i18n Function:</h4>';
            
            setTimeout(() => {
                if (window.tnbLang) {
                    html += `<div>window.tnbLang: <span class="success">EXISTS</span></div>`;
                    html += `<div>Initialized: <span class="${window.tnbLang._initialized ? 'success' : 'warning'}">${window.tnbLang._initialized}</span></div>`;
                    html += `<div>Current Lang: ${window.tnbLang.getCurrentLang()}</div>`;
                    html += `<div>Supported: ${window.tnbLang.getSupportedLangs().join(', ')}</div>`;
                    
                    // Test setting language
                    if (window.tnbLang.setLang) {
                        window.tnbLang.setLang('th');
                        setTimeout(() => {
                            const title = window.tnbLang.resolve('branches.title');
                            html += `<div>Translation Test: <span class="${title ? 'success' : 'error'}">${title || 'NOT FOUND'}</span></div>`;
                            document.getElementById('i18n-test').innerHTML = html;
                        }, 500);
                    }
                } else {
                    html += `<div>window.tnbLang: <span class="error">NOT FOUND</span></div>`;
                    document.getElementById('i18n-test').innerHTML = html;
                }
            }, 1000);
        }
        
        function manualTranslate() {
            if (window.tnbLang && window.tnbLang.setLang) {
                window.tnbLang.setLang('th');
                setTimeout(() => {
                    const elements = document.querySelectorAll('#manual-test [data-i18n]');
                    let html = '<h4>Manual Translation Results:</h4>';
                    elements.forEach(el => {
                        const key = el.getAttribute('data-i18n');
                        html += `<div>${key}: "${el.textContent}"</div>`;
                    });
                    document.getElementById('manual-test').innerHTML += html;
                }, 500);
            } else {
                alert('i18n system not available');
            }
        }
        
        function showSummary() {
            const workingPaths = Object.entries(testResults).filter(([path, result]) => result.ok).map(([path]) => path);
            const failedPaths = Object.entries(testResults).filter(([path, result]) => !result.ok).map(([path]) => path);
            
            let html = '<h3>=== SUMMARY ===</h3>';
            
            if (workingPaths.length > 0) {
                html += `<div class="success">Working paths: ${workingPaths.join(', ')}</div>`;
                html += '<div class="success">SOLUTION: Use a working path in i18n.js</div>';
            } else {
                html += '<div class="error">No working paths found - Check server configuration</div>';
            }
            
            if (failedPaths.length > 0) {
                html += `<div class="error">Failed paths: ${failedPaths.join(', ')}</div>`;
            }
            
            html += '<h4>Recommended Fix:</h4>';
            if (workingPaths.length > 0) {
                html += `<div>Update LANG_BASE in i18n.js to: "${workingPaths[0]}"</div>`;
            } else {
                html += '<div>Check if web server is running and files are accessible</div>';
            }
            
            document.getElementById('auto-results').innerHTML = html;
        }
        
        // Auto-run on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(runAllTests, 500);
        });
    </script>
</body>
</html>
