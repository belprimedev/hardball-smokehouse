<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email - Hardball Caribbean Smokehouse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #23a04f, #f9de47);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background: #23a04f;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hardball Caribbean Smokehouse</h1>
        <p>Test Email</p>
    </div>
    
    <div class="content">
        <h2>Hello!</h2>
        <p>This is a test email to verify that the email system is working correctly.</p>
        
        <p>If you're receiving this email, it means:</p>
        <ul>
            <li>Resend is properly configured</li>
            <li>Email notifications are working</li>
            <li>The system is ready for production</li>
        </ul>
        
        <a href="{{ url('/') }}" class="button">Visit Our Website</a>
        
        <p>Thank you for testing our email system!</p>
    </div>
    
    <div class="footer">
        <p>Hardball Caribbean Smokehouse<br>
        Ipswich, UK<br>
        Email: info@hardballsmokehouse.co.uk</p>
    </div>
</body>
</html> 