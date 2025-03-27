<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification à deux facteurs</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #3490dc;">Code de vérification à deux facteurs</h2>
    <p>Bonjour {{ $user_name }},</p>
    <p>Vous avez demandé une connexion à partir d'un nouvel appareil ou d'un nouvel emplacement. Pour des raisons de sécurité, veuillez utiliser le code suivant pour compléter votre connexion :</p>
    <div style="background-color: #e9ecef; padding: 10px; text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0;">
        {{ $two_factor_code }}
    </div>
    <p>Ce code expirera dans 10 minutes.</p>
    <p>Si vous n'avez pas initié cette demande de connexion, veuillez ignorer cet e-mail et contacter immédiatement notre équipe de support.</p>
    <p>Cordialement,<br>L'équipe de sécurité</p>
</body>
</html>

