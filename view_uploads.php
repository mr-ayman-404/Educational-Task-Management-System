<?php
include 'db.php'; 
$conn->set_charset("utf8mb4");

// جلب المادة من الرابط (URL)
$current_subject = isset($_GET['subject']) ? $_GET['subject'] : '';

// استعلام لجلب طلاب هذه المادة فقط
$sql = "SELECT student_name, upload_date FROM student_assignments 
        WHERE subject_name = '" . $conn->real_escape_string($current_subject) . "' 
        ORDER BY upload_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المسلمون في مادة <?php echo htmlspecialchars($current_subject); ?></title>
    <link rel="stylesheet" href="style-home.css">
    <style>
        .container { width: 85%; margin: 30px auto; text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .subject-title { color: #104e8b; background: #eef4f9; padding: 10px; border-radius: 8px; display: inline-block; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #eee; text-align: center; }
        th { background-color: #104e8b; color: white; }
        .back-btn { display: inline-block; margin-top: 20px; padding: 10px 25px; background: #333; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>قائمة الطلاب الذين سلموا التكليف</h2>
        <div class="subject-title">المادة: <strong><?php echo htmlspecialchars($current_subject); ?></strong></div>

        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>اسم الطالب</th>
                    <th>تاريخ التسليم</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $i = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $i++ . "</td>
                                <td>" . htmlspecialchars($row['student_name']) . "</td>
                                <td>" . $row['upload_date'] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>لا يوجد طلاب سلموا هذه المادة بعد.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="index.html" class="back-btn">العودة للرئيسية</a>
    </div>
</body>
</html>
