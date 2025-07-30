<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé - Main Courante SETER</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #67152e 0%, #ebba7d 100%);
            background-attachment: fixed;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(235, 186, 125, 0.3);
            border-radius: 24px;
            padding: 48px 32px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(103, 21, 46, 0.2);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(235, 186, 125, 0.8), transparent);
        }

        .logo-section {
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            background: rgba(235, 186, 125, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(235, 186, 125, 0.4);
            animation: float 3s ease-in-out infinite;
        }

        .logo-icon svg {
            width: 40px;
            height: 40px;
            color: #ebba7d;
        }

        .logo-text {
            color: white;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            background: linear-gradient(45deg, #ebba7d, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 16px;
        }

        .divider {
            width: 64px;
            height: 4px;
            background: linear-gradient(90deg, #ebba7d, #67152e);
            margin: 0 auto 32px;
            border-radius: 2px;
        }

        .title {
            color: white;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(103, 21, 46, 0.3);
        }

        .message {
            color: white;
            opacity: 0.95;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .sub-message {
            color: white;
            opacity: 0.8;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .illustration {
            width: 96px;
            height: 96px;
            margin: 0 auto 32px;
            opacity: 0.7;
        }

        .illustration svg {
            width: 100%;
            height: 100%;
            color: #ebba7d;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 32px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ebba7d 0%, #d4a574 100%);
            color: #67152e;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(235, 186, 125, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(235, 186, 125, 0.6);
            background: linear-gradient(135deg, #f0c688 0%, #ebba7d 100%);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: 1px solid rgba(235, 186, 125, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: rgba(235, 186, 125, 0.15);
            border-color: rgba(235, 186, 125, 0.7);
        }

        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(235, 186, 125, 0.3);
        }

        .footer-text {
            color: white;
            opacity: 0.7;
            font-size: 12px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Particules d'arrière-plan */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: #ebba7d;
            border-radius: 50%;
            opacity: 0.4;
        }

        .particle:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 8px;
            height: 8px;
            animation: pulse 2s infinite;
        }

        .particle:nth-child(2) {
            top: 25%;
            right: 20%;
            width: 4px;
            height: 4px;
            animation: pulse 2s infinite 0.5s;
        }

        .particle:nth-child(3) {
            bottom: 33%;
            left: 25%;
            width: 6px;
            height: 6px;
            animation: bounce 1.5s infinite;
        }

        .particle:nth-child(4) {
            bottom: 20%;
            right: 33%;
            width: 4px;
            height: 4px;
            animation: pulse 2s infinite 1s;
        }

        .particle:nth-child(5) {
            top: 50%;
            left: 80%;
            width: 3px;
            height: 3px;
            background: white;
            animation: pulse 3s infinite 1.5s;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.8; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 32px 24px;
            }

            .error-code {
                font-size: 80px;
            }

            .title {
                font-size: 24px;
            }

            .message {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Particules d'arrière-plan -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Container principal -->
    <div class="container">
        <!-- Logo SETER -->
        <div class="logo-section">
            <div class="logo-icon">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"/>
                </svg>
            </div>
            <div class="logo-text">SETER - Main Courante</div>
        </div>

        <!-- Code d'erreur -->
        <div class="error-code">403</div>
        <div class="divider"></div>

        <!-- Message principal -->
        <h1 class="title">Accès refusé</h1>
        <p class="message">
            Vous n'avez pas les permissions nécessaires pour accéder à cette ressource.
        </p>
        <p class="sub-message">
            Si vous pensez qu'il s'agit d'une erreur, contactez votre administrateur.
        </p>

        <!-- Icône d'illustration -->
        <div class="illustration">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>

        <!-- Boutons d'action -->
        <div class="buttons">
            <a href="{{ route('evenements.index') }}" class="btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Retour à l'accueil
            </a>

            <button onclick="history.back()" class="btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Page précédente
            </button>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                © {{ date('Y') }} SETER - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>
