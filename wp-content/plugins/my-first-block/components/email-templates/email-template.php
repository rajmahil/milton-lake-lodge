<!doctype html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Confirmation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f4f6;
      padding: 40px 20px;
    }
    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background: #f5f5f5;
      padding: 30px;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      color: #111827;
      font-size: 16px;
      line-height: 1.6;
    }
    .logo {
      text-align: center;
      margin-bottom: 24px;
      background-color: #00473f;
      border-radius: 10px;
      padding: 10px 0;
    }
    .logo img {
      max-height: 70px;
    }
    h1 {
      font-size: 20px;
      text-align: center;
      margin-bottom: 16px;
    }
    strong {
      font-weight: bold;
    }
    .highlight-box {
      background-color: #ffffff;
      padding: 16px;
      margin: 20px 0;
      border-radius: 6px;
    }
   
    .cta-button {
      display: inline-block;
      background-color: #00473f;
      color: #ffffff !important;
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 4px;
      margin-top: 24px;
      text-align: center;
    }
    .footer {
        margin-top: 40px;
        font-size: 12px;
        color: #6b7280;
        border-top: 1px solid #e5e7eb;
        padding-top: 16px;
        word-wrap: break-word;
        overflow-wrap: break-word;
      }
    .footer a {
      color: #000;
      font-weight: bold;
      text-decoration: none;
    }
    .greeting {
      font-weight: 600;
      font-size: 1.2rem;
      line-height: 1.75rem;
      margin-bottom: 16px;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="logo">
      <img
        src="https://bucket-production-599e.up.railway.app/wpmedia/2025/06/logo.png"
        alt="Milton Lake Lodge Logo"
      />
    </div>

    <p class="greeting">Hello,</p>

    <p>
      Thank you for reaching out to
      <strong>Milton Lake Lodge</strong>. We have received your message and appreciate you taking the time to contact us.
    </p>
    <p>
      Our team is currently reviewing your inquiry and will get back to you as soon as possible. We strive to respond to all inquiries within 24–48 hours. If your matter is urgent, please feel free to call us directly at
      <strong>(306) 123-4567</strong>.
    </p>

    <div class="highlight-box">
     
        {{dynamic_rows}}
     
    </div>

    <p>
      If any of the above information is incorrect, please reply to this email with the necessary corrections.
    </p>
    <p>
      In the meantime, feel free to browse our website for more information about our lodge and experiences.
    </p>

    <a href="https://miltonlakelodge.com" class="cta-button">Visit Our Website</a>

    <div class="footer">
      <p>
        If you wish to unsubscribe from our newsletter and stop receiving emails from us, please click here (<a href="#">unsubscribe link</a>).
      </p>
    </div>
  </div>
</body>
</html>
