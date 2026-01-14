<template>
  <v-app id="wedding-app">
    <!-- Menu Desktop -->
    <v-app-bar app flat color="transparent" :class="{ 'scrolled': isScrolled }" class="wedding-navbar" elevation="0">
      <v-container>
        <v-row align="center" justify="center">
          <v-col cols="auto">
            <nav class="navbar-links">
              <a v-for="link in navLinks" :key="link.id" :href="`#${link.id}`" @click.prevent="scrollToSection(link.id)"
                :class="{ active: activeSection === link.id }">
                {{ link.name }}
              </a>
            </nav>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <!-- Music Player -->
    <MusicPlayer />

    <!-- Menu Mobile -->
    <MobileMenu v-if="isMobile" />

    <v-main>
      <section id="home" class="hero-section">
        <div class="hero-background">
          <v-img src="/images/home.jpeg" cover class="hero-image">
            <div class="hero-overlay"></div>
          </v-img>
        </div>
      </section>
    </v-main>
    <!-- Hero Section -->
    <div class="section" :style="{ transform: `translateY(${parallax1}px)` }">
      <div class="hero-content">
        <!-- Decoração Superior -->
        <div class="floral-decoration floral-top-decoration">
          <img src="/images/top_flower.jpeg" alt="Decoração floral superior" class="floral-image floral-top"
            loading="lazy" />
        </div>

        <!-- Conteúdo Hero -->
        <h2 class="hero-subtitle">O Nosso Casamento</h2>
        <h1 class="hero-title">Sílvia & João</h1>
        <p class="hero-subtitle">14 de Junho, 2026</p>

        <!-- Decoração Inferior -->
        <div class="floral-decoration floral-bottom-decoration">
          <img src="/images/bottom_flower.jpeg" alt="Decoração floral inferior" class="floral-image floral-bottom"
            loading="lazy" />
        </div>
      </div>
    </div>

    <!-- Countdown Section -->
    <div class="section" :style="{ transform: `translateY(${parallax1}px)` }">
      <div class="content-wrapper" :class="{ visible: sections.details }" style="overflow: hidden;">
        <div class="hero-content" :class="{ visible: sections.details }">
          <h2 class="section-headline">Faltam apenas </h2>
        </div>
        <div class="counters-grid">

          <!-- Dias -->
          <div class="counter-item" :class="{ visible: sections.details }">
            <div class="detail-button-outside">
              <div class="detail-button-inside">
                <div class="icon">
                  <p id="day" class="button-number">{{ countdown.days }}</p>
                </div>
                <div class="detail-text">
                  <p class="button-text" v-if="countdown.days > 1 || countdown.days == 0">dias</p>
                  <p class="button-text" v-if="countdown.days == 1">dia</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Horas -->
          <div class="counter-item" :class="{ visible: sections.details }">
            <div class="detail-button-outside">
              <div class="detail-button-inside">
                <div class="icon">
                  <h4 class="button-number" id="hour">{{ countdown.hours }}</h4>
                </div>
                <div class="detail-text">
                  <p class="button-text" v-if="countdown.hours > 1 || countdown.hours == 0">horas</p>
                  <p class="button-text" v-if="countdown.hours == 1">hora</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Minutos -->
          <div class="counter-item" :class="{ visible: sections.details }">
            <div class="detail-button-outside">
              <div class="detail-button-inside">
                <div class="icon">
                  <h4 id="minute" class="button-number">{{ countdown.minutes }}</h4>
                </div>
                <div class="detail-text">
                  <p class="button-text" v-if="countdown.minutes > 1 || countdown.minutes == 0">minutos</p>
                  <p class="button-text" v-if="countdown.minutes == 1">minuto</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Segundos -->
          <div class="counter-item" :class="{ visible: sections.details }">
            <div class="detail-button-outside">
              <div class="detail-button-inside">
                <div class="icon">
                  <h4 id="second" class="button-number">{{ countdown.seconds }}</h4>
                </div>
                <div class="detail-text">
                  <p class="button-text" v-if="countdown.seconds > 1 || countdown.seconds == 0">segundos</p>
                  <p class="button-text" v-if="countdown.seconds == 1">segundo</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- História Section -->
    <div class="section" :style="{ transform: `translateY(${parallax1}px)` }">
      <div class="hero-image-2"></div>
      <div class="hero-content">
        <h2 class="hero-title">A nossa história</h2>
        <p class="hero-subtitle">
          Começou com um olhar. Cresceu com cada riso partilhado.
          E agora, estamos prontos para o próximo capítulo.
        </p>
      </div>
    </div>

    <!-- Localização Section -->
    <div class="section" :style="{ transform: `translateY(${parallax1}px)` }">
      <div class="content-wrapper" :class="{ visible: sections.details }">
        <h2 class="section-headline">Junte-se a nós</h2>
        <p class="section-subheadline">Para celebrar o nosso casamento</p>

        <div class="details-grid">
          <div class="detail-item" :class="{ visible: sections.details }">
            <div class="detail-icon">
              <i class="fa-solid fa-calendar-day calendar-icon"></i>
            </div>
            <h3 class="detail-title">Quando</h3>
            <p class="detail-text">
              Domingo<br>
              14 de Junho de 2026
            </p>
          </div>

          <div class="detail-item" :class="{ visible: sections.details }">
            <div class="detail-icon">
              <i class="fa-solid fa-clock calendar-icon"></i>
            </div>
            <h3 class="detail-title">Horário</h3>
            <p class="detail-text">
              Cerimónia: 11h45<br>
              Copo D'Água: Após a cerimónia
            </p>
          </div>

          <div class="detail-item" :class="{ visible: sections.details }">
            <div class="detail-icon">
              <i class="fa-solid fa-church church-icon"></i>
            </div>
            <h3 class="detail-title">Cerimónia</h3>
            <p class="detail-text">
              Catedral da Sé,<br>
              Torreiro da Sé, Porto <a style="color: var(--baseColor);" href="https://maps.app.goo.gl/ucgbNwhh56Y6NFwL7"
                target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </p>
          </div>

          <div class="detail-item" :class="{ visible: sections.details }">
            <div class="detail-icon">
              <i class="fa-solid fa-champagne-glasses local-icon"></i>
            </div>
            <h3 class="detail-title">Copo D'Água</h3>
            <p class="detail-text">
              Quinta Redolho de Cima,<br>
              N320 451,<br>
              4620-505 Lousada <a style="color: var(--baseColor);" href="https://maps.app.goo.gl/uRkVyvDHe5Hwwy3t7"
                target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Section -->
    <div class="section" :style="{ transform: `translateY(${parallax1}px)` }">
      <div class="form-wrapper" :class="{ visible: sections.form }">
        <div class="form-headline">
          <h2 class="section-headline">Confirme a sua presença</h2>
          <p class="section-subheadline">Mal podemos esperar para celebrar convosco</p>
        </div>
        <v-card class="form-instructions">
          <v-form @submit.prevent="submitForm" v-model="valid">
            <v-container>

              <!-- Success Message -->
              <div v-if="successMessage" class="success-message">
                ✓ {{ successMessage }}
              </div>

              <!-- Error Messages -->
              <div v-if="Object.keys(errors).length > 0" class="error-message">
                <p v-for="(error, field) in errors" :key="field">{{ error[0] }}</p>
              </div>

              <!-- Primeira Row: 2 Colunas -->
              <v-row class="form-row">

                <!-- Coluna Esquerda -->
                <v-col cols="12" md="6">
                  <!-- Nome -->
                  <v-text-field v-model="form.nome" label="Nome *" required></v-text-field>

                  <!-- Idade -->
                  <v-text-field v-model="form.idade" inset required label="Idade *"
                    :rules="[rules.integer]"></v-text-field>
                </v-col>

                <!-- Coluna Direita -->
                <v-col cols="12" md="6">
                  <!-- Apelido -->
                  <v-text-field v-model="form.apelido" label="Apelido *" required></v-text-field>

                  <v-text-field v-model="form.telefone" required :disabled="loading" label="Telefone *" inset
                    :rules="[rules.mobile]"></v-text-field>
                </v-col>
              </v-row>

              <!-- Segunda Row: Presença e Acompanhantes -->
              <v-row class="form-row">
                <v-col cols="12" md="12">

                  <v-select v-model="select" :items="items" :rules="[v => !!v || 'Item is required']"
                    label="Confirma Presença? *" required></v-select>
                  <!-- <div class="form-group">
                      <label for="presenca"></label>
                      <select id="presenca" v-model="form.presenca" required :disabled="loading">
                        <option value="">Selecione uma opção</option>
                        <option value="sim">Sim, estarei presente!</option>
                        <option value="nao">Infelizmente não poderei comparecer</option>
                      </select>
                    </div> -->
                  <!-- Parceiro -->
                  <v-checkbox label="Irei acompanhado(a)" v-model="form.temParceiro"></v-checkbox>

                  <div v-if="form.temParceiro">
                    <h4>Informações do Acompanhante</h4>
                    <v-text-field v-model="form.parceiro.nome" label="Nome do acompanhante *" required></v-text-field>
                    <v-text-field v-model="form.parceiro.sobrenome" label="Idade *" required
                      :rules="[rules.integer]"></v-text-field>
                  </div>

                  <!-- Filhos -->
                  <v-checkbox label="Tenho filhos que irão comigo" v-model="form.temFilhos"></v-checkbox>

                  <!-- Seção de Filhos - Só aparece se temFilhos for true -->
                  <div v-if="form.temFilhos" class="children-section active">
                    <h4>Informações dos Filhos</h4>

                    <!-- Loop dos filhos existentes -->
                    <div v-for="(filho, index) in form.filhos" :key="index" class="child-entry">
                      <div>
                        <label :for="`childName${index}`">Nome do Filho {{ index + 1 }}</label>
                        <input type="text" :id="`childName${index}`" v-model="filho.nome" placeholder="Nome completo"
                          :disabled="loading">
                        <label :for="`childAge${index}`" style="margin-top: 0.8rem;">Idade</label>
                        <input type="number" :id="`childAge${index}`" v-model="filho.idade" min="0" max="18"
                          placeholder="Idade" :disabled="loading">
                      </div>
                      <button type="button" class="remove-child-btn" @click="removeChild(index)" :disabled="loading">
                        ✕
                      </button>
                    </div>

                    <!-- Botão para adicionar mais filhos -->
                    <button type="button" class="add-child-btn" @click="addChild" :disabled="loading">
                      + Adicionar Filho
                    </button>
                  </div>

                  <!-- Restrições Alimentares -->
                  <v-checkbox label="Restrições Alimentares ou Observações" v-model="form.temRestricoes"></v-checkbox>

                  <!-- Textarea só aparece se temRestricoes for true -->
                  <v-textarea v-if="form.temRestricoes" label="Descreva suas restrições" v-model="form.restricoes"
                    placeholder="Alergias, preferências vegetarianas, etc."></v-textarea>

                </v-col>
              </v-row>

              <!-- Segunda Row: Botão Centralizado -->
              <v-row class="button-row">
                <v-col cols="12" class="text-center">
                  <button type="submit" class="submit-btn" :disabled="loading">
                    {{ loading ? 'Enviando...' : 'Confirmar Presença' }}
                  </button>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </v-card>
      </div>
    </div>
  </v-app>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';
