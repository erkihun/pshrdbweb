import pathlib
path = pathlib.Path('app/Http/Middleware/LogVisitor.php')
with path.open(encoding='utf-8') as f:
    for idx, line in enumerate(f, 1):
        print(f'{idx:03d}: {line.rstrip()}')
