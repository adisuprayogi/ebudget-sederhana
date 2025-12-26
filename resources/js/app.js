import './bootstrap';

import Alpine from 'alpinejs'

// Import Alpine plugins
import persist from '@alpinejs/persist'
import mask from '@alpinejs/mask'
import anchor from '@alpinejs/anchor'
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'

// Import components
import './components/modal'
import './components/dropdown'
import './components/datatable'
import './components/form-pengajuan'

// Register plugins
Alpine.plugin(persist)
Alpine.plugin(mask)
Alpine.plugin(anchor)
Alpine.plugin(collapse)
Alpine.plugin(focus)

// Global data
Alpine.data('app', () => ({
    user: null,

    init() {
        this.getUser()
    },

    getUser() {
        // Fetch user data if needed
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(value)
    },

    formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID')
    }
}))

// Start Alpine
window.Alpine = Alpine
Alpine.start()
