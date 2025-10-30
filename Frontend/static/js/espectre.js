// --- 1. Variáveis e Inicialização do Espectro ---
const API_TOKEN_URL = 'http://127.0.0.1:8131/spotify/my/access-token'; // O novo endpoint
const dummyAudio = document.getElementById('dummy-audio-source');

// Configuração do Canvas (Espectro)
const canvas = document.getElementById('audio-visualizer');
const canvasContext = canvas.getContext('2d');
const audioContext = new (window.AudioContext || window.webkitAudioContext)();
const analyser = audioContext.createAnalyser();

let animationFrameId = null;

// Configuração do AnalyserNode
analyser.fftSize = 256; 
const bufferLength = analyser.frequencyBinCount;
const dataArray = new Uint8Array(bufferLength);

// Conexão: Dummy Audio -> Analisador -> Saída (Muda)
const source = audioContext.createMediaElementSource(dummyAudio);
source.connect(analyser);
analyser.connect(audioContext.destination); // Conecta à saída de áudio

// --- 2. Função de Desenho do Espectro (Visualizador) ---
function draw() {
    animationFrameId = requestAnimationFrame(draw); 

    analyser.getByteFrequencyData(dataArray);
    canvasContext.clearRect(0, 0, canvas.width, canvas.height);

    const barWidth = (canvas.width / bufferLength) * 0.8;
    let x = 0;

    for (let i = 0; i < bufferLength; i++) {
        const barHeight = dataArray[i];
        
        // Cor reativa (exemplo)
        canvasContext.fillStyle = `rgb(${barHeight + 50}, 50, 200)`; 
        
        // Desenha a barra do rodapé para cima
        canvasContext.fillRect(x, canvas.height - barHeight, barWidth, barHeight);

        x += barWidth + 2;
    }
}

// --- 3. Inicialização do Spotify Web Playback SDK ---

async function initializeSpotifyPlayer() {
    try {
        // Puxa o Access Token atualizado do seu backend
        const tokenResponse = await fetch(API_TOKEN_URL, {
            method: 'GET',
            credentials: 'include'
        });
        
        if (!tokenResponse.ok) {
            throw new Error('Falha ao obter token de acesso.');
        }

        const data = await tokenResponse.json();
        const spotifyAccessToken = data.access_token;

        // Verifica se a SDK está pronta (função obrigatória do Spotify)
        window.onSpotifyWebPlaybackSDKReady = () => {
            const player = new Spotify.Player({
                name: 'Seu Player Espectro',
                getOAuthToken: cb => { cb(spotifyAccessToken); },
                volume: 0.5
            });

            // 3.1. Sincronização e Controle
            player.addListener('player_state_changed', state => {
                const isPlaying = !state.paused;

                if (isPlaying) {
                    // Inicia o contexto de áudio na interação do usuário (necessário no Chrome)
                    if (audioContext.state === 'suspended') {
                        audioContext.resume();
                    }
                    // SINCRONIZAÇÃO: Inicia o player dummy para alimentar o espectro
                    dummyAudio.play();
                    // Inicia o loop de desenho
                    if (!animationFrameId) {
                        draw();
                    }
                    
                    // Aqui você atualizaria sua UI com: 
                    // state.track_window.current_track.name etc.
                    
                } else {
                    // Pausa ambos
                    dummyAudio.pause();
                    if (animationFrameId) {
                        cancelAnimationFrame(animationFrameId);
                        animationFrameId = null;
                    }
                }
            });

            // Conecta o player
            player.connect();
        };

    } catch (error) {
        console.error('Erro de Inicialização do Spotify:', error);
        // Exibir mensagem de erro ao usuário na UI
    }
}

window.addEventListener('load', initializeSpotifyPlayer);