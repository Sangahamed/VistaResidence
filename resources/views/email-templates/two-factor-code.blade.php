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
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .code-box {
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .device-info {
            background: #e3f2fd;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Code de vérification</h1>
        <p>VistaImmob - Sécurité renforcée</p>
    </div>
    
    <div class="content">
        <h2>Bonjour {{ $user_name }},</h2>
        
        <p>Nous avons détecté une connexion depuis un nouvel appareil. Pour votre sécurité, veuillez saisir le code de vérification ci-dessous :</p>
        
        <div class="code-box">
            <p><strong>Votre code de vérification :</strong></p>
            <div class="code">{{ $two_factor_code }}</div>
            <p><small>Ce code expire dans <strong>10 minutes</strong></small></p>
        </div>

        @if(isset($type))
            @if($type === 'sms_backup')
            <div class="device-info">
                <p>📱 <strong>SMS envoyé :</strong> Ce code a également été envoyé par SMS à votre numéro de téléphone pour plus de commodité.</p>
            </div>
            @elseif($type === 'email_fallback')
            <div class="warning">
                <p>⚠️ <strong>SMS indisponible :</strong> L'envoi par SMS n'est temporairement pas disponible. Nous vous envoyons donc le code par email.</p>
            </div>
            @endif
        @endif
        
        <div class="warning">
            <p><strong>⚠️ Important :</strong></p>
            <ul>
                <li>Ne partagez jamais ce code avec personne</li>
                <li>VistaImmob ne vous demandera jamais votre code par téléphone</li>
                <li>Si vous n'avez pas tenté de vous connecter, ignorez cet email</li>
            </ul>
        </div>
        
        <p>Si vous avez des questions concernant la sécurité de votre compte, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br><strong>L'équipe VistaImmob</strong></p>
    </div>
</body>
</html>
