document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('logoutButton').addEventListener('click', function() {
        fetch(`${config.API_BASE_URL}/php/logout.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.removeItem('userLoggedIn');
                window.location.href = 'login.html';
            } else {
                alert('Logout failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});