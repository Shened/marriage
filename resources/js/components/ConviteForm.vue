<template>
    <v-app id="wedding-app">
        <!-- Music Player (mantido do original) -->
        <MusicPlayer v-if="showMusicPlayer" />

        <v-main>
            <!-- Hero Section -->
            <section id="home" class="hero-section">
                <div class="hero-background">
                    <v-img src="/images/kiss.jpg" cover class="hero-image">
                        <div class="hero-overlay"></div>
                    </v-img>
                </div>

                <v-container class="hero-content" fluid>
                    <v-row justify="center" align="center" class="fill-height">
                        <v-col cols="12" class="text-center"
                            style="display:flex; justify-content: center; align-items: center;">

                            <!-- Conteúdo principal -->
                            <v-fade-transition variant="outlined">
                                <v-card class="hero-card" elevation="8" v-if="showHero">
                                    <div v-if="showHero" class="hero-text">
                                        <h2 class="hero-subtitle mb-4 secondary-color">O Nosso Casamento</h2>
                                        <h1 class="hero-title mb-4 base-color">Sílvia & João</h1>
                                        <p class="hero-date mb-4 alternative-color">14 de Junho de 2026</p>
                                    </div>
                                </v-card>
                            </v-fade-transition>

                        </v-col>
                    </v-row>
                </v-container>

                <!-- Scroll indicator -->
                <div class="scroll-indicator" @click="scrollToSection('countdown')">
                    <v-icon color="white" size="32">mdi-chevron-down</v-icon>
                </div>
            </section>

            <!-- Countdown Section -->
            <section id="countdown" class="countdown-section">
                <v-container>
                    <v-row justify="center" align="center" class="fill-height">
                        <v-col cols="12" sm="10" md="8" lg="6" class="countdown-row">
                            <!-- Card Elegante -->

                            <!-- Title -->
                            <div class="text-center mb-10">
                                <h2 class="countdown-title">Em Breve !</h2>
                                <v-divider class="countdown-divider mx-auto mt-6"></v-divider>
                            </div>

                            <!-- Countdown Grid -->
                            <div class="countdown-grid">
                                <v-row justify="center" class="mb-8">
                                    <!-- Days -->
                                    <v-col cols="6" lg="3" class="text-center">
                                        <div class="countdown-item">
                                            <div class="countdown-value">{{ countdown.days }}</div>
                                            <div class="countdown-label">DIAS</div>
                                        </div>
                                    </v-col>

                                    <!-- Hours -->
                                    <v-col cols="6" lg="3" class="text-center">
                                        <div class="countdown-item">
                                            <div class="countdown-value">{{ countdown.hours }}</div>
                                            <div class="countdown-label">HORAS</div>
                                        </div>
                                    </v-col>

                                    <!-- Minutes -->
                                    <v-col cols="6" lg="3" class="text-center">
                                        <div class="countdown-item">
                                            <div class="countdown-value">{{ countdown.minutes }}</div>
                                            <div class="countdown-label">MINUTOS</div>
                                        </div>
                                    </v-col>

                                    <!-- Seconds -->
                                    <v-col cols="6" lg="3" class="text-center">
                                        <div class="countdown-item">
                                            <div class="countdown-value">{{ countdown.seconds }}</div>
                                            <div class="countdown-label">SEGUNDOS</div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Mirror Image Section -->
            <section id="mirror" class="mirror-section">
                <div class="mirror-background">
                    <v-img src="/images/mirror.jpg" cover class="mirror-image">
                        <div class="mirror-overlay"></div>
                    </v-img>
                </div>
            </section>

            <!-- História Section -->
            <section id="historia" class="historia-section">
                <div class="historia-background"></div>
                <v-container>
                    <v-row justify="center">
                        <v-col cols="12" md="8" lg="6">
                            <v-card class="historia-card" elevation="8">
                                <v-card-text class="pa-8 pa-md-12">
                                    <div class="section-header text-center mb-12">
                                        <h2 class="section-title">Uma Aventura</h2>
                                        <p class="section-subtitle">que começou há muitos anos e que agora escolhe
                                            continuar
                                            para
                                            sempre</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <!-- Primeira linha: uma imagem grande e duas médias -->
                        <v-col cols="12" md="6">
                            <v-img :src="getImage('foto11.jpeg')" height="408" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto2.jpeg')" height="200" cover class="mb-2"></v-img>
                            <v-img :src="getImage('foto3.jpeg')" height="200" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto4.jpeg')" height="408" cover class="mb-2"></v-img>
                        </v-col>

                        <!-- Segunda linha: três médias -->
                        <v-col cols="12" md="4" v-for="i in 3" :key="'row2-' + i">
                            <v-img :src="getImage(`foto${i + 4}.jpeg`)" height="250" cover class="mb-2"></v-img>
                        </v-col>

                        <!-- Terceira linha: uma grande e duas pequenas -->
                        <v-col cols="12" md="6">
                            <v-img :src="getImage('foto9.jpeg')" height="300" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto19.jpeg')" height="146" cover class="mb-2"></v-img>
                            <v-img :src="getImage('foto10.jpeg')" height="146" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto1.jpeg')" height="300" cover class="mb-2"></v-img>
                        </v-col>

                        <!-- Quarta linha: cinco pequenas lado a lado -->
                        <v-col cols="12" md="6" v-for="i in 2" :key="'row4-' + i">
                            <v-img :src="getImage(`foto${i + 11}.jpeg`)" height="300" cover class="mb-2"></v-img>
                        </v-col>

                        <v-col cols="12" md="3" v-for="i in 4" :key="'row4-' + i">
                            <v-img :src="getImage(`foto${i + 13}.jpeg`)" height="300" cover class="mb-2"></v-img>
                        </v-col>

                        <!-- Última linha: quatro médias -->

                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto18.jpeg')" height="146" cover class="mb-2"></v-img>
                            <v-img :src="getImage('foto22.jpeg')" height="146" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-img :src="getImage('foto19.jpeg')" height="300" cover class="mb-2"></v-img>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-img :src="getImage('foto20.jpeg')" height="146" cover class="mb-2"></v-img>
                            <v-img :src="getImage('foto21.jpeg')" height="146" cover class="mb-2"></v-img>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <section class="divider-section">
                <div class="divider-line"></div>
            </section>

            <!-- Localização Section -->
            <section id="localizacao" class="localizacao-section section-padding">
                <v-container>
                    <div class="section-header text-center mb-12">
                        <h2 class="section-title">Junte-se a nós</h2>
                        <p class="section-subtitle">Para celebrar o nosso casamento</p>
                    </div>

                    <v-row justify="center">
                        <v-col cols="6" md="4" lg="3" class="text-center" v-for="value in eventDetails"
                            :key="value.title">
                            <div class="local-card">
                                <v-row>
                                    <v-col cols="12">
                                        <div class="detail-icon">
                                            <v-icon size="48" class="mb-3 base-color">{{ value.icon }}</v-icon>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="detail-icon">
                                            <h3 class="detail-title mb-3">{{ value.title }}</h3>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="detail-content mb-4" v-html="value.content"></div>
                                        <v-btn class="button link-button" v-if="value.link" variant="outlined"
                                            size="small" color="primary">
                                            <a :href="value.link" target="_blank" class="detail-link"
                                                style="text-decoration: none;">
                                                Ver no Mapa
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        </v-btn>
                                    </v-col>

                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <section class="divider-section">
                <div class="divider-line"></div>
            </section>

            <section class="dress-code-section section-padding">
                <v-container>
                    <div class="section-header text-center mb-12">
                        <h2 class="section-title">Dress Code</h2>
                        <p class="section-subtitle">
                            Para manter a harmonia das cores, agradecemos que evitem os tons abaixo.
                        </p>
                        <v-row class="justify-center">
                            <v-col cols="12" style="display: flex; justify-content: center;">
                                <div class="dress-code-card">
                                    <v-row class="justify-center">
                                        <!-- Título -->

                                        <!-- Cores reservadas -->
                                        <v-col cols="6" md="6" class="d-flex justify-center dress-color-avatar">
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-avatar color="surface-variant woman" size="64"></v-avatar>
                                                </v-col>
                                                <v-col cols="12">
                                                    <p class="section-subtitle">Tons reservados </p>
                                                    <p class="section-subtitle">para as <b>Madrinhas</b></p>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col cols="6" class="d-flex justify-center dress-color-avatar">
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-avatar color="surface-variant man" size="64"></v-avatar>
                                                </v-col>
                                                <v-col cols="12">
                                                    <p class="section-subtitle">Tons reservados </p>
                                                    <p class="section-subtitle">para os <b>Padrinhos</b></p>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <!-- Nota opcional -->
                                        <v-col cols="12" class="text-center mt-8">
                                            <p class="section-info">
                                                Se tiver alguma dúvida, pode perguntar-nos!
                                            </p>
                                        </v-col>
                                    </v-row>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                </v-container>
            </section>

            <section class="divider-section">
                <div class="divider-line"></div>
            </section>

            <!-- Gallery Section (Novo) -->
            <section id="galeria" class="gallery-section section-padding" v-if="showGallery">
                <v-container>
                    <div class="section-header text-center mb-12">
                        <h2 class="section-title">Nossos Momentos</h2>
                    </div>

                    <v-row>
                        <v-col v-for="(photo, index) in galleryPhotos" :key="index" cols="6" sm="4" md="3">
                            <v-card class="gallery-card" elevation="2" hover>
                                <v-img :src="photo.src" :alt="photo.alt" aspect-ratio="1" cover class="gallery-image">
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate
                                                color="grey-lighten-5"></v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Confirmação Section -->
            <section id="confirmacao" class="confirmacao-section section-padding">
                <v-container>
                    <v-row justify="center">
                        <v-col cols="12" md="10" lg="8">
                            <div class="section-header text-center mb-8">
                                <h2 class="section-title">Confirme a sua presença</h2>
                                <p class="section-subtitle">Mal podemos esperar para celebrar convosco</p>
                                <br>
                                <p class="section-subtitle">Pedimos que confirmem até ao dia <b>04/06/2026</b></p>
                            </div>

                            <v-card class="form-card" elevation="4">
                                <v-card-text class="pa-6 pa-md-10">
                                    <v-form @submit.prevent="submitForm" v-model="valid" ref="formRef">

                                        <!-- Success Alert -->
                                        <v-alert v-if="successMessage" type="success" variant="tonal" closable
                                            class="mb-6" prominent>
                                            <template v-slot:prepend>
                                                <v-icon>mdi-check-circle</v-icon>
                                            </template>
                                            {{ successMessage }}
                                        </v-alert>

                                        <!-- Error Alert -->
                                        <v-alert v-if="Object.keys(errors).length > 0" type="error" variant="tonal"
                                            closable class="mb-6" prominent>
                                            <div v-for="(error, field) in errors" :key="field">{{ error[0] }}</div>
                                        </v-alert>

                                        <!-- Informações Pessoais -->
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field v-model="form.nome" label="Nome *" variant="outlined"
                                                    :rules="[rules.required]" :disabled="loading" color="primary"
                                                    density="comfortable"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field v-model="form.apelido" label="Apelido *"
                                                    variant="outlined" :rules="[rules.required]" :disabled="loading"
                                                    color="primary" density="comfortable"></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field v-model="form.idade" label="Idade *" variant="outlined"
                                                    type="number" :rules="[rules.required, rules.integer]"
                                                    :disabled="loading" color="primary"
                                                    density="comfortable"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field v-model="form.telefone" label="Telefone *"
                                                    variant="outlined" :rules="[rules.required, rules.mobile]"
                                                    :disabled="loading" color="primary"
                                                    density="comfortable"></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <!-- Confirmação de Presença -->
                                        <v-row>
                                            <v-col cols="12">
                                                <v-select v-model="form.presenca" :items="presencaOptions"
                                                    label="Confirma Presença? *" variant="outlined"
                                                    :rules="[rules.required]" :disabled="loading" color="primary"
                                                    density="comfortable"></v-select>
                                            </v-col>
                                        </v-row>

                                        <v-expand-transition>
                                            <div v-if="form.presenca == 'sim' || form.presenca == presencaOptions[0]"
                                                class="fade-delay">
                                                <!-- Acompanhante -->
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-checkbox v-model="form.temParceiro"
                                                            label="Irei acompanhado(a)" :disabled="loading"
                                                            color="primary" density="comfortable"></v-checkbox>
                                                        <v-expand-transition>
                                                            <v-card v-if="form.temParceiro" variant="outlined"
                                                                class="pa-4 mb-4 card-parceiro fade-delay">
                                                                <v-card-title>
                                                                    <h4 class="text-h6 mb-4">Informações do Acompanhante
                                                                    </h4>
                                                                </v-card-title>
                                                                <v-row>
                                                                    <v-col cols="12" md="6">
                                                                        <v-text-field v-model="form.parceiro.nome"
                                                                            label="Nome do acompanhante *"
                                                                            variant="outlined"
                                                                            :rules="form.temParceiro ? [rules.required] : []"
                                                                            :disabled="loading" color="primary"
                                                                            density="comfortable"></v-text-field>
                                                                    </v-col>
                                                                    <v-col cols="12" md="6">
                                                                        <v-text-field v-model="form.parceiro.idade"
                                                                            label="Idade *" variant="outlined"
                                                                            type="number"
                                                                            :rules="form.temParceiro ? [rules.required, rules.integer] : []"
                                                                            :disabled="loading" color="primary"
                                                                            density="comfortable"></v-text-field>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-card>
                                                        </v-expand-transition>
                                                    </v-col>
                                                </v-row>
                                                <!-- Filhos -->
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-checkbox v-model="form.temFilhos"
                                                            label="Tenho filhos menores que irão comigo"
                                                            :disabled="loading" color="primary" density="comfortable"
                                                            @change="toggleFilhos"></v-checkbox>

                                                        <v-expand-transition>
                                                            <v-card v-if="form.temFilhos" variant="outlined"
                                                                class="pa-4 mb-4 card-filhos fade-delay"
                                                                color="primary">
                                                                <div
                                                                    class="d-flex justify-space-between align-center mb-4">
                                                                    <v-card-title>
                                                                        <h4 class="text-h6">Informações dos Filhos</h4>
                                                                    </v-card-title>
                                                                    <v-btn color="primary" class="link-button"
                                                                        variant="outlined" size="small"
                                                                        @click="addChild" :disabled="loading"
                                                                        prepend-icon="mdi-plus">
                                                                        Adicionar
                                                                    </v-btn>
                                                                </div>

                                                                <v-card v-for="(filho, index) in form.filhos"
                                                                    :key="index" variant="outlined"
                                                                    class="mb-3 pa-4 card-filhos">
                                                                    <v-row align="center">
                                                                        <v-col cols="12" sm="5">
                                                                            <v-text-field v-model="filho.nome"
                                                                                :label="`Nome do filho ${index + 1} *`"
                                                                                variant="outlined" density="compact"
                                                                                :rules="[rules.required]"
                                                                                :disabled="loading"
                                                                                color="primary"></v-text-field>
                                                                        </v-col>
                                                                        <v-col cols="12" sm="5">
                                                                            <v-text-field v-model="filho.idade"
                                                                                label="Idade *" variant="outlined"
                                                                                density="compact" type="number"
                                                                                :rules="[rules.required, rules.integer, rules.maxAge]"
                                                                                :disabled="loading"
                                                                                color="primary"></v-text-field>
                                                                        </v-col>
                                                                        <v-col cols="12" sm="2" class="text-center">
                                                                            <v-btn icon="mdi-delete" color="error"
                                                                                variant="text"
                                                                                @click="removeChild(index)"
                                                                                :disabled="loading"></v-btn>
                                                                        </v-col>
                                                                    </v-row>
                                                                </v-card>
                                                            </v-card>
                                                        </v-expand-transition>
                                                    </v-col>
                                                </v-row>

                                                <!-- Restrições -->
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-checkbox v-model="form.temRestricoes"
                                                            label="Restrições Alimentares ou Observações"
                                                            :disabled="loading" color="primary"
                                                            density="comfortable"></v-checkbox>

                                                        <v-expand-transition>
                                                            <v-textarea v-if="form.temRestricoes"
                                                                v-model="form.restricoes"
                                                                label="Descreva suas restrições ou observações"
                                                                variant="outlined" rows="3" :disabled="loading"
                                                                color="primary" class="fade-delay"></v-textarea>
                                                        </v-expand-transition>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                        </v-expand-transition>
                                        <!-- Submit Button -->
                                        <v-row>
                                            <v-col cols="12">
                                                <v-btn type="submit" block size="x-large" class="form-button"
                                                    :loading="loading" :disabled="!valid || loading" rounded="lg"
                                                    elevation="2">
                                                    Submeter Resposta
                                                </v-btn>
                                            </v-col>
                                        </v-row>
                                        <v-row justify="center">
                                            <v-col cols="12" justify="center" class="text-center mt-8">
                                                <div class="section-info">
                                                    <p>
                                                        Agradecemos que respondam ao questionário mesmo que não vos seja
                                                        possível
                                                        estar presentes.
                                                    </p>
                                                    <p>
                                                        Obrigado!
                                                    </p>
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Footer -->
            <footer class="wedding-footer">
                <v-container>
                    <v-row justify="center">
                        <v-col cols="12" class="text-center">
                            <p class="footer-text mb-2">Sílvia & João</p>
                            <p class="footer-date">14 de Junho, 2026</p>
                            <div class="footer-divider my-4"></div>
                            <p class="footer-copyright">
                                Feito com <v-icon size="small" color="red">mdi-heart</v-icon> para o nosso dia especial
                            </p>
                        </v-col>
                    </v-row>
                    <div class="footer-divider my-4"></div>
                    <v-row justify="center">
                        <v-col cols="12">
                            <v-row>
                                <v-col cols="12" class="text-center">
                                    <p class="footer-text">Contactos</p>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-row>
                                <v-col cols="6" md="6" class="text-center">
                                    <p class="footer-text">Sílvia</p>
                                </v-col>
                                <v-col cols="6" md="6" class="text-center">
                                    <p class="footer-text">João</p>
                                </v-col>
                            </v-row>
                            <v-row>
                                <!-- Silvia Socials -->
                                <v-col cols="6" style="border-right: 1px solid #A3B8D8;">
                                    <v-row justify="center">
                                        <v-col cols="4" class="text-center">
                                            <a href="https://www.instagram.com/silviaaferreiira/" target="_blank"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-instagram" style="cursor: pointer;"></i>
                                            </a>
                                        </v-col>
                                        <v-col cols="4" class="text-center">
                                            <a href="https://www.facebook.com/Silviaferreira204" target="_blank"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-facebook" style="cursor: pointer;">
                                                </i>
                                            </a>
                                        </v-col>
                                        <v-col cols="4" class="text-center">
                                            <a href="https://wa.me/910398401" target="_blank"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-whatsapp" style="cursor: pointer;">
                                                </i>
                                            </a>
                                        </v-col>
                                    </v-row>
                                </v-col>

                                <!-- Martins Socials -->
                                <v-col cols="6">
                                    <v-row justify="center">
                                        <v-col cols="4" class="text-center">
                                            <a href="https://www.instagram.com/joao_martins29/" target="_blank"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-instagram" style="cursor: pointer;"></i>
                                            </a>
                                        </v-col>
                                        <v-col cols="4" class="text-center">
                                            <a href="https://www.facebook.com/tuttashu/"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-facebook" style="cursor: pointer;"></i>
                                            </a>
                                        </v-col>
                                        <v-col cols="4" class="text-center">
                                            <a href="https://wa.me/915312862" target="_blank"
                                                style="text-decoration: none; color: #A3B8D8;">
                                                <i class="fa-brands fa-whatsapp" style="cursor: pointer;">
                                                </i>
                                            </a>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                    <div v-if="isMobile" class="my-4 mt-10"></div>
                </v-container>
            </footer>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref, reactive, watch, computed, onMounted, onUnmounted } from 'vue';
