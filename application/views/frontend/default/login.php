<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?php echo $page_title; ?>
                            </a>
                        </li>
                    </ol>
                </nav>
                <h1 class="category-name">
               
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="category-manuscript-list-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="user-dashboard-box mt-3">
                    <div class="user-dashboard-content w-100 login-form">
                        <div class="content-title-box">
                            <div class="title"><?php echo get_phrase('login'); ?></div>
                            <div class="subtitle"><?php echo get_phrase('provide_your_valid_login_credentials'); ?>.</div>
                        </div>
                        <form action="<?php echo site_url('login/validate_login/user'); ?>" method="post">
                            <div class="content-box">
                                <div class="basic-group">
                                    <div class="form-group">
                                        <label for="login-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                        <input type="email" class="form-control" name="email" id="login-email" placeholder="<?php echo get_phrase('email'); ?>" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="login-password"><span class="input-field-icon"><i class="fas fa-lock"></i></span> <?php echo get_phrase('password'); ?>:</label>
                                        <input type="password" class="form-control" name="password" id="login-password" placeholder="<?php echo get_phrase('password'); ?>" value="" required>
                                        <input type="checkbox" id="toggle-login-password" onclick="togglePassword('login-password')"> Show Password
                                    </div>
                                    <div class="form-group text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <!-- Refresh CAPTCHA button -->
                                        <button 
                                            type="button" 
                                            id="refresh-captcha" 
                                            class="btn btn-link me-3" 
                                            
                                            onclick="refreshCaptcha()">
                                            Refresh CAPTCHA
                                        </button>
                                        <!-- CAPTCHA image placeholder -->
                                        <span id="captcha-image" style="display: inline-block; font-size: 24px;">
                                            <?php echo isset($captcha_images) ? $captcha_images : 'CAPTCHA not generated'; ?>
                                        </span>
                                    </div>
                                    <!-- CAPTCHA input field -->
                                    <input 
                                        type="text" 
                                        class="form-control mt-3" 
                                        name="captcha" 
                                        id="captcha" 
                                        placeholder="<?php echo get_phrase('enter_captcha_here'); ?>" 
                                        required>
                                </div>



                                </div>
                            </div>
                            <div class="content-update-box">
                                <button type="submit" class="btn"><?php echo get_phrase('login'); ?></button>
                            </div>
                            <div class="forgot-pass text-center">
                                <span>or</span>
                                <a href="javascript::" onclick="toggleForm('forgot_password')"><?php echo get_phrase('forgot_password'); ?></a>
                            </div>
                            <div class="account-have text-center">
                                <?php echo get_phrase('do_not_have_an_account'); ?>? <a href="javascript::" onclick="toggleForm('registration')"><?php echo get_phrase('sign_up'); ?></a>
                            </div>
                        </form>
                    </div>
                    <div class="user-dashboard-content w-100 register-form hidden">
                        <div class="content-title-box">
                            <div class="title"><?php echo get_phrase('registration_form'); ?></div>
                            <div class="subtitle"><?php echo get_phrase('sign_up_and_start_learning'); ?>.</div>
                        </div>
                        <form action="<?php echo site_url('login/register'); ?>" method="post">
                            <div class="content-box">
                                <div class="basic-group">
                                    <div class="form-group">
                                        <label for="first_name"><span class="input-field-icon"><i class="fas fa-user"></i></span> <?php echo get_phrase('first_name'); ?>:</label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php echo get_phrase('first_name'); ?>" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name"><span class="input-field-icon"><i class="fas fa-user"></i></span> <?php echo get_phrase('last_name'); ?>:</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php echo get_phrase('last_name'); ?>" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                        <input type="email" class="form-control" name="email" id="registration-email" placeholder="<?php echo get_phrase('email'); ?>" value="" required>
                                    </div>
            <!-- Enhanced Password Validation Messages -->
