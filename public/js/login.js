function showPasswordStep() {
    const emailInput = document.getElementById('emailInput');
    const emailGroup = document.getElementById('emailGroup');
    const passwordGroup = document.getElementById('passwordGroup');
    const emailNextBtn = document.getElementById('emailNextBtn');
    const passwordInput = document.getElementById('passwordInput');

    if (emailInput.value.trim() !== "") {
        // Apply Locked/Stacked State
        emailInput.setAttribute('readonly', true);
        emailInput.classList.add('bg-light'); // Bootstrap gray bg
        emailNextBtn.classList.add('d-none'); // Hide arrow

        // Stack Logic: Add classes to email group to remove bottom rounded corners/border
        // But we need to target the input itself or a wrapper? 
        // Bootstrap form-control has border-radius.
        emailInput.classList.add('stacked-top'); // Custom class defined above? No, I defined .stacked-top .form-control
        // Actually let's just add the class directly to input via JS or wrap them.
        // Simpler: Just modify styles directly or specific class
        emailInput.style.borderBottomLeftRadius = '0';
        emailInput.style.borderBottomRightRadius = '0';
        emailInput.style.borderBottom = 'none';

        // Show Password
        passwordGroup.classList.remove('d-none');
        // Animate? Bootstrap doesn't have fade-in built-in for d-none switch easily without transition classes.
        // Just show it for now.

        const passInputControl = passwordGroup.querySelector('input');
        passInputControl.style.borderTopLeftRadius = '0';
        passInputControl.style.borderTopRightRadius = '0';

        passwordInput.focus();
    } else {
        emailInput.focus();
    }
}

function handleAuthSubmit(e) {
    e.preventDefault();
    alert("Signing in...");
}

// Handle Enter on Email
document.getElementById('emailInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        showPasswordStep();
    }
});