import MusicPlayer from '@/components/MusicPlayer.vue'
import axios from 'axios';

const isMobile = window.innerWidth <= 768;

// Props
const showMusicPlayer = ref(true);
const showGallery = ref(false);

// Navigation
const navLinks = [
    { id: 'home', name: 'Home', icon: 'mdi-home' },
    { id: 'countdown', name: 'Countdown', icon: 'mdi-timer-sand' },
    { id: 'historia', name: 'História', icon: 'mdi-heart' },
    { id: 'localizacao', name: 'Localização', icon: 'mdi-map-marker' },
    { id: 'confirmacao', name: 'Confirmação', icon: 'mdi-check-circle' }
];

const getImage = (name) => `/images/carousels/${name}`;

const activeSection = ref('home');
const isScrolled = ref(false);
const showHero = ref(false);

// Countdown
const countdown = reactive({
    days: '00',
    hours: '00',
    minutes: '00',
    seconds: '00'
});

// Event Details
const eventDetails = [
    {
        icon: 'mdi-calendar-today',
        title: 'Quando',
        content: 'Domingo<br>14 de Junho de 2026'
    },
    {
        icon: 'mdi-clock-outline',
        title: 'Horário',
        content: 'Cerimónia: 11h45<br>Copo D\'Água: Após a cerimónia'
    },
    {
        icon: 'mdi-church',
        title: 'Cerimónia',
        content: 'Catedral da Sé<br>Torreiro da Sé, Porto',
        link: 'https://maps.app.goo.gl/ucgbNwhh56Y6NFwL7'
    },
    {
        icon: 'mdi-glass-cocktail',
        title: 'Copo D\'Água',
        content: 'Quinta Redolho de Cima<br>N320 451, Lousada',
        link: 'https://maps.app.goo.gl/uRkVyvDHe5Hwwy3t7'
    }
];

