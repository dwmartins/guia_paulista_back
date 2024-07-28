<!DOCTYPE html>
<html>
<head>
    <title><?php echo WELCOME_TITLE." {SITENAME}" ?></title>
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

        .highlight {
            background-color: #aedbf9;
            padding: 10px;
            border-radius: 5px;
        }

        .quote {
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #aedbf9;
            border-radius: 5px;
            font-style: italic;
            line-height: 1.3;
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
        <h1><?php echo WELCOME_TITLE." {SITENAME}" ?>  </h1>
        <p class="highlight"><?php echo HIGHLIGHT_MESSAGE; ?></p>
        <p><?php echo GREETING; ?> <span class="highlight">{USERNAME},</span></p>
        <p><?php echo WELCOME_MESSAGE; ?></p>
        <p class="quote">"<?php echo QUOTE_MESSAGE; ?>"</p>
        <p><?php echo FEATURES_TITLE; ?></p>
        <ul>
            <li><?php echo FEATURE_1; ?></li>
            <li><?php echo FEATURE_2; ?></li>
            <li><?php echo FEATURE_3; ?></li>
            <li><?php echo FEATURE_4; ?></li>
            <li><?php echo FEATURE_5; ?></li>
        </ul>
        <p><?php echo HELP_MESSAGE; ?></p>
        <p class="signature"><?php echo SIGNATURE." {SITENAME}" ?></p>
    </div>
</body>
</html>