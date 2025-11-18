<?php
echo "<h2>🚀 حل مشكلة الاستضافة</h2>";

echo "
<div style='background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>
<h3>📋 المشكلة: الاستضافة تمنع الاتصال الخارجي</h3>
<p>الاستضافة الحالية (<code>atwebpages.com</code>) لا تسمح للبوت بالاتصال بتيليجرام</p>
</div>

<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>
<h3>🎯 الحلول المتاحة:</h3>

<h4>🅰️ <a href='https://www.000webhost.com' target='_blank'>000webhost</a> (مستحسن)</h4>
<ul>
<li>✅ يدعم الاتصال الخارجي</li>
<li>✅ مجاني</li>
<li>✅ PHP 8.x</li>
<li>✅ مثبت أنه يعمل مع البوتات</li>
</ul>

<h4>🅱️ <a href='https://render.com' target='_blank'>Render.com</a></h4>
<ul>
<li>✅ مجاني للبوتات</li>
<li>✅ يدعم الاتصال الخارجي</li>
<li>✅ سهل الاستخدام</li>
</ul>

<h4>🅲️ <a href='https://byet.host' target='_blank'>ByetHost</a></h4>
<ul>
<li>✅ مجاني</li>
<li>✅ يدعم الاتصال الخارجي</li>
<li>✅ cPanel كامل</li>
</ul>
</div>

<h3>📝 خطوات الترحيل لـ 000webhost:</h3>
<ol>
<li>اذهب إلى <a href='https://www.000webhost.com' target='_blank'>000webhost.com</a></li>
<li>سجل حساب جديد (5 دقائق)</li>
<li>انقل الملفات الأربعة</li>
<li>شغّل webhook.php</li>
<li>اختبر البوت</li>
</ol>
";

// اختبار بديل باستخدام cURL
echo "<h3>🔍 اختبار بديل:</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot8552490350:AAHNLzLV7TdvhiLaZAmAI80JHsrsqQItJZQ/getMe");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($result) {
    echo "<p style='color: green;'>✅ cURL يعمل! البوت يمكن أن يعمل</p>";
} else {
    echo "<p style='color: red;'>❌ cURL فشل أيضاً: $error</p>";
    echo "<p><b>الحل الوحيد: تغيير الاستضافة</b></p>";
}
?>