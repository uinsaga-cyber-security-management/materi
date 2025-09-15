<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook - Masuk atau Daftar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Helvetica, Arial, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: #1c1e21;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .header {
            padding: 16px 0;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 40px;
            font-weight: bold;
            color: #1877f2;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            max-width: 980px;
            margin: 0 auto;
            padding: 20px;
            gap: 40px;
        }
        
        .intro-text {
            max-width: 500px;
        }
        
        .intro-text h2 {
            font-size: 28px;
            font-weight: normal;
            margin-bottom: 15px;
            color: #1c1e21;
        }
        
        .intro-text p {
            font-size: 16px;
            line-height: 1.5;
        }
        
        .login-form {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 396px;
        }
        
        .login-form input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #dddfe2;
            font-size: 17px;
        }
        
        .login-form input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 0 2px #e7f3ff;
            outline: none;
        }
        
        .login-button {
            background-color: #1877f2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            margin-bottom: 15px;
            cursor: pointer;
        }
        
        .login-button:hover {
            background-color: #166fe5;
        }
        
        .forgot-password {
            color: #1877f2;
            text-align: center;
            display: block;
            margin-bottom: 20px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .divider {
            border-bottom: 1px solid #dadde1;
            margin: 20px 0;
        }
        
        .create-account {
            background-color: #42b72a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            padding: 12px 16px;
            font-weight: bold;
            display: block;
            margin: 0 auto;
            cursor: pointer;
        }
        
        .create-account:hover {
            background-color: #36a420;
        }
        
        .create-page {
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
        }
        
        .create-page a {
            color: #1c1e21;
            font-weight: bold;
            text-decoration: none;
        }
        
        .create-page a:hover {
            text-decoration: underline;
        }
        
        .footer {
            background-color: #ffffff;
            padding: 20px;
            margin-top: auto;
            text-align: center;
            font-size: 14px;
            color: #737373;
        }
        
        .security-warning {
            background-color: #fff4ce;
            border: 1px solid #ffd351;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
            color: #1c1e21;
        }
        
        .security-warning h3 {
            color: #d93b21;
            margin-bottom: 10px;
        }
        
        .url-bar {
            background-color: #f0f2f5;
            padding: 10px;
            font-family: monospace;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            border-radius: 4px;
            color: #65676b;
        }
        
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                text-align: center;
            }
            
            .intro-text {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">facebook</div>
    </div>
    
    <div class="container">
        <div class="intro-text">
            <h2>Facebook membantu Anda terhubung dan berbagi dengan orang-orang dalam kehidupan Anda.</h2>
            <p>Facebook adalah layanan media sosial yang memungkinkan Anda terhubung dengan teman, keluarga, dan rekan kerja.</p>
        </div>
        
        <div class="login-form">
            <div class="url-bar">https://www-facebook-com.example.login</div>
            
            <form id="facebookLoginForm">
                <input type="text" placeholder="Email atau nomor telepon" id="email">
                <input type="password" placeholder="Kata sandi" id="password">
                <button type="submit" class="login-button">Masuk</button>
            </form>
            
            <a href="#" class="forgot-password">Lupa kata sandi?</a>
            
            <div class="divider"></div>
            
            <button class="create-account">Buat Akun Baru</button>
        </div>
    </div>
    
    
    <div class="footer">
        <p>Halaman ini dibuat untuk tujuan edukasi keamanan siber.</p>
        <p>© 2023 Edukasi Keamanan Siber - Bukan Facebook yang sebenarnya</p>
    </div>

    <script>
        document.getElementById('facebookLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                alert('Harap masukkan email dan kata sandi.');
                return;
            }
            
            // Dalam skenario nyata, data ini akan dikirim ke server penyerang
            // Untuk tujuan edukasi, kita hanya menampilkan pesan
            alert('PERINGATAN: Dalam serangan phishing nyata, informasi login Anda akan dicuri.\n\n' +
                  'Email: ' + email + '\n' +
                  'Password: ' + password + '\n\n' +
                  'Selalu periksa URL dan keaslian halaman sebelum memasukkan informasi login.');
            
            // Reset form
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
        });
        
        // Menampilkan URL palsu untuk demonstrasi
        const fakeUrls = [
            'facebook-security-verify.com',
            'facebook-login.secure-account.com',
            'update-your-facebook.xyz',
            'facebook.com.login.security-check.site'
        ];
        
        // Pilih URL acak untuk ditampilkan
        const randomUrl = fakeUrls[Math.floor(Math.random() * fakeUrls.length)];
        document.querySelector('.url-bar').textContent = 'https://' + randomUrl;
    </script>
</body>
</html>