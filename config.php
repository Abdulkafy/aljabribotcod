<?php
// إعدادات البوت
define('BOT_TOKEN', '8552490350:AAHNLzLV7TdvhiLaZAmAI80JHsrsqQItJZQ');
define('BOT_USERNAME', '@abadcodbot');
define('CHANNEL_ID', '-1003318280532');
define('SUPPORT_USERNAME', '@abadcodbot');

// مسارات الملفات
define('DATA_DIR', __DIR__ . '/data/');

// إنشاء مجلد البيانات إذا لم يكن موجوداً
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// دوال التخزين
function loadData($file) {
    $file_path = DATA_DIR . $file . '.json';
    if (!file_exists($file_path)) {
        file_put_contents($file_path, '{}');
        return [];
    }
    $data = file_get_contents($file_path);
    return json_decode($data, true) ?: [];
}

function saveData($file, $data) {
    $file_path = DATA_DIR . $file . '.json';
    file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
}
?>