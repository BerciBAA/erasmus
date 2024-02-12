document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.innerHTML = ''; 

        const firstName = document.getElementById('firstName').value;
        const secondName = document.getElementById('secondName').value;
        const emailAddress = document.getElementById('emailAddress').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const schoolId =  document.getElementById('schoolSelect').value

        if (!firstName || !secondName || !emailAddress || !password || !confirmPassword || !schoolId) {
            messageContainer.innerHTML = "<div class='alert alert-warning' role='alert'>Please fill out all fields.</div>";
            return;
        }

        const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!emailRegex.test(emailAddress)) {
            messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>Please enter a valid email address.</div>";
            return;
        }

        if (password !== confirmPassword) {
            messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>The passwords do not match.</div>";
            return;
        }

        const formData = {
            firstName: firstName,
            secondName: secondName,
            emailAddress: emailAddress,
            password: password,
            confirmPassword: confirmPassword,
            schoolId: schoolId
        };

        fetch(`${config.API_BASE_URL}/php/register.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(result => {
            if (result.status !== 201) {
                messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>" + result.body.message + "</div>";
            } else {
                window.location.href = 'login.html';
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            messageContainer.innerHTML = "<div class='alert alert-danger' role='alert'>An error occurred. Please try again later.</div>";
        });
    });

    fetch(`${config.API_BASE_URL}/php/register.php`)
    .then(response => response.json())
    .then(data => {
        const selectElement = document.getElementById('schoolSelect');
        data.forEach(school => {
            const option = new Option(school.name, school.id);
            selectElement.add(option);
        });
    })
    .catch(error => console.error('Error fetching schools:', error));
});