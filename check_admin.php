<?php
// كلمة السر هنا مخفية في السيرفر ولا يمكن لأي طالب رؤيتها
$admin_password = "Ayman"; 

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        echo "success";
    } else {
        echo "fail";
    }
}
?>