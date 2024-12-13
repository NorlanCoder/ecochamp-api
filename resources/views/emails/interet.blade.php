<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Un nouveau participant/bénévole s’intéresse à votre événement</title>
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
            <img src="{{asset('assets/interet.png')}}" width="100" alt="">
        </p>
        <p>Bonjour <strong>{{ $user->fullname }}</strong></p>
        <p>Nous avons le plaisir de vous informer qu’une nouvelle personne a manifesté de l’intérêt pour votre événement en tant que <strong>Bénévole</strong></p>
        <p>Détails de la personne intéressée :</p>
        <ul>
            <li><strong>Nom:</strong> {{ $participant->fullname }}</li>
            <li><strong>Adresse e-mail:</strong> {{ $participant->email }}</li>
        </ul>
        <p>Nous vous invitons à entrer en contact avec cette personne pour lui fournir des informations complémentaires ou confirmer son inscription/engagement.</p>
        <p><strong>👉 Consultez les détails complets de votre événement sur votre tableau de bord</strong></p>
        <p>Merci de faire de votre événement un succès ! 🎉</p>
        <p>Cordialement,</p>
        <p>L’équipe <strong>Ecochamp</strong></p>
    </main>
</body>
</html>
