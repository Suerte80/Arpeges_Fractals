<nav id="sec_navbar" class="navbar" aria-label="navbar">
    <div class="nav-title">
        <a href="/" aria-label="Page d’accueil"><img src="/images/logo_40x40.png" alt="Logo du site"></a>
        <h1>Arpèges Fractals</h1>
    </div>

    <div id="link">
        <?php if (isset($_SESSION['user-is-connected'])): ?>
            <div class="dropdown">
                <button class="dropbtn">Mon compte ▾</button>
                <div class="dropdown-content">
                    <a href="/profile">Profil</a>
                    <?php if (isset($_SESSION['user-role']) && ($_SESSION['user-role'] == 'creator' || $_SESSION['user-role'] == 'admin')): ?>
                        <a href="/create-article">Créer un article</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user-role']) && $_SESSION['user-role'] == 'admin'): ?>
                        <a href="/admin/user/panel">Panneau d'administration</a>
                    <?php endif; ?>

                    <a href="/logout">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/login">Se connecter</a>
            <a href="/signup">S'inscrire</a>
        <?php endif; ?>

        <a href="/articles">Articles</a>
        <a href="/contact">Contact</a>
    </div>
</nav>