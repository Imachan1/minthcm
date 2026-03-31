/**
 * contacts.js — walidacja formularza kontaktu
 * ref #400002
 */

function validateContactForm() {
    var phoneField = document.getElementById('phone_work');
    var phoneError = document.getElementById('phone_work_error');

    if (!phoneField || !phoneError) {
        return true;
    }

    var phoneValue = phoneField.value.trim();

    if (phoneValue === '') {
        phoneError.style.display = 'none';
        return true;
    }

    // Akceptuje formaty: +48 123 456 789, 123456789, 123-456-789
    var phoneRegex = /^(\+\d{1,3}\s?)?\d[\d\s\-]{7,14}\d$/;
    if (!phoneRegex.test(phoneValue)) {
        phoneError.style.display = 'inline';
        phoneField.focus();
        return false;
    }

    phoneError.style.display = 'none';
    return true;
}
