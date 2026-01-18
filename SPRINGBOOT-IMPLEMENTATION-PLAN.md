# Implementation Plan: E-Budget Sederhana dengan Spring Boot & Maven

## Overview
Membuat aplikasi manajemen anggaran enterprise yang identik dengan E-Budget Sederhana (Laravel) menggunakan Spring Boot dan Maven.

---

## Teknologi Stack

### Backend
| Komponen | Teknologi |
|----------|-----------|
| Framework | Spring Boot 3.3.x |
| Build Tool | Maven 3.9.x |
| Java Version | JDK 21 (LTS) |
| Database | PostgreSQL / MySQL |
| ORM | Spring Data JPA (Hibernate) |
| Authentication | Spring Security + JWT |
| API Documentation | SpringDoc OpenAPI |
| Testing | JUnit 5, Mockito |
| Lombok | Code generation |

### Frontend (Terpisah atau Embedded)
- Alpine.js + TailwindCSS (sama dengan aslinya)
- Vite untuk build
- Tetap menggunakan blade-like template (Thymeleaf)

---

## Struktur Project

```
ebudget-springboot/
├── src/
│   ├── main/
│   │   ├── java/com/ebudget/
│   │   │   ├── EbudgetApplication.java
│   │   │   ├── config/                 # Configuration
│   │   │   │   ├── SecurityConfig.java
│   │   │   │   ├── JwtConfig.java
│   │   │   │   ├── DatabaseConfig.java
│   │   │   │   └── SwaggerConfig.java
│   │   │   ├── controller/             # REST Controllers
│   │   │   │   ├── auth/
│   │   │   │   ├── master/
│   │   │   │   ├── anggaran/
│   │   │   │   ├── program/
│   │   │   │   ├── pengajuan/
│   │   │   │   ├── pencairan/
│   │   │   │   ├── laporan/
│   │   │   │   └── approval/
│   │   │   ├── service/                # Business Logic
│   │   │   │   ├── auth/
│   │   │   │   ├── master/
│   │   │   │   ├── anggaran/
│   │   │   │   ├── program/
│   │   │   │   ├── pengajuan/
│   │   │   │   ├── pencairan/
│   │   │   │   ├── laporan/
│   │   │   │   └── approval/
│   │   │   ├── repository/             # Data Access
│   │   │   │   ├── UserRepository.java
│   │   │   │   ├── RoleRepository.java
│   │   │   │   ├── DivisiRepository.java
│   │   │   │   └── ... (lainnya)
│   │   │   ├── entity/                 # JPA Entities
│   │   │   │   ├── User.java
│   │   │   │   ├── Role.java
│   │   │   │   ├── Divisi.java
│   │   │   │   ├── PeriodeAnggaran.java
│   │   │   │   ├── ProgramKerja.java
│   │   │   │   ├── PengajuanDana.java
│   │   │   │   ├── PencairanDana.java
│   │   │   │   └── ... (lainnya)
│   │   │   ├── dto/                    # Data Transfer Objects
│   │   │   │   ├── request/
│   │   │   │   ├── response/
│   │   │   │   └── mapper/
│   │   │   ├── exception/              # Exception Handling
│   │   │   │   ├── BusinessException.java
│   │   │   │   ├── ResourceNotFoundException.java
│   │   │   │   └── GlobalExceptionHandler.java
│   │   │   ├── security/               # Security
│   │   │   │   ├── JwtTokenProvider.java
│   │   │   │   ├── JwtAuthenticationFilter.java
│   │   │   │   └── UserDetailsServiceImpl.java
│   │   │   └── util/                   # Utilities
│   │   │       ├── DateUtil.java
│   │   │       ├── ExcelUtil.java
│   │   │       └── FileUtil.java
│   │   └── resources/
│   │       ├── application.yml
│   │       ├── application-dev.yml
│   │       ├── application-prod.yml
│   │       └── db/
│   │           └── migration/          # Flyway migrations
│   └── test/
│       └── java/com/ebudget/
│           ├── controller/
│           ├── service/
│           └── repository/
├── pom.xml
└── README.md
```

---

## Tahapan Implementasi

### Phase 1: Project Setup & Infrastructure
- [ ] Buat Maven project structure
- [ ] Konfigurasi pom.xml dengan dependencies
- [ ] Setup application.yml dengan profile
- [ ] Konfigurasi database connection
- [ ] Setup Flyway untuk database migration
- [ ] Konfigurasi Spring Security + JWT
- [ ] Setup Swagger/OpenAPI documentation

