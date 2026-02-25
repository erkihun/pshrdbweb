from pathlib import Path
path = Path('lang/am/common.php')
text = path.read_text(encoding='utf-8')
old = \ form =
close = \ ],\n\n ],\
idx = text.index(old)
close_idx = text.index(close, idx)
