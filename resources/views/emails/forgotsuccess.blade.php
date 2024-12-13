<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Votre mot de passe a été restauré avec succès</title>
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
            <img src="{{asset('assets/password_success.jpg')}}" width="150" alt="">
        </p>
        <p>Bonjour <strong>{{ $user->fullname }}</strong></p>
        <p>Nous vous confirmons que votre mot de passe a été restauré avec succès. Vous pouvez désormais utiliser votre nouveau mot de passe pour vous connecter à votre compte sur <strong>Ecochamp</strong></p>
        <p><strong>Pour assurer la sécurité de votre compte :</strong></p>
        <ul>
            <li>Si vous n’êtes pas à l’origine de cette demande, veuillez réinitialiser votre mot de passe immédiatement</li>
            <li>Contactez-nous sans attendre à <a href="mailto:support@ecochamp.org">support@ecochamp.org</a> en cas de doute.</li>
        </ul>
        <p>Nous vous remercions pour votre confiance et restons disponibles pour toute question ou assistance.</p>
        <p>Cordialement,</p>
        <p>L’équipe <strong>Ecochamp</strong></p>
    </main>
</body>
</html>
