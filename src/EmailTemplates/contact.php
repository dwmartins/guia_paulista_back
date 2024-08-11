<!DOCTYPE html>
<html>
<head>
    <title><?php echo "{SITENAME}" ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #3a9dff;
            font-size: 28px;
            margin-top: 0;
            text-align: center;
        }

        p {
            color: #333333;
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .signature {
            color: #808080;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo "{SITENAME}" ?></h1>
        
        <h2><?php echo CONTACT_INFORMATION ?></h2>
        <p><strong>Nome:</strong> <?php echo "{CONTACT_NAME}"; ?></p>
        <p><strong>Sobrenome:</strong> <?php echo "{CONTACT_LASTNAME}"; ?></p>
        <p><strong>E-mail:</strong> <?php echo "{CONTACT_EMAIL}"; ?></p>
        <p><strong>Empresa:</strong> <?php echo "{CONTACT_COMPANY}"; ?></p>
        <p><strong>Mensagem:</strong></p>
        <p><?php echo "{CONTACT_MESSAGE}"; ?></p>
        
        <p class="signature"><?php echo SIGNATURE." {SITENAME}" ?></p>
    </div>
</body>
</html>
