from datetime import datetime
import re

version = datetime.now().strftime("%Y%m%d%H%M%S")

with open("index.html", "r", encoding="utf-8") as f:
    content = f.read()

content = re.sub(
    r'style\.css\?v=.*?"',
    f'style.css?v={version}"',
    content
)

content = re.sub(
    r'logic\.js\?v=.*?"',
    f'logic.js?v={version}"',
    content
)

content = content.replace("{{VERSION}}", version)

with open("index.html", "w", encoding="utf-8") as f:
    f.write(content)

print("Build complete. Version:", version)
