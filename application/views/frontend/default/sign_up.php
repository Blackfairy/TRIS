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
                    <?php echo get_phrase('register_yourself'); ?>
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
                <div class="user-dashboard-content w-100 login-form hidden">
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
                    <div class="user-dashboard-content w-100 register-form">
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
                                            onkeyup="validatePasswordStrength()"
                                            required
                                        >
                                        <input
                                            type="checkbox"
                                            id="toggle-registration-password"
                                            onclick="togglePassword('registration-password')">
                                        Show Password
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
                                    <div class="form-group">
                                        <input type="checkbox" id="data-privacy" name="data_privacy" required>
                                        <label for="data-privacy">
                                            I agree to the 
                                            <a href="<?php echo site_url('home/privacy_policy'); ?>"target="_blank">Data Privacy</a> 
                                            and 
                                            <a href="<?php echo site_url('home/terms_and_condition'); ?>"  target="_blank">Terms and Conditions</a>.
                                        </label>
                                    </div>

                                    <!-- Enhanced Password Validation Messages -->
                                    <div class="form-group">
                                    <div id="message" style="margin-top: 10px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9;">
                                        <h4 style="margin-bottom: 10px;">Password must contain:</h4>
                                        <ul style="list-style-type: none; padding: 0;">
                                            <li id="letter" class="invalid" style="margin-bottom: 5px;">
                                                <i class="fas fa-times-circle" style="color: red;"></i> A <b>lowercase</b> letter
                                            </li>
                                            <li id="capital" class="invalid" style="margin-bottom: 5px;">
                                                <i class="fas fa-times-circle" style="color: red;"></i> A <b>uppercase</b> letter
                                            </li>
                                            <li id="number" class="invalid" style="margin-bottom: 5px;">
                                                <i class="fas fa-times-circle" style="color: red;"></i> A <b>number or special character</b>
                                            </li>
                                            <li id="length" class="invalid" style="margin-bottom: 5px;">
                                                <i class="fas fa-times-circle" style="color: red;"></i> Minimum <b>16 characters</b>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-update-box">
                                <button type="submit" class="btn"><?php echo get_phrase('sign_up'); ?></button>
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

</style>


<!-- Add JavaScript for password validation and toggle password visibility -->
<script>
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


</script>
<script>
function refreshCaptcha() {
    const refreshButton = document.getElementById('refresh-captcha');
    const captchaImage = document.getElementById('captcha-image');

    // Disable the button temporarily to prevent spamming
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


document.querySelector('form').addEventListener('submit', function (e) {
    const dataPrivacyCheckbox = document.getElementById('data-privacy');
    if (!dataPrivacyCheckbox.checked) {
        e.preventDefault();
        alert('You must agree to the Data Privacy and Terms and Conditions before proceeding.');
    }
});

</script>

