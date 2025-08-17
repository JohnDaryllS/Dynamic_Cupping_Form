<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SCA Cupping Form</title>
    <link rel="shortcut icon" href="img/1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6F4E37, #3E2723);
        }
        
        .welcome-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.8s ease-in-out;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            object-fit: contain;
        }
        
        h1 {
            color: #6F4E37;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        p {
            color: #555;
            margin-bottom: 30px;
            font-size: 18px;
            line-height: 1.6;
        }
        
        .btn-login {
            background-color: #6F4E37;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-login:hover {
            background-color: #3E2723;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-login i {
            margin-right: 10px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <img src="img/1.png" alt="SCA Cupping Form Logo" class="logo">
        <h1>Welcome to SCA Cupping Form</h1>
        <p>The Specialty Coffee Association's standardized cupping form for evaluating coffee quality and characteristics.</p>
        <a href="login.php" class="btn btn-login">
            <i class="fas fa-sign-in-alt"></i> Login to Continue
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>