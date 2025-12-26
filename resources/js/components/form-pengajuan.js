// Form pengajuan component
export default () => ({
    jenisPengajuan: '',
    penerimaManfaatType: '',
    penerimaManfaatId: null,
    penerimaManfaatName: '',
    penerimaManfaatDetail: {},
    details: [],
    total: 0,

    // Available options based on jenis pengajuan
    penerimaOptions: {
        kegiatan: [
            { type: 'pic_kegiatan', label: 'PIC Kegiatan' }
        ],
        pengadaan: [
            { type: 'pengaju', label: 'Pengaju' }
        ],
        pembayaran: [
            { type: 'pegawai', label: 'Pegawai Internal' },
            { type: 'vendor', label: 'Vendor' },
            { type: 'non_pegawai', label: 'Non-Pegawai' }
        ],
        honorarium: [
            { type: 'pegawai', label: 'Pegawai Internal' },
            { type: 'non_pegawai', label: 'Non-Pegawai' }
        ],
        sewa: [
            { type: 'vendor', label: 'Vendor' }
        ],
        konsumsi: [
            { type: 'pengaju', label: 'Pengaju' }
        ],
        lainnya: [
            { type: 'pegawai', label: 'Pegawai Internal' },
            { type: 'vendor', label: 'Vendor' },
            { type: 'non_pegawai', label: 'Non-Pegawai' },
            { type: 'pengaju', label: 'Pengaju' }
        ]
    },

    init() {
        this.addDetail()
    },

    get currentPenerimaOptions() {
        return this.penerimaOptions[this.jenisPengajuan] || []
    },

    onJenisPengajuanChange() {
        this.penerimaManfaatType = ''
        this.penerimaManfaatId = null
        this.penerimaManfaatName = ''
        this.penerimaManfaatDetail = {}
    },

    addDetail() {
        this.details.push({
            sub_program_id: null,
            uraian: '',
            volume: 1,
            satuan: '',
            harga_satuan: 0,
            subtotal: 0
        })
    },

    removeDetail(index) {
        if (this.details.length > 1) {
            this.details.splice(index, 1)
            this.calculateTotal()
        }
    },

    calculateSubtotal(index) {
        const detail = this.details[index]
        detail.subtotal = detail.volume * detail.harga_satuan
        this.calculateTotal()
    },

    calculateTotal() {
        this.total = this.details.reduce((sum, detail) => {
            return sum + (parseFloat(detail.subtotal) || 0)
        }, 0)
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(value)
    },

    reset() {
        this.jenisPengajuan = ''
        this.penerimaManfaatType = ''
        this.penerimaManfaatId = null
        this.penerimaManfaatName = ''
        this.penerimaManfaatDetail = {}
        this.details = []
        this.total = 0
        this.addDetail()
    }
})