<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استقبال البيانات وحمايتها من SQL Injection
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doc_user']);
    $password_input = $_POST['doc_pass'];

    // البحث عن الدكتور في قاعدة البيانات
    $sql = "SELECT * FROM doctors WHERE doctor_name = '$doctor_name' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();

        // التحقق من كلمة المرور
        if ($password_input == $doctor['password']) {
            
            // تخزين بيانات الجلسة (Session)
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['doctor_name'];
            $_SESSION['subject'] = $doctor['subject_name'];
            
            // تخزين الرتبة (admin أو doctor) لفتح الصلاحيات المناسبة
            $_SESSION['role'] = $doctor['role']; 

            // التوجه للوحة التحكم في حال النجاح
            header("Location: dashboard.php");
            exit();
        }
    }

    // --- في حال فشل تسجيل الدخول (سواء الاسم خطأ أو الباسورد خطأ) ---
    // نتوجه لصفحة الدخول مع كود خطأ موحد 'invalid_auth'
    // هذا الكود هو الذي سيقوم بإظهار الرسالة الحمراء أسفل الحقل في الصفحة الأخرى
    header("Location: doctor_login.php?error=invalid_auth");
    exit();
}
?>