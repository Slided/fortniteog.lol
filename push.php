<?php
header('Content-Type: application/json');

// Paths to log files
define('HWID_LOG_FILE', __DIR__ . '/google.json');
define('BLACKLIST_LOG_FILE', __DIR__ . '/bl.json');

function read_json_file($filepath) {
    if (!file_exists($filepath)) {
        file_put_contents($filepath, json_encode([]));
        return [];
    }
    $content = file_get_contents($filepath);
    $data = json_decode($content, true);
    if (!is_array($data)) {
        $data = [];
    }
    return $data;
}

function write_json_file($filepath, $data) {
    // Use file locking for safety
    $fp = fopen($filepath, 'c+');
    if (!$fp) return false;
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
    fclose($fp);
    return false;
}

// Get POST inputs
$type = $_POST['type'] ?? null;
$content_json = $_POST['content'] ?? null;

if (!$type || !$content_json) {
    echo json_encode(['success' => false, 'error' => 'Missing type or content']);
    exit;
}

$content = json_decode($content_json, true);
if ($content === null) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON content']);
    exit;
}

switch ($type) {
    case 'hwid':
        $data = read_json_file(HWID_LOG_FILE);

        // Content expected: [hwid, aes_key_hex, "Premium: status"]
        if (!is_array($content) || count($content) < 3) {
            echo json_encode(['success' => false, 'error' => 'Invalid hwid content format']);
            exit;
        }

        $hwid = $content[0];
        $found = false;
        foreach ($data as &$entry) {
            if (is_array($entry) && count($entry) >= 1 && $entry[0] === $hwid) {
                // Update existing entry
                $entry = $content;
                $found = true;
                break;
            }
        }
        unset($entry);

        if (!$found) {
            // Append new entry
            $data[] = $content;
        }

        if (write_json_file(HWID_LOG_FILE, $data)) {
            echo json_encode(['success' => true, 'message' => 'HWID log updated']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to write HWID log file']);
        }
        break;

    case 'blacklist':
        $data = read_json_file(BLACKLIST_LOG_FILE);

        // Content expected: object with hwid, ip, date, os, device
        if (!is_array($content) || !isset($content['hwid'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid blacklist content format']);
            exit;
        }

        $data[] = $content;

        if (write_json_file(BLACKLIST_LOG_FILE, $data)) {
            echo json_encode(['success' => true, 'message' => 'Blacklist log updated']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to write blacklist log file']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown log type']);
        exit;
}