import MobileMenu from './MobileMenu.vue';
import MusicPlayer from './MusicPlayer.vue';
import { rule } from 'postcss';

// Responsividade
const isMobile = ref(window.innerWidth <= 768);

// Parallax
const parallax1 = ref(0);

// Visibility
const sections = reactive({
  parallax1: false,
  details: false,
  parallax2: false,
  images: false,
  form: false
});

// Countdown
const countdown = reactive({
  days: 0,
  hours: 0,
  minutes: 0,
  seconds: 0
});

// Form
const form = reactive({
  nome: '',
  apelido: '',
  idade: '',
  telefone: '',
  presenca: '',
  temParceiro: false,
  temFilhos: false,
  temRestricoes: false,
  restricoes: '',
  parceiro: {
    nome: '',
    idade: ''
  },
  filhos: []
});

const items = [
  'Sim, estarei presente!',
  'Infelizmente não poderei comparecer',
]

const select = ref(null)

const loading = ref(false);
const successMessage = ref('');
const errors = ref({});

const rules = {
  integer: value => Number.isInteger(Number(value)) || 'Deve ser um número',
  mobile: value => {
    const pattern = /^\+?[0-9]{7,15}$/;
    return pattern.test(value) || 'Número de telefone inválido';
  }
};

let observer;
let timer;