// Gallery Photos
const galleryPhotos = ref([
    { src: '/images/gallery/1.jpg', alt: 'Foto 1' },
    { src: '/images/gallery/2.jpg', alt: 'Foto 2' },
    { src: '/images/gallery/3.jpg', alt: 'Foto 3' },
    { src: '/images/gallery/4.jpg', alt: 'Foto 4' },
]);

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

const presencaOptions = [
    'Sim, estarei presente!',
    'Infelizmente não poderei comparecer'
];

const valid = ref(false);
const formRef = ref(null);
const loading = ref(false);
const successMessage = ref('');
const errors = ref({});

// Validation Rules
const rules = {
    required: value => !!value || 'Campo obrigatório',
    integer: value => Number.isInteger(Number(value)) || 'Deve ser um número',
    mobile: value => {
        const pattern = /^\+?[0-9]{7,15}$/;
        return pattern.test(value) || 'Número de telefone inválido';
    },
    maxAge: value => {
        17 || 'A idade máxima permitida é 17 anos'
    }
};

let timer;

// Functions
const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        const offset = 80;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
};

watch(
    () => form.presenca,
    (novoValor) => {
        const respostasNegativas = ['infelizmente não poderei comparecer', 'não'];
        if (respostasNegativas.includes(novoValor?.trim().toLowerCase())) {
            form.temFilhos = false;
            form.temParceiro = false;
            form.filhos = []
        }
    }
);

