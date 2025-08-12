<!-- ========== HEADER ========== -->
<header class="z-50 w-full py-4 bg-[var(--primary)]/80 backdrop-blur-md text-[var(--text)] lg:overflow-visible">
    <nav class="relative mx-auto w-full max-w-7xl px-4 md:px-6 lg:px-8 flex items-center flex-wrap lg:grid lg:grid-cols-12 lg:gap-4 lg:flex-none lg:overflow-visible">
        <!-- Logo -->
        <div class="lg:col-span-3 flex items-center gap-2">
            <a href="/" aria-label="Arpèges Fractals"
                class="flex-none rounded-xl text-xl inline-flex items-center gap-3 font-semibold focus:outline-hidden focus:opacity-80">
                <img src="/images/logo_40x40.png" alt="Logo du site" class="h-10 w-10 rounded-md" />
                <span class="tracking-wide">Arpèges Fractals</span>
            </a>
        </div>

        <!-- ===== Desktop: liens centraux (toujours visibles) ===== -->
        <div class="hidden lg:flex lg:col-span-6 justify-center items-center gap-x-8">
            <a href="/articles" class="group relative inline-block text-white/90 hover:text-white transition">
                Articles
                <span class="pointer-events-none absolute -bottom-1 left-0 h-0.5 w-0 bg-teal-300 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/contact" class="group relative inline-block text-white/90 hover:text-white transition">
                Contact
                <span class="pointer-events-none absolute -bottom-1 left-0 h-0.5 w-0 bg-teal-300 transition-all duration-300 group-hover:w-full"></span>
            </a>
        </div>

        <!-- ===== Droite: Profil (desktop) + Burger (mobile) ===== -->
        <div class="ms-auto lg:ps-6 lg:order-3 lg:col-span-3 flex items-center gap-x-2">

            <?php if (!isset($_SESSION['user-is-connected']) || !$_SESSION['user-is-connected']): ?>
                <!-- Desktop: Se connecter -->
                <a href="/login"
                    class="hidden lg:inline-flex py-2 px-3 items-center text-sm font-medium rounded-lg
              bg-white/10 hover:bg-white/15 text-white focus:outline-hidden transition">
                    Se connecter
                </a>
            <?php else: ?>
                <!-- Desktop: Dropdown profil (markup Preline correct) -->
                <div class="hs-dropdown [--scope:window] relative hidden lg:inline-flex z-[70]">
                    <!-- Trigger -->
                    <button type="button" id="profile-menu-button"
                        class="hs-dropdown-toggle inline-flex items-center gap-2 py-2 px-3 rounded-lg
                     hover:bg-white/15 text-white focus:outline-hidden transition"
                        aria-haspopup="menu" aria-expanded="false" aria-label="Profil">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                            <img src="<?= $_SESSION['user-profil-image'] ?>" alt="Image de profil">
                        </span>
                        <span class="text-sm"><?= $_SESSION['user-username'] ?? 'undefined' ?></span>
                        <svg class="size-4 opacity-80 hs-dropdown-open:rotate-180 transition-transform"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    <!-- Menu -->
                    <div id="profile-menu"
                        class="hs-dropdown-menu hidden mt-2 min-w-56 rounded-xl bg-white text-gray-800 shadow-md p-2
                  dark:bg-neutral-900 dark:text-neutral-200
                  transition-[opacity,transform] duration-200
                  opacity-0 -translate-y-1 hs-dropdown-open:opacity-100 hs-dropdown-open:translate-y-0"
                        role="menu" aria-labelledby="profile-menu-button">
                        <a href="/profile" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-800" role="menuitem">
                            Profil
                        </a>

                        <?php if (isset($_SESSION['user-role']) && ($_SESSION['user-role'] === 'creator' || $_SESSION['user-role'] === 'admin')): ?>
                            <a href="/create-article" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-800" role="menuitem">
                                Créer un article
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user-role']) && $_SESSION['user-role'] === 'admin'): ?>
                            <a href="/admin/user/panel" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-800" role="menuitem">
                                Panneau d'administration
                            </a>
                        <?php endif; ?>

                        <div class="my-2 h-px bg-gray-100 dark:bg-neutral-800"></div>

                        <a href="/logout" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-800" role="menuitem">
                            Déconnexion
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Bouton burger (mobile) -->
            <button
                id="hs-nav-toggle"
                type="button"
                class="lg:hidden inline-flex items-center justify-center rounded-lg p-2
                text-white/90 hover:text-white hover:bg-white/10 focus:outline-hidden
                hs-collapse-toggle"
                aria-controls="mobile-nav"
                aria-expanded="false"
                aria-label="Ouvrir la navigation"
                data-hs-collapse="#mobile-nav">
                <span class="sr-only">Ouvrir le menu</span>
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>


        <!-- ===== Mobile: menu sous le header (inline) ===== -->
        <div
            id="mobile-nav"
            class="hs-collapse hidden w-full basis-full  <!-- 👈 force la ligne suivante + pleine largeur -->
         overflow-hidden transition-[height] duration-300
         lg:hidden"
            aria-labelledby="hs-nav-toggle">

            <nav class="mt-4 pt-3 border-t border-white/10 flex flex-col gap-y-2">
                <a href="/articles" class="inline-block text-white/90 hover:text-white transition">Articles</a>
                <a href="/contact" class="inline-block text-white/90 hover:text-white transition">Contact</a>

                <?php if (!isset($_SESSION['user-is-connected']) || !$_SESSION['user-is-connected']): ?>
                    <div class="border-t border-white/10 my-2"></div>
                    <a href="/login" class="inline-block text-white/90 hover:text-white transition">Se connecter</a>
                <?php else: ?>
                    <div class="border-t border-white/10 my-2"></div>
                    <a href="/profile" class="inline-block text-white/90 hover:text-white transition">Profil</a>
                    <?php if (isset($_SESSION['user-role']) && ($_SESSION['user-role'] === 'creator' || $_SESSION['user-role'] === 'admin')): ?>
                        <a href="/create-article" class="inline-block text-white/90 hover:text-white transition">Créer un article</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user-role']) && $_SESSION['user-role'] === 'admin'): ?>
                        <a href="/admin/user/panel" class="inline-block text-white/90 hover:text-white transition">Panneau d'administration</a>
                    <?php endif; ?>
                    <a href="/logout" class="inline-block text-white/90 hover:text-white transition">Déconnexion</a>
                <?php endif; ?>
            </nav>
        </div>


    </nav>
</header>
<!-- ========== END HEADER ========== -->