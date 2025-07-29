{{-- filepath: resources/views/errors/403.blade.php --}}
{{-- @extends('errors::minimal')

@section('title', 'Accès refusé')
@section('code', '403')
@section('message', 'Vous n’avez pas la permission d’accéder à cette page.') --}}


{{-- filepath: resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès refusé</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center" style="background: url('{{ asset('assets/images/error/error.png') }}') no-repeat center center; background-size: cover;;">
    <div class="bg-white p-10 rounded shadow text-center bg-opacity-90">
        <h1 style="border-color: #fff; color: #fff;font-weight: bold;font-size: 90px; ">403</h1>

        <h2 style="border-color: #fff; color: #fff;font-weight: bold;font-size: 24px; ">Accès refusé</h2>
        <p style="border-color: #fff; color: #fff;font-weight: bold;font-size: 24px; ">Vous n’avez pas la permission d’accéder à cette page.</p>

                    <button class="btn btn-primary ml-auto flex items-center"
                        style="background-color: #ebba7d; border-color: #fff; color: #fff;font-weight: bold;font-size: 16px; padding: 6px 20px; border-radius: 5px;"
                       >
                        <a href="{{ url()->previous() }}">Retour</a>
                    </button>


    </div>
</body>
</html>

</html>
