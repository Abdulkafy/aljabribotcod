<?php
echo "<h2>⚡ إعداد سريع لاستضافة جديدة</h2>";

// إنشاء الملفات تلقائياً
$files = [
    'config.php' => '<?php
define(\'BOT_TOKEN\', \'8552490350:AAHNLzLV7TdvhiLaZAmAI80JHsrsqQItJZQ\');
define(\'BOT_USERNAME\', \'@abadcodbot\');
define(\'CHANNEL_ID\', \'-1003318280532\');
define(\'DATA_DIR\', __DIR__ . \'/data/\');
if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
?>',

    'index.php' => '<?php
require_once \"config.php\";
function bot($method, \$data = []) {
    \$url = \"https://api.telegram.org/bot\" . BOT_TOKEN . \"/\" . \$method;
    \$ch = curl_init();
    curl_setopt(\$ch, CURLOPT_URL, \$url);
    curl_setopt(\$ch, CURLOPT_POST, true);
    curl_setopt(\$ch, CURLOPT_POSTFIELDS, \$data);
    curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(\$ch, CURLOPT_TIMEOUT, 10);
    curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, false);
    \$result = curl_exec(\$ch);
    curl_close(\$ch);
    return json_decode(\$result, true);
}

\$update = json_decode(file_get_contents(\"php://input\"), true);
if (\$update && isset(\$update[\"message\"])) {
    \$chat_id = \$update[\"message\"][\"chat\"][\"id\"];
    \$text = \$update[\"message\"][\"text\"] ?? \"\";
    if (\$text == \"/start\") {
        bot(\"sendMessage\", [
            \"chat_id\" => \$chat_id,
            \"text\" => \"🎉 البوت يعمل بنجاح على الاستضافة الجديدة!\"
        ]);
    }
}

if (\$_SERVER[\"REQUEST_METHOD\"] == \"GET\") {
    echo \"🤖 البوت جاهز على استضافة جديدة!\";
}
?>'
];

foreach ($files as $filename => $content) {
    echo "<h4>📄 $filename:</h4>";
    echo "<textarea style='width: 100%; height: 150px; font-family: monospace;'>$content</textarea>";
    echo "<br><br>";
}

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
echo "<h3>🚀 الخطوات السريعة:</h3>";
echo "1. اختر استضافة من: 000webhost, AwardSpace, ByetHost<br>";
echo "2. انسخ الأكواد أعلاه<br>";
echo "3. الصقها في ملفات جديدة<br>";
echo "4. شغّل البوت<br>";
echo "</div>";
?>