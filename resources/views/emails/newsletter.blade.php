<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $edition->subject }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1b1b18;
            background: linear-gradient(135deg, #FDFDFC 0%, #e8f5e9 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            padding: 44px 30px;
            text-align: center;
            position: relative;
        }
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-family: 'Great Vibes', cursive;
        }
        .tagline {
            color: #f9de47;
            font-size: 18px;
            font-style: italic;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .badge {
            display: inline-block;
            background: rgba(249, 222, 71, 0.25);
            color: #f9de47;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 14px;
            border-radius: 20px;
            margin-top: 16px;
        }
        .content {
            padding: 40px 30px;
            background: #ffffff;
        }
        .body-html {
            font-size: 16px;
            color: #2d3436;
            line-height: 1.8;
        }
        .body-html h1 { font-size: 22px; color: #0c4149; margin-bottom: 16px; }
        .body-html h2 { font-size: 18px; color: #0c4149; margin: 20px 0 12px; }
        .body-html p { margin-bottom: 16px; }
        .body-html a { color: #23a04f; font-weight: 600; text-decoration: none; }
        .body-html a:hover { text-decoration: underline; }
        .body-html ul, .body-html ol { margin: 16px 0 16px 24px; }
        .body-html li { margin-bottom: 8px; }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #23a04f, transparent);
            margin: 32px 0;
        }
        .visit-box {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            border-radius: 15px;
            padding: 24px;
            color: white;
            text-align: center;
            margin: 28px 0;
        }
        .visit-box strong { color: #f9de47; }
        .visit-box .address, .visit-box .phone { margin: 8px 0; font-size: 15px; }
        .unsubscribe-row {
            text-align: center;
            padding: 24px 0 16px;
            border-top: 1px solid #e9ecef;
        }
        .unsubscribe-row p { font-size: 13px; color: #636e72; margin-bottom: 12px; }
        .btn-unsubscribe {
            display: inline-block;
            padding: 12px 24px;
            background: transparent;
            color: #e74c3c;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: 2px solid #e74c3c;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-unsubscribe:hover {
            background: #e74c3c;
            color: #fff;
        }
        .footer {
            background: linear-gradient(135deg, #0c4149 0%, #23a04f 100%);
            color: white;
            padding: 32px 30px;
            text-align: center;
        }
        .footer-contact { font-size: 15px; opacity: 0.95; line-height: 1.7; }
        .footer-tagline { font-style: italic; color: #f9de47; font-size: 16px; margin-top: 16px; font-weight: 700; }
        @media (max-width: 600px) {
            .header, .content { padding: 28px 20px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Hardball Caribbean Smokehouse</div>
            <div class="tagline">Come for the food, stay for the vibes! 🌴</div>
            <span class="badge">Weekly Newsletter</span>
        </div>

        <div class="content">
            <div class="body-html">
                {!! $edition->body !!}
            </div>

            <div class="divider"></div>

            <div class="visit-box">
                <strong>📍 Visit Us</strong><br>
                <span class="address">24 Lloyds Ave, Ipswich IP1 3HD</span><br>
                <span class="phone">+44 01473 807117</span>
            </div>

            <div class="unsubscribe-row">
                <p>You received this email because you subscribed to our newsletter.</p>
                <a href="{{ $unsubscribeUrl }}" class="btn-unsubscribe">Unsubscribe</a>
            </div>
        </div>

        <div class="footer">
            <div class="footer-contact">
                <strong>Hardball Caribbean Smokehouse</strong><br>
                24 Lloyds Ave, Ipswich IP1 3HD · +44 01473 807117
            </div>
            <div class="footer-tagline">🌴 Come for the food, stay for the vibes! 🌴</div>
        </div>
    </div>
</body>
</html>