<div class="form-group" id="password-validation-messages" style="display: none;">
    <div id="message" style="margin-top: 10px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9;">
        <h4 style="margin-bottom: 10px;">Password must contain:</h4>
        <table style="width: 100%;">
            <tr>
                <td id="letter" class="invalid" style="padding: 5px;">
                    <i class="fas fa-times-circle" style="color: red;"></i> A <b>lowercase</b> letter
                </td>
                <td id="capital" class="invalid" style="padding: 5px;">
                    <i class="fas fa-times-circle" style="color: red;"></i> A <b>uppercase</b> letter
                </td>
            </tr>
            <tr>
                <td id="number" class="invalid" style="padding: 5px;">
                    <i class="fas fa-times-circle" style="color: red;"></i> A <b>number or special character</b>
                </td>
                <td id="length" class="invalid" style="padding: 5px;">
                    <i class="fas fa-times-circle" style="color: red;"></i> Minimum <b>16 characters</b>
                </td>
            </tr>
        </table>
    </div>
     <!-- Password Strength Progress Bar -->
     <div class="form-group">
        <label for="password-strength"><?php echo get_phrase('password_strength'); ?>:</label>
        <div class="progress" style="height: 20px; background-color: #f5f5f5; border-radius: 5px;">
            <div
                class="progress-bar"
                id="password-strength-bar"
                role="progressbar"
                style="width: 0%; transition: width 0.4s;"
                aria-valuenow="0"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>
        <small id="password-strength-text" class="form-text text-muted mt-2"></small>
    </div>
</div>                                    
<!-- Password Input Field with Event Listener -->
<div class="form-group">
    <label for="registration-password">
        <span class="input-field-icon"><i class="fas fa-lock"></i></span>
        <?php echo get_phrase('password'); ?>:
    </label>
    <input
        type="password"
        class="form-control"
        name="password"
        id="registration-password"
        placeholder="<?php echo get_phrase('password'); ?>"
        onfocus="showPasswordValidationMessages()"
        onblur="hidePasswordValidationMessages()"
        onkeyup="validatePasswordStrength()"
        required
    >
    <input
        type="checkbox"
        id="toggle-registration-password"
        onclick="togglePassword('registration-password')">
    Show Password
</div>
<!-- Add the confirm password input field -->
<div class="form-group">
    <label for="confirm-password">
        <span class="input-field-icon"><i class="fas fa-lock"></i></span>
        <?php echo get_phrase('confirm_password'); ?>:
    </label>
    <input
        type="password"
        class="form-control"
        name="confirm_password"
        id="confirm-password"
        placeholder="<?php echo get_phrase('confirm_password'); ?>"
        onkeyup="validateConfirmPassword()"
        required
    >
    <input
        type="checkbox"
        id="toggle-confirm-password"
        onclick="togglePassword('confirm-password')">
    Show Password
    <small id="confirm-password-message" class="form-text text-muted mt-2"></small>
</div>
                                   
<!-- Update the checkbox label to trigger the modals -->
<div class="form-group">
    <input type="checkbox" id="data-privacy" name="data_privacy" required>
    <label for="data-privacy">
        I agree to the 
        <a href="javascript:void(0);" data-toggle="modal" data-target="#privacyPolicyModal">Data Privacy</a> 
        and 
        <a href="javascript:void(0);" data-toggle="modal" data-target="#termsAndConditionsModal">Terms and Conditions</a>.
    </label>
