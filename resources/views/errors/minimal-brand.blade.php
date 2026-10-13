{{-- Dependency-free error page (no database), used for 419/429/500/503. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }}</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #F6F1EA; color: #292B27; font-family: Georgia, serif; text-align: center; padding: 1.5rem; }
        h1 { font-weight: 500; font-size: 2.5rem; margin: .5rem 0; }
        p { font-family: system-ui, sans-serif; color: #4A4C46; }
        a { display: inline-block; margin-top: 1.5rem; padding: .8rem 1.6rem; border-radius: 999px; background: #292B27; color: #F6F1EA; text-decoration: none; font: 600 .8rem system-ui, sans-serif; letter-spacing: .12em; text-transform: uppercase; }
        .code { color: #C7B59C; font-size: 4rem; }
    </style>
</head>
<body>
    <main>
        <div class="code">{{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="/">Back to home</a>
    </main>
</body>
</html>
