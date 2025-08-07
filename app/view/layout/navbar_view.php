<nav
    id="sec_navbar"
    aria-label="navbar"
    class="
        /* Pour le mobile */
        fixed top-0 left-0 right-0 
        bg-[var(--primary)]
        grid grid-cols-1 grid-rows-2
        items-center justify-center
        p-2
        z-[900] 
        text-[var(--text)]
        text-lg

        /* Pour la tablette */
        md:grid-cols-2 md:grid-rows-1

        /* Pour le desktop */
    ">
    <div class="
        flex flex-row items-center justify-start
    ">
        <a href="/" aria-label="Page d’accueil">
            <img src="/images/logo_40x40.png" alt="Logo du site" class="h-10 w-10" />
        </a>
        <h1 class="font-bold text-lg">Arpèges Fractals</h1>
    </div>

    <div id="link" class="
        flex items-center justify-center
        gap-4

        md:justify-end
    ">

        <?php if (isset($_SESSION['user-is-connected'])): ?>
            <div class="relative group">
                <button id="dropdown-btn" class="px-4 py-2 bg-transparent text-[var(--text)] rounded hover:bg-[var(--primary-hover)]">
                    Mon compte ▾
                </button>
                <div id="dropdown-content" class="hidden absolute mt-2 w-48 bg-white rounded shadow-lg">
                    <a href="/profile" class="block px-4 py-2 text-black hover:bg-gray-100">Profil</a>
                    <?php if (isset($_SESSION['user-role']) && ($_SESSION['user-role'] == 'creator' || $_SESSION['user-role'] == 'admin')): ?>
                        <a href="/create-article" class="block px-4 py-2 text-black hover:bg-gray-100">Créer un article</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user-role']) && $_SESSION['user-role'] == 'admin'): ?>
                        <a href="/admin/user/panel" class="block px-4 py-2 text-black hover:bg-gray-100">Panneau d'administration</a>
                    <?php endif; ?>

                    <a href="/logout" class="block px-4 py-2 text-black hover:bg-gray-100">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/login" class="px-4 py-2 hover:underline">Se connecter</a>
        <?php endif; ?>

        <a href="/articles" class="px-4 py-2 hover:underline">Articles</a>
        <a href="/contact" class="px-4 py-2 hover:underline">Contact</a>
    </div>
</nav>