fetch('/api/token')
    .then(res => res.json())
    .then(({ token }) => {
        window.onSpotifyWebPlaybackSDKReady = () => {
            const player = new Spotify.Player({
            name: 'Lecteur Custom',
            getOAuthToken: cb => { cb(token); },
            volume: 0.5
            });

            player.addListener('ready', ({ device_id }) => {
            console.log('Lecteur prêt :', device_id);

            // Transfert de lecture sur ce device
            fetch('https://api.spotify.com/v1/me/player', {
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

            player.connect();

            const playPauseBtn = document.getElementById('play-pause-btn');
            const playBtn = document.getElementById('play-btn');
            const pauseBtn = document.getElementById('pause-btn');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const volumeRane = document.getElementById('volume');
            var isPlaying = false;
            
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

            prevBtn.onclick = () => player.previousTrack();
            nextBtn.onclick = () => player.nextTrack();
            volumeRane.oninput = (e) => player.setVolume(parseFloat(e.target.value));

        };
    })
    .catch(err => {
        console.error('Erreur lors de la récupération du token :', err.message);
    });
    console.log('Chargement du SDK Spotify...');