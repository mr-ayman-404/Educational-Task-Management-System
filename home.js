/* home.js - النسخة المطورة والمتوافقة مع التصميم الجديد */

// 1. إظهار وإخفاء حقل كلمة السر بناءً على نوع المستخدم
function togglePasswordField() {
    let role = document.getElementById("user-role").value;
    let passwordSection = document.getElementById("password-section");
    let passwordInput = document.getElementById("password-input");

    // التغيير لـ block يضمن أن الحقل يأخذ العرض الكامل داخل الكرت
    if (role === "teacher") {
        passwordSection.style.display = "block"; 
        passwordInput.focus(); // تركيز المؤشر على حقل كلمة السر فور ظهوره
    } else {
        passwordSection.style.display = "none";
        passwordInput.value = ""; 
    }
}

// 2. معالجة عملية الدخول وتخزين البيانات
function processLogin() {
    let subject = document.getElementById("subject").value;
    let role = document.getElementById("user-role").value;
    let password = document.getElementById("password-input").value;
    let messageBox = document.getElementById("message-box");
    let loginBtn = document.getElementById("login-btn");

    // التأكد من اختيار المادة
    if (!subject) {
        messageBox.innerHTML = " <span style='color:red;'>يرجى اختيار المادة أولاً!</span>";
        return;
    }

    // حفظ البيانات في ذاكرة المتصفح
    localStorage.setItem("subject", subject);
    localStorage.setItem("role", role);

    if (role === "student") {
        // دخول الطالب مباشر لصفحة الرفع (upload.html)
        messageBox.innerHTML = " <span style='color:#048853;'>تم الدخول بنجاح.. جاري التحويل</span>";
        loginBtn.disabled = true; // تعطيل الزر لتجنب التكرار
        setTimeout(() => { 
            window.location.href = "upload.html"; 
        }, 1200);
    } else {
        // منطق الدكتور: التحقق من كلمة السر عبر السيرفر
        if (!password) {
            messageBox.innerHTML = "<span style='color:red;'>يرجى إدخال كلمة السر!</span>";
            return;
        }

        messageBox.innerHTML = " <span style='color:orange;'>جاري التحقق من الصلاحية...</span>";
        loginBtn.disabled = true;

        fetch('check_admin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `password=${encodeURIComponent(password)}`
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                messageBox.innerHTML = " <span style='color:green;'>أهلاً بك دكتور. جاري فتح لوحة التحكم</span>";
                setTimeout(() => { 
                    window.location.href = "dashboard.php"; 
                }, 1200);
            } else {
                messageBox.innerHTML = " <span style='color:red;'>كلمة السر خاطئة!</span>";
                loginBtn.disabled = false;
            }
        })
        .catch(error => {
            messageBox.innerHTML = " <span style='color:red;'>خطأ في الاتصال بالسيرفر</span>";
            loginBtn.disabled = false;
            console.error('Error:', error);
        });
    }
}