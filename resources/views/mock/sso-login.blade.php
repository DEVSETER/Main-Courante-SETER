<!-- filepath: c:\Projet\MainCourante\resources\views\mock\sso-login.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Mock SSO - Test Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; }
        .info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>🧪 Mock SSO - Test de connexion</h2>

    <div class="info">
        <strong>Mode Test Local</strong><br>
        Client ID: {{ $clientId }}<br>
        Redirect URI: {{ $redirectUri }}<br>
        State: {{ $state }}
    </div>

    <form id="mockLoginForm">
        <div class="form-group">
            <label>Utilisateur de test :</label>
            <select name="test_user" id="testUser">
                <option value="admin">Admin User (admin@test.com)</option>
                <option value="user">Standard User (user@test.com)</option>
                <option value="guest">Guest User (guest@test.com)</option>
            </select>
        </div>

        <button type="button" onclick="simulateLogin()">✅ Simuler connexion SSO</button>
        <button type="button" onclick="simulateError()">❌ Simuler erreur</button>
    </form>

    <script>
        function simulateLogin() {
            const redirectUri = '{{ $redirectUri }}';
            const state = '{{ $state }}';
            const code = '{{ $authCode }}';

            // Rediriger avec le code d'autorisation
            const callbackUrl = `${redirectUri}?code=${code}&state=${state}`;
            window.location.href = callbackUrl;
        }

        function simulateError() {
            const redirectUri = '{{ $redirectUri }}';
            const state = '{{ $state }}';

            // Simuler une erreur
            const errorUrl = `${redirectUri}?error=access_denied&error_description=User%20denied%20access&state=${state}`;
            window.location.href = errorUrl;
        }
    </script>
</body>
</html>
