<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الأكاديميين - aymantec</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="header.css"> 
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f0f2f5; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* حاوية الدخول */
        .login-wrapper { flex: 1; display: flex; justify-content: center; align-items: flex-start; padding: 50px 20px; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        
        .doctor-icon { width: 70px; height: 70px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 30px; margin: 0 auto 20px; }
        
        h2 { color: #244b7a; margin-bottom: 25px; font-size: 22px; }
        
        .form-group { text-align: right; margin-bottom: 20px; position: relative; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; transition: 0.3s; }
        .form-group input:focus { border-color: #4caf50; }
        
        /* ستايل رسائل الخطأ أسفل الحقول */
        .error-hint { color: #d32f2f; font-size: 12px; margin-top: 5px; display: block; font-weight: bold; }
        .js-error { display: none; } /* رسائل الـ JavaScript تكون مخفية افتراضياً */
        .input-invalid { border-color: #d32f2f !important; background-color: #fff8f8; }

        .btn-login { width: 100%; padding: 12px; background-color: #244b7a; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-login:hover { background-color: #1a3556; }
    </style>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="doctor-icon"><i class="fas fa-user-tie"></i></div>
            
            <h2>دخول أعضاء هيئة التدريس</h2>
            
            <form action="verify_doctor.php" method="POST" id="loginForm" novalidate>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> اسم الدكتور</label>
                    <input type="text" name="doc_user" id="doc_user" placeholder="أدخل اسم المستخدم" 
                           class="<?php echo (isset($_GET['error'])) ? 'input-invalid' : ''; ?>">
                    <span id="user_error" class="error-hint js-error">يرجى إدخال اسم الدكتور</span>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> كلمة المرور</label>
                    <input type="password" name="doc_pass" id="doc_pass" placeholder="أدخل كلمة المرور"
                           class="<?php echo (isset($_GET['error'])) ? 'input-invalid' : ''; ?>">
                    
                    <span id="pass_error" class="error-hint js-error">يرجى إدخال كلمة المرور</span>

                    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_auth'): ?>
                        <span class="error-hint">
                            <i class="fas fa-exclamation-triangle"></i> اسم المستخدم أو كلمة المرور غير صحيحة
                        </span>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn-login">دخول للوحة التحكم</button>
            </form>
        </div>
    </div>

    <script>
        // التحقق من الحقول قبل إرسال الفورم
        document.getElementById('loginForm').onsubmit = function(e) {
            let userField = document.getElementById('doc_user');
            let passField = document.getElementById('doc_pass');
            let userError = document.getElementById('user_error');
            let passError = document.getElementById('pass_error');
            let isValid = true;

            // فحص حقل الاسم
            if (userField.value.trim() === "") {
                userError.style.display = 'block';
                userField.classList.add('input-invalid');
                isValid = false;
            } else {
                userError.style.display = 'none';
                userField.classList.remove('input-invalid');
            }

            // فحص حقل كلمة المرور
            if (passField.value.trim() === "") {
                passError.style.display = 'block';
                passField.classList.add('input-invalid');
                isValid = false;
            } else {
                passError.style.display = 'none';
                passField.classList.remove('input-invalid');
            }

            // منع الإرسال إذا كان هناك حقل فارغ
            if (!isValid) {
                e.preventDefault();
            }
        };

        // إخفاء إشارات الخطأ فور البدء بالكتابة لتجربة مستخدم أفضل
        document.getElementById('doc_user').oninput = function() {
            document.getElementById('user_error').style.display = 'none';
            this.classList.remove('input-invalid');
        };
        document.getElementById('doc_pass').oninput = function() {
            document.getElementById('pass_error').style.display = 'none';
            this.classList.remove('input-invalid');
        };
    </script>

    <script src="header.js"></script>
</body>
</html>