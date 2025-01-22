<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase - <?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="/core/styles/styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="icon" href="/assets/venue.svg">
</head>

<body>
    <?php
    require_once __DIR__ . '/../utils/session_storage/native_session_storage.php';

    use Utils\Session_storage\NativeSessionStorage;

    // Create a new session storage instance
    $sessionStorage = new NativeSessionStorage();

    if ($sessionStorage->get('user_id') === null) {
        require_once __DIR__ . '/navbar.php';
    } else {
        require_once __DIR__ . '/navbar_logged_in.php';
    }
    ?>
    <div class="col s12">
        <?php if (isset($_GET['error'])): ?>
            <div class="card-panel red lighten-2">
                <span class="white-text"><?php echo htmlspecialchars($_GET['error']); ?></span>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="card-panel green lighten-2">
                <span class="white-text"><?php echo htmlspecialchars($_GET['success']); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <main>