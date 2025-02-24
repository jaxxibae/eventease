<?php
$pageTitle = 'Login';
require_once __DIR__ . '/../core/components/header.php';
?>

<main>
    <div class="container">
        <h1>Login</h1>
        <div class="row">
            <form class="login-form" id="login-form" method="post" action="/actions/user/login.php">
                <div class="row">
                    <div class="input-field">
                        <input id="email_address" name="email_address" type="email" class="validate">
                        <label for="email_address">Email Address</label>
                    </div>
                    <div class="input-field">
                        <input id="password" name="password" type="password" class="validate">
                        <label for="password">Password</label>
                    </div>

                    <button class="btn waves-effect waves-light" id="submit" type="submit" name="action">
                        Login
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/core/scripts/login.js"></script>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>