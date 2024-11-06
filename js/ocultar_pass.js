function togglePassword(id) {
    const passwordSpan = document.getElementById('password_' + id);
    const passwordInput = document.getElementById('password_input_' + id);
    const button = passwordInput.nextElementSibling;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        button.textContent = 'Ocultar';
    } else {
        passwordInput.type = 'password';
        button.textContent = 'Mostrar';
    }
}