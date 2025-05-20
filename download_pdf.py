import sys
import time
import os
import shutil
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

if len(sys.argv) < 3:
    print("Usage: python3 download_pdf.py <url> <output_path>")
    sys.exit(1)

url = sys.argv[1]
output_path = sys.argv[2]

# Use non-headless mode to mimic real browser
options = Options()
# options.add_argument("--headless")  # Uncomment for production after testing
options.add_argument("--no-sandbox")
options.add_argument("--disable-dev-shm-usage")
download_dir = "/tmp/selenium_downloads"
os.makedirs(download_dir, exist_ok=True)
options.add_experimental_option("prefs", {
    "download.default_directory": download_dir,
    "download.prompt_for_download": False,
    "download.directory_upgrade": True,
    "plugins.always_open_pdf_externally": True
})

try:
    driver = webdriver.Chrome(options=options)
    print("ChromeDriver started successfully")
except Exception as e:
    print(f"Failed to start ChromeDriver: {str(e)}")
    sys.exit(1)

try:
    # Simulate Gmail context
    driver.get("https://mail.google.com")
    print("Navigated to Gmail for context")
    time.sleep(2)

    driver.get(url)
    print(f"Navigated to {url}")
    time.sleep(5)

    timeout = 30
    elapsed = 0
    downloaded_file = None
    while elapsed < timeout and not downloaded_file:
        for file in os.listdir(download_dir):
            if file.endswith(".pdf"):
                downloaded_file = os.path.join(download_dir, file)
                break
        time.sleep(1)
        elapsed += 1

    if downloaded_file and os.path.getsize(downloaded_file) > 0:
        shutil.move(downloaded_file, output_path)
        print(f"Successfully downloaded to {output_path}")
    else:
        page_source = driver.page_source
        print(f"Failed to download - Timeout after {timeout} seconds")
        if "<!DOCTYPE html>" in page_source:
            print(f"Received HTML: {page_source[:200]}")
        sys.exit(1)

except Exception as e:
    print(f"Error during download: {str(e)}")
    sys.exit(1)

finally:
    driver.quit()
    if os.path.exists(download_dir):
        shutil.rmtree(download_dir)