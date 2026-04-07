<?php
// Simple PHP test to check server configuration
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Server Configuration Test</title>
</head>
<body>
    <h1>Server Configuration Test</h1>
    
    <h2>PHP Info</h2>
    <p>PHP Version: <?php echo phpversion(); ?></p>
    <p>Current working directory: <?php echo getcwd(); ?></p>
    <p>Script filename: <?php echo __FILE__; ?></p>
    <p>Document root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set'; ?></p>
    
    <h2>File System Test</h2>
    <?php
    $langPath = '../lang/th.json';
    if (file_exists($langPath)) {
        echo "<p style='color: green;'>Language file exists: $langPath</p>";
        if (is_readable($langPath)) {
            echo "<p style='color: green;'>Language file is readable</p>";
            $content = file_get_contents($langPath);
            if ($content) {
                $data = json_decode($content, true);
                if ($data && isset($data['branches']['title'])) {
                    echo "<p style='color: green;'>Translation found: " . $data['branches']['title'] . "</p>";
                } else {
                    echo "<p style='color: red;'>JSON parse error or missing translation</p>";
                }
            } else {
                echo "<p style='color: red;'>Cannot read file content</p>";
            }
        } else {
            echo "<p style='color: red;'>Language file is not readable</p>";
        }
    } else {
        echo "<p style='color: red;'>Language file NOT found: $langPath</p>";
        
        // Try different paths
        $paths = [
            '../lang/th.json',
            './lang/th.json',
            '/lang/th.json',
            dirname(__FILE__) . '/../lang/th.json'
        ];
        
        echo "<h3>Trying different paths:</h3>";
        foreach ($paths as $path) {
            $exists = file_exists($path) ? 'YES' : 'NO';
            echo "<p>$path: $exists</p>";
        }
    }
    ?>
    
    <h2>Directory Listing</h2>
    <p>Current directory contents:</p>
    <pre><?php print_r(scandir('.')); ?></pre>
    
    <p>Parent directory contents:</p>
    <pre><?php print_r(scandir('..')); ?></pre>
    
    <p>Lang directory contents:</p>
    <pre><?php 
    if (is_dir('../lang')) {
        print_r(scandir('../lang')); 
    } else {
        echo '../lang directory not found';
    }
    ?></pre>
    
    <h2>HTTP Headers Test</h2>
    <p>All headers received:</p>
    <pre><?php print_r(getallheaders()); ?></pre>
</body>
</html>