// Funções
const afterLoad = () => {
};

const addChild = () => {
  form.filhos.push({
    nome: '',
    idade: ''
  });
};

const removeChild = (index) => {
  form.filhos.splice(index, 1);
};

const toggleFilhos = (event) => {
  // Quando marca o checkbox, adiciona um filho automaticamente
  if (event.target.checked && form.filhos.length === 0) {
    addChild();
  }
  // Quando desmarca, limpa os filhos
  if (!event.target.checked) {
    form.filhos = [];
  }
};

const submitForm = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';

  try {
    const response = await axios.post('/api/confirmacoes', form);
    successMessage.value = 'Presença confirmada com sucesso! Obrigado!';

    // Reset form
    form.nome = '';
    form.apelido = '';
    form.idade = '';
    form.telefone = '';
    form.presenca = '';
    form.temParceiro = false;
    form.temFilhos = false;
    form.temRestricoes = false;
    form.restricoes = '';
    form.parceiro = { nome: '', idade: '' };
    form.filhos = [];
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errors.value = error.response.data.errors;
    } else {
      console.error('Erro ao enviar:', error);
    }
  } finally {
    loading.value = false;
  }
};

// Countdown function
const startCountdown = () => {
  const end = new Date('06/14/2026 11:45 AM');

  const _second = 1000;
  const _minute = _second * 60;
  const _hour = _minute * 60;
  const _day = _hour * 24;

  const showRemaining = () => {
    const now = new Date();
    const distance = end - now;

    if (distance < 0) {
      clearInterval(timer);
      return;
    }

    countdown.days = Math.floor(distance / _day);
    countdown.hours = Math.floor((distance % _day) / _hour);
    countdown.minutes = Math.floor((distance % _hour) / _minute);
    countdown.seconds = Math.floor((distance % _minute) / _second);
  };

  showRemaining();
  timer = setInterval(showRemaining, 1000);
};