const addChild = () => {
    form.filhos.push({
        nome: '',
        idade: ''
    });
};

const removeChild = (index) => {
    form.filhos.splice(index, 1);

    if (form.filhos.length === 0) {
        form.temFilhos = false;
    }
};

const toggleFilhos = () => {
    if (form.temFilhos && form.filhos.length === 0) {
        addChild();
    } else {
        form.filhos = [];
    }
};

const submitForm = async () => {
    const payload = { ...form }

    if (!form.temParceiro) {
        delete payload.parceiro;
    }

    // Se não tiver filhos, remove
    if (!form.temFilhos) {
        delete payload.filhos;
    }

    payload.presenca = payload.presenca == presencaOptions[0] ?
        'sim' :
        'não'


    if (!valid.value) return;

    loading.value = true;
    errors.value = {};
    successMessage.value = '';

    try {
        const response = await axios.post('/confirmacoes', payload);
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

        if (formRef.value) {
            formRef.value.reset();
        }

        setTimeout(() => {
            scrollToSection('confirmacao');
        }, 100);
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

        countdown.days = String(Math.floor(distance / _day)).padStart(2, '0');
        countdown.hours = String(Math.floor((distance % _day) / _hour)).padStart(2, '0');
        countdown.minutes = String(Math.floor((distance % _hour) / _minute)).padStart(2, '0');
        countdown.seconds = String(Math.floor((distance % _minute) / _second)).padStart(2, '0');
    };

    showRemaining();
    timer = setInterval(showRemaining, 1000);
};

