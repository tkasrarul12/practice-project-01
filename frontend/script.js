const container = document.getElementById('container');
const loginBox = document.querySelector('.login-box');
const signupBox = document.querySelector('.signup-box');
const toSignup = document.getElementById('toSignup');
const toLogin = document.getElementById('toLogin');

document.addEventListener('mousemove', (e) => {
    let xAxis = (window.innerWidth / 2 - e.pageX) / 25;
    let yAxis = (window.innerHeight / 2 - e.pageY) / 25;
    container.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
});

// Reset tilt on mouse leave
document.addEventListener('mouseleave', () => {
    container.style.transform = `rotateY(0deg) rotateX(0deg)`;
});

toSignup.addEventListener('click', (e) => {
    e.preventDefault();
    loginBox.classList.remove('active');
    signupBox.classList.add('active');
});

toLogin.addEventListener('click', (e) => {
    e.preventDefault();
    signupBox.classList.remove('active');
    loginBox.classList.add('active');
});

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.innerText = message;
    toast.style.borderColor = isError ? '#ff4e50' : '#00dfd8';
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3500);
}

document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const fullName = document.getElementById('signupName').value;
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;

    try {
        const response = await fetch('auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'signup', full_name: fullName, email, password })
        });

        const result = await response.json();

        if (result.status === 'success') {
            showToast(result.message);
            document.getElementById('signupForm').reset();
            setTimeout(() => toLogin.click(), 1200); // Redirect to login form animation
        } else {
            showToast(result.message, true);
        }
    } catch (err) {
        showToast('Something went wrong. Please check connection.', true);
    }
});

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    try {
        const response = await fetch('auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'login', email, password })
        });

        const result = await response.json();

        if (result.status === 'success') { 

            showToast(`Welcome back, ${result.user.name}! Redirecting...`);
            document.getElementById('loginForm').reset();

            localStorage.setItem('loggedInUser', result.user.name);

            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1500);

        } else {
            showToast(result.message, true);
        }
    } catch (err) {
        showToast('Login failed. Please try again.', true);
    }
});
