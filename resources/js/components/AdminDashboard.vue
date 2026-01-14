<template>
    <div class="dashboard-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ stats.total }}</div>
                <div class="stat-label">Total de Respostas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ stats.confirmados }}</div>
                <div class="stat-label">Confirmaram Presença</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ stats.total_convidados }}</div>
                <div class="stat-label">Total de Convidados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ stats.nao_confirmados }}</div>
                <div class="stat-label">Não Podem Comparecer</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <div class="filters">
                <select v-model="filters.presenca" @change="loadConfirmacoes">
                    <option value="todos">Todos</option>
                    <option value="sim">Confirmados</option>
                    <option value="nao">Não Confirmados</option>
                </select>

                <input v-model="filters.busca" type="text" placeholder="Buscar por nome, email ou telefone..."
                    @input="debounceSearch" />

                <button @click="clearFilters" class="clear-btn">Limpar</button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading">
            Carregando...
        </div>

        <!-- Table -->
        <div v-else class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Presença</th>
                        <th>Parceiro</th>
                        <th>Filhos</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="confirmacao in confirmacoes.data" :key="confirmacao.id">
                        <td>{{ confirmacao.nome }}</td>
                        <td>{{ confirmacao.idade }} anos</td>
                        <td>
                            <span :class="['badge', `badge-${confirmacao.presenca}`]">
                                {{ confirmacao.presenca === 'sim' ? '✓ Confirma' : '✗ Não confirma' }}
                            </span>
                        </td>
                        <td>{{ confirmacao.tem_parceiro ? '✓' : '-' }}</td>
                        <td>{{ confirmacao.tem_filhos ? '✓' : '-' }}</td>
                        <td>{{ formatDate(confirmacao.created_at) }}</td>
                        <td>
                            <button @click="showDetails(confirmacao)" class="details-btn">
                                Ver Detalhes
                            </button>
                        </td>
                    </tr>
                    <tr v-if="confirmacoes.data.length === 0">
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #86868b;">
                            Nenhuma confirmação encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="confirmacoes.last_page > 1" class="pagination">
                <button @click="goToPage(confirmacoes.current_page - 1)" :disabled="confirmacoes.current_page === 1">
                    Anterior
                </button>
                <span>Página {{ confirmacoes.current_page }} de {{ confirmacoes.last_page }}</span>
                <button @click="goToPage(confirmacoes.current_page + 1)"
                    :disabled="confirmacoes.current_page === confirmacoes.last_page">
                    Próxima
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="selectedConfirmacao" class="modal" @click.self="closeModal">
            <div class="modal-content">
                <span class="modal-close" @click="closeModal">&times;</span>
                <h2>Detalhes da Confirmação</h2>

                <div class="detail-group">
                    <div class="detail-label">Nome Completo</div>
                    <div class="detail-value">{{ selectedConfirmacao.nome }}</div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Idade</div>
                    <div class="detail-value">{{ selectedConfirmacao.idade }} anos</div>
                </div>

                <div v-if="selectedConfirmacao.email" class="detail-group">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ selectedConfirmacao.email }}</div>
                </div>

                <div v-if="selectedConfirmacao.telefone" class="detail-group">
                    <div class="detail-label">Telefone</div>
                    <div class="detail-value">{{ selectedConfirmacao.telefone }}</div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Presença</div>
                    <div class="detail-value">
                        <span :class="['badge', `badge-${selectedConfirmacao.presenca}`]">
                            {{ selectedConfirmacao.presenca === 'sim' ? '✓ Confirmada' : '✗ Não confirmada' }}
                        </span>
                    </div>
                </div>

                <div v-if="selectedConfirmacao.parceiro" class="detail-group">
                    <div class="detail-label">Acompanhante</div>
                    <div class="detail-value">
                        {{ selectedConfirmacao.parceiro.nome }}
                        ({{ selectedConfirmacao.parceiro.idade }} anos)
                    </div>
                </div>

                <div v-if="selectedConfirmacao.filhos && selectedConfirmacao.filhos.length > 0" class="detail-group">
                    <div class="detail-label">Filhos</div>
                    <div class="detail-value">
                        <div v-for="filho in selectedConfirmacao.filhos" :key="filho.id">
                            {{ filho.nome }} ({{ filho.idade }} anos)
                        </div>
                    </div>
                </div>

                <div v-if="selectedConfirmacao.restricoes" class="detail-group">
                    <div class="detail-label">Restrições Alimentares</div>
                    <div class="detail-value">{{ selectedConfirmacao.restricoes }}</div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Data da Confirmação</div>
                    <div class="detail-value">{{ formatDate(selectedConfirmacao.created_at) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

// State
const stats = reactive({
    total: 0,
    confirmados: 0,
    nao_confirmados: 0,
    total_convidados: 0,
    com_parceiro: 0,
    com_filhos: 0,
});

const confirmacoes = ref({
    data: [],
    current_page: 1,
    last_page: 1,
});

const filters = reactive({
    presenca: 'todos',
    busca: '',
});

const loading = ref(false);
const selectedConfirmacao = ref(null);
let searchTimeout = null;

// Methods
const loadStats = async () => {
    try {
        const response = await axios.get('/api/confirmacoes/stats/all');
        if (response.data.success) {
            Object.assign(stats, response.data.data);
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
};

const loadConfirmacoes = async (page = 1) => {
    loading.value = true;
    try {
        const params = {
            page,
            presenca: filters.presenca,
            busca: filters.busca,
        };

        const response = await axios.get('/api/confirmacoes', { params });
        if (response.data.success) {
            confirmacoes.value = response.data.data;
        }
    } catch (error) {
        console.error('Erro ao carregar confirmações:', error);
    } finally {
        loading.value = false;
    }
};

const showDetails = async (confirmacao) => {
    try {
        const response = await axios.get(`/api/confirmacoes/${confirmacao.id}`);
        if (response.data.success) {
            selectedConfirmacao.value = response.data.data;
        }
    } catch (error) {
        console.error('Erro ao carregar detalhes:', error);
    }
};

const closeModal = () => {
    selectedConfirmacao.value = null;
};

const goToPage = (page) => {
    loadConfirmacoes(page);
};

const clearFilters = () => {
    filters.presenca = 'todos';
    filters.busca = '';
    loadConfirmacoes();
};

const debounceSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadConfirmacoes();
    }, 500);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-PT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Lifecycle
onMounted(() => {
    loadStats();
    loadConfirmacoes();
});
</script>

<style scoped>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 18px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 600;
    color: #0071e3;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #86868b;
    font-size: 0.95rem;
}

.filters-section {
    background: white;
    padding: 1.5rem;
    border-radius: 18px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filters {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filters select,
.filters input {
    padding: 0.7rem 1rem;
    border: 1px solid #d2d2d7;
    border-radius: 10px;
    font-size: 0.95rem;
}

.filters input {
    flex: 1;
    min-width: 250px;
}

.clear-btn {
    padding: 0.7rem 1.5rem;
    background: #86868b;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.table-container {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f5f5f7;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #1d1d1f;
    border-bottom: 2px solid #e5e5e5;
}

td {
    padding: 1rem;
    border-bottom: 1px solid #e5e5e5;
}

tr:hover {
    background: #fafafa;
}

.badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-sim {
    background: #d1f4e0;
    color: #0d5028;
}

.badge-nao {
    background: #ffe0e0;
    color: #a30000;
}

.details-btn {
    padding: 0.5rem 1rem;
    background: #0071e3;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
}

.details-btn:hover {
    background: #0077ed;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.pagination button {
    padding: 0.5rem 1rem;
    background: #0071e3;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.pagination button:disabled {
    background: #d2d2d7;
    cursor: not-allowed;
}

.loading {
    text-align: center;
    padding: 3rem;
    color: #86868b;
    font-size: 1.1rem;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 18px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-close {
    float: right;
    font-size: 1.5rem;
    cursor: pointer;
    color: #86868b;
}

.detail-group {
    margin-bottom: 1.5rem;
}

.detail-label {
    font-weight: 600;
    color: #86868b;
    font-size: 0.9rem;
    margin-bottom: 0.3rem;
}

.detail-value {
    color: #1d1d1f;
    font-size: 1rem;
}
</style>