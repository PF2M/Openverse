<div class="main-column center">
    <div class="post-list-outline">
        <form method="post"><br>
        <img src="/assets/img/menu-logo.png"><br>
        <p style="font-weight: bold; font-size: 20px;">Sign Up</p>
        <p>Create an Openverse account to make posts and comments to various communities, give Yeahs to other users' content, and interact with other members of the Openverse community.</p>
        <?php
            if(isset($error) && $error !== false) {
                http_response_code(400);
                echo '<p class="red">Error: ' . $error . '</p>';
            } else {
                echo '<br>';
            }
        ?>
        <h3 class="label"><label><span class="red">*</span> Username: <input type="text" class="auth-input" name="username" maxlength="32" minlength="4" placeholder="Username"></label></h3>
        <h3 class="label"><label>Screen Name: <input type="text" class="auth-input" name="screen_name" maxlength="32" placeholder="Screen Name"></label></h3>
        <h3 class="label"><label>Nintendo Network ID: <input type="text" class="auth-input" name="user_nnid" maxlength="16" minlength="6" placeholder="NNID Username"></label></h3>
        <h3 class="label"><label><span class="red">*</span> Email Address: <input type="email" class="auth-input" name="email" maxlength="254" minlength="6" placeholder="Email"></label></h3>
        <h3 class="label"><label><span class="red">*</span> Password: <input type="password" class="auth-input" name="password" maxlength="32" minlength="6" placeholder="Password"></label></h3>
        <h3 class="label"><label><span class="red">*</span> Confirm Password: <input type="password" class="auth-input" name="confirm_password" maxlength="32" minlength="6" placeholder="Confirm Password"></label></h3><?php if(isset($open_recaptcha_public)) {?><br>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div class="g-recaptcha" style="display: inline-block;" data-sitekey="<?=$open_recaptcha_public?>"></div><?php } ?><br><br>
        <button class="button" type="submit">Create Account</button><br>
        <p>All fields with a red asterisk (<span class="red">*</span>) are required.</p>
        <p>Nintendo Network IDs are only needed to get Mii data, and can be hidden from the public.</p>
        <p>A confirmation email will be sent to the email address you provide.</p><br>
        </form>
    </div>
</div>