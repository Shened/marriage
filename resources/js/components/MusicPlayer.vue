<template>
    <div class="music-player">
        <!-- Botão de Controle de Música -->
        <button class="music-toggle" :class="{ playing: isPlaying, padding: !isPlaying }" @click="toggleMusic"
            :title="isPlaying ? 'Pausar música' : 'Reproduzir música'" aria-label="Controle de música">
            <!-- Ícone quando tocando -->
            <span v-if="isPlaying" class="music-icon">
                <i class="fas fa-pause"></i>
            </span>
            <!-- Ícone quando parado -->
            <span v-else class="music-icon">
                <i class="fas fa-play"></i>
            </span>
        </button>

        <!-- Tooltip -->
        <div v-if="showTooltip" class="music-tooltip">
            {{ isPlaying ? 'Pausar' : 'Reproduzir' }}
        </div>

        <!-- Audio Tag (oculta) -->
        <audio ref="audioPlayer" :src="musicUrl" loop @play="onAudioPlay" @pause="onAudioPause"
            @error="handleError"></audio>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const audioPlayer = ref(null);
const isPlaying = ref(false);
const showTooltip = ref(false);

// URL da sua música (coloque aqui o caminho da sua música)
const musicUrl = ref('/music/still_falling.mp3');

// Alternar reprodução
const toggleMusic = () => {
    if (!audioPlayer.value) {
        console.error('Audio player não está disponível');
        return;
    }

    try {
        if (isPlaying.value) {
            audioPlayer.value.pause();
        } else {
            const playPromise = audioPlayer.value.play();

            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.error('Erro ao reproduzir áudio:', error);
                    isPlaying.value = false;
                });
            }
        }
    } catch (error) {
        console.error('Erro ao controlar áudio:', error);
    }
};

// Evento quando áudio começa
const onAudioPlay = () => {
    isPlaying.value = true;
    localStorage.setItem('musicPlaying', 'true');
};

// Evento quando áudio pausa
const onAudioPause = () => {
    isPlaying.value = false;
    localStorage.setItem('musicPlaying', 'false');
};

// Tratamento de erro
const handleError = (error) => {
    console.error('Erro ao carregar a música:', error);
    console.error('Verifique se o arquivo está em:', musicUrl.value);
    isPlaying.value = false;
};

// Mostrar tooltip
const showMusicTooltip = () => {
    showTooltip.value = true;
    setTimeout(() => {
        showTooltip.value = false;
    }, 2000);
};

// Inicializar
onMounted(() => {
    if (!audioPlayer.value) {
        console.error('Audio element não encontrado');
        return;
    }
    if (audioPlayer.value) {
        audioPlayer.value.volume = 0.3 // 0.0 = mudo, 1.0 = máximo
    }

    // Restaurar estado anterior (se ativado antes)
    const wasMusicPlaying = localStorage.getItem('musicPlaying') === 'true';

    if (wasMusicPlaying) {
        setTimeout(() => {
            toggleMusic();
        }, 500);
    }

    console.log('🎵 Music Player pronto.');
});

onUnmounted(() => {
    if (audioPlayer.value) {
        audioPlayer.value.pause();
    }
});
</script>

<style scoped>
:root {
    --baseColor: #CDC3A7;
    --secondaryColor: #BEAE7F;
    --alternativeColor: #8C7B5C;
}

.music-player {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 500;
}

.music-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #F0E1D8 0%, #ecd2c2 100%);
    color: #0C2452;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: all 0.3s ease;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
    position: relative;
    overflow: hidden;
}

.music-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(205, 195, 167, 0.5);
}

.music-toggle:active {
    transform: scale(0.95);
}

/* Animação pulsante quando tocando */
.music-toggle.playing {
    animation: pulse 1.5s ease-in-out infinite;
}

.music-toggle.padding {
    padding-left: 3px;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {

    0%,
    100% {
        box-shadow: 0 4px 15px rgba(205, 195, 167, 0.3);
    }

    50% {
        box-shadow: 0 4px 25px rgba(205, 195, 167, 0.6);
    }
}

/* Animação do ícone */
.music-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    animation: iconFadeIn 0.3s ease;
}

@keyframes iconFadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Tooltip */
.music-tooltip {
    position: absolute;
    bottom: 70px;
    right: 0;
    background-color: rgba(0, 0, 0, 0.8);
    color: var(--baseColor);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    animation: tooltipFadeIn 0.3s ease;
    z-index: 501;
}

@keyframes tooltipFadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsivo para mobile */
@media (max-width: 480px) {
    .music-player {
        bottom: 20px;
        right: 20px;
    }

    .music-toggle {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 360px) {
    .music-player {
        bottom: 15px;
        right: 15px;
    }

    .music-toggle {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
}
</style>