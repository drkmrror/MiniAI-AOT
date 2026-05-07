<?php

session_start();

if(isset($_POST['reset'])) {
    $_SESSION['chat'] = [];
}

if (!isset($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

$message = "";
$reply = "";

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apikey = $_ENV['OPENAI_API_KEY'];

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = $_POST['message'];

    $url = "https://api.openai.com/v1/chat/completions";

    $data = array(
        "model" => "gpt-4o-mini",

"messages" => array(

    array(
        "role" => "system",
        "content" => "You are Eren Yeager. You want freedom. You are an angry type, but also friendly."
    ),

    array(
        "role" => "user",
        "content" => $message
    )

),

        "max_tokens" => 100,
        "temperature" => 0.2
    );

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $apikey
    );

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($curl);

    curl_close($curl);

    $result = json_decode($response, true);

    $reply = $result["choices"][0]["message"]["content"] ?? "Hata oluştu";

        $_SESSION['chat'][] = [
        'message' => $_POST['message'],
        'reply' => $reply
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eren AI</title>
    <link rel="stylesheet" href="eren.css">
</head>
<body>
            <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="#">MiniAI</a>
            </div>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#" class="active">Characters</a>
                        <div class="dropdown-content">
                    <ul>
                        <li><a href="levi.php">Levi</a></li>
                        <li><a href="mikasa.php">Mikasa</a></li>
                        <li><a href="#" class="active">Eren</a></li>
                    </ul>
                    </div>
                    </li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
</div>
      </div>
        </div>
        <form action="eren.php" method="post" class="chat">
            <div class="chat-header">
                <h2>Eren AI</h2>

                <button type="submit" name="reset" class="reset-btn">
               Reset
            </button>
            </div>
<div class="chat-messages">

<?php foreach($_SESSION['chat'] as $chat): ?>

<div class="user-message">
    <?php echo $chat['message']; ?>
</div>

<div class="ai-message">
    <?php echo $chat['reply']; ?>
</div>

<?php endforeach; ?>

</div>

            <div class="chat-input">
        <input
            type="text"
            name="message"
            placeholder="Type your message here"
        >

        <button type="submit">
            Send
        </button>
        </div>
            </div>
        </form>
        <div class="center">
            <h1>This is Eren AI</h1>
            <h2>You can chat with him but don't expect much</h2>
        </div>
    </div>
</body>
</html>