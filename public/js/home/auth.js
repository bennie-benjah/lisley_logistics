// auth.js
document.addEventListener('DOMContentLoaded', () => {
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');

    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const confirmPasswordForm = document.getElementById('confirmPasswordForm');

    function showForm(form) {
        [loginForm, signupForm, forgotPasswordForm, resetPasswordForm, confirmPasswordForm].forEach(f => f.classList.remove('active'));
        form.classList.add('active');
    }

    // Switch tabs
    loginTab.addEventListener('click', () => {
        loginTab.classList.add('active');
        signupTab.classList.remove('active');
        showForm(loginForm);
    });

    signupTab.addEventListener('click', () => {
        signupTab.classList.add('active');
        loginTab.classList.remove('active');
        showForm(signupForm);
    });

    // Forgot password links
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const backToLoginFromForgot = document.getElementById('backToLoginFromForgot');
    const backToLoginFromReset = document.getElementById('backToLoginFromReset');
    const backToLoginFromConfirm = document.getElementById('backToLoginFromConfirm');

    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', (e) => {
            e.preventDefault();
            showForm(forgotPasswordForm);
        });
    }

    if (backToLoginFromForgot) backToLoginFromForgot.addEventListener('click', (e) => { e.preventDefault(); showForm(loginForm); });
    if (backToLoginFromReset) backToLoginFromReset.addEventListener('click', (e) => { e.preventDefault(); showForm(loginForm); });
    if (backToLoginFromConfirm) backToLoginFromConfirm.addEventListener('click', (e) => { e.preventDefault(); showForm(loginForm); });
});
