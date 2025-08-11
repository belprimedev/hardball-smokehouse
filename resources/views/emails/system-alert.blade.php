<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Hardball Caribbean Smokehouse</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Header with gradient */
        .header {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 50%, #CD853F 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .tagline {
            color: #FFD700;
            font-size: 16px;
            font-style: italic;
            position: relative;
            z-index: 1;
        }
        
        /* Alert section */
        .alert-section {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .alert-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .alert-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .alert-message {
            font-size: 16px;
            opacity: 0.9;
        }
        
        /* Content section */
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 20px;
        }
        
        .alert-details {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #dc3545;
        }
        
        .details-title {
            font-size: 18px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .details-title::before {
            content: '🔔';
            margin-right: 10px;
        }
        
        .alert-message-text {
            font-size: 16px;
            color: #495057;
            margin-bottom: 20px;
        }
        
        /* Additional data */
        .additional-data {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #8B4513;
        }
        
        .data-title {
            font-size: 16px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 15px;
        }
        
        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .data-item:last-child {
            border-bottom: none;
        }
        
        .data-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 120px;
        }
        
        .data-value {
            font-weight: 500;
            color: #495057;
            text-align: right;
        }
        
        /* Dashboard info */
        .dashboard-info {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            color: white;
        }
        
        .dashboard-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .dashboard-title::before {
            content: '📊';
            margin-right: 10px;
        }
        
        .dashboard-details {
            margin-bottom: 20px;
        }
        
        .dashboard-detail {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }
        
        .dashboard-detail::before {
            content: '•';
            margin-right: 10px;
            color: #FFD700;
        }
        
        /* CTA Button */
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer-message {
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .footer-contact {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        
        .footer-tagline {
            font-style: italic;
            color: #FFD700;
            font-size: 16px;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header, .content, .footer {
                padding: 20px 15px;
            }
            
            .data-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .data-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Hardball Caribbean Smokehouse</div>
            <div class="tagline">Come for the food, stay for the vibes! 🌴</div>
        </div>
        
        <!-- Alert Section -->
        <div class="alert-section">
            <div class="alert-icon">🔔</div>
            <div class="alert-title">{{ $title }}</div>
            <div class="alert-message">System Alert Notification</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello Admin!</div>
            
            <!-- Alert Details -->
            <div class="alert-details">
                <div class="details-title">Alert Information</div>
                <div class="alert-message-text">{{ $message }}</div>
            </div>
            
            @if(!empty($data))
            <!-- Additional Data -->
            <div class="additional-data">
                <div class="data-title">Additional Information</div>
                
                @foreach($data as $key => $value)
                <div class="data-item">
                    <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="data-value">{{ is_string($value) ? $value : (is_object($value) ? 'Object' : (string) $value) }}</span>
                </div>
                @endforeach
            </div>
            @endif
            
            <!-- Dashboard Info -->
            <div class="dashboard-info">
                <div class="dashboard-title">Quick Access</div>
                <div class="dashboard-details">
                    <div class="dashboard-detail">View all reservations and manage bookings</div>
                    <div class="dashboard-detail">Monitor system health and performance</div>
                    <div class="dashboard-detail">Manage users and permissions</div>
                    <div class="dashboard-detail">Check email delivery status</div>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="cta-section">
                <a href="{{ url('/dashboard') }}" class="cta-button">
                    📊 View Dashboard
                </a>
            </div>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>This is an automated system notification.</strong> Please check the admin panel for more details.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">
                Stay informed with real-time system alerts!
            </div>
            
            <div class="footer-contact">
                <strong>Hardball Caribbean Smokehouse</strong><br>
                24 Lloyds Ave, Ipswich IP1 3HD<br>
                +44 01473 807117
            </div>
            
            <div class="footer-tagline">
                🌴 Come for the food, stay for the vibes! 🌴
            </div>
        </div>
    </div>
</body>
</html> 