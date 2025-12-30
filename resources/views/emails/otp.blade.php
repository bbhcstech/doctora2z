<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .email-header {
            background-color: #98c1ed;
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
        }
        .email-header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .email-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        .email-body {
            padding: 20px;
            color: #333;
        }
        .email-body p {
            font-size: 1rem;
            margin: 10px 0;
        }
        .email-body .otp-code {
            display: inline-block;
            background: #f9f9f9;
            color: #007bff;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 10px 20px;
            margin: 10px 0;
            border: 1px solid #007bff;
            border-radius: 4px;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            color: #555;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }
        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <img src="https://staging.doctora2z.com/public/img/doctor-logo.png" width="60" alt="Logo">
            
            <h1>OTP Verification</h1>
        </div>

        <!-- Body Section -->
        <div class="email-body">
            <p>Hello,</p>
            <p>Your OTP code is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Please use this code to verify your login. This code is valid for the next 10 minutes.</p>
            <p>Thank you for using our application!</p>
        </div>

        <!-- Footer Section -->
        <div class="email-footer">
            <p>If you did not request this OTP, please ignore this email.</p>
            <p>&copy; {{ date('Y') }} doctora2z.com. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
