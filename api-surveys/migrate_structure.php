<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbConfig = ["database" => "db-surveys", "user" => "root", "pass" => ""];

echo "<h3>Starting Migration...</h3>";

try {
    $link = new PDO("mysql:host=localhost;dbname=" . $dbConfig["database"], $dbConfig["user"], $dbConfig["pass"]);
    $link->exec("set names utf8");
    echo "Connected DB.<br><hr>";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 1. Questions (bsurveys)
echo "<h3>Processing Questions...</h3>";
$stmt = $link->prepare("SELECT id_bsurvey, name_bsurvey, type_bsurvey, detail_bsurvey FROM bsurveys WHERE type_bsurvey IN (3, 4)");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_OBJ);

$countUpdated = 0;
$countSkipped = 0;

foreach ($questions as $q) {
    echo "<strong>ID: " . $q->id_bsurvey . " (" . $q->name_bsurvey . ")</strong><br>";
    $decoded = json_decode($q->detail_bsurvey, true);

    if (!is_array($decoded)) {
        echo "<span style='color:red'>Invalid JSON.</span><br>";
        continue;
    }

    $needsUpdate = false;
    $newStructure = [];

    // Case A: Array of Strings ["Op1", "Op2"]
    if (count($decoded) > 0 && is_string($decoded[0])) {
        echo "Format: Strings. Converting... ";
        $needsUpdate = true;
        foreach ($decoded as $opt) {
            $newStructure[] = ["nombre" => $opt, "has_input" => false];
        }
    }
    // Case B: Array of Objects but missing 'has_input'
    elseif (count($decoded) > 0 && is_array($decoded[0])) {
        if (isset($decoded[0]['nombre'])) {
            if (!isset($decoded[0]['has_input'])) {
                echo "Format: Missing 'has_input'. Fixing... ";
                $needsUpdate = true;
                foreach ($decoded as $item) {
                    if (!isset($item['has_input']))
                        $item['has_input'] = false;
                    $newStructure[] = $item;
                }
            } else {
                echo "Format: OK.<br>";
                $countSkipped++;
            }
        } else {
            echo "Format: Unknown structure.<br>";
            $countSkipped++;
        }
    } else {
        echo "Format: Empty/Unknown.<br>";
        $countSkipped++;
    }

    if ($needsUpdate) {
        $newJson = json_encode($newStructure, JSON_UNESCAPED_UNICODE);
        $upd = $link->prepare("UPDATE bsurveys SET detail_bsurvey = :json WHERE id_bsurvey = :id");
        $upd->execute([':json' => $newJson, ':id' => $q->id_bsurvey]);
        echo "<span style='color:green'>UPDATED</span><br>";
        $countUpdated++;
    }
}

// 2. Answers
echo "<hr><h3>Processing Answers (Type 4)...</h3>";
$stmtAns = $link->prepare("SELECT a.id_answer, a.detail_answer FROM answers a JOIN bsurveys b ON a.id_bsurvey_answer = b.id_bsurvey WHERE b.type_bsurvey = 4");
$stmtAns->execute();
$answers = $stmtAns->fetchAll(PDO::FETCH_OBJ);
$ansUpdated = 0;

foreach ($answers as $ans) {
    if (empty($ans->detail_answer))
        continue;
    $val = $ans->detail_answer;
    $decoded = json_decode($val, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        // OK
    } else {
        echo "Ans " . $ans->id_answer . ": Converting to JSON... ";
        if (strpos($val, ',') !== false) {
            $parts = array_map('trim', explode(',', $val));
            $newVal = json_encode($parts, JSON_UNESCAPED_UNICODE);
        } else {
            $newVal = json_encode([$val], JSON_UNESCAPED_UNICODE);
        }
        $upd = $link->prepare("UPDATE answers SET detail_answer = :json WHERE id_answer = :id");
        $upd->execute([':json' => $newVal, ':id' => $ans->id_answer]);
        echo "<span style='color:green'>UPDATED</span><br>";
        $ansUpdated++;
    }
}
echo "<hr>Done. Q: $countUpdated upd/$countSkipped skp. A: $ansUpdated upd.";
?>