### Phase 2: Master Data Module
- [ ] Entity: User, Role, Divisi, JobPosition
- [ ] Entity: Vendor, Bank, RekeningPerusahaan
- [ ] Entity: SumberDana
- [ ] Repository & Service layer
- [ ] REST API Controllers
- [ ] CRUD operations
- [ ] Role-based permission check

### Phase 3: Modul Perencanaan Anggaran
- [ ] Entity: PeriodeAnggaran
- [ ] Entity: PerencanaanPenerimaan
- [ ] Entity: PencatatanPenerimaan
- [ ] Entity: PenetapanPagu
- [ ] Repository & Service layer
- [ ] Controllers untuk perencanaan
- [ ] Validasi periode aktif

### Phase 4: Modul Program Kerja
- [ ] Entity: ProgramKerja
- [ ] Entity: SubProgram
- [ ] Entity: DetailAnggaran
- [ ] Entity: EstimasiPengeluaran
- [ ] Repository & Service layer
- [ ] Controllers untuk program
- [ ] Logic hierarchy program

### Phase 5: Modul Pengajuan Dana
- [ ] Entity: PengajuanDana
- [ ] Entity: DetailPengajuan
- [ ] Entity: HonorariumDetail
- [ ] Entity: Attachment
- [ ] Repository & Service layer
- [ ] File upload handling
- [ ] Validasi pagu anggaran
- [ ] Trigger approval workflow

### Phase 6: Modul Pencairan Dana
- [ ] Entity: PencairanDana
- [ ] Entity: DetailPencairan
- [ ] Repository & Service layer
- [ ] Integration dengan rekening perusahaan
- [ ] Validasi status pencairan
- [ ] Update status pengajuan

### Phase 7: Modul LPJ & Refund
- [ ] Entity: LaporanPertanggungJawaban
- [ ] Entity: DetailLPJ
- [ ] Entity: Refund
- [ ] Repository & Service layer
- [ ] Logic kalkulasi selisih
- [ ] Validasi LPJ complete

### Phase 8: Sistem Approval
- [ ] Entity: Approval
- [ ] Entity: ApprovalConfig
- [ ] Repository & Service layer
- [ ] Workflow engine sederhana
- [ ] Notifikasi system
- [ ] Bulk approval

### Phase 9: Modul Laporan
- [ ] ReportService untuk berbagai laporan
- [ ] Laporan Realisasi Anggaran
- [ ] Laporan Pengajuan vs Pencairan
- [ ] Laporan LPJ
- [ ] Laporan Refund
- [ ] Export Excel dengan Apache POI

### Phase 10: Frontend Integration
- [ ] Setup Thymeleaf templates
- [ ] Port Alpine.js components dari Laravel
- [ ] API integration dengan frontend
- [ ] Implementasi reactive UI

### Phase 11: Testing
- [ ] Unit test untuk Services
- [ ] Integration test untuk Controllers
- [ ] Repository tests
- [ ] Security tests

### Phase 12: Documentation & Deployment
- [ ] API documentation lengkap
- [ ] README dengan setup instructions
- [ ] Docker configuration
- [ ] Deployment script

---

## Detail Entity Mapping

### User Management
```java
@Entity
@Table(name = "users")
public class User {
    @Id @GeneratedValue(strategy = IDENTITY)
    private Long id;
    private String name;
    @Column(unique = true, nullable = false)
    private String email;
    private String password;
    private String phone;
    private Boolean isActive;
    @ManyToOne
    private Role role;
    @ManyToOne
    private Divisi divisi;
    @ManyToMany
    private List<JobPosition> jobPositions;
    // timestamps, audit fields
}

@Entity
@Table(name = "roles")
public class Role {
    @Id @GeneratedValue(strategy = IDENTITY)
    private Long id;
    @Column(unique = true)
    private String name;
    private String description;
    @Convert(converter = JsonPermissionConverter.class)
    private Map<String, List<String>> permissions;
    // timestamps
}
```

### Transaksi Utama
```java
@Entity
@Table(name = "pengajuan_dana")
public class PengajuanDana {
    @Id @GeneratedValue(strategy = IDENTITY)
    private Long id;

    @Enumerated(EnumType.STRING)
    private TipePengajuan tipePengajuan; // KEGIATAN, PENGADAAN, PEMBAYARAN, dll

    @Enumerated(EnumType.STRING)
    private StatusPengajuan status; // DRAFT, PENDING, APPROVED, REJECTED, DISBURSED

    private String nomorPengajuan;
    private LocalDate tanggalPengajuan;
    private BigDecimal totalAmount;

    @ManyToOne
    private User pengusul;

    @ManyToOne
    private Divisi divisi;

    @ManyToOne
    private ProgramKerja programKerja;

    @ManyToOne
    private SubProgram subProgram;

    @ManyToOne
    private PeriodeAnggaran periodeAnggaran;

    private String tujuanPenggunaan;
    private String keterangan;

    @OneToMany(mappedBy = "pengajuanDana", cascade = ALL)
    private List<DetailPengajuan> details;

    @OneToMany(mappedBy = "pengajuanDana", cascade = ALL)
    private List<Approval> approvals;

    @OneToMany(mappedBy = "pengajuanDana", cascade = ALL)
    private List<Attachment> attachments;

    // timestamps
}
```

