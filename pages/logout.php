<?php
$pageTitle = 'Logout';

require_once __DIR__ . '/../core/components/header.php';

$sessionStorage->remove('user_id');
$sessionStorage->remove('user_data');

?>

<main>
    <div class="container">
        <h1>Logout</h1>
        <p>You have been logged out. Redirecting to the home page...</p>
    </div>
</main>

<script>
    setTimeout(() => {
        window.location.href = '/';
    }, 3000);
</script>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>