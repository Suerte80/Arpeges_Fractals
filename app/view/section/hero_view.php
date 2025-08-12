<section id="hero-container"
    class="
        flex items-center justify-center
        h-screen
        p-8
        overflow-hidden
        relative

        text-lg

        before:content-['']
        before:absolute before:top-0 before:left-0 before:w-full before:h-full
        before:bg-[url('/images/fond_2.png')]
        before:bg-cover before:bg-center
        before:blur-[3px] before:brightness-[0.8] before:contrast-[1.2]
        before:z-0
        before:bg-fixed
    ">

    <div class="
    math-girl
    absolute left-0 top-1/2
    -translate-y-1/2
    translate-x-1/2
    z-10
    pointer-events-none select-none
">
        <img
            class="
                hidden md:block
                rotate-y-180
                md:visible
            "
            src="images/math_rock_girl.png"
            alt="Image de la mascotte du site." width="160" height="200" class="max-w-xs" />
        <div class="particles-container"></div>
    </div>

    <div id="central-hero"
        class="
        flex flex-col items-center justify-center
        text-center
        text-[var(--text)]
        z-10
        max-w-lg
        mx-auto
        space-y-4
    ">
        <img id="logo" src="images/logo_164X194.png" alt="Image du logo">
        <h2>Arpèges Fractals</h2>
        <p>Explorations math rock: riffs angulaires, rythmes tordus et beauté géométrique du son.</p>
        <div id="arrow" aria-hidden="true" role="presentation">&#x2193;</div>
    </div>

    <div class="
        hidden md:block
        math-girl
        absolute right-0 top-1/2
        -translate-y-1/2
        -translate-x-1/2
        z-10
        pointer-events-none select-none
    ">
        <img src="images/math_rock_girl.png"
            alt="Image de la mascotte du site."
            width="160" height="200"
            class="max-w-xs" />
        <div class="particles-container"></div>
    </div>

    <div class="wave-bottom">
        <svg viewBox="0 0 1440 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <defs>
                <linearGradient id="fade-to-black" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#0D0D0D" stop-opacity="1" />
                    <stop offset="100%" stop-color="#0D0D0D" stop-opacity="1" />
                </linearGradient>
            </defs>
            <path class="wave-path"
                d="M0,60 C180,140 360,0 540,80 C720,160 900,20 1080,90 C1260,160 1440,40 1440,40 L1440,150 L0,150 Z"
                fill="url(#fade-to-black)" />
        </svg>
    </div>
</section>