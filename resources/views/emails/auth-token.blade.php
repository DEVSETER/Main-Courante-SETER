<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Connexion Main Courante</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(225deg, #67152e 0%, #ebba7d 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .button { display: inline-block; background: #67152e; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; border-top: 1px solid #eee; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Connexion Main Courante</h1>
        </div>

        <div class="content">
            <h2>Bonjour {{ $user->name }},</h2>

            <p>Vous avez demandé à vous connecter à l'application Main Courante. Comme notre système d'authentification principal (Wallix) est temporairement indisponible, nous vous envoyons ce lien de connexion sécurisé.</p>

            <div style="text-align: center;">
                <a href="{{ $login_url }}" class="button">
                    🚀 Se connecter maintenant
                </a>
            </div>

            <div class="warning">
                <strong>⚠️ Important :</strong>
                <ul>
                    <li>Ce lien est valide jusqu'au {{ $expires_at->format('d/m/Y à H:i') }}</li>
                    <li>Il ne peut être utilisé qu'une seule fois</li>
                    <li>Ne partagez pas ce lien avec d'autres personnes</li>
                </ul>
            </div>

            <p><strong>Si vous n'avez pas demandé cette connexion,</strong> ignorez simplement cet email. Votre compte reste sécurisé.</p>

            <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

            <p><small>Si le bouton ne fonctionne pas, vous pouvez copier et coller cette URL dans votre navigateur :<br>
            <code style="background: #f8f9fa; padding: 5px; border-radius: 3px; word-break: break-all;">{{ $login_url }}</code></small></p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Main Courante - Application de gestion des événements</p>
        </div>
    </div>
</body>
</html>
