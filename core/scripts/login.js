window.onload = async function () {
    var submitButton = document.getElementById('submit');

    submitButton.onclick = async function (event) {
        event.preventDefault();
        var emailAddress = document.getElementById('email_address').value;
        var password = document.getElementById('password').value;

        var response = await fetch('/actions/user/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "email_address": emailAddress,
                "password": password
            })
        });

        var data = await response.json();

        if (data.success) {
            window.location.href = '/pages/events.php';
        } else {
            alert(data.message);
        }
    }
}