<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenue sur Ecochamp</title>
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
        <p>Bonjour <strong>{{ $user->fullname }}</strong></p>
        <p>Bienvenue parmi nous ! 🎉 Toute l’équipe de <strong>Ecochamp</strong> est ravie de vous compter parmi les <strong>eco-citoyen</strong>.</p>
        <p>Si vous avez des questions ou besoin d’aide, notre équipe est disponible pour vous accompagner. Vous pouvez nous écrire à <a href="mailto:support@ecochamp.org">support@ecochamp.org</a> ou visiter notre FAQ.</p>
        <p>Encore une fois, bienvenue dans notre communauté !</p>
        <p>Cordialement,</p>
        <p>L’équipe <strong>Ecochamp</strong></p>
    </main>
</body>
</html>
