<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo SQL Injection & Pencegahan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f8fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #2c3e50, #4a6491);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .description {
            font-size: 16px;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .content {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
        }
        
        .panel {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            margin: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e1e4e8;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a6491;
        }
        
        h3 {
            color: #3498db;
            margin: 15px 0 10px;
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #edf2f7;
            border-radius: 6px;
            border-left: 4px solid #3498db;
        }
        
        .injection-danger {
            background-color: #fed7d7;
            color: #c53030;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #e53e3e;
        }
        
        .prevention-safe {
            background-color: #c6f6d5;
            color: #2d5016;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #38a169;
        }
        
        pre {
            background-color: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 15px 0;
            font-size: 14px;
        }
        
        .code-comment {
            color: #a0aec0;
            font-style: italic;
        }
        
        .example {
            background-color: #fff5eb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #dd6b20;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }
            
            .panel {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Demo SQL Injection & Pencegahan</h1>
            <p class="description">
                Contoh bagaimana SQL Injection bekerja dan cara mencegahnya menggunakan parameterized queries
            </p>
        </header>
        
        <div class="content">
            <div class="panel">
                <h2>Apa itu SQL Injection?</h2>
                <p>SQL Injection adalah teknik penyerangan dengan menyisipkan perintah SQL yang tidak diinginkan melalui input form, yang dapat mengakses, memodifikasi, atau menghapus data dalam database.</p>
                
                <div class="injection-danger">
                    <h3>❌ Contoh Input Berbahaya</h3>
                    <p>Coba masukkan input berikut pada form login:</p>
                    <p><code>' OR '1'='1</code> - Login tanpa password</p>
                    <p><code>'; DROP TABLE users; --</code> - Menghapus tabel (jika vulnerability ada)</p>
                </div>
                
                <h2>Contoh Kerentanan SQL</h2>
                <pre>
// Kode PHP yang rentan SQL Injection
$username = $_POST['username'];
$password = $_POST['password'];

// ❌ Kode yang rentan - Jangan gunakan ini!
$query = "SELECT * FROM users 
          WHERE username = '$username' 
          AND password = '$password'";
$result = mysqli_query($conn, $query);

// Jika user memasukkan: ' OR '1'='1
// Query menjadi: 
// SELECT * FROM users WHERE username = '' OR '1'='1' AND password = ''
// Ini akan mengembalikan semua baris dari tabel users!
                </pre>
            </div>
            
            <div class="panel">
                <h2>Simulasi Login</h2>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" placeholder="Masukkan username">
                </div>
                
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" placeholder="Masukkan password">
                </div>
                
                <button onclick="simulateLogin()">Login (Vulnerable)</button>
                <button onclick="simulateSafeLogin()" style="background-color: #38a169;">Login (Safe)</button>
                
                <div id="result" class="result">
                    <p>Hasil akan ditampilkan di sini setelah login.</p>
                </div>
                
                <div class="prevention-safe">
                    <h3>✅ Cara Mencegah SQL Injection</h3>
                    <p>Selalu gunakan parameterized queries (prepared statements):</p>
                </div>
                
                <pre>
// Kode PHP yang aman dari SQL Injection
$username = $_POST['username'];
$password = $_POST['password'];

// ✅ Gunakan prepared statements
$stmt = $conn->prepare("SELECT * FROM users 
                        WHERE username = ? 
                        AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Input berbahaya sekarang akan diperlakukan sebagai string biasa
// bukan sebagai bagian dari query SQL
                </pre>
            </div>
        </div>
        
        <div class="content">
            <div class="panel">
                <h2>Teknik Pencegahan Lainnya</h2>
                
                <h3>1. Validasi Input</h3>
                <p>Selalu validasi dan sanitasi input pengguna:</p>
                <pre>
// Validasi input
if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
    die("Username hanya boleh mengandung huruf, angka, dan underscore");
}
                </pre>
                
                <h3>2. Principle of Least Privilege</h3>
                <p>Gunakan database user dengan hak akses minimal yang diperlukan:</p>
                <pre>
// Jangan gunakan akun root untuk aplikasi web
// Buat user khusus dengan hak akses terbatas:
GRANT SELECT, INSERT ON database.users TO 'webuser'@'localhost';
                </pre>
                
                <h3>3. Escape Input</h3>
                <p>Jika terpaksa tidak bisa menggunakan prepared statements, gunakan fungsi escape:</p>
                <pre>
// Escape input (kurang direkomendasikan)
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);
                </pre>
            </div>
            
            <div class="panel">
                <h2>Contoh Serangan SQL Injection</h2>
                
                <div class="example">
                    <h3>📑 Mengambil Data Sensitif</h3>
                    <p>Input: <code>' UNION SELECT username, password FROM users --</code></p>
                    <p>Query yang dihasilkan:</p>
                    <pre>
SELECT id, name FROM products 
WHERE category = '' 
UNION SELECT username, password FROM users --'
                    </pre>
                    <p>Ini akan menggabungkan hasil query dengan data username dan password!</p>
                </div>
                
                <div class="example">
                    <h3>✏️ Memodifikasi Data</h3>
                    <p>Input: <code>'; UPDATE users SET password = 'hacked' WHERE username = 'admin' --</code></p>
                    <p>Query yang dihasilkan:</p>
                    <pre>
SELECT * FROM users 
WHERE username = ''; 
UPDATE users SET password = 'hacked' WHERE username = 'admin' --'
                    </pre>
                    <p>Ini akan mengubah password admin menjadi 'hacked'!</p>
                </div>
                
                <div class="example">
                    <h3>🗑️ Menghapus Data</h3>
                    <p>Input: <code>'; DROP TABLE users; --</code></p>
                    <p>Query yang dihasilkan:</p>
                    <pre>
SELECT * FROM users 
WHERE username = ''; 
DROP TABLE users; --'
                    </pre>
                    <p>Ini akan menghapus seluruh tabel users!</p>
                </div>
            </div>
        </div>
        
        <footer>
            <p>© 2023 Demo Edukasi Keamanan Siber | Hanya untuk tujuan edukasi</p>
        </footer>
    </div>

    <script>
        // Simulasi database
        const users = [
            { username: "admin", password: "password123", name: "Administrator" },
            { username: "john", password: "john123", name: "John Doe" },
            { username: "jane", password: "jane123", name: "Jane Smith" }
        ];
        
        // Fungsi untuk mensimulasikan login yang rentan SQL Injection
        function simulateLogin() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Simulasi query yang rentan (hanya untuk demo)
            const resultDiv = document.getElementById('result');
            
            // Cek jika input mengandung payload SQL injection
            if (username.includes("' OR '1'='1") && password === "") {
                resultDiv.innerHTML = `
                    <h3>❌ Login Berhasil (Karena SQL Injection)</h3>
                    <p>Query yang dijalankan: </p>
                    <pre>SELECT * FROM users WHERE username = '${username}' AND password = '${password}'</pre>
                    <p>Query menjadi: </p>
                    <pre>SELECT * FROM users WHERE username = '' OR '1'='1' AND password = ''</pre>
                    <p><strong>Semua data user berhasil diambil:</strong></p>
                    <ul>
                        ${users.map(user => `<li>Username: ${user.username}, Password: ${user.password}, Name: ${user.name}</li>`).join('')}
                    </ul>
                    <p class="injection-danger">⚠️ SISTEM ANDA RENTAN TERHADAP SQL INJECTION!</p>
                `;
            } else if (username === "' ; DROP TABLE users; --") {
                resultDiv.innerHTML = `
                    <h3>❌ Serangan DETECTED!</h3>
                    <p>Query yang dijalankan: </p>
                    <pre>SELECT * FROM users WHERE username = '${username}' AND password = '${password}'</pre>
                    <p>Query menjadi: </p>
                    <pre>SELECT * FROM users WHERE username = ''; DROP TABLE users; --' AND password = '${password}'</pre>
                    <p class="injection-danger">⚠️ ANDA BARU SAJA MENCOBA MENGHAPUS TABEL USERS!</p>
                `;
            } else {
                // Login normal
                const user = users.find(u => u.username === username && u.password === password);
                if (user) {
                    resultDiv.innerHTML = `
                        <h3>✅ Login Berhasil</h3>
                        <p>Selamat datang, ${user.name}!</p>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <h3>❌ Login Gagal</h3>
                        <p>Username atau password salah.</p>
                    `;
                }
            }
        }
        
        // Fungsi untuk mensimulasikan login yang aman
        function simulateSafeLogin() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            const resultDiv = document.getElementById('result');
            
            // Simulasi parameterized query (aman dari SQL injection)
            const user = users.find(u => u.username === username && u.password === password);
            
            if (user) {
                resultDiv.innerHTML = `
                    <h3>✅ Login Berhasil</h3>
                    <p>Selamat datang, ${user.name}!</p>
                    <p>Query yang dijalankan: </p>
                    <pre>SELECT * FROM users WHERE username = ? AND password = ?</pre>
                    <p>Parameter: username = "${username}", password = "${password}"</p>
                    <p class="prevention-safe">✅ SISTEM ANDA AMAN DARI SQL INJECTION!</p>
                `;
            } else {
                resultDiv.innerHTML = `
                    <h3>❌ Login Gagal</h3>
                    <p>Username atau password salah.</p>
                    <p>Query yang dijalankan: </p>
                    <pre>SELECT * FROM users WHERE username = ? AND password = ?</pre>
                    <p>Parameter: username = "${username}", password = "${password}"</p>
                    <p class="prevention-safe">✅ SISTEM ANDA AMAN DARI SQL INJECTION!</p>
                `;
            }
        }
    </script>
</body>
</html>