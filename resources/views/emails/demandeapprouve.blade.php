<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Votre demande de retrait a été approuvée</title>
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
            <img src="{{asset('assets/financement.png')}}" width="100" alt="">
        </p>
        <p>Bonjour <strong>John Doe</strong></p>
        <p>Nous vous informons que votre demande de retrait a été approuvée et est actuellement en cours de traitement.</p>
        <p>Les fonds seront transférés vers votre compte mobile money sous un délai de <strong>4h</strong>.</p>
        <p><strong>👉 Vous pouvez consulter l’état de votre transaction à tout moment via votre tableau de bord</strong></p>
        <p>Si vous avez des questions ou des préoccupations, n’hésitez pas à nous contacter à <a href="mailto:support@ecochamp.org">support@ecochamp.org</a></p>
        <p>Merci pour votre implication en tant qu'<strong>eco-citoyen</strong> 🎉</p>
        <p>Cordialement,</p>
        <p>L’équipe <strong>Ecochamp</strong></p>
    </main>
</body>
</html>
