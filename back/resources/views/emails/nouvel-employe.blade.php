<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .credentials-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }

        .credential-item {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #2563eb;
        }

        .value {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 4px;
            font-family: monospace;
            display: inline-block;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎉 Bienvenue chez Admin+</h1>
        <p>République du Bénin</p>
    </div>

    <div class="content">
        <p>Bonjour <strong>{{ $user->fullname }}</strong>,</p>

        <p>Nous sommes ravis de vous accueillir au sein de notre équipe en tant que
            <strong>{{ $collaborator->poste }}</strong>.</p>

        <p>Votre compte a été créé avec succès. Voici vos identifiants de connexion à la plateforme Admin+ :</p>

        <div class="credentials-box">
            <div class="credential-item">
                <div class="label">📧 Email :</div>
                <div class="value">{{ $user->email }}</div>
            </div>

            <div class="credential-item">
                <div class="label">🔑 Mot de passe temporaire :</div>
                <div class="value">{{ $password }}</div>
            </div>

            <div class="credential-item">
                <div class="label">🆔 Matricule :</div>
                <div class="value">{{ $collaborator->matricule }}</div>
            </div>
        </div>

        <p style="background: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b;">
            ⚠️ <strong>Important :</strong> Pour des raisons de sécurité, nous vous recommandons vivement de changer
            votre mot de passe lors de votre première connexion.
        </p>

        <div style="text-align: center;">
            <a href="http://localhost:5173/login" class="button">Se connecter à Admin+</a>
        </div>

        <p>Si vous avez des questions ou besoin d'assistance, n'hésitez pas à contacter le service RH.</p>

        <p>Cordialement,<br>
            <strong>L'équipe Admin+</strong>
        </p>
    </div>

    <div class="footer">
        <p>© 2025 Admin+ Bénin. Tous droits réservés.</p>
        <p style="font-size: 12px; color: #9ca3af;">
            Cet email contient des informations confidentielles. Si vous l'avez reçu par erreur, veuillez le supprimer.
        </p>
    </div>
</body>

</html>
