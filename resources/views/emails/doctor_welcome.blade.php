<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>DoctorA2Z - Login Credentials</title>
    </head>

    <body>
        <h2>Hello {{ $doctorName }},</h2>

        <p>Your DoctorA2Z profile has been created successfully.</p>

        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Password:</strong> {{ $plainPassword }}</p>

        <p>You can now log in to your doctor panel and update your profile details.</p>

        <br>
        <p>Thanks,<br>DoctorA2Z Team</p>
    </body>

</html>
