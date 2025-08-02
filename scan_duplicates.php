<?php
// Duplicate File Scanner for DigitalNovels
$project_root = __DIR__;

function scanDirectory($dir, $files = []) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            $files = scanDirectory($path, $files);
        } else {
            $files[] = $path;
        }
    }
    return $files;
}

function findDuplicates($files) {
    $duplicates = [];
    $basenames = [];
    
    foreach ($files as $file) {
        $basename = basename($file);
        $dir = dirname($file);
        
        if (!isset($basenames[$basename])) {
            $basenames[$basename] = [];
        }
        $basenames[$basename][] = $file;
    }
    
    foreach ($basenames as $name => $paths) {
        if (count($paths) > 1) {
            $duplicates[$name] = $paths;
        }
    }
    
    return $duplicates;
}

$all_files = scanDirectory($project_root);
$duplicates = findDuplicates($all_files);

echo "<h2>Duplicate File Analysis for DigitalNovels</h2>";

if (empty($duplicates)) {
    echo "<p style='color: green;'>✅ No duplicate files found!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Found " . count($duplicates) . " sets of duplicate files:</p>";
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Filename</th><th>Locations</th><th>Action Needed</th></tr>";
    
    foreach ($duplicates as $filename => $paths) {
        echo "<tr>";
        echo "<td><strong>{$filename}</strong></td>";
        echo "<td>";
        foreach ($paths as $path) {
            $relative_path = str_replace($project_root . DIRECTORY_SEPARATOR, '', $path);
            echo "• {$relative_path}<br>";
        }
        echo "</td>";
        
        // Determine action needed
        echo "<td>";
        if (strpos($filename, 'login.php') !== false) {
            echo "Keep pages/login.php, remove user/login.php and root login.php";
        } elseif (strpos($filename, 'register.php') !== false) {
            echo "Keep pages/register.php, remove user/register.php";
        } elseif (strpos($filename, 'profile.php') !== false) {
            echo "Keep pages/profile.php, remove user/profile.php";
        } elseif (strpos($filename, 'style.css') !== false) {
            echo "Keep assets/css/style.css, remove root style.css";
        } elseif (strpos($filename, 'products') !== false && strpos($filename, 'backup') !== false) {
            echo "Remove backup file";
        } elseif (strpos($filename, 'debug') !== false || strpos($filename, 'test') !== false) {
            echo "Remove debug/test file";
        } else {
            echo "Review manually";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Also check for temporary/debug files
echo "<h3>Temporary/Debug Files Found:</h3>";
$temp_patterns = ['debug_', 'test_', 'fix_', 'update_', 'check_', 'create_', 'cleanup_', 'current_', 'reset_', 'image_'];
$temp_files = [];

foreach ($all_files as $file) {
    $basename = basename($file);
    foreach ($temp_patterns as $pattern) {
        if (strpos($basename, $pattern) === 0) {
            $temp_files[] = str_replace($project_root . DIRECTORY_SEPARATOR, '', $file);
            break;
        }
    }
}

if (!empty($temp_files)) {
    echo "<ul>";
    foreach ($temp_files as $file) {
        echo "<li>{$file}</li>";
    }
    echo "</ul>";
    echo "<p><strong>Recommendation:</strong> These temporary files can likely be deleted.</p>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; }
</style>
