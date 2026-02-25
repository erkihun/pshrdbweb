# coding: utf-8
from pathlib import Path
path = Path('lang/am/home.php')
text = path.read_text(encoding='utf-8')
marker = '\n];\n'
pos = text.rfind(marker)
if pos == -1:
    raise SystemExit('marker not found')
insert = "    'public_servant_dashboard' => [\n        'title' => ' <\" %c %z <© ^? %ç ^s',\n        'description' => ' <\" ^r S? <c %3 <^ S? O^ ^? O? ^Z %c  S? ^c %r %z ^? ^c Os ^i %z ? Š',\n        'total_label' => ' <\" ^Y %c %z <©',\n        'male_label' => ' <\" %r ^? %c',\n        'female_label' => ' <\" %r ^? %c (?)',\n    ],\n"
text = text[:pos] + insert + text[pos:]
path.write_text(text, encoding='utf-8')