// Lifecycle
onMounted(() => {
    startCountdown();

    setTimeout(() => {
        showHero.value = true;
    }, 300);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>

<style scoped>
/* ====================================
    HERO SECTION
   ==================================== */

:root {
    --background: #B4CCE9;
    --background-alt: #DCE6F7;
    --text: #0C2452;
    --text-alt: #6E6E6E;
    --border: #A3B8D8;
    --accent: #5B8FA3;
    --accent-alt: #78A89A;
    --hover: #F0E1D8;
}

.fade-delay {
    transition-delay: 0.2s;
    /* atraso de 0.3s */
    transition-duration: 0.5s;
    /* duração da animação */
}

.hero-section {
    height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.hero-image {
    width: 100%;
    height: 100%;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* background: linear-gradient(to bottom,
            rgba(74, 124, 89, 0.2) 0%,
            rgba(74, 124, 89, 0.3) 50%,
            rgba(74, 124, 89, 0.4) 100%); */
    background: linear-gradient(to bottom,
            rgba(12, 36, 82, 0.1) 0%,
            rgba(12, 36, 82, 0.2) 50%,
            rgba(12, 36, 82, 0.3) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    height: 100%;
}

.hero-card {
    background: rgba(12, 36, 82, 0.2) !important;
    backdrop-filter: blur(2px);
    border-radius: 0px;
    padding: clamp(2rem, 5vw, 4rem);
    box-shadow: 0 8px 32px rgba(74, 124, 89, 0.2) !important;
    border: 2px solid;
    border-color: rgb(180, 204, 233);
}

.hero-text {
    color: rgb(180, 204, 233);
    /* texto principal */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    /* glow subtil de profundidade */
}

.hero-subtitle {
    font-size: clamp(1.2rem, 2.5vw, 1.6rem);
    font-weight: 500;
    letter-spacing: 1px;
    opacity: 1;
}

.hero-title {
    font-size: clamp(3rem, 8vw, 7rem);
    font-weight: 500;
    font-family: 'Lora', serif;
    letter-spacing: -0.02em;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-date {
    font-size: clamp(1.1rem, 2.5vw, 1.5rem);
    font-weight: 500;
    letter-spacing: 1px;
    opacity: 1;
}

.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    cursor: pointer;
    animation: bounce 2s infinite;
    z-index: 3;
}

@keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateX(-50%) translateY(0);
    }

    40% {
        transform: translateX(-50%) translateY(-10px);
    }

    60% {
        transform: translateX(-50%) translateY(-5px);
    }
}

/* ====================================
    MIRROR SECTION
   ==================================== */
.mirror-section {
    height: 60vh;
    position: relative;
    overflow: hidden;
}

.mirror-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.mirror-image {
    width: 100%;
    height: 100%;
}

.mirror-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom,
            rgba(91, 143, 163, 0.1) 0%,
            rgba(91, 143, 163, 0.2) 100%);
}

