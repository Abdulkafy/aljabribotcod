<?php
require_once 'config.php';

// دوال API للبوت
function bot($method, $data = []) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/" . $method;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($result, true);
}

function sendMessage($chat_id, $text, $reply_markup = null) {
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    if ($reply_markup) $data['reply_markup'] = $reply_markup;
    return bot('sendMessage', $data);
}

function answerCallback($callback_id, $text = '', $show_alert = false) {
    $data = [
        'callback_query_id' => $callback_id,
        'show_alert' => $show_alert
    ];
    if ($text) $data['text'] = $text;
    return bot('answerCallbackQuery', $data);
}

// التحقق من الاشتراك في القناة
function checkSubscription($chat_id) {
    $member = bot('getChatMember', [
        'chat_id' => CHANNEL_ID,
        'user_id' => $chat_id
    ]);
    
    return isset($member['result']['status']) && 
           ($member['result']['status'] == 'member' || 
            $member['result']['status'] == 'administrator' ||
            $member['result']['status'] == 'creator');
}

// الخدمات
function getServices() {
    return [
        'whatsapp' => ['name' => 'واتساب', 'icon' => '💬', 'price' => 5],
        'telegram' => ['name' => 'تيليجرام', 'icon' => '📢', 'price' => 4],
        'facebook' => ['name' => 'فيسبوك', 'icon' => '🌐', 'price' => 6],
        'instagram' => ['name' => 'انستجرام', 'icon' => '📸', 'price' => 7]
    ];
}

// لوحات المفاتيح
function mainMenuKeyboard() {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => '📱 شراء رقم', 'callback_data' => 'buy_number'],
                ['text' => '💰 رصيدي', 'callback_data' => 'my_balance']
            ],
            [
                ['text' => '💳 شحن الرصيد', 'callback_data' => 'charge_balance'],
                ['text' => '📞 الدعم', 'callback_data' => 'support']
            ]
        ]
    ]);
}

function subscriptionKeyboard() {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => '📢 اشترك في القناة', 'url' => 'https://t.me/https://t.me/alabadgo']
            ],
            [
                ['text' => '✅ تحقق من الاشتراك', 'callback_data' => 'check_subscription']
            ]
        ]
    ]);
}

// المعالجة الرئيسية
$update = json_decode(file_get_contents('php://input'), true);

if ($update) {
    $chat_id = $update['message']['chat']['id'] ?? $update['callback_query']['from']['id'];
    $text = $update['message']['text'] ?? '';
    $callback_data = $update['callback_query']['data'] ?? '';
    $callback_id = $update['callback_query']['id'] ?? '';
    
    // تحميل البيانات
    $users = loadData('users');
    $points = loadData('points');
    
    // معالجة المستخدم الجديد
    if (!isset($users[$chat_id])) {
        $users[$chat_id] = [
            'id' => $chat_id,
            'username' => $update['message']['chat']['username'] ?? '',
            'first_name' => $update['message']['chat']['first_name'] ?? '',
            'join_date' => date('Y-m-d H:i:s')
        ];
        
        $points[$chat_id] = [
            'balance' => 10,
            'total_spent' => 0,
            'total_orders' => 0
        ];
        
        saveData('users', $users);
        saveData('points', $points);
    }
    
    // التحقق من الاشتراك
    if (!checkSubscription($chat_id) && $text != '/start') {
        sendMessage($chat_id, 
            "📢 <b>عذراً عزيزي</b>\n\nيجب الاشتراك في قناتنا أولاً\n\n✅ بعد الاشتراك، اضغط على زر التحقق", 
            subscriptionKeyboard()
        );
        exit;
    }
    
    // معالجة /start
    if ($text == '/start') {
        if (!checkSubscription($chat_id)) {
            sendMessage($chat_id, 
                "🎉 <b>أهلاً بك في بوت الأرقام</b>\n\n📢 <b>للاستخدام يجب الاشتراك في قناتنا أولاً</b>", 
                subscriptionKeyboard()
            );
        } else {
            sendMessage($chat_id, 
                "🎉 <b>أهلاً بك في بوت الأرقام</b>\n\n💰 <b>رصيدك:</b> " . $points[$chat_id]['balance'] . " ريال\n\n🚀 <b>ابدأ بشراء أول رقم لك!</b>", 
                mainMenuKeyboard()
            );
        }
    }
    
    // معالجة Callback Queries
    elseif ($callback_data) {
        answerCallback($callback_id);
        
        if ($callback_data == 'check_subscription') {
            if (checkSubscription($chat_id)) {
                sendMessage($chat_id, 
                    "🎉 <b>شكراً للاشتراك!</b>\n\n💰 <b>رصيدك:</b> " . $points[$chat_id]['balance'] . " ريال\n\n🚀 <b>ابدأ باستخدام البوت</b>", 
                    mainMenuKeyboard()
                );
            } else {
                answerCallback($callback_id, "❌ لم تشترك بعد في القناة", true);
            }
        }
        elseif ($callback_data == 'main_menu') {
            sendMessage($chat_id, "🏠 <b>القائمة الرئيسية</b>", mainMenuKeyboard());
        }
        elseif ($callback_data == 'buy_number') {
            $services = getServices();
            $keyboard = [];
            foreach ($services as $code => $service) {
                $keyboard[] = [
                    ['text' => $service['icon'] . ' ' . $service['name'], 'callback_data' => "service_$code"]
                ];
            }
            $keyboard[] = [['text' => '🔙 رجوع', 'callback_data' => 'main_menu']];
            sendMessage($chat_id, "📱 اختر التطبيق:", json_encode(['inline_keyboard' => $keyboard]));
        }
        elseif ($callback_data == 'my_balance') {
            sendMessage($chat_id, 
                "💳 <b>معلومات الرصيد</b>\n\n💰 الرصيد: " . $points[$chat_id]['balance'] . " ريال\n📦 الطلبات: " . $points[$chat_id]['total_orders'], 
                mainMenuKeyboard()
            );
        }
        elseif ($callback_data == 'charge_balance') {
            sendMessage($chat_id, 
                "💳 <b>شحن الرصيد</b>\n\n📞 تواصل مع الدupport: " . SUPPORT_USERNAME, 
                mainMenuKeyboard()
            );
        }
        elseif ($callback_data == 'support') {
            sendMessage($chat_id, 
                "📞 <b>الدعم الفني</b>\n\n👨‍💻 الدعم: " . SUPPORT_USERNAME . "\n🕒 متوفر 24/7", 
                mainMenuKeyboard()
            );
        }
    }
}

// رد على الطلبات العادية
if ($_SERVER['REQUEST_METHOD'] == 'GET' && empty($input)) {
    echo "🤖 <b>بوت الأرقام الافتراضية</b><br>";
    echo "✅ الحالة: نشط<br>";
    echo "🚀 أرسل /start للبدء";
}
?>