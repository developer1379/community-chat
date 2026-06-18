<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            padding: 30px;
            text-align: center;
        }
        .logo {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .logo span {
            color: #93c5fd;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 10px 0;
        }
        .greeting {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 25px;
        }
        .otp-container {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 20px;
            margin: 25px 0;
            display: inline-block;
            letter-spacing: 6px;
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
            border: 1px solid #e2e8f0;
        }
        .instructions {
            font-size: 13px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }
        .expiry {
            font-size: 11px;
            font-weight: 700;
            color: #ef4444;
            background: #fef2f2;
            padding: 8px 16px;
            border-radius: 9999px;
            display: inline-block;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">XEN<span>PROFESSIONAL</span></div>
        </div>
        <div class="content">
            <h1 class="title">Email Verification</h1>
            <p class="greeting">Hello, {{ $username }}!</p>
            <p class="instructions">Thank you for joining our community. To complete your registration and verify your email address, please use the following one-time password (OTP):</p>
            
            <div class="otp-container">{{ $otp }}</div>
            
            <br>
            <div class="expiry">Expires in 15 minutes</div>
            
            <p class="instructions" style="margin-top: 30px; font-size: 11px; color: #94a3b8;">If you did not request this verification code, please ignore this email or contact support.</p>
        </div>
        <div class="footer">
            &copy; 2026 Rawsio.com. All rights reserved.
        </div>
    </div>
</body>
</html>
