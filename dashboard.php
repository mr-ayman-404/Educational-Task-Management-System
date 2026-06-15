<?php
session_start();
include 'db.php'; 
$conn->set_charset("utf8mb4");

// 1. التحقق من أن المستخدم سجل دخوله
if (!isset($_SESSION['role'])) {
    header("Location: doctor_login.php");
    exit();
}

$user_role = $_SESSION['role']; 
$doctor_subject = isset($_SESSION['subject']) ? $_SESSION['subject'] : '';

// 2. استقبال المادة المختارة
if ($user_role == 'admin') {
    $filter = isset($_GET['subject_filter']) ? $_GET['subject_filter'] : 'all';
} else {
    $filter = $doctor_subject;
}

// 3. بناء استعلام SQL (SELECT * تجلب عمود student_note الجديد تلقائياً)
if ($filter != 'all' && !empty($filter)) {
    $stmt = $conn->prepare("SELECT * FROM student_assignments WHERE subject_name = ? ORDER BY upload_date DESC");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM student_assignments ORDER BY upload_date DESC";
    $result = $conn->query($sql);
}

// 4. جلب قائمة المواد للقائمة المنسدلة
$subjects_query = "SELECT DISTINCT subject_name FROM student_assignments";
$subjects_result = $conn->query($subjects_query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - <?php echo ($user_role == 'admin') ? 'المندوب' : 'الدكتور'; ?></title>
    <link rel="stylesheet" href="style-home.css">
    <link rel="stylesheet" href="header.css"> 
    <style>
        .filter-container { width: 90%; margin: 20px auto; text-align: right; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd; }
        select { padding: 8px; font-size: 16px; border-radius: 5px; width: 250px; font-family: inherit; }
        table { width: 95%; margin: 20px auto; border-collapse: collapse; background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #104e8b; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .download-btn { background-color: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px; }
        .logout-btn { display: block; width: 100px; margin: 20px auto; background-color: #dc3545; color: white; text-align: center; padding: 10px; text-decoration: none; border-radius: 5px; }
        .welcome-header { text-align: center; color: #333; margin-top: 20px; }
        
        /* تنسيق خاص لخلية الملاحظات */
        .note-text { 
            max-width: 250px; 
            font-size: 13px; 
            color: #555; 
            word-wrap: break-word; 
            font-style: italic;
            text-align: right; 
        }
        .no-note { color: #ccc; font-style: normal; }
    </style>
</head>
<body>
    <div id="header-placeholder"></div>

    <h2 class="welcome-header">سجل تسليم التكاليف </h2>
    
    <?php if ($user_role == 'admin'): ?>
        <div class="filter-container">
            <form method="GET" id="filterForm">
                <label for="subject_filter">تصفية حسب المادة: </label>
                <select name="subject_filter" onchange="document.getElementById('filterForm').submit()">
                    <option value="all">عرض جميع المواد</option>
                    <?php
                    if ($subjects_result->num_rows > 0) {
                        while($sub = $subjects_result->fetch_assoc()) {
                            $selected = ($filter == $sub['subject_name']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($sub['subject_name']) . "' $selected>" . htmlspecialchars($sub['subject_name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </form>
        </div>
    <?php else: ?>
        <h3 style="text-align:center; color:#104e8b;">مرحباً د. <?php echo htmlspecialchars($_SESSION['doctor_name']); ?> | مادة : <?php echo htmlspecialchars($doctor_subject); ?></h3>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>رقم</th>
                <th>اسم الطالب</th>
                <th>المادة</th>
                <th>تاريخ التسليم</th>
                <th>الملاحظة</th> <th>الملف</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $count = 1;
                while($row = $result->fetch_assoc()) {
                    // التحقق من وجود ملاحظة
                    $display_note = !empty($row['student_note']) ? htmlspecialchars($row['student_note']) : '<span class="no-note">لا توجد</span>';
                    
                    echo "<tr>
                            <td>" . $count++ . "</td>
                            <td>" . htmlspecialchars($row['student_name']) . "</td>
                            <td>" . htmlspecialchars($row['subject_name']) . "</td>
                            <td>" . $row['upload_date'] . "</td>
                            <td class='note-text'>" . $display_note . "</td>
                            <td><a href='uploads/" . $row['file_name'] . "' class='download-btn' download>تحميل الملف</a></td>
                          </tr>";
                }
            } else {
                // تعديل colspan ليكون 6 أعمدة بدلاً من 5
                echo "<tr><td colspan='6'>لا توجد تكاليف مرفوعة حالياً</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="logout.php" class="logout-btn">تسجيل خروج</a>
    <script src="header.js"></script>
</body>
</html>