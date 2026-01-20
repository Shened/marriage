<template>
    <div class="gallery-container">
        <!-- Hero Header -->
        <div class="gallery-header">
            <div class="header-content">
                <div class="header-icon">
                    <img src="/images/heart.jpg" alt="Galeria" />
                </div>
                <div class="header-text">
                    <h1 class="gallery-title">Galeria Partilhada</h1>
                    <p class="gallery-subtitle">{{ formattedPhotos.length }} {{ formattedPhotos.length === 1 ?
                        'fotografia' : 'fotografias' }}</p>
                </div>
            </div>
            <button @click="uploadDialog = true" class="upload-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z" />
                </svg>
                <span>Adicionar</span>
            </button>
        </div>

        <!-- Empty State -->
        <div v-if="formattedPhotos.length === 0" class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"
                        opacity="0.3" />
                </svg>
            </div>
            <h2>Ainda não há fotografias</h2>
            <p>Seja o primeiro a adicionar memórias a esta galeria</p>
            <button @click="uploadDialog = true" class="empty-cta">
                Adicionar primeira foto
            </button>
        </div>

        <!-- Photo Grid -->
        <div v-else class="photo-grid">
            <div v-for="(photo, index) in formattedPhotos" :key="photo.id" class="photo-item"
                :style="{ animationDelay: `${index * 0.05}s` }" @click="openLightbox(index)">
                <img :src="photo.url" :alt="photo.name || 'Foto'" loading="lazy" />

                <!-- Admin Delete Button -->
                <button v-if="isAdmin" @click.stop="confirmDelete(photo)" class="delete-btn" title="Eliminar foto">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                </button>

                <div class="photo-overlay">
                    <div class="photo-info">
                        <span class="photo-date">{{ formatDate(photo.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button v-if="formattedPhotos.length > 0" @click="uploadDialog = true" class="fab">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
            </svg>
        </button>

        <!-- Upload Dialog -->
        <Teleport to="body">
            <Transition name="dialog">
                <div v-if="uploadDialog" class="dialog-backdrop" @click.self="closeUploadDialog">
                    <div class="dialog">
                        <div class="dialog-header">
                            <h2>Adicionar Fotografias</h2>
                            <button @click="closeUploadDialog" class="close-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                </svg>
                            </button>
                        </div>

                        <div class="dialog-content">
                            <!-- File Input -->
                            <div class="upload-zone" @click="triggerFileInput">
                                <input ref="fileInput" type="file" accept="image/*" multiple @change="handleFileSelect"
                                    style="display: none" />
                                <div class="upload-zone-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                                    </svg>
                                    <p class="upload-text">Clique para selecionar fotografias</p>
                                    <p class="upload-hint">ou arraste os ficheiros aqui</p>
                                    <p class="upload-limit">Máximo 15MB por ficheiro</p>
                                </div>
                            </div>

                            <!-- Preview Grid -->
                            <div v-if="previews.length > 0" class="preview-section">
                                <h3>{{ previews.length }} {{ previews.length === 1 ?
                                    'foto selecionada' : 'fotos selecionadas' }}</h3>
                                <div class="preview-grid">
                                    <div v-for="(preview, index) in previews" :key="index" class="preview-item">
                                        <img :src="preview.url" :alt="`Preview ${index + 1}`" />
                                        <button @click="removePreview(index)" class="remove-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path
                                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                            </svg>
                                        </button>
                                        <span class="preview-name">{{ preview.name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div v-if="uploading" class="upload-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" :style="{ width: `${uploadProgress}%` }"></div>
                                </div>
                                <p class="progress-text">A enviar... {{ uploadProgress }}%</p>
                            </div>
                        </div>

                        <div class="dialog-footer">
                            <button @click="closeUploadDialog" :disabled="uploading" class="btn-secondary">
                                Cancelar
                            </button>
                            <button @click="uploadPhotos" :disabled="!selectedFiles.length || uploading"
                                class="btn-primary">
                                <span v-if="!uploading">Enviar Fotografias</span>
                                <span v-else>A enviar...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Lightbox -->
        <Teleport to="body">
            <Transition name="lightbox">
                <div v-if="lightboxOpen" class="lightbox" @click.self="closeLightbox">
                    <button @click="closeLightbox" class="lightbox-close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                        </svg>
                    </button>

                    <button @click="previousPhoto" :disabled="currentPhotoIndex === 0"
                        class="lightbox-nav lightbox-prev">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                        </svg>
                    </button>

                    <button @click="nextPhoto" :disabled="currentPhotoIndex === formattedPhotos.length - 1"
                        class="lightbox-nav lightbox-next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                        </svg>
                    </button>

                    <div class="lightbox-content">
                        <img v-if="currentPhoto" :src="currentPhoto.url" :alt="currentPhoto.name || 'Foto'" />
                        <div v-if="currentPhoto" class="lightbox-info">
                            <span class="lightbox-counter">{{ currentPhotoIndex + 1 }} / {{ formattedPhotos.length
                                }}</span>
                            <span class="lightbox-date">{{ formatDate(currentPhoto.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Delete Confirmation Dialog -->
        <Teleport to="body">
            <Transition name="dialog">
                <div v-if="deleteConfirmDialog" class="dialog-backdrop" @click.self="cancelDelete">
                    <div class="dialog confirm-dialog">
                        <div class="dialog-header">
                            <h2>Eliminar Fotografia?</h2>
                            <button @click="cancelDelete" class="close-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                </svg>
                            </button>
                        </div>

                        <div class="dialog-content">
                            <div v-if="photoToDelete" class="confirm-preview">
                                <img :src="photoToDelete.url" alt="Preview" />
                            </div>
                            <p class="confirm-text">
                                Esta ação é <strong>irreversível</strong>. A foto será eliminada permanentemente.
                            </p>
                        </div>

                        <div class="dialog-footer">
                            <button @click="cancelDelete" class="btn-secondary">
                                Cancelar
                            </button>
                            <button @click="deletePhoto" class="btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                </svg>
                                Eliminar Fotografia
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    token: {
        type: String,
        required: true
    },
    photos: {
        type: Array,
        default: () => []
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
})

// Transformar photos para incluir URL completa
const formattedPhotos = computed(() => {
    return props.photos.map(photo => ({
        id: photo.id,
        url: `/storage/${photo.path}`,
        name: null,
        created_at: photo.created_at
    }))
})

// State
const uploadDialog = ref(false)
const lightboxOpen = ref(false)
const selectedFiles = ref([])
const previews = ref([])
const uploading = ref(false)
const uploadProgress = ref(0)
const currentPhotoIndex = ref(0)
const fileInput = ref(null)
const deleteConfirmDialog = ref(false)
const photoToDelete = ref(null)

// Computed
const currentPhoto = computed(() => formattedPhotos.value[currentPhotoIndex.value])

// Methods
const triggerFileInput = () => {
    fileInput.value?.click()
}

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files || [])

    // Validar tamanho (15MB como no controller)
    const validFiles = files.filter(file => {
        if (file.size > 15 * 1024 * 1024) { // 15MB
            alert(`${file.name} excede o tamanho máximo de 15MB`)
            return false
        }
        return true
    })

    selectedFiles.value = validFiles
    previewFiles(validFiles)
}

const previewFiles = (files) => {
    previews.value = []

    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = (e) => {
            previews.value.push({
                url: e.target.result,
                name: file.name
            })
        }
        reader.readAsDataURL(file)
    })
}

const removePreview = (index) => {
    previews.value.splice(index, 1)
    selectedFiles.value.splice(index, 1)

    // Reset file input
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const uploadPhotos = async () => {
    if (!selectedFiles.value.length) return

    uploading.value = true
    uploadProgress.value = 0

    const formData = new FormData()
    selectedFiles.value.forEach(file => {
        formData.append('photos[]', file)
    })

    // Simular progresso
    const progressInterval = setInterval(() => {
        if (uploadProgress.value < 90) {
            uploadProgress.value += 10
        }
    }, 200)

    try {
        router.post(`/galeria/${props.token}/upload`, formData, {
            onSuccess: () => {
                clearInterval(progressInterval)
                uploadProgress.value = 100

                setTimeout(() => {
                    closeUploadDialog()
                    // Redirecionar para remover /upload da URL
                    router.visit(`/galeria/${props.token}`, {
                        preserveScroll: true,
                        preserveState: false
                    })
                }, 500)
            },
            onError: (errors) => {
                clearInterval(progressInterval)
                console.error('Erro no upload:', errors)
                alert('Erro ao enviar fotografias. Tente novamente.')
            },
            onFinish: () => {
                uploading.value = false
            }
        })
    } catch (error) {
        clearInterval(progressInterval)
        console.error('Erro no upload:', error)
        alert('Erro ao enviar fotografias. Tente novamente.')
        uploading.value = false
    }
}

const closeUploadDialog = () => {
    if (uploading.value) return

    uploadDialog.value = false
    selectedFiles.value = []
    previews.value = []
    uploadProgress.value = 0

    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const openLightbox = (index) => {
    currentPhotoIndex.value = index
    lightboxOpen.value = true
    document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
    lightboxOpen.value = false
    document.body.style.overflow = ''
}

const nextPhoto = () => {
    if (currentPhotoIndex.value < props.photos.length - 1) {
        currentPhotoIndex.value++
    }
}

const previousPhoto = () => {
    if (currentPhotoIndex.value > 0) {
        currentPhotoIndex.value--
    }
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    const now = new Date()
    const diffTime = Math.abs(now - date)
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

    if (diffDays === 1) return 'Hoje'
    if (diffDays === 2) return 'Ontem'
    if (diffDays < 7) return `Há ${diffDays} dias`

    return date.toLocaleDateString('pt-PT', {
        day: 'numeric',
        month: 'short',
        year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
    })
}

/**
 * Confirmar eliminação de foto
 */
const confirmDelete = (photo) => {
    photoToDelete.value = photo
    deleteConfirmDialog.value = true
}

/**
 * Eliminar foto (apenas admin)
 */
const deletePhoto = async () => {
    if (!photoToDelete.value) return

    try {
        await router.delete(`/galeria/${props.token}/photo/${photoToDelete.value.id}`, {
            onSuccess: () => {
                deleteConfirmDialog.value = false
                photoToDelete.value = null
            },
            onError: (errors) => {
                console.error('Erro ao eliminar foto:', errors)
                alert('Erro ao eliminar foto. Verifique se tem permissões de administrador.')
            }
        })
    } catch (error) {
        console.error('Erro ao eliminar foto:', error)
        alert('Erro ao eliminar foto. Tente novamente.')
    }
}

/**
 * Cancelar eliminação
 */
const cancelDelete = () => {
    deleteConfirmDialog.value = false
    photoToDelete.value = null
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Outfit:wght@300;400;600&display=swap');

* {
    box-sizing: border-box;
}

.gallery-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #e8eef5 0%, #b4cce9 100%);
    padding: 2rem 1rem 4rem;
    font-family: 'Outfit', sans-serif;
}

/* Header */
.gallery-header {
    max-width: 1400px;
    margin: 0 auto 3rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
    padding: 0 1rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #b4cce9 0%, #8fb3d9 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 8px 24px rgba(180, 204, 233, 0.4);
    overflow: hidden;
}

.header-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.header-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.header-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.gallery-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 900;
    margin: 0;
    color: #1a1a2e;
    letter-spacing: -0.02em;
}

.gallery-subtitle {
    font-size: 1rem;
    color: #64748b;
    margin: 0;
    font-weight: 300;
}

.upload-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #b4cce9 0%, #8fb3d9 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(180, 204, 233, 0.4);
}

.upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(180, 204, 233, 0.5);
}

.upload-btn svg {
    width: 20px;
    height: 20px;
}

/* Empty State */
.empty-state {
    max-width: 500px;
    margin: 6rem auto;
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, #e1ecf7 0%, #d4e4f5 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8fb3d9;
}

.empty-icon svg {
    width: 64px;
    height: 64px;
}

.empty-state h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: #1a1a2e;
    margin: 0 0 0.5rem;
}

.empty-state p {
    color: #64748b;
    font-size: 1.125rem;
    margin: 0 0 2rem;
    font-weight: 300;
}

.empty-cta {
    padding: 1rem 2.5rem;
    background: linear-gradient(135deg, #b4cce9 0%, #8fb3d9 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(180, 204, 233, 0.4);
}

.empty-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(180, 204, 233, 0.5);
}

/* Photo Grid */
.photo-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 0 1rem;
}

