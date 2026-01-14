<template>
    <div>
        <!-- Botão Hamburger (apenas mobile) -->
        <button v-if="isMobile" class="hamburger" :class="{ active: menuOpen }" @click="toggleMenu"
            aria-label="Abrir menu">
            <i class="fa-solid fa-bars" v-if="!menuOpen"></i>
            <i class="fa-solid fa-xmark" v-if="menuOpen" style="color: white;"></i>
        </button>

        <!-- Overlay (fecha menu ao clicar) -->
        <div v-if="isMobile && menuOpen" class="menu-overlay" @click="closeMenu"></div>

        <!-- Menu Lateral -->
        <ul id="menu" v-if="isMobile" class="mobile" :class="{ open: menuOpen }">
            <li data-menuanchor="Home" class="active">
                <a href="#Home" @click="handleMenuClick">Home</a>
            </li>
            <li data-menuanchor="Countdown">
                <a href="#Countdown" @click="handleMenuClick">Countdown</a>
            </li>
            <li data-menuanchor="História">
                <a href="#História" @click="handleMenuClick">História</a>
            </li>
            <li data-menuanchor="Localização">
                <a href="#Localização" @click="handleMenuClick">Localização</a>
            </li>
            <li data-menuanchor="Confirmação">
                <a href="#Confirmação" @click="handleMenuClick">Confirmação</a>
            </li>
        </ul>

        <!-- Menu Desktop (se quiser manter o menu horizontal em desktop) -->
        <!-- <ul id="menu-desktop" v-if="!isMobile" class="desktop">
            <li data-menuanchor="Home" class="active"><a href="#Home">Home</a></li>
            <li data-menuanchor="Countdown"><a href="#Countdown">Countdown</a></li>
            <li data-menuanchor="História"><a href="#História">História</a></li>
            <li data-menuanchor="Localização"><a href="#Localização">Localização</a></li>
            <li data-menuanchor="Confirmação"><a href="#Confirmação">Confirmação</a></li>
        </ul> -->
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isMobile = ref(false);
const menuOpen = ref(false);

// Detectar tamanho da tela
const checkMobile = () => {
    isMobile.value = window.innerWidth <= 768;
    if (!isMobile.value) {
        menuOpen.value = false; // Fechar menu quando mudar para desktop
    }
};

// Toggle do menu
const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
};

// Fechar menu
const closeMenu = () => {
    menuOpen.value = false;
};

// Fechar menu ao clicar em um link
const handleMenuClick = () => {
    menuOpen.value = false;
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
/* Botão Hamburger */
.hamburger {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
    width: 50px;
    height: 50px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.hamburger span {
    display: block;
    width: 28px;
    height: 3px;
    background-color: var(--baseColor);
    border-radius: 2px;
    transition: all 0.3s ease;
}

/* Animação do hamburger quando ativo */
.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(10px, 10px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
    transform: translateX(-10px);
}

.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

/* Menu Overlay */
.menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 998;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* Menu Lateral Mobile */
#menu.mobile {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background-color: #000;
    list-style: none;
    padding: 80px 20px 20px 20px;
    margin: 0;
    z-index: 999;
    display: flex;
    flex-direction: column;
    gap: 20px;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
}

#menu.mobile.open {
    transform: translateX(0);
}

#menu.mobile li {
    display: block;
    margin: 0;
    padding: 0;
}

#menu.mobile li a {
    color: var(--baseColor);
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.2s ease;
    position: relative;
}

#menu.mobile li a:hover {
    color: var(--alternativeColor);
    background-color: rgba(205, 195, 167, 0.1);
    text-shadow: var(--shadow-size) var(--shadow-size) var(--shadow-blur) var(--shadow-color);
    padding-left: 25px;
}

#menu.mobile li.active a {
    color: var(--alternativeColor);
    text-shadow: var(--shadow-size) var(--shadow-size) var(--shadow-blur) var(--shadow-color);
    background-color: rgba(205, 195, 167, 0.1);
    padding-left: 25px;
}

</style>