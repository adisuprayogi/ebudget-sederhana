// Dropdown component
export default () => ({
    show: false,
    align: 'left', // left, right, center

    init() {
        // Close dropdown when clicking outside
        const handleClick = (event) => {
            if (!this.$el.contains(event.target)) {
                this.show = false
            }
        }

        document.addEventListener('click', handleClick)
        this.$el.addEventListener('click', (e) => e.stopPropagation())

        // Cleanup
        this.$destroy = () => {
            document.removeEventListener('click', handleClick)
        }
    },

    toggle() {
        this.show = !this.show
    },

    close() {
        this.show = false
    },

    open() {
        this.show = true
    }
})