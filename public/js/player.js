fetch('/api/token')
    .then(res => res.json())
    .then(({ token }) => {
        window.onSpotifyWebPlaybackSDKReady = async () => {
            const player = new Spotify.Player({
            name: 'Lecteur Custom',
            getOAuthToken: cb => { cb(token); },
            volume: 0.5
            });

            player.addListener('ready', ({ device_id }) => {
            console.log('Lecteur prêt :', device_id);

            // Transfert de lecture sur ce device
            fetch('https://api.spotify.com/v1/me/player/play', {
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
            });

            console.log('Player en cours de chargement ...');

            player.addListener('ready', ({device_id}) => {
                playPlaylist(device_id);
                retrieveMusicData();
            });

            player.connect();

            const playPauseBtn = document.getElementById('play-pause-btn');
            const playBtn = document.getElementById('play-btn');
            const pauseBtn = document.getElementById('pause-btn');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const volumeRane = document.getElementById('volume');
            var isPlaying = false;

            pauseBtn.classList.toggle('hidden');
            
            playPauseBtn.addEventListener('click', () => {
                if(isPlaying){
                    player.pause();
                } else{
                    player.resume();
                }

                playBtn.classList.toggle('hidden');
                pauseBtn.classList.toggle('hidden');
                isPlaying = !isPlaying;
            });

            prevBtn.onclick = () => {player.previousTrack(); retrieveMusicData();}
            nextBtn.onclick = () => {player.nextTrack(); retrieveMusicData();}
            volumeRane.oninput = (e) => player.setVolume(parseFloat(e.target.value));
        };
    })
    .catch(err => {
        console.error('Erreur lors de la récupération du token :', err.message);
    });
    console.log('Chargement du SDK Spotify...');

async function retrieveMusicData()
{
    await fetch('/api/track')
    .then(res => res.json())
    .then(data => {
        if(data.error) return;

        document.getElementById('album-cover').src = data.image;
        document.getElementById('track-title').innerText = data.title;
        document.getElementById('track-artist').innerText = data.artist;
    });
}

async function playPlaylist(device_id)
{
    // https://open.spotify.com/playlist/37i9dQZF1DZ06evO3mYWcg?si=c456bfa276974f0f
    fetch('/api/playPlaylist?uri=spotify:playlist:37i9dQZF1DZ06evO3mYWcg&device_id=' + device_id)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
        console.log('Playlist lancée ✅');
        } else {
        console.error('Erreur Spotify :', data.error);
        }
    });
}