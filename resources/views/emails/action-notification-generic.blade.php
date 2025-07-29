{{-- filepath: resources/views/emails/action-notification-generic.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification Main Courante</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #67152e; color: white; padding: 15px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .message-box { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>
                @if($type === 'demande_validation')
                    🔔 Demande de validation
                @elseif($type === 'aviser')
                    ⚠️ Avis important
                @elseif($type === 'informer')
                    📢 Information
                @endif
            </h2>
        </div>

        <div class="content">
            <p><strong>Bonjour,</strong></p>

            <h3>Détails de l'événement</h3>
            <ul>
                <li><strong>Numéro :</strong> {{ $evenement->id }}</li>
                <li><strong>Date :</strong> {{ $evenement->date_evenement }}</li>
                <li><strong>Nature :</strong> {{ $evenement->nature_evenement->libelle ?? 'Non spécifiée' }}</li>
                <li><strong>Description :</strong> {{ $evenement->description }}</li>
            </ul>

            @if($message)
            <div class="message-box">
                <h3>Message</h3>
                <p>{{ $message }}</p>
            </div>
            @endif

            <p>
                <a href="{{ $eventUrl }}" style="color: #007bff; font-weight: bold;">
                    👉 Voir l'événement dans la main courante
                </a>
            </p>
        </div>

        <div class="footer">
            <p>Cette notification a été envoyée automatiquement par le système Main Courante.</p>
        </div>
    </div>
</body>
</html>