/* ====================================
    SECTIONS
   ==================================== */
.section-padding {
    padding: clamp(4rem, 10vw, 8rem) 0;
}

.section-header {
    margin-bottom: clamp(3rem, 6vw, 5rem);
}

.section-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 500;
    font-family: 'Lora', serif;
    color: #0C2452;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.section-subtitle {
    font-size: clamp(1rem, 2vw, 1.3rem);
    color: #0C2452;
    font-weight: 500;
}

.section-info {
    font-size: clamp(0.8rem, 1.8vw, 1.1rem);
    color: #0C2452;
    font-weight: 500;
}

.dress-code-card {
    /* border: 2px solid #4A4A4A; */
    padding: 30px;
    margin-top: 30px;
    /* background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    background-attachment: fixed;
    backdrop-filter: blur(6px); */
}

/* ====================================
    OUNTDOWN SECTION
   ==================================== */
.countdown-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
    padding: clamp(3rem, 8vw, 6rem) 1rem;
}

.countdown-title {
    font-size: clamp(1.5rem, 4vw, 2.2rem);
    font-weight: 400;
    letter-spacing: 6px;
    color: #0C2452;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.15);
    font-family: 'Lora', serif;
    text-align: center;
    position: relative;
    z-index: 1;
}

.countdown-divider {
    width: 240px;
    max-width: 85%;
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
    opacity: 1;
    border: none;
    box-shadow: 0 0 10px rgba(74, 124, 89, 0.3);
}

