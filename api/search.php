<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

// Clean up the input
$query = trim($_GET['q'] ?? '');
if (!$query) {
    echo json_encode([]);
    exit;
}

// Some known nicknames/abbreviations mapped to actual artist names
$aliases = [
    'mj' => 'michael jackson',
    'jt' => 'justin timberlake',
    'em' => 'eminem',
    'kdot' => 'kendrick lamar',
    'weeknd' => 'the weeknd',
    'slim shady' => 'eminem',
    'queen bey' => 'beyonce',
    'bee' => 'beyonce',
    'taytay' => 'taylor swift',
    'drizzy' => 'drake',
    'aubrey' => 'drake',
    'rih' => 'rihanna',
    'kanye' => 'ye',
    'ye' => 'kanye west',
    'bruno' => 'bruno mars',
];

// Expand alias if query matches a known short form
$lowerQuery = strtolower($query);
if (array_key_exists($lowerQuery, $aliases)) {
    $query = $aliases[$lowerQuery];
}

// Helper: remove accents, symbols etc. for better matching
function normalize($string) {
    return preg_replace('/[^A-Za-z0-9 ]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $string));
}

$normalizedQuery = normalize($query);

// Break user input into possible tokens (to catch camelCase / space issues)
$tokens = preg_split('/(?=[A-Z])|\s+/', $query);
$partialVariants = [];
foreach ($tokens as $word) {
    $word = normalize($word);
    if (strlen($word) >= 3) {
        $partialVariants[] = "%$word%";
    }
}

// Dynamically build the search query based on tokens
$likeConditions = implode(' OR ', array_fill(0, count($partialVariants), 'name LIKE ?'));
$sql = "SELECT * FROM artists WHERE name LIKE ? OR name LIKE ?" .
       ($likeConditions ? " OR $likeConditions" : "") . " LIMIT 1000";

$params = ["%$query%", "%" . explode(' ', $query)[0] . "%"];
$params = array_merge($params, $partialVariants);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute fuzzy match score between input and artist name
function computeScore($name, $input) {
    $nameNorm = strtolower(normalize($name));
    $inputNorm = strtolower(normalize($input));

    // Direct substring gets max score
    if (strpos($nameNorm, $inputNorm) !== false || strpos($inputNorm, $nameNorm) !== false) {
        return 1000;
    }

    // Slight bonus for phonetic match (e.g. Cold Play → Coldplay)
    $metaInput = metaphone($inputNorm);
    $metaName = metaphone($nameNorm);
    $phoneticScore = ($metaInput === $metaName) ? 150 : 0;

    // Typo similarity
    $lev = levenshtein($inputNorm, $nameNorm);
    similar_text($inputNorm, $nameNorm, $percentMatch);

    // Final weighted score
    return $phoneticScore + ($percentMatch * 1.2) - ($lev * 1.5);
}


// Sort based on fuzzy score (descending)
usort($artists, function ($a, $b) use ($query) {
    return computeScore($b['name'], $query) <=> computeScore($a['name'], $query);
});

// Return top 10 ranked suggestions as JSON
echo json_encode(array_slice($artists, 0, 10));