.photo-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) backwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.photo-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}

.photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
    padding: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.photo-item:hover .photo-overlay {
    opacity: 1;
}

.photo-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.photo-date {
    color: white;
    font-size: 0.875rem;
    font-weight: 400;
}

/* Floating Action Button */
.fab {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #b4cce9 0%, #8fb3d9 100%);
    border: none;
    color: white;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(180, 204, 233, 0.5);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fab:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 12px 32px rgba(180, 204, 233, 0.6);
}

.fab svg {
    width: 28px;
    height: 28px;
}

/* Dialog */
.dialog-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}

.dialog {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.2);
}

.dialog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.dialog-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem;
    margin: 0;
    color: #1a1a2e;
}

.close-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #f1f5f9;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: #64748b;
}

.close-btn:hover {
    background: #e2e8f0;
    color: #1a1a2e;
}

.close-btn svg {
    width: 20px;
    height: 20px;
}

.dialog-content {
    flex: 1;
    overflow-y: auto;
    padding: 2rem;
}

/* Upload Zone */
.upload-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.upload-zone:hover {
    border-color: #b4cce9;
    background: #f0f6fc;
}

.upload-zone-content svg {
    width: 48px;
    height: 48px;
    color: #8fb3d9;
    margin-bottom: 1rem;
}