</div>
                                </div>
                            </div>
                            <div class="content-update-box">
                            <button type="button" class="btn" id="sign-up-button" onclick="showCaptchaModal()" disabled><?php echo get_phrase('sign_up'); ?></button>
                            </div>
                            <div class="account-have text-center">
                                <?php echo get_phrase('already_have_an_account'); ?>? <a href="javascript::" onclick="toggleForm('login')"><?php echo get_phrase('login'); ?></a>
                            </div>
                        </form>
                    </div>

                    <div class="user-dashboard-content w-100 forgot-password-form hidden">
                        <div class="content-title-box">
                            <div class="title"><?php echo get_phrase('forgot_password'); ?></div>
                            <div class="subtitle"><?php echo get_phrase('provide_your_email_address_to_get_password'); ?>.</div>
                        </div>
                        <form action="<?php echo site_url('login/forgot_password/frontend'); ?>" method="post">
                            <div class="content-box">
                                <div class="basic-group">
                                    <div class="form-group">
                                        <label for="forgot-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                        <input type="email" class="form-control" name="email" id="forgot-email" placeholder="<?php echo get_phrase('email'); ?>" value="" required>
                                        <small class="form-text text-muted"><?php echo get_phrase('provide_your_email_address_to_get_password'); ?>.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="content-update-box">
                                <button type="submit" class="btn"><?php echo get_phrase('reset_password'); ?></button>
                            </div>
                            <div class="forgot-pass text-center">
                                <?php echo get_phrase('want_to_go_back'); ?>? <a href="javascript::" onclick="toggleForm('login')"><?php echo get_phrase('login'); ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.progress-bar {
    height: 100%;
}
.valid {
    color: green;
}
.invalid {
    color: red;
}
#message ul li {
    display: flex;
    align-items: center;
}
#refresh-captcha:disabled {
    cursor: not-allowed;
    color: gray;
    opacity: 0.6;
}

</style>


<!-- Add JavaScript for password validation and toggle password visibility -->
<script>
function showPasswordValidationMessages() {
    document.getElementById('password-validation-messages').style.display = 'block';
}

function hidePasswordValidationMessages() {
    document.getElementById('password-validation-messages').style.display = 'none';
}

function toggleForm(form_type) {
  if (form_type === 'login') {
    document.querySelector('.login-form').style.display = 'block';
    document.querySelector('.forgot-password-form').style.display = 'none';
    document.querySelector('.register-form').style.display = 'none';
  } else if (form_type === 'registration') {
    document.querySelector('.login-form').style.display = 'none';
    document.querySelector('.forgot-password-form').style.display = 'none';
    document.querySelector('.register-form').style.display = 'block';
  } else if (form_type === 'forgot_password') {
    document.querySelector('.login-form').style.display = 'none';
    document.querySelector('.forgot-password-form').style.display = 'block';
    document.querySelector('.register-form').style.display = 'none';
  }
}

function togglePassword(fieldId) {
  var field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
  } else {
    field.type = "password";
  }
}

