<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Italy Heaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="bg-dark text-white p-3 my-0">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3"><i class="bi bi-fire mx-2"></i>Italy Heaven</h1>
            <nav class="d-flex align-items-center">
                <a href="/" class="text-white text-decoration-none mx-3">Strona Główna</a>
                <a href="/menu" class="text-white text-decoration-none mx-3">Menu</a>

                <a href="/kontakt" class="text-white text-decoration-none mx-3">Kontakt</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/rezerwuj" class="text-white text-decoration-none mx-3">Rezerwacja</a>
                    <?php if ($_SESSION['role'] === 'admin') {
                        echo '<a href="/admin" class="btn btn-danger my-2 btn-sm"><i class="bi bi-shield-fill-check"></i><span class="ms-1">Panel administratora</span></a>';
                    } elseif ($_SESSION['role'] === 'kelner') {
                        echo '<a href="/kelner" class="btn btn-warning my-2 btn-sm"><i class="bi bi-shield-fill-check"></i><span class="ms-1">Panel kelnera</span></a>';
                    }
                    ?>
                    <a href="/logout" class="btn mx-2 btn-light btn-sm">Wyloguj się</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-light btn-sm">Zaloguj się</a>
                    <span class="px-2"> / </span>
                    <a href="/register" class="btn btn-light-2 btn-sm">Zarejestruj się</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container-fluid p-0">