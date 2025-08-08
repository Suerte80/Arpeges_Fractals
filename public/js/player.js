console.log('Chargement du SDK Spotify...');

// Cette fonction est appelée quand le SDK est prêt
window.onSpotifyWebPlaybackSDKReady = async () => {
    try {
        // Récupération du token depuis ton backend
        const { token, expires_at } = await fetch('/api/token').then(res => res.json());
        if (!token) throw new Error("Aucun token reçu du serveur");

        const player = new Spotify.Player({
            name: 'Lecteur Custom',
            getOAuthToken: cb => cb(token),
            volume: 0.5
        });

        // Quand le lecteur est prêt
        player.addListener('ready', async ({ device_id }) => {
            console.log('Lecteur prêt :', device_id);

            // Transfert de la lecture sur ce device
            await fetch('https://api.spotify.com/v1/me/player', {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    device_ids: [device_id],
                    play: true
                })
            });

            // Lancer la playlist
            /*await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${device_id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    context_uri: 'spotify:playlist:37i9dQZF1DZ06evO3mYWcg'
                })
            });*/

            // On va rafraichir l'access_token 1 minute avant l'expiration pour évité que la musique ne coupe.
            const expiresAtMinus1Min = expires_at - (new Date()).getTime() - 60;
            console.log('Expires_at: ', expires_at);
            if( expiresAtMinus1Min > 0 )
                setTimeout(async () => {
                    const res = await getAccessToken(); // J'attend que la fonction soit fini pour lancer le minuteurs de 50 minutes
                    if(!res)
                        return; // Erreur
                    setInterval(
                        getAccessToken(),
                        50 * 60 * 1000
                    );
                }, expiresAtMinus1Min); // On lance un timeout dans expires_at - 1 minutes
            else // Si l'access token doit être rafraichie avant 1 minutes.
                getAccessToken();

            await retrieveMusicData();
        });

        // Événement sur changement de lecture
        player.addListener('player_state_changed', state => {
            if (!state) return;
            const isPlaying = !state.paused;
            document.getElementById('play-btn').classList.toggle('hidden', isPlaying);
            document.getElementById('pause-btn').classList.toggle('hidden', !isPlaying);
        });

        player.connect();

        // --- Gestion des boutons ---
        const playPauseBtn = document.getElementById('play-pause-btn');
        const playBtn = document.getElementById('play-btn');
        const pauseBtn = document.getElementById('pause-btn');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const volumeRange = document.getElementById('volume');
        const slider = document.getElementById("player-range");
        const playerDuration = document.getElementById('player-duration');

        // élément d'affichage Cover, titre, Nom artiste
        const cover = document.getElementById('album-cover');
        const track = document.getElementById('track-title');
        const artist = document.getElementById('track-artist');

        let playerState = { 
            isPlaying: false,
            duration: 0,
            position: 0
        };

        pauseBtn.classList.add('hidden'); // on commence avec Play affiché

        /**
         * Partie du code ou l'ont ajoute les Listeners au player !
         */

        // on ajoute un event 
        player.addListener('player_state_changed', ({
            paused,
            position,
            duration,
            track_window: { current_track }
        }) => {
            // Mise a jour du playerState
            playerState.isPlaying = paused;
            playerState.duration = duration;
            playerState.position = position;

            // mise a jour de la durée du morceau
            const durationInSeconds = duration / 1000;
            const minutes = parseInt(durationInSeconds / 60);
            const secondsRemaining = parseInt(durationInSeconds % 60);
            playerDuration.textContent = minutes + ':' + ((secondsRemaining<10)?'0'+secondsRemaining:secondsRemaining);

            // Mise a jour du slider
            slider.value = ( position / duration ) * 100;

            // Mise à jour du titre et de l'artiste
            if(current_track){
                cover.src = current_track.album.images[0].url;
                track.innerText = current_track.name;
                artist.innerText = current_track.artists.name;
            }
        });

        // Ici on gère le cas ou l'access token est invalide donc le refresh sur le backend !
        player.addListener('authentication_error', () => {
            const accessToken = getAccessToken();
            player.setAccessToken(accessToken);
        });

        playPauseBtn.addEventListener('click', async () => {
            await player.activateElement();

            await player.togglePlay();
        });

        prevBtn.onclick = async () => {
            await player.previousTrack();
            //await retrieveMusicData();
        };

        nextBtn.onclick = async () => {
            await player.nextTrack();
            //await retrieveMusicData();
        };

        // Ajout de l'animation du slider.
        setInterval((playerState, slider) => {
            let state = playerState.isPlaying;

            playerState.position += 1000;

            if(!state){
                slider.value = ((playerState.position) / playerState.duration) * 100;
            }
        }, 1000, playerState, slider);

        volumeRange.oninput = e => player.setVolume(parseFloat(e.target.value));

    } catch (err) {
        console.error('Erreur lors de l’initialisation Spotify :', err.message);
    }
};

async function retrieveMusicData() {
    try {
        const data = await fetch('/api/track').then(res => res.json());
        if (data.error) return;

        document.getElementById('album-cover').src = data.image;
        document.getElementById('track-title').innerText = data.title;
        document.getElementById('track-artist').innerText = data.artist;
    } catch (err) {
        console.error('Erreur lors de la récupération des infos du morceau :', err.message);
    }
}

// TODO A faire 
async function sliderAnimation(){

}

async function getAccessToken(){
    return await fetch('/api/refreshToken')
    .then(data => data.json())
    .then(data => () => {
        // Si il y a une erreur de connexion ou autre on va a la page index.
        if(data.error){
            window.location = '/';
            return null;
        }

        return data.access_token;
    })
    .catch(err => {
        console.error("Erreur: ", err);
        return null;
    });
}