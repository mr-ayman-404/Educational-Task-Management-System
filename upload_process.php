<?php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->set_charset("utf8mb4");

    $student_name = isset($_POST['studentName']) ? $conn->real_escape_string($_POST['studentName']) : '';
    $subject_name = isset($_POST['subjectSelect']) ? $conn->real_escape_string($_POST['subjectSelect']) : '';
    $student_note = isset($_POST['studentNote']) ? $conn->real_escape_string($_POST['studentNote']) : '';

    if (empty($student_name) || !isset($_FILES['assignmentFile'])) {
        die("بيانات ناقصة");
    }

    $file = $_FILES['assignmentFile'];
    $file_name_original = $file['name'];
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));
    
    // 1. القائمة البيضاء للامتدادات المسموحة
    $allowed_ext = array("pdf", "doc", "docx", "jpg", "jpeg", "png");

    // 2. فحص نوع الملف (MIME Type) لزيادة الأمان
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mime = array(
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png'
    );

    // التحقق من الامتداد والنوع معاً
    if (!in_array($file_ext, $allowed_ext) || !in_array($mime_type, $allowed_mime)) {
        die("نوع الملف غير مسموح به! يرجى رفع ملفات PDF، Word أو صور فقط.");
    }

    // 3. منع رفع الملفات الكبيرة (أكبر من 10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        die("حجم الملف كبير جداً.");
    }

    // توليد اسم عشوائي تماماً للملف لمنع الكتابة فوق ملفات قديمة
    $new_file_name = time() . "_" . bin2hex(random_bytes(8)) . "." . $file_ext;
    $upload_path = "uploads/" . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $sql = "INSERT INTO student_assignments (student_name, subject_name, file_name, student_note) 
                VALUES ('$student_name', '$subject_name', '$new_file_name', '$student_note')";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "خطأ في القاعدة: " . $conn->error;
        }
    } else {
        echo "فشل نقل الملف، تأكد من صلاحيات مجلد uploads.";
    }
}
?>