document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageContainer = document.getElementById('messageContainer')
        messageContainer.innerHTML = '';

        const emailAddress = document.getElementById('emailAddress').value
        const password = document.getElementById('password').value
  
        if (!emailAddress || !password) {
            messageContainer.innerHTML = "<div class='alert alert-warning' role='alert'>Please fill out all fields.</div>";
            return;
        }

        const formData = {
            emailAddress: emailAddress,
            password: password
        }

        fetch(`${config.API_BASE_URL}/php/login.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(result => {
            if (result.status !== 200) {
                messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>" + result.body.message + "</div>";
            } else {
                localStorage.setItem('userLoggedIn', 'true');
                window.location.href = 'index.html';
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>An error occurred. Please try again later.</div>";
        });
    });
});