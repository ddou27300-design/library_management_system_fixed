<?php

function translateText($text, $source = 'en', $target = 'km')
{
    $url = 'https://api.mymemory.translated.net/get?q=' .
           urlencode($text) .
           '&langpair=' . $source . '|' . $target;

    $result = @file_get_contents($url);

    if ($result === false) {
        return 'API Connection Failed';
    }

    $response = json_decode($result, true);

    if (isset($response['responseData']['translatedText'])) {
        return $response['responseData']['translatedText'];
    }

    return 'Translation Failed';
}
?>