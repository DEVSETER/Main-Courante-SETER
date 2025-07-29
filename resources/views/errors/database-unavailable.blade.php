<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service temporairement indisponible</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .details {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 0.9rem;
        }

        .retry-btn {
            background: #67152e;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px;
        }

        .retry-btn:hover {
            background: #8b1538;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .contact-info {
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ff4757;
            margin-right: 8px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>

        <h1>Service temporairement indisponible</h1>

        <p>
            <span class="status-indicator"></span>
            Notre système de base de données rencontre actuellement des difficultés techniques.
        </p>

        <div class="details">
            <h3>🔍 Que s'est-il passé ?</h3>
            <p>La connexion à la base de données a été interrompue. Nos équipes techniques travaillent activement pour résoudre ce problème.</p>

            <h3>⏰ Que faire ?</h3>
            <ul style="text-align: left; margin: 15px 0; padding-left: 20px;">
                <li>Attendez quelques minutes et réessayez</li>
                <li>Actualisez la page</li>
                <li>Vérifiez votre connexion internet</li>
                <li>Contactez l'administrateur si le problème persiste</li>
            </ul>
        </div>

        <button class="retry-btn" onclick="window.location.reload()">
            🔄 Réessayer
        </button>

        <button class="retry-btn" onclick="window.history.back()">
            ← Retour
        </button>

        <div class="contact-info">
            <p>
                <strong>Besoin d'aide ?</strong><br>
                Contactez l'équipe technique ou essayez de nouveau dans quelques minutes.
            </p>
            <p style="margin-top: 15px; font-size: 0.8rem;">
                Erreur générée le {{ date('d/m/Y à H:i:s') }}
            </p>
        </div>
    </div>

    <script>
        // Auto-refresh toutes les 30 secondes
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Afficher un message dans la console
        console.log('🔧 Service temporairement indisponible - Auto-refresh dans 30 secondes');
    </script>
</body>
</html>
