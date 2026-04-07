<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test i18n - TNB Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .debug-info { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>i18n Test Page</h1>
    
    <div class="test-section">
        <h2>Translation Test</h2>
        <p data-i18n="branches.title">Original:  branches.title</p>
        <p data-i18n="branches.subtitle">Original: branches.subtitle</p>
        <p data-i18n="branches.bangsaen_name">Original: branches.bangsaen_name</p>
    </div>

    <div class="debug-info">
        <h3>Debug Information</h3>
        <div id="debug-output">Loading...</div>
    </div>

    <div class="test-section">
        <h3>Manual Language Switch</h3>
        <button onclick="testLanguage('th')">Thai</button>
        <button onclick="testLanguage('en')">English</button>
        <button onclick="testLanguage('zh')">Chinese</button>
        <button onclick="testLanguage('jp')">Japanese</button>
    </div>

    <script src="../js/i18n.js"></script>
    <script>
        function testLanguage(lang) {
            console.log('Testing language:', lang);
            if (window.tnbLang && window.tnbLang.setLang) {
                window.tnbLang.setLang(lang);
                updateDebugInfo();
            } else {
                console.error('tnbLang not available');
            }
        }

        function updateDebugInfo() {
            const debugDiv = document.getElementById('debug-output');
            if (window.tnbLangDebug) {
                const debug = window.tnbLangDebug();
                debugDiv.innerHTML = `
                    <p><strong>Current Language:</strong> ${debug.currentLang}</p>
                    <p><strong>Has Data:</strong> ${debug.hasData}</p>
                    <p><strong>Cache Keys:</strong> ${debug.cacheKeys.join(', ')}</p>
                    <p><strong>Supported:</strong> ${debug.supported.join(', ')}</p>
                `;
            } else {
                debugDiv.innerHTML = '<p style="color: red;">tnbLangDebug not available</p>';
            }
        }

        // Wait for i18n to initialize
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                console.log('DOM loaded, checking i18n...');
                updateDebugInfo();
                
                if (window.tnbLangTest) {
                    window.tnbLangTest();
                }
            }, 1000);
        });
    </script>
</body>
</html>