---

## API Endpoints Structure

```
/api/v1/
├── auth/
│   ├── POST /login
│   ├── POST /logout
│   ├── POST /refresh
│   └── GET  /me
├── master/
│   ├── users/          (CRUD + change-password)
│   ├── roles/          (CRUD + permissions)
│   ├── divisions/      (CRUD)
│   ├── job-positions/  (CRUD)
│   ├── vendors/        (CRUD)
│   ├── banks/          (CRUD)
│   └── fund-sources/   (CRUD)
├── anggaran/
│   ├── periode/        (CRUD + open/close)
│   ├── penerimaan/     (plan & actual)
│   └── pagu/           (allocation)
├── program/
│   ├── programs/       (CRUD)
│   ├── sub-programs/   (CRUD)
│   └── details/        (CRUD)
├── pengajuan/
│   ├── /               (create, list, detail)
│   ├── /{id}/submit    (submit for approval)
│   ├── /{id}/approve   (approve action)
│   ├── /{id}/reject    (reject action)
│   └── /types          (honorarium handling)
├── pencairan/
│   ├── /               (create, list)
│   ├── /{id}/verify    (verify beneficiary)
│   └── /{id}/release   (release fund)
├── lpj/
│   ├── /               (create, list)
│   └── /{id}           (detail, update)
├── refund/
│   ├── /               (create, list)
│   └── /{id}/process   (process refund)
├── laporan/
│   ├── /realisasi      (budget realization)
│   ├── /pengajuan      (request report)
│   ├── /pencairan      (disbursement report)
│   └── /executive      (executive summary)
└── approval/
    ├── /pending        (my pending approvals)
    ├── /history        (approval history)
    └── /bulk           (bulk approve)
```

---

## Konfigurasi Maven (pom.xml)

### Dependencies Utama:
- spring-boot-starter-web
- spring-boot-starter-data-jpa
- spring-boot-starter-security
- spring-boot-starter-validation
- spring-boot-starter-thymeleaf (optional)
- spring-boot-starter-mail (notifikasi)
- springdoc-openapi-starter-webmvc-ui
- jjwt (JWT token)
- postgresql / mysql
- flyway-core
- lombok
- apache-poi (Excel export)
- commons-lang3
- modelmapper

---

## Catatan Penting

1. **Mapping dari Laravel ke Spring Boot:**
   - Migrations → Flyway migrations
   - Models → JPA Entities
   - Controllers → REST Controllers
   - Blade Templates → Thymeleaf (atau frontend terpisah)
   - Eloquent ORM → Spring Data JPA
   - Auth/Security → Spring Security + JWT

2. **Fitur yang perlu implementasi khusus:**
   - JSON permissions storage → @Converter untuk Map<String, List<String>>
   - File upload → Multipart handling
   - Approval workflow → State pattern atau workflow engine sederhana
   - Dynamic role permissions → Method security with @PreAuthorize

3. **Testing Strategy:**
   - Unit tests untuk business logic
   - Integration tests untuk API endpoints
   - Testcontainers untuk database testing

4. **Deployment:**
   - Docker containerization
   - Environment-based configuration
   - Database migration on startup

---

## Estimasi Modul

| Modul | Entity | Controller | Service | Repository |
|-------|--------|------------|---------|------------|
| Master Data | 8 | 8 | 8 | 8 |
| Anggaran | 4 | 4 | 4 | 4 |
| Program | 4 | 4 | 4 | 4 |
| Pengajuan | 4 | 6 | 6 | 4 |
| Pencairan | 2 | 4 | 4 | 2 |
| LPJ/Refund | 3 | 4 | 4 | 3 |
| Approval | 2 | 3 | 3 | 2 |
| Laporan | - | 6 | 6 | - |
| **TOTAL** | **27** | **39** | **39** | **27** |

---

## File yang akan dibuat: ±150-200 files

Aplikasi ini cukup kompleks dan akan memerlukan waktu untuk implementasi lengkap. Disarankan untuk dikerjakan secara bertahap per phase.
