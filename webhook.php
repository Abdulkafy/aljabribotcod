<?php
require_once 'config.php';

$webhookUrl = "https://" . $_SERVER['HTTP_HOST'] . str_replace('webhook.php', 'index.php', $_SERVER['REQUEST_URI']);

echo "<h3>🔧 إعداد ويب هوك البوت</h3>";

// حذف الويب هوك القديم
$delete = file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/deleteWebhook");
echo "<h4>حذف الويب هوك القديم:</h4><pre>" . $delete . "</pre>";

// تعيين الويب هوك الجديد
$setWebhook = file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/setWebhook?url=" . urlencode($webhookUrl));
echo "<h4>تعيين الويب هوك الجديد:</h4><pre>" . $setWebhook . "</pre>";

// معلومات الويب هوك
$webhookInfo = file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/getWebhookInfo");
echo "<h4>معلومات الويب هوك:</h4><pre>" . $webhookInfo . "</pre>";

echo "<h3>🎯 الخطوات التالية:</h3>";
echo "1. اذهب إلى البوت: https://t.me/abadcodbot<br>";
echo "2. أرسل /start<br>";
echo "3. اشترك في القناة<br>";
echo "4. ابدأ باستخدام البوت";
?>