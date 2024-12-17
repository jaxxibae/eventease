<?php
$pageTitle = 'Register';
require_once __DIR__ . '/../core/components/header.php';
?>

<main>
    <div class="container">
        <h1>Register</h1>
        <div class="row">
            <form action="/actions/user/register.php" class="col s12" method="POST">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="email_address" name="email_address" type="email" class="validate" required>
                        <label for="email_address">Email Address</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="password" name="password" type="password" class="validate" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="confirm_password" name="confirm_password" type="password" class="validate" required>
                        <label for="confirm_password">Confirm Password</label>
                    </div>
                    <div class="input-field col s12">
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Choose your position</option>
                            <option value="Event Manager">Event Manager</option>
                            <option value="Attendee">Attendee</option>
                        </select>
                        <label for="role">Position</label>
                    </div>
                    <button class="btn waves-effect waves-light" type="submit" name="action">Register
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
</script>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>