.countdown-grid {
    position: relative;
    z-index: 1;
}

.countdown-item {
    position: relative;
    padding: 1rem 0;
}

.countdown-value {
    font-size: clamp(4rem, 15vw, 7rem);
    font-weight: 300;
    color: #0C2452;
    font-family: 'Lora', serif;
    font-style: italic;
    line-height: 0.9;
    margin-bottom: 0.8rem;
    /* text-shadow: 0 0 20px rgba(74, 124, 89, 0.2),
        0 4px 12px rgba(74, 124, 89, 0.15); */
    letter-spacing: -2px;
    transition: all 0.3s ease;
}

.countdown-item:hover .countdown-value {
    transform: scale(1.05);
    /* text-shadow: 0 0 30px rgba(74, 124, 89, 0.3),
        0 4px 16px rgba(74, 124, 89, 0.2); */
}

.countdown-label {
    font-size: clamp(0.8rem, 2vw, 1rem);
    font-weight: 500;
    letter-spacing: 3px;
    color: #0C2452;
    text-transform: uppercase;
    opacity: 0.85;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* ====================================
    HISTÓRIA SECTION
   ==================================== */
.historia-section {
    position: relative;
    padding: clamp(6rem, 12vw, 10rem) 0;
    overflow: hidden;
}

.historia-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
    opacity: 1;
    z-index: 0;
}

.historia-card {
    position: relative;
    z-index: 1;
    background: transparent;
    border-radius: 0px;
    border: 0 solid #0C2452;
    box-shadow: 0 0 0 0 !important;
}

