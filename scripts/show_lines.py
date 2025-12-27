from pathlib import Path
path = Path('vendor/laravel/framework/src/Illuminate/Foundation/Configuration/Middleware.php')
for i,line in enumerate(path.read_text().splitlines(), start=1):
    if 600 <= i <= 660:
        print(f"{i}: {line}")
