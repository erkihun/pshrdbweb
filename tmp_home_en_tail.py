from pathlib import Path
lines = Path('lang/en/home.php').read_text(encoding='utf-8').splitlines()
for i in range(len(lines)-30, len(lines)):
    print(f"{i+1}:{lines[i]}")
