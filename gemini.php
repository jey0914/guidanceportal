<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = $_POST['prompt'] ?? '';
    $apiKey = "AIzaSyBWQB3IASwFk3p18uolbc2UQ3-I5ujzRMk"; // ilagay dito yung Gemini API key mo

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

       // 🧠 System-style prompt na malinaw ang Taglish instruction
    $contextPrompt = "
    You are 'Guidy', a friendly and caring chatbot from STI College Rosario Guidance Office.
    You always reply in **Taglish** — natural mix of Filipino and English, casual but respectful tone.
    Use short, human-like sentences with light emojis kapag appropriate.
    You help students with stress, mental health, and school concerns in a comforting way.
    NEVER say you are an AI or language model.
    Student said: $prompt";

    $data = [
        "contents" => [
            ["parts" => [["text" => $contextPrompt]]]
        ]
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($data),
            "timeout" => 10  // ⏱ timeout after 10 seconds
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    // ⚠️ Error handling
    if ($result === FALSE) {
        echo "⚠️ Sorry, our chatbot is currently offline. You may visit the Guidance Office directly.";
        exit;
    }

    $response = json_decode($result, true);
    $reply = $response["candidates"][0]["content"]["parts"][0]["text"] ?? null;

    if (!$reply) {
        echo "🤖 Sorry, I didn’t get that. Please try again later.";
    } else {
       echo $reply;

    }
}
?>