.upload-text {
    font-size: 1.125rem;
    color: #1a1a2e;
    margin: 0 0 0.5rem;
    font-weight: 600;
}

.upload-hint {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 1rem;
}

.upload-limit {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0;
}

/* Preview Section */
.preview-section {
    margin-top: 2rem;
}

.preview-section h3 {
    font-size: 1rem;
    color: #1a1a2e;
    margin: 0 0 1rem;
    font-weight: 600;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
}

.preview-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    background: #f1f5f9;
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.95);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
    color: #64748b;
}

.remove-btn:hover {
    background: #fee;
    color: #dc2626;
}

.remove-btn svg {
    width: 16px;
    height: 16px;
}

.preview-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
    color: white;
    font-size: 0.625rem;
    padding: 1rem 0.5rem 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Upload Progress */
.upload-progress {
    margin-top: 2rem;
}

.progress-bar {
    height: 8px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #b4cce9 0%, #8fb3d9 100%);
    transition: width 0.3s ease;
    border-radius: 999px;
}

.progress-text {
    text-align: center;
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: #64748b;
}

.dialog-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-secondary,
.btn-primary {
    padding: 0.875rem 1.75rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-secondary {
    background: #f1f5f9;
    color: #64748b;
}

.btn-secondary:hover:not(:disabled) {
    background: #e2e8f0;
    color: #1a1a2e;
}

.btn-primary {
    background: linear-gradient(135deg, #b4cce9 0%, #8fb3d9 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(180, 204, 233, 0.4);
}

.btn-primary:hover:not(:disabled) {
    box-shadow: 0 6px 20px rgba(180, 204, 233, 0.5);
    transform: translateY(-1px);
}

.btn-primary:disabled,
.btn-secondary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Lightbox */
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 10;
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.lightbox-close svg {
    width: 24px;
    height: 24px;
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 10;
}

.lightbox-nav:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.2);
}

