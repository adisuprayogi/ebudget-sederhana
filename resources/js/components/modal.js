// Modal component
export default () => ({
    show: false,
    title: '',
    content: '',
    size: 'md', // sm, md, lg, xl

    init() {
        this.$watch('show', (value) => {
            if (value) {
                document.body.classList.add('overflow-hidden')
            } else {
                document.body.classList.remove('overflow-hidden')
            }
        })
    },

    open(title, content = '', size = 'md') {
        this.title = title
        this.content = content
        this.size = size
        this.show = true
    },

    close() {
        this.show = false
        this.title = ''
        this.content = ''
        this.size = 'md'
    },

    closeOnEscape(event) {
        if (event.key === 'Escape' && this.show) {
            this.close()
        }
    }
})