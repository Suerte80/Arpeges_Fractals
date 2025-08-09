<div class="fixed bottom-0 left-0 w-full bg-gray-900 text-white shadow-lg z-50">
    <p
        id="player-position"
        class="
            absolute
            top-[-10px]
            left-0
            px-2
            text-white
            text-[10px]
            z-10
        ">0:00</p>
    <input type="range" id="player-range" min="0" max="100" step="0.001"
        class="
            absolute
            top-[-8px]
            w-full
            appearance-none
        ">
    <p
        id="player-duration"
        class="
            absolute
            top-[-10px]
            px-2
            right-0
            text-white
            text-[10px]
        ">1:32</p>

    <div class="w-full px-4 py-3 grid grid-cols-1 grid-rows-3 md:grid-cols-3 md:grid-rows-1">

        <!-- Infos du morceau -->
        <div class="flex items-center justify-center space-x-4 w-full sm:w-auto mb-3 sm:mb-0 md:items-start">
            <img id="album-cover" src="https://placehold.co/60"
                alt="Jaquette"
                class="w-14 h-14 rounded-md object-cover shadow" />
            <div>
                <p id="track-title" class="text-sm font-semibold">Titre du morceau</p>
                <p id="track-artist" class="text-xs text-gray-400">Nom de l'artiste</p>
            </div>
        </div>

        <!-- Contrôles -->
        <div class="flex items-center space-x-4 w-full justify-center sm:w-auto mb-3 sm:mb-0">
            <button id="prev-btn" class="text-gray-300 hover:text-white transition text-xl">⏮️</button>
            <div id="play-pause-btn" class="sm:w-auto mb-3 sm:mb-0">
                <button id="play-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-2 rounded-full shadow transition">▶️</button>
                <button id="pause-btn" class="hidden bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-2 rounded-full shadow transition">⏸️</button>
            </div>
            <button id="next-btn" class="text-gray-300 hover:text-white transition text-xl">⏭️</button>
        </div>

        <!-- Volume -->
        <div class="flex items-center justify-center space-x-2 w-full sm:w-auto md:justify-end">
            <label for="volume" class="text-sm text-gray-400">🔊</label>
            <input id="volume" type="range" min="0" max="1" step="0.01" value="0.5"
                class="w-24" />
        </div>

    </div>
</div>