function validateConfirmPassword() {
    const password = document.getElementById('registration-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const message = document.getElementById('confirm-password-message');

    if (confirmPassword === password) {
        message.textContent = 'Passwords match';
        message.style.color = 'green';
    } else {
        message.textContent = 'Passwords do not match';
        message.style.color = 'red';
    }

    validateForm();
}
function validatePasswordStrength() {
    const password = document.getElementById('registration-password').value;
    const progressBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    // Criteria
    const hasLowercase = /[a-z]/.test(password);
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasMinimumLength = password.length >= 16;

    // Update validation messages
    updateValidationMessage('letter', hasLowercase);
    updateValidationMessage('capital', hasUppercase);
    updateValidationMessage('number', hasNumber);
    updateValidationMessage('length', hasMinimumLength);

    // Calculate strength
    let strength = 0;
    if (hasLowercase) strength += 25;
    if (hasUppercase) strength += 25;
    if (hasNumber) strength += 25;
    if (hasMinimumLength) strength += 25;

    // Update progress bar
    progressBar.style.width = strength + '%';
    progressBar.ariaValueNow = strength;

    // Update progress bar color and strength text
    if (strength === 0) {
        progressBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Weak';
    } else if (strength < 50) {
        progressBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Fair';
    } else if (strength < 100) {
        progressBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Good';
    } else {
        progressBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Strong';
    }
}

function updateValidationMessage(elementId, isValid) {
    const element = document.getElementById(elementId);
    const icons = {
        valid: '<i class="fas fa-check-circle" style="color: green;"></i>',
        invalid: '<i class="fas fa-times-circle" style="color: red;"></i>'
    };

    element.innerHTML = `${isValid ? icons.valid : icons.invalid} ${element.textContent.trim()}`;
    element.className = isValid ? 'valid' : 'invalid';
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function refreshCaptcha() {
        const captchaImage = document.getElementById('captcha-image');
        const refreshButton = document.querySelector('button[onclick="refreshCaptcha()"]');
        refreshButton.disabled = true;
        refreshButton.textContent = "Refreshing...";

        // Make an AJAX call to fetch a new CAPTCHA
        fetch('<?php echo site_url('login/refresh_captcha'); ?>')
            .then(response => response.text())
            .then(data => {
                // Update the CAPTCHA image
                captchaImage.innerHTML = data;

                // Set a delay before enabling the button
                setTimeout(() => {
                    refreshButton.disabled = false;
                    refreshButton.textContent = "Refresh CAPTCHA";
                }, 2000); // 2-second delay
            })
            .catch(error => {
                console.error("Error refreshing CAPTCHA:", error);

                // Set a delay before enabling the button even if there's an error
                setTimeout(() => {
                    refreshButton.disabled = false;
                    refreshButton.textContent = "Refresh CAPTCHA";
                }, 2000); // 2-second delay
            });
    }

    // Add an event listener to call refreshCaptcha when the page loads
    window.onload = function() {
        refreshCaptcha();
    };

    let privacyPolicyAgreed = false;
let termsAndConditionsAgreed = false;

function agreePrivacyPolicy() {
    privacyPolicyAgreed = true;
    checkAgreements();
}

function agreeTermsAndConditions() {
    termsAndConditionsAgreed = true;
    checkAgreements();
}

function checkAgreements() {
    if (privacyPolicyAgreed && termsAndConditionsAgreed) {
        document.getElementById('data-privacy').checked = true;
    }
    validateForm();
}

function validateForm() {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('registration-email').value.trim();
    const password = document.getElementById('registration-password').value.trim();
    const confirmPassword = document.getElementById('confirm-password').value.trim();
    const dataPrivacyChecked = document.getElementById('data-privacy').checked;

    const passwordsMatch = password === confirmPassword;
    const isFormValid = firstName && lastName && email && password && confirmPassword && passwordsMatch && dataPrivacyChecked;
    document.getElementById('sign-up-button').disabled = !isFormValid;
}

document.getElementById('first_name').addEventListener('input', validateForm);
document.getElementById('last_name').addEventListener('input', validateForm);
document.getElementById('registration-email').addEventListener('input', validateForm);
document.getElementById('registration-password').addEventListener('input', validateForm);
document.getElementById('confirm-password').addEventListener('input', validateForm);
document.getElementById('data-privacy').addEventListener('change', validateForm);

window.onload = function() {
    refreshCaptcha();
    validateForm();
};

function showCaptchaModal() {
    $('#captchaModal').modal('show');
}

function submitForm() {
    $('#captchaModal').modal('hide');
    document.getElementById('registration-form').submit();
}

</script>

<!-- hCaptcha Modal -->
<div class="modal fade" id="captchaModal" tabindex="-1" role="dialog" aria-labelledby="captchaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="captchaModalLabel">Captcha Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="h-captcha" data-sitekey="191215ea-85e2-4c06-ae76-576ef66a7fa0"></div>
                <button type="button" class="btn btn-secondary mt-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary mt-3" onclick="submitForm()">Verify and Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Add modals for Privacy Policy and Terms and Conditions -->
<div class="modal fade" id="privacyPolicyModal" tabindex="-1" role="dialog" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyPolicyModalLabel">Privacy Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo get_frontend_settings('privacy_policy'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="agreePrivacyPolicy()">Agree</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="termsAndConditionsModal" tabindex="-1" role="dialog" aria-labelledby="termsAndConditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsAndConditionsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo get_frontend_settings('terms_and_condition'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="agreeTermsAndConditions()">Agree</button>
            </div>
        </div>
    </div>
</div>

<!-- Include hCaptcha API -->
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

