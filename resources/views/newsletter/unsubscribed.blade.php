<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed - Hardball Caribbean Smokehouse</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1b1b18;
            background: linear-gradient(135deg, #FDFDFC 0%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            max-width: 440px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
        }
        .header {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            padding: 36px 24px;
            color: #fff;
        }
        .logo { font-size: 26px; font-weight: 900; font-family: 'Great Vibes', cursive; margin-bottom: 6px; }
        .tagline { font-size: 14px; color: #f9de47; font-style: italic; }
        .body { padding: 36px 28px; }
        .icon { font-size: 48px; margin-bottom: 16px; }
        .title { font-size: 22px; font-weight: 800; color: #0c4149; margin-bottom: 12px; }
        .text { color: #636e72; font-size: 15px; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            color: #fff !important;
            font-weight: 700;
            text-decoration: none;
            border-radius: 10px;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="logo">Hardball Caribbean Smokehouse</div>
            <div class="tagline">Come for the food, stay for the vibes! 🌴</div>
        </div>
        <div class="body">
            <div class="icon">✓</div>
            <h1 class="title">You're unsubscribed</h1>
            <p class="text">You will no longer receive our weekly newsletter. We're sorry to see you go! You can resubscribe anytime from our website.</p>
            <a href="{{ url('/') }}" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
