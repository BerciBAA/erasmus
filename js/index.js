document.addEventListener('DOMContentLoaded', function(){
    if (!localStorage.getItem('userLoggedIn')) {
        window.location.href = 'login.html';
    }

    fetch(`${config.API_BASE_URL}/php/index.php`, {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('User ID:', data.user_id);
            document.querySelector('.container').insertAdjacentHTML('beforeend', `<p>User ID: ${data.user_id}</p>`);
        } else {
            console.error('Error or user not logged in:', data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
});