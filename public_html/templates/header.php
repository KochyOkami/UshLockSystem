<header>
    <!-- Sign In and Login Buttons -->
    <div style="display:flex;justify-content: space-between;width: 100%;">
        <div class='left'>
            <a href='/index' class='login-button'>Accueil</a>
            <?php
            if (array_key_exists('username', $_SESSION)) {
                echo "<a href=\"/profile\" class='login-button'>Profile</a>";
            }?>
        </div>
        <div class='right'>
            <?php if (!array_key_exists('username', $_SESSION)) {
                echo "<a href='login' class='login-button'>Se connecter</a>
                      <a href='register' class='sign-in-button'>S'inscrire</a>";
            }?>
        </div>
    </div>
</header>