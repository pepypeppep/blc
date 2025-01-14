```mermaid
flowchart TD
    A(["Mulai"]) --> B["Admin OPD membuat kursus baru"]
    B --> C{"Apakah kursus internal atau publik?"}
    C -- Internal --> D["Menugaskan Pegawai ke kursus"]
    C -- Publik --> E["Pegawai dapat bergabung ke kursus"]
    D --> F["Superadmin meninjau kursus"]
    E --> F
    F --> G{"Apakah kursus disetujui?"}
    G -- Ya --> H["Menerbitkan kursus"]
    G -- Tidak --> I["Admin OPD merevisi dan mengajukan ulang kursus"]
    I --> F
    H --> J["Pegawai bergabung ke kursus"]
    J --> K["Pegawai menyelesaikan materi dan kuis"]
    K --> L["Pegawai mengunggah laporan ke KMS"]
    L --> M["Admin OPD/Superadmin meninjau laporan"]
    M --> N{"Apakah laporan disetujui?"}
    N -- Ya --> O["Pegawai mengunduh sertifikat"]
    N -- Tidak --> P["Pegawai merevisi dan mengajukan ulang laporan"]
    P --> M
    O --> Q(["Selesai"])
```