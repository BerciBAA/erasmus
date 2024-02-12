document.addEventListener('DOMContentLoaded', function(){
    if (!localStorage.getItem('userLoggedIn')) {
        window.location.href = 'login.html';
    }
});