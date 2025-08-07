<div id="container-player"
    class="fixed bottom-0 left-0 w-full bg-gray-900 text-white shadow-lg z-50">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between flex-wrap sm:flex-nowrap">

        <!-- Infos du morceau -->
        <div class="flex items-center space-x-4 w-full sm:w-auto mb-3 sm:mb-0">
            <img id="album-cover" src="https://placehold.co/60"
                alt="Jaquette"
                class="w-14 h-14 rounded-md object-cover shadow transition duration-300 ring-2 ring-green-500 animate-pulse" />
            <div>
                <p id="track-title" class="text-sm font-semibold">Titre du morceau</p>
                <p id="track-artist" class="text-xs text-gray-400">Nom de l'artiste</p>
            </div>
        </div>

        <!-- Contrôles -->
        <div class="flex items-center space-x-4 w-full justify-center sm:w-auto mb-3 sm:mb-0">
            <button id="prev-btn" class="text-gray-300 hover:text-white transition text-xl">⏮️</button>
            <div id="play-pause-btn" class="sm:w-auto mb-3 sm:mb-0">
                <button id="play-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-2 rounded-full">▶️</button>
                <button id="pause-btn" class="bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-2 rounded-full shadow transition animate-pulse">⏸️</button>
            </div>
            <button id="next-btn" class="text-gray-300 hover:text-white transition text-xl">⏭️</button>
        </div>

        <!-- Volume -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <label for="volume" class="text-sm text-gray-400">🔊</label>
            <input id="volume" type="range" min="0" max="1" step="0.01" value="0.5"
                class="accent-green-500 w-24" />
        </div>

    </div>
</div>