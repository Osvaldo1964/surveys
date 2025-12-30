<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dbConfig = ["database" => "db-surveys", "user" => "root", "pass" => ""];

echo "<h3>Fixing Type 5 Structure (Adding has_input)</h3>";

try {
    $link = new PDO("mysql:host=localhost;dbname=" . $dbConfig["database"], $dbConfig["user"], $dbConfig["pass"]);
    $link->exec("set names utf8");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$stmt = $link->prepare("SELECT id_bsurvey, name_bsurvey, detail_bsurvey FROM bsurveys WHERE type_bsurvey = 5");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_OBJ);

$countUpdated = 0;

foreach ($questions as $q) {
    echo "Processing ID: " . $q->id_bsurvey . " (" . $q->name_bsurvey . ")... ";

    $decoded = json_decode($q->detail_bsurvey, true);

    if (!is_array($decoded)) {
        echo "Invalid JSON. Skipping.<br>";
        continue;
    }

    $needsUpdate = false;
    $newStructure = [];

    foreach ($decoded as $item) {
        if (!isset($item['has_input'])) {
            $item['has_input'] = false; // Default value
            $needsUpdate = true;
        }
        $newStructure[] = $item;
    }

    if ($needsUpdate) {
        $newJson = json_encode($newStructure, JSON_UNESCAPED_UNICODE);
        $upd = $link->prepare("UPDATE bsurveys SET detail_bsurvey = :json WHERE id_bsurvey = :id");
        $upd->execute([':json' => $newJson, ':id' => $q->id_bsurvey]);
        echo "<span style='color:green'>UPDATED</span><br>";
        $countUpdated++;
    } else {
        echo "Already correct.<br>";
    }
}

echo "<hr>Done. Updated: $countUpdated questions.";
?>