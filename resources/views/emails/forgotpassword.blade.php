<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Votre code de vérification</title>
    <style>
        body {
            background-color: #EEE;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        body h1 {
            color: #22844e,
        }

        main {
            width: 500px;
            padding: 20px;
            background-color: #FFF;
            border-radius: 8px
        }
    </style>
</head>
<body>
    <h1><img src="{{asset('assets/icon.png')}}" width="50" alt=""></h1>
    <main>
        <p style="text-align: center">
            <img src="{{asset('assets/otp.jpg')}}" width="150" alt="">
        </p>
        <p>Bonjour <strong>John Doe</strong></p>
        <p>Voici votre code de vérification à usage unique :</p>
        <h1 style="text-align: center">025876</h1>
        <p>Veuillez l’utiliser pour finaliser la réinitialisation de votre mot de passe.</p>
        <p>⚠️ Pour votre sécurité :</p>
        <ul>
            <li>Ne partagez jamais ce code avec qui que ce soit.</li>
            <li>Si vous n’avez pas demandé ce code, veuillez ignorer cet e-mail ou contactez notre équipe support immédiatement à <a href="mailto:support@ecochamp.org">support@ecochamp.org</a></li>
        </ul>
        <p>Cordialement,</p>
        <p>L’équipe <strong>Ecochamp</strong></p>
    </main>
</body>
</html>
