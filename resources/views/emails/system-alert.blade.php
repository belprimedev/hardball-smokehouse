<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ e($title ?? 'System Alert') }} - Hardball Caribbean Smokehouse</title>
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
            background: linear-gradient(135deg, #1a5f7a 0%, #2c88a0 50%, #4a9bb3 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
            border-radius: 12px 12px 0 0;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.2;
            border-radius: 12px 12px 0 0;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .tagline {
            color: #FFD700;
            font-size: 18px;
            font-style: italic;
            position: relative;
            z-index: 1;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Alert section */
        .alert-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 35px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .alert-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain2" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain2)"/></svg>');
            opacity: 0.1;
        }
        
        .alert-icon {
            font-size: 56px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        
        .alert-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .alert-message {
            font-size: 18px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }
        
        /* Content section */
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #1a5f7a;
            margin-bottom: 25px;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            border-left: 4px solid #1a5f7a;
        }
        
        .alert-details {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #28a745;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
        }
        
        .details-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a5f7a;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }
        
        .details-title::before {
            content: '📋';
            margin-right: 12px;
            font-size: 24px;
        }
        
        .alert-message-text {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
            padding: 15px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
        }
        
        /* Additional data */
        .additional-data {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #1a5f7a;
            box-shadow: 0 4px 15px rgba(26, 95, 122, 0.1);
        }
        
        .data-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a5f7a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .data-title::before {
            content: '📊';
            margin-right: 10px;
            font-size: 20px;
        }
        
        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e6f3ff;
            transition: background-color 0.2s ease;
        }
        
        .data-item:hover {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .data-item:last-child {
            border-bottom: none;
        }
        
        .data-label {
            font-weight: 600;
            color: #495057;
            min-width: 140px;
        }
        
        .data-value {
            font-weight: 500;
            color: #2c3e50;
            text-align: right;
            background: rgba(255, 255, 255, 0.7);
            padding: 4px 12px;
            border-radius: 6px;
        }
        
        /* Dashboard info */
        .dashboard-info {
            background: linear-gradient(135deg, #1a5f7a 0%, #2c88a0 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            color: white;
            box-shadow: 0 6px 20px rgba(26, 95, 122, 0.2);
        }
        
        .dashboard-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .dashboard-title::before {
            content: '🎯';
            margin-right: 12px;
            font-size: 24px;
        }
        
        .dashboard-details {
            margin-bottom: 25px;
        }
        
        .dashboard-detail {
            margin: 12px 0;
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        
        .dashboard-detail::before {
            content: '✓';
            margin-right: 12px;
            color: #FFD700;
            font-weight: bold;
            font-size: 16px;
        }
        
        /* CTA Button */
        .cta-section {
            text-align: center;
            margin: 35px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a5f7a 0%, #2c88a0 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-radius: 0 0 12px 12px;
        }
        
        .footer-message {
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 500;
        }
        
        .footer-contact {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .footer-tagline {
            font-style: italic;
            color: #FFD700;
            font-size: 18px;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
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
            <div class="alert-title">{{ e($title ?? 'System Alert') }}</div>
            <div class="alert-message">System Alert Notification</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello Admin!</div>
            
            <!-- Alert Details -->
            <div class="alert-details">
                <div class="details-title">Alert Information</div>
                <div class="alert-message-text">{{ e($alertMessage ?? $message ?? 'No message provided') }}</div>
            </div>
            
            @if(!empty($data))
            <!-- Additional Data -->
            <div class="additional-data">
                <div class="data-title">Additional Information</div>
                
                @foreach($data as $key => $value)
                <div class="data-item">
                    <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="data-value">{{ e(is_string($value) ? $value : (is_object($value) ? 'Object' : (is_array($value) ? 'Array' : (string) $value))) }}</span>
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