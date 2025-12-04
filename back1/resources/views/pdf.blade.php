<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bulletin de paie</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Bulletin de paie - {{ $paie->mois }}</h2>
    <p><strong>Nom :</strong> {{ $paie->collaborateur->nom }}</p>
    <p><strong>Brut :</strong> {{ $paie->brut }}</p>
    <p><strong>Retenue :</strong> {{ $paie->retenue }}</p>
    <p><strong>Net :</strong> {{ $paie->net }}</p>
</body>

</html>
