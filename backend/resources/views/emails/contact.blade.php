<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { padding: 20px; border: 1px solid #eee; border-radius: 10px; max-width: 600px; }
        .header { background: #5F2EEA; color: white; padding: 10px; border-radius: 5px; text-align: center; }
        .content { margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 0.8em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Message from Contact Form</h2>
        </div>
        <div class="content">
            <p><strong>Name:</strong> {{ $formData['name'] }}</p>
            <p><strong>Email:</strong> {{ $formData['email'] }}</p>
            <p><strong>Subject:</strong> {{ $formData['subject'] }}</p>
            <hr>
            <p><strong>Message:</strong></p>
            <p>{{ $formData['message'] }}</p>
        </div>
        <div class="footer">
            <p>This email was sent from your website's contact form.</p>
        </div>
    </div>
</body>
</html>