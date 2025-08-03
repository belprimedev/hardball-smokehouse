<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed - Hardball Caribbean Smokehouse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
            position: relative;
        }
        
        /* Header with video-inspired design */
        .header {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="palm" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M30 10 Q40 20 50 10 Q60 20 70 10 Q80 20 90 10 L85 50 Q80 40 70 50 Q60 40 50 50 Q40 40 30 50 Z" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23palm)"/></svg>');
            opacity: 0.3;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-family: 'Great Vibes', cursive;
        }
        
        .tagline {
            color: #f9de47;
            font-size: 18px;
            font-style: italic;
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Success section with Welcome.vue colors */
        .success-section {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .success-section::before {
            content: '🎉';
            font-size: 60px;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.2;
        }
        
        .success-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            color: #f9de47;
        }
        
        .success-message {
            font-size: 18px;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }
        
        /* Content section */
        .content {
            padding: 40px 30px;
            background: #ffffff;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 900;
            color: #0c4149;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .welcome-message {
            text-align: center;
            font-size: 16px;
            color: #636e72;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        /* Reservation details with Welcome.vue styling */
        .reservation-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            border: 2px solid #23a04f;
            position: relative;
        }
        
        .details-title {
            font-size: 20px;
            font-weight: 900;
            color: #0c4149;
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .details-title::before {
            content: '📋';
            margin-right: 10px;
            font-size: 24px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 16px;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 700;
            color: #0c4149;
            min-width: 140px;
        }
        
        .detail-value {
            font-weight: 600;
            color: #23a04f;
            text-align: right;
        }
        
        /* Special requests section with Welcome.vue colors */
        .special-requests {
            background: linear-gradient(135deg, #f9de47 0%, #ffd600 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid #f9de47;
        }
        
        .special-title {
            font-size: 18px;
            font-weight: 900;
            color: #0c4149;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .special-title::before {
            content: '📝';
            margin-right: 10px;
            font-size: 20px;
        }
        
        .special-text {
            font-size: 16px;
            color: #0c4149;
            line-height: 1.6;
            font-style: italic;
        }
        
        /* Restaurant info with Welcome.vue theme */
        .restaurant-info {
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            color: white;
            text-align: center;
        }
        
        .restaurant-title {
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f9de47;
        }
        
        .restaurant-title::before {
            content: '📍';
            margin-right: 10px;
            font-size: 24px;
        }
        
        .restaurant-details {
            margin-bottom: 20px;
        }
        
        .restaurant-detail {
            margin: 12px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .restaurant-detail::before {
            content: '•';
            margin-right: 10px;
            color: #f9de47;
            font-size: 20px;
        }
        
        /* Features with Welcome.vue colors */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .feature {
            text-align: center;
            padding: 25px 15px;
            background: #ffffff;
            border-radius: 12px;
            color: #0c4149;
            transition: transform 0.3s ease;
            border: 2px solid #23a04f;
            box-shadow: 0 4px 15px rgba(35, 160, 79, 0.1);
        }
        
        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(35, 160, 79, 0.2);
        }
        
        .feature-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }
        
        .feature-title {
            font-weight: 900;
            margin-bottom: 8px;
            font-size: 16px;
            color: #23a04f;
        }
        
        .feature-desc {
            font-size: 14px;
            color: #636e72;
        }
        
        /* CTA Button with Welcome.vue styling */
        .cta-section {
            text-align: center;
            margin: 35px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #23a04f 0%, #0c4149 100%);
            color: white;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 900;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(35, 160, 79, 0.4);
            border: 2px solid #f9de47;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(35, 160, 79, 0.5);
        }
        
        /* Footer with Welcome.vue theme */
        .footer {
            background: linear-gradient(135deg, #0c4149 0%, #23a04f 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .footer-message {
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 700;
        }
        
        .footer-contact {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        
        .footer-tagline {
            font-style: italic;
            color: #f9de47;
            font-size: 18px;
            font-weight: 900;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .email-container {
                border-radius: 15px;
            }
            
            .header, .content, .footer {
                padding: 30px 20px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            
            .detail-value {
                text-align: left;
                margin-top: 8px;
                font-weight: 700;
            }
            
            .restaurant-detail {
                justify-content: flex-start;
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
        
        <!-- Success Section -->
        <div class="success-section">
            <div class="success-title">Reservation Confirmed!</div>
            <div class="success-message">Your table is reserved and we're excited to serve you!</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello {{ $reservation->customer_name }}!</div>
            
            <div class="welcome-message">
                Thank you for choosing Hardball Caribbean Smokehouse! We're thrilled to have you join us for an unforgettable dining experience filled with authentic Caribbean flavors and warm hospitality.
            </div>
            
            <!-- Reservation Details -->
            <div class="reservation-details">
                <div class="details-title">Reservation Details</div>
                
                <div class="detail-row">
                    <span class="detail-label">📅 Date:</span>
                    <span class="detail-value">{{ $reservation->formattedDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">🕐 Time:</span>
                    <span class="detail-value">{{ $reservation->reservation_time }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">👥 Guests:</span>
                    <span class="detail-value">{{ $reservation->number_of_guest }} {{ Str::plural('person', $reservation->number_of_guest) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">📧 Email:</span>
                    <span class="detail-value">{{ $reservation->customer_email }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">📞 Phone:</span>
                    <span class="detail-value">{{ $reservation->customer_phone }}</span>
                </div>
            </div>
            
            @if($reservation->special_request)
            <!-- Special Requests -->
            <div class="special-requests">
                <div class="special-title">Special Requests</div>
                <div class="special-text">{{ $reservation->special_request }}</div>
            </div>
            @endif
            
            <!-- Restaurant Info -->
            <div class="restaurant-info">
                <div class="restaurant-title">Visit Us</div>
                <div class="restaurant-details">
                    <div class="restaurant-detail">24 Lloyds Ave, Ipswich IP1 3HD</div>
                    <div class="restaurant-detail">Phone: +44 01473 807117</div>
                    <div class="restaurant-detail">Email: info@hardballsmokehouse.co.uk</div>
                </div>
            </div>
            
            <!-- Features -->
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">🎵</div>
                    <div class="feature-title">Live Music</div>
                    <div class="feature-desc">Caribbean vibes & entertainment</div>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">🍖</div>
                    <div class="feature-title">Caribbean Jerk</div>
                    <div class="feature-desc">Traditional Jamaican jerk seasoning</div>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">🍹</div>
                    <div class="feature-title">Craft Cocktails</div>
                    <div class="feature-desc">Tropical drinks & spirits</div>
                </div>
            </div>
            
            <p style="margin-top: 30px; color: #636e72; font-size: 15px; text-align: center;">
                <strong>Need to modify or cancel?</strong><br>
                Please contact us at +44 01473 807117 at least 24 hours before your reservation.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">
                We look forward to creating an unforgettable dining experience for you!
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