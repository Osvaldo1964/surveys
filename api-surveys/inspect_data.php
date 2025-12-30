<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dbConfig = ["database" => "db-surveys", "user" => "root", "pass" => ""];

try {
    $link = new PDO("mysql:host=localhost;dbname=" . $dbConfig["database"], $dbConfig["user"], $dbConfig["pass"]);
    $link->exec("set names utf8");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

echo "<h3>inspeccao</h3>\n";
$stmt = $link->prepare("SELECT * FROM bsurveys ORDER BY id_bsurvey");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($questions)) {
    echo "Columns: " . implode(", ", array_keys($questions[0])) . "\n\n";
}

echo "Searching for ID 20 or Type 5...\n";
foreach ($questions as $q) {
    if ($q['id_bsurvey'] == 20 || $q['type_bsurvey'] == 5) {
        echo "ID: " . $q['id_bsurvey'] . " | Name: " . $q['name_bsurvey'] . " | Type: " . $q['type_bsurvey'] . "\n";
        echo "Detail: " . $q['detail_bsurvey'] . "\n";
        $json = json_decode($q['detail_bsurvey'], true);
        echo "Structure:\n" . print_r($json, true) . "\n";
        echo "--------------------------------------------------\n";
    }
}
?>