// Datatable component
export default () => ({
    data: [],
    filteredData: [],
    columns: [],
    perPage: 10,
    currentPage: 1,
    search: '',
    sort: {
        column: null,
        direction: 'asc'
    },

    init() {
        this.filteredData = this.data
    },

    get paginatedData() {
        const start = (this.currentPage - 1) * this.perPage
        const end = start + this.perPage
        return this.filteredData.slice(start, end)
    },

    get totalPages() {
        return Math.ceil(this.filteredData.length / this.perPage)
    },

    get from() {
        return (this.currentPage - 1) * this.perPage + 1
    },

    get to() {
        let to = this.currentPage * this.perPage
        return to > this.filteredData.length ? this.filteredData.length : to
    },

    searchItems() {
        if (!this.search) {
            this.filteredData = this.data
            return
        }

        this.filteredData = this.data.filter(item => {
            return this.columns.some(column => {
                const value = this.getNestedValue(item, column.key)
                return value != null && value.toString().toLowerCase().includes(this.search.toLowerCase())
            })
        })

        this.currentPage = 1
    },

    sortItems(column) {
        if (this.sort.column === column.key) {
            this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc'
        } else {
            this.sort.column = column.key
            this.sort.direction = 'asc'
        }

        this.filteredData.sort((a, b) => {
            const aValue = this.getNestedValue(a, column.key)
            const bValue = this.getNestedValue(b, column.key)

            if (aValue === null || aValue === undefined) return 1
            if (bValue === null || bValue === undefined) return -1

            const comparison = aValue.toString().localeCompare(bValue.toString())
            return this.sort.direction === 'asc' ? comparison : -comparison
        })
    },

    getNestedValue(obj, path) {
        return path.split('.').reduce((current, key) => current && current[key], obj)
    },

    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page
        }
    },

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++
        }
    },

    reset() {
        this.search = ''
        this.sort = { column: null, direction: 'asc' }
        this.currentPage = 1
        this.filteredData = this.data
    }
})