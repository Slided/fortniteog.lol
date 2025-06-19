<?php
// Simple PHP push script to update JSON logs for Hybrid app

// Set JSON files
$google_file = "google.json";
$bl_file = "bl.json";

// Get JSON data from POST
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "No JSON data received"]);
    exit;
}

// Create files if missing
if (!file_exists($google_file)) {
    file_put_contents($google_file, json_encode([]));
}
if (!file_exists($bl_file)) {
    file_put_contents($bl_file, json_encode(["blacklist" => []]));
}

// Read current data
$google_data = json_decode(file_get_contents($google_file), true);
$bl_data = json_decode(file_get_contents($bl_file), true);

// Handle types
switch ($data['type']) {
    case 'new_user':
        // Add new user if not exist
        $exists = false;
        foreach ($google_data as $entry) {
            if ($entry['hwid'] === $data['hwid']) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $google_data[] = [
                "hwid" => $data['hwid'],
                "aes" => $data['aes'],
                "premium" => false
            ];
        }
        break;

    case 'update_aes':
        foreach ($google_data as &$entry) {
            if ($entry['hwid'] === $data['hwid']) {
                $entry['aes'] = $data['aes'];
                $entry['premium'] = true;
                break;
            }
        }
        unset($entry);
        break;

    case 'blacklist_log':
        $found = false;
        foreach ($bl_data['blacklist'] as &$entry) {
            if ($entry['hwid'] === $data['hwid']) {
                $entry['logs'][] = $data['log'];
                $found = true;
                break;
            }
        }
        unset($entry);
        if (!$found) {
            $bl_data['blacklist'][] = [
                "hwid" => $data['hwid'],
                "logs" => [$data['log']]
            ];
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Unknown type"]);
        exit;
}

// Save updated JSON
file_put_contents($google_file, json_encode($google_data, JSON_PRETTY_PRINT));
file_put_contents($bl_file, json_encode($bl_data, JSON_PRETTY_PRINT));

echo json_encode(["success" => true]);
?>
