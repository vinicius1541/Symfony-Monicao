function validateCPF(cpf) {
    cpf = cpf.toString().replace(/[^\d]+/g,'');
    if (cpf === '' || cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    // Validation for CPF
    let add = 0;
    for (let i = 0; i < 9; i++) {
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }
    if (rev !== parseInt(cpf.charAt(9))) {
        return false;
    }

    add = 0;
    for (let i = 0; i < 10; i++) {
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }
    return rev === parseInt(cpf.charAt(10));
}

function validateEmail(email) {
    // Validation for e-mail
    const regexEmail = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    return regexEmail.test(email.toString());
}

function validateFields(e) {
    const validate = (validator, inputSelector, feedbackSelector) => {
        const inputValue = $(inputSelector).val();
        // Verifica se o campo não está em branco
        if (inputValue.trim() !== "" || e.type === "submit") {
            const validationResult = validator(inputValue);
            const feedbackElement = $(feedbackSelector);

            if (!validationResult) {
                e.preventDefault();
                feedbackElement.show().addClass('animate__animated animate__shakeX');
                setTimeout(() => {
                    feedbackElement.removeClass('animate__animated animate__shakeX');
                }, 500);
            } else {
                feedbackElement.hide();
            }
        }
    };
    // Validation
    validate(validateCPF, '.cpf', '.invalid-feedback.validate-cpf');
    validate(validateEmail, '.email', '.invalid-feedback.validate-email');
}

$(document).ready(function() {
    const validate = () => {
        inputMask();

        const validateField = (selector, event) => {
            $(selector).on(event, function (e) {
                validateFields(e);
            });
        };
        // Validate on blur
        validateField('.cpf', 'blur');
        validateField('.email', 'blur');

        // Validate on change (for CPF, after the field reaches the number of characters)
        $('.cpf').on('input', function (e) {
            if ($(this).val().length === 11) {
                validateFields(e);
            }
        });

        // Validation on submit
        $('.form-employee').submit(function (e) {
            validateFields(e);
        });
    };
    validate();
});
