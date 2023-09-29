<?php session_start();   ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="shortcut icon" href="img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>
   <!-- Header -->
   <?php include_once("templates/header.php"); ?>
   
    <!-- Main Content -->
    <div class="box">
        <div class="container">
            <h1>Inscription à LockSystem</h1>
            <form action="includes/register_process.php" method="POST">
                <p><?php if (array_key_exists('error_message', $_SESSION)) {
                        echo $_SESSION['error_message'];
                    } ?></p>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur
                        <span id="username-status" style="font-style: italic; color:red;"></span></label>
                    <input style="margin-bottom:21px;" type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe de nouveau</label>
                    <div class="password-input-container">
                        <input type="password" id="password2" name="password2" required>
                        <span class="toggle-password">
                            <i class="fas fa-eye" id="togglePassword2"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="registerButton">S'inscrire</button>
            </form>
        </div>
    </div>
</body>

</html>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            togglePassword.classList.toggle("active");
        });

        const passwordInput2 = document.getElementById("password2");
        const togglePassword2 = document.getElementById("togglePassword2");

        togglePassword2.addEventListener("click", function() {
            const type = passwordInput2.getAttribute("type") === "password" ? "text" : "password";
            passwordInput2.setAttribute("type", type);
            togglePassword2.classList.toggle("active");
        });

        const passwordField1 = document.getElementById("password");
        const passwordField2 = document.getElementById("password2");
        const usernameInput = document.getElementById("username");
        const registerButton = document.getElementById("registerButton");
        const usernameStatus = document.getElementById("username-status");

        passwordField1.addEventListener("input", checkPasswordsMatch);
        passwordField2.addEventListener("input", checkPasswordsMatch);
        usernameInput.addEventListener("input", checkUsername);

        function checkPasswordsMatch() {
            const password1 = passwordField1.value;
            const password2 = passwordField2.value;

            if (password1 === password2) {
                passwordField1.style.borderColor = "green";
                passwordField2.style.borderColor = "green";
            } else {
                passwordField1.style.borderColor = "red";
                passwordField2.style.borderColor = "red";
            }

            checkFormValidity();
        }

        function checkUsername() {
            const username = usernameInput.value;

            // Make a POST request to the PHP script to check the username
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "includes/check_username.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText;
                    if (response === "taken") {
                        usernameInput.style.borderColor = "red";
                        usernameStatus.innerText = "Déjà utilisé !";
                    } else if (response === "available") {
                        usernameInput.style.borderColor = "green";
                        usernameStatus.innerText = "";
                    } else {
                        console.log(response);
                        usernameInput.style.borderColor = "red";
                        usernameStatus.innerText = "Erreur :/";
                    }
                    checkFormValidity();
                }
            };
            xhr.send("username=" + username);
        }

        function checkFormValidity() {
            const isUsernameValid = usernameInput.style.borderColor === "green";
            const isPasswordValid = passwordField1.style.borderColor === "green" && passwordField2.style.borderColor === "green";

            if (isUsernameValid && isPasswordValid) {
                registerButton.disabled = false;
            } else {
                registerButton.disabled = true;
            }
        }
    });
</script>