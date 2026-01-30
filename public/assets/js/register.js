
document.getElementById('roleSelect').addEventListener('change', function () {
    const studentFields = document.getElementById('studentFields');
    if (this.value === 'student') {
        studentFields.style.display = 'block';
    } else {
        studentFields.style.display = 'none';
    }
});

if (document.getElementById('roleSelect').value === 'student') {
    document.getElementById('studentFields').style.display = 'block';
}

const passwordInput = document.getElementById('passwordInput');
const strengthBar = document.getElementById('strengthBar');

passwordInput.addEventListener('input', function () {
    const password = this.value;

    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[@$!%*?&]/.test(password);

    document.getElementById('req-length').classList.toggle('met', hasLength);
    document.getElementById('req-uppercase').classList.toggle('met', hasUppercase);
    document.getElementById('req-lowercase').classList.toggle('met', hasLowercase);
    document.getElementById('req-number').classList.toggle('met', hasNumber);
    document.getElementById('req-special').classList.toggle('met', hasSpecial);

    const metCount = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;

    strengthBar.className = 'password-strength-bar';
    passwordInput.classList.remove('is-valid', 'is-invalid');

    if (password.length === 0) {
        strengthBar.className = 'password-strength-bar';
    } else if (metCount <= 2) {
        strengthBar.classList.add('weak');
    } else if (metCount <= 4) {
        strengthBar.classList.add('medium');
    } else {
        strengthBar.classList.add('strong');
        passwordInput.classList.add('is-valid');
    }
});

document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const registerBtn = document.getElementById('registerBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    const form = this;

    registerBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-block';

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.success) {
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                registerBtn.disabled = false;

                const modal = document.getElementById('successModal');
                const message = document.getElementById('successMessage');
                message.textContent = data.message;
                modal.classList.add('show');
                modal.style.display = 'flex';

                setTimeout(function () {
                    window.location.href = data.redirect;
                }, 3000);
            } else {
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                registerBtn.disabled = false;

                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = data.errors[key][0];
                                feedback.style.display = 'block';
                            } else {
                                const newFeedback = document.createElement('div');
                                newFeedback.className = 'invalid-feedback';
                                newFeedback.style.display = 'block';
                                newFeedback.textContent = data.errors[key][0];
                                input.parentNode.insertBefore(newFeedback, input.nextSibling);
                            }
                        }
                    });
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            form.submit();
        });
});