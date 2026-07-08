<!DOCTYPE html>
<html>
<head>
    <title>Welcome to LOOKSMEN</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $user->name }}!</h2>
    <p>Thank you for registering at <strong>LOOKSMEN</strong>. We are thrilled to have you with us!</p>
    <p>Your username is: <strong>{{ $user->email }}</strong></p>
    <p>Now you can log in and explore our collection.</p>
    <br>
    <p>Best Regards,</p>
    <p>The LOOKSMEN Team</p>
</body>
</html>