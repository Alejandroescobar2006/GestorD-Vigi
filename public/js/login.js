document.getElementById('loginForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const error = document.getElementById('passwordError');
    if (pass.length > 0 && pass.length < 6) {
        error.style.display = 'block';
        e.preventDefault();
    } else {
        error.style.display = 'none';
    }
});

document.getElementById('password').addEventListener('input', function() {
    const error = document.getElementById('passwordError');
    error.style.display = (this.value.length > 0 && this.value.length < 6) ? 'block' : 'none';
});