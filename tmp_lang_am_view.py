# coding: utf-8
from pathlib import Path
lines = Path('lang/am/home.php').read_text(encoding='utf-8').splitlines()
start = next(i for i,line in enumerate(lines) if "'public_servant_dashboard'" in line)
for j in range(start, min(start+15, len(lines))):
    print(f"{j+1}:{lines[j].encode('unicode_escape').decode('ascii')}" )
