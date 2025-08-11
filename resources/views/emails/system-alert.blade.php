<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ e($title ?? 'System Alert') }} - Hardball Caribbean Smokehouse</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 1.6; color: #333333; background-color: #f8f9fa;">
    <!-- Main Container -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header Section -->
                    <tr>
                        <td bgcolor="#1a5f7a" style="background-color: #1a5f7a; padding: 40px 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="color: #ffffff; font-size: 28px; font-weight: bold; margin-bottom: 15px;">
                                        Hardball Caribbean Smokehouse
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #FFD700; font-size: 16px; font-style: italic; margin-top: 10px;">
                                        Come for the food, stay for the vibes! 🌴
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
        
        <!-- Alert Section -->
                    <tr>
                        <td bgcolor="#28a745" style="background-color: #28a745; padding: 30px; text-align: center; color: #ffffff;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size: 48px; margin-bottom: 15px;">
                                        🔔
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">
                                        {{ e($title ?? 'System Alert') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; opacity: 0.9;">
                                        System Alert Notification
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Greeting -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td bgcolor="#f8f9fa" style="background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px; border-left: 4px solid #1a5f7a;">
                                        <span style="font-size: 20px; font-weight: bold; color: #1a5f7a;">
                                            Hello Admin!
                                        </span>
                                    </td>
                                </tr>
                            </table>
            
            <!-- Alert Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background-color: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="margin-bottom: 20px;">
                                                    <span style="font-size: 18px; font-weight: bold; color: #1a5f7a;">
                                                        📋 Alert Information
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin-top: 15px;">
                                                    <div style="font-size: 16px; color: #2c3e50; line-height: 1.6; padding: 15px; background-color: rgba(255, 255, 255, 0.7); border-radius: 6px;">
                                                        {{ e($alertMessage ?? $message ?? 'No message provided') }}
            </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
            
            @if(!empty($data))
            <!-- Additional Data -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background-color: #f0f8ff; border-radius: 8px; border-left: 4px solid #1a5f7a;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="margin-bottom: 20px;">
                                                    <span style="font-size: 18px; font-weight: bold; color: #1a5f7a;">
                                                        📊 Additional Information
                                                    </span>
                                                </td>
                                            </tr>
                @foreach($data as $key => $value)
                                            <tr>
                                                <td style="padding: 8px 0; border-bottom: 1px solid #e6f3ff;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="40%" style="font-weight: 600; color: #495057;">
                                                                {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                            </td>
                                                            <td width="60%" style="text-align: right;">
                                                                <span style="font-weight: 500; color: #2c3e50; background-color: rgba(255, 255, 255, 0.7); padding: 4px 8px; border-radius: 4px;">
                                                                    {{ e(is_string($value) ? $value : (is_object($value) ? 'Object' : (is_array($value) ? 'Array' : (string) $value))) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
            @endif
            
            <!-- Dashboard Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background-color: #1a5f7a; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 25px; color: #ffffff;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="margin-bottom: 20px;">
                                                    <span style="font-size: 18px; font-weight: bold;">
                                                        🎯 Quick Access
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin-top: 15px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding: 8px 0;">
                                                                <span style="color: #FFD700; font-weight: bold; margin-right: 10px;">✓</span>
                                                                <span>View all reservations and manage bookings</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 8px 0;">
                                                                <span style="color: #FFD700; font-weight: bold; margin-right: 10px;">✓</span>
                                                                <span>Monitor system health and performance</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 8px 0;">
                                                                <span style="color: #FFD700; font-weight: bold; margin-right: 10px;">✓</span>
                                                                <span>Manage users and permissions</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 8px 0;">
                                                                <span style="color: #FFD700; font-weight: bold; margin-right: 10px;">✓</span>
                                                                <span>Check email delivery status</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td bgcolor="#28a745" style="background-color: #28a745; padding: 15px 30px; border-radius: 25px;">
                                                    <a href="{{ url('/dashboard') }}" style="color: #ffffff; text-decoration: none; font-weight: bold; font-size: 16px;">
                    📊 View Dashboard
                </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Footer Note -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 20px 0; text-align: center;">
                                        <p style="margin: 0; color: #6c757d; font-size: 14px;">
                                            <strong>This is an automated system notification.</strong><br>
                                            Please check the admin panel for more details.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer Section -->
                    <tr>
                        <td bgcolor="#1a5f7a" style="background-color: #1a5f7a; padding: 30px; text-align: center; border-radius: 0 0 8px 8px; color: #ffffff;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="margin-bottom: 20px;">
                                        <span style="font-size: 16px; font-weight: 500;">
                Stay informed with real-time system alerts!
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="margin-bottom: 15px; line-height: 1.6;">
                                        <span style="font-size: 14px; opacity: 0.9;">
                <strong>Hardball Caribbean Smokehouse</strong><br>
                24 Lloyds Ave, Ipswich IP1 3HD<br>
                +44 01473 807117
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="margin-top: 15px;">
                                        <span style="font-style: italic; color: #FFD700; font-size: 16px; font-weight: 500;">
                                            🌴 Come for the food, stay for the vibes! 🌴
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html> 