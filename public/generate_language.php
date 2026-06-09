<?php
// កំណត់ Header ឱ្យទៅជា JSON format និងគាំទ្រអក្សរខ្មែរ
header('Content-Type: application/json; charset=utf-8');

// ១. ចាប់យកឈ្មោះឯកសារពី URL (ឧទាហរណ៍៖ ?file=books តាមពិតក្នុងរូបមាន auth, menu, pagination...)
$file = isset($_GET['file']) ? $_GET['file'] : '';
$file = preg_replace('/[^a-zA-Z0-9_]/', '', $file); // ការពារសុវត្ថិភាព

if (empty($file)) {
    echo json_encode(['status' => 'error', 'message' => 'សូមបញ្ជាក់ឈ្មោះ file ឧទាហរណ៍៖ ?file=auth ឬ ?file=pagination'], JSON_UNESCAPED_UNICODE);
    exit;
}

// ២. កំណត់ទីតាំង Folder 'lang' ឱ្យចំរចនាសម្ព័ន្ធពិតប្រាកដរបស់អ្នក (ថយចេញពី public ១ ជំហាន)
$basePath = dirname(__DIR__) . '/lang'; 

$enFilePath = "{$basePath}/en/{$file}.php";
$khFolderPath = "{$basePath}/kh";
$khFilePath = "{$khFolderPath}/{$file}.php";

// ៣. ពិនិត្យមើលថាតើមាន File ភាសាអង់គ្លេសដើម (Source) ដែរឬទេ
if (!file_exists($enFilePath)) {
    echo json_encode([
        'status' => 'error', 
        'message' => "រកមិនឃើញឯកសារអង់គ្លេសដើមឡើយ! សូមប្រាកដថាមានឯកសារឈ្មោះ '{$file}.php' នៅក្នុងថត lang/en/"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ទាញយក Array ភាសាអង់គ្លេសមកប្រើ
$enTranslations = include($enFilePath);
$khTranslations = [];

// ៤. មុខងារសម្រាប់ហៅទៅកាន់ Google API ដើម្បីបកប្រែពាក្យ
function translateWithGoogle($text) {
    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=km&dt=t&q=" . urlencode($text);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    return isset($result[0][0][0]) ? $result[0][0][0] : $text;
}

// ៥. រត់ Loop ដើរបកប្រែទិន្នន័យ Key => Value
foreach ($enTranslations as $key => $value) {
    if (is_string($value)) {
        $khTranslations[$key] = translateWithGoogle($value);
    } else {
        // ករណីតម្លៃជា Array ជាន់គ្នា (Nested Array)
        $khTranslations[$key] = $value; 
    }
}

// ៦. បង្កើត Folder 'kh' បើមិនទាន់មាន
if (!is_dir($khFolderPath)) {
    mkdir($khFolderPath, 0755, true);
}

// ៧. បម្លែង Array ទៅជាកូដ PHP format រួចសរសេរចូល File ខ្មែរ
$fileContent = "<?php\n\nreturn " . var_export($khTranslations, true) . ";\n";
$fileContent = str_replace("array (", "[", $fileContent);
$fileContent = str_replace("),", "],", $fileContent);
$fileContent = preg_replace("/\)$/", "];", trim($fileContent));

if (file_put_contents($khFilePath, $fileContent)) {
    echo json_encode([
        'status' => 'success',
        'message' => "បានបកប្រែ និងបង្កើតឯកសារភាសាខ្មែរជោគជ័យ!",
        'file_created' => "lang/kh/{$file}.php",
        'data' => $khTranslations
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(['status' => 'error', 'message' => 'មិនអាចសរសេរចូល File បានទេ សូមពិនិត្យ Permission របស់ Folder'], JSON_UNESCAPED_UNICODE);
}