.lightbox-nav:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.lightbox-nav svg {
    width: 24px;
    height: 24px;
}

.lightbox-prev {
    left: 1.5rem;
}

.lightbox-next {
    right: 1.5rem;
}

.lightbox-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    max-width: 90vw;
    max-height: 85vh;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5);
}

.lightbox-info {
    display: flex;
    gap: 2rem;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 1rem 1.5rem;
    border-radius: 999px;
    color: white;
}

.lightbox-counter,
.lightbox-date {
    font-size: 0.875rem;
    font-weight: 400;
}

/* Transitions */
.dialog-enter-active,
.dialog-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.dialog-enter-from,
.dialog-leave-to {
    opacity: 0;
}

.dialog-enter-from .dialog,
.dialog-leave-to .dialog {
    transform: scale(0.9);
    opacity: 0;
}

.lightbox-enter-active,
.lightbox-leave-active {
    transition: all 0.3s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
    opacity: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-container {
        padding: 1rem 0.5rem 3rem;
    }

    .gallery-header {
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .gallery-title {
        font-size: 2rem;
    }

    .upload-btn {
        width: 100%;
        justify-content: center;
    }

    .photo-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
    }

    .fab {
        bottom: 1rem;
        right: 1rem;
        width: 56px;
        height: 56px;
    }

    .lightbox-nav {
        width: 40px;
        height: 40px;
    }

    .lightbox-prev {
        left: 0.75rem;
    }

    .lightbox-next {
        right: 0.75rem;
    }
}

/* Delete Button (Admin) */
.delete-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(239, 68, 68, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    color: white;
    opacity: 0;
}

.delete-btn svg {
    width: 20px;
    height: 20px;
}

.delete-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.5);
    background: rgba(220, 38, 38, 0.95);
}

.photo-item:hover .delete-btn {
    opacity: 1;
}

/* Confirm Dialog */
.confirm-dialog {
    max-width: 450px;
}

.confirm-preview {
    width: 100%;
    max-height: 300px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    background: #f1f5f9;
}

.confirm-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.confirm-text {
    font-size: 1rem;
    color: #1a1a2e;
    margin: 0;
    text-align: center;
}

.confirm-text strong {
    color: #dc2626;
    font-weight: 700;
}

.btn-danger {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-danger svg {
    width: 18px;
    height: 18px;
}

.btn-danger:hover {
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    transform: translateY(-1px);
}

/* Mobile adjustments for delete button */
@media (max-width: 768px) {
    .delete-btn {
        opacity: 1;
        width: 36px;
        height: 36px;
        top: 8px;
        right: 8px;
    }

    .delete-btn svg {
        width: 18px;
        height: 18px;
    }
}
</style>