// Resize handler
const handleResize = () => {
  isMobile.value = window.innerWidth <= 768;
};

// Intersection Observer
onMounted(() => {
  startCountdown();

  // Listener para resize
  window.addEventListener('resize', handleResize);

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const target = entry.target;
          if (target.classList.contains('content-wrapper')) sections.details = true;
          if (target.classList.contains('form-wrapper')) sections.form = true;
        }
      });
    },
    { threshold: 0.2 }
  );

  setTimeout(() => {
    document.querySelectorAll('.content-wrapper, .form-wrapper').forEach((el) => {
      observer.observe(el);
    });
  }, 100);
});

onUnmounted(() => {
  if (observer) observer.disconnect();
  if (timer) clearInterval(timer);
  window.removeEventListener('resize', handleResize);
});
</script>

<style>
ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #ffffff;
}

#menu {
  position: fixed;
  top: 10px;
  z-index: 100;
  color: white !important;
  width: 100%;
  display: flex;
  text-align: center;
  justify-content: center;
}

#menu li a {
  transition: all 200ms;
  color: var(--baseColor);
  text-decoration: none;
  font-size: clamp(14px, 2vw, 24px);
}

#menu li.active a {
  color: var(--alternativeColor);
  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
}

#menu li a:hover {
  color: var(--alternativeColor);
  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
  transition: all 200ms;
}

.hero-image {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50%;
  height: 100%;
  background-image: url('/images/home.jpeg');
  background-size: cover;
  background-position: center;
}

.fp-watermark {
  display: none !important;
}
</style>