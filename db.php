<?php
// بيانات الاتصال الخاصة بحساب أيمن عادل - مشروع Aymantec
$host   = "sql205.infinityfree.com"; 
$user   = "if0_41482427"; 
$pass   ="YOUR_PASSWORD_HERE"; // تأكد أنها نفس كلمة سر دخولك للموقع
$dbname = "if0_41482427_tasks"; 

// إنشاء الاتصال باستخدام MySQLi
$conn = new mysqli($host, $user, $pass, $dbname);

// فحص الاتصال - خطوة أمنية هامة
if ($conn->connect_error) {
    // في مرحلة التطوير نظهر الخطأ، لاحقاً سنخفيه لأسباب أمنية
    die("فشل الاتصال بالقاعدة: " . $conn->connect_error);
}

// ضبط الترميز لدعم اللغة العربية بشكل كامل
$conn->set_charset("utf8mb4");

// ملاحظة: لا نغلق وسم PHP هنا لتجنب مشاكل "Headers already sent"
