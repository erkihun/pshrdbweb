from pathlib import Path
lines = Path('resources/views/welcome.blade.php').read_text(encoding='utf-8').splitlines()
for i in range(248, 280):
    print(f"{i+1}:{lines[i]}")