.historia-text {
    font-size: clamp(1rem, 2vw, 1.2rem);
    line-height: 1.8;
    color: #0C2452;
    font-weight: 500;
}

/* ====================================
    LOCALIZAÇÃO SECTION
   ==================================== */
.localizacao-section {
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
}

.local-card {
    min-height: 300px;
    transition: all .5s ease-in-out;
}

.local-card:hover {
    transform: scale(1.1);
}

.detail-icon {
    color: #0C2452
}

.detail-title {
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    font-weight: 600;
    color: #0C2452;
    margin-bottom: 1rem;
}

.detail-content {
    font-size: clamp(0.9rem, 1.5vw, 1rem);
    color: #4A4A4A;
    line-height: 1.6;
}

.link-button {
    border: 2px solid;
    border-radius: 0px;
    border-color: #0C2452 !important;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
}

.link-button:hover {
    background-color: #0C2452 !important;
    border-color: #ffffff !important;
    color: #ffffff !important;
}

.link-button:hover a {
    color: #ffffff !important;
}

.detail-link {
    color: #0C2452 !important;
    font-weight: bold;
}

/* ====================================
    DIVIDER SECTION
   ==================================== */


.divider-section {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
    height: 10px;
}

.divider-line {
    border-top: 2px solid #0C2452;
    height: 5px;
    width: 40%;
}

/* ====================================
    DRESS CODE SECTION
   ==================================== */

.dress-code-section {
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
}

.dress-color-avatar {
    transition: all .5s ease-in-out;
}

.dress-color-avatar:hover {
    transform: scale(1.2);
}

.woman {
    background-color: #B4CCE9 !important;
    border: none;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
}

.man {
    background-color: #0C2452 !important;
    border: none;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
}

/* ====================================
    GALLERY SECTION
   ==================================== */

.gallery-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.gallery-card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(74, 124, 89, 0.1);
}

.gallery-card:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(74, 124, 89, 0.2);
    z-index: 1;
}

.gallery-image {
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-image {
    transform: scale(1.1);
}

/* ====================================
    CONFIRMAÇÃO SECTION
   ==================================== */
.confirmacao-section {
    background: linear-gradient(135deg,
            #B4CCE9 0%,
            #DCE6F7 50%,
            #B4CCE9 100%);
    background-attachment: fixed;
}

.form-card {
    border-radius: 0px;
    border: 2px solid #0C2452;
    background: linear-gradient(135deg, #f0f2f5 0%, #f0f2f5 50% #f2f4f7 100%);
    backdrop-filter: blur(2px);
    background-attachment: fixed;
}

.form-button {
    background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
    color: #DCE6F7;
}

/* ====================================
    FOOTER
   ==================================== */
.wedding-footer {
    background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
    padding: clamp(3rem, 6vw, 5rem) 0 clamp(2rem, 4vw, 3rem);
    color: white;
}

.footer-text {
    font-size: clamp(1.2rem, 2vw, 1.5rem);
    font-weight: 500;
    font-family: 'Lora', serif;
    color: #DCE6F7;
}

.footer-date {
    font-size: clamp(0.9rem, 1.5vw, 1.1rem);
    color: #A3B8D8;
    font-weight: 400;
}

.footer-divider {
    width: 60px;
    height: 2px;
    background: #DCE6F7;
    margin: 0 auto;
}

.footer-copyright {
    font-size: clamp(0.8rem, 1.3vw, 0.9rem);
    color: #A3B8D8;
}

.card-filhos,
.card-parceiro {
    border: 2px solid;
    border-radius: 0px !important;
}

/* ====================================
    MOBILE RESPONSIVENESS
   ==================================== */
@media (max-width: 768px) {
    .hero-title {
        font-size: clamp(2.5rem, 10vw, 4rem);
    }

    .countdown-grid {
        gap: 0.8rem;
    }

    .section-padding {
        padding: clamp(3rem, 8vw, 6rem) 0;
    }
}

@media (max-width: 480px) {
    .countdown-value {
        font-size: clamp(2rem, 8vw, 3rem);
    }
}

@media (min-width: 769px) {
    .dress-code-card {
        width: 60%;
    }
}

@media (min-width: 1281px) {
    .countdown-row {
        flex: auto;
        max-width: 90%;
    }
}

/* ====================================
    ANIMATIONS
   ==================================== */
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

/* ====================================
    ACCESSIBILITY
   ==================================== */
@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

*:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
</style>