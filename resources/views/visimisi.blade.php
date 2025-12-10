@extends('layouts.app')

@section('content')
    <section id="visi">
        <h2><i class="fas fa-bullseye"></i> Visi, Misi, dan Tujuan RSHP</h2>
        
        <div class="visi-misi-container">
            <div class="visi-box">
                <div class="vm-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Visi</h3>
                <div class="vm-content">
                    <p>Menjadi Rumah Sakit Hewan Pendidikan terdepan di Indonesia yang unggul dalam pelayanan kesehatan hewan, pendidikan, dan penelitian veteriner yang berkualitas tinggi serta berstandar internasional.</p>
                </div>
            </div>

            <div class="misi-box">
                <div class="vm-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h3>Misi</h3>
                <div class="vm-content">
                    <div class="misi-item">
                        <div class="misi-number">1</div>
                        <div class="misi-text">
                            <h4>Pelayanan Berkualitas</h4>
                            <p>Menyediakan layanan kesehatan hewan yang berkualitas tinggi dengan tenaga medis profesional dan fasilitas modern.</p>
                        </div>
                    </div>
                    <div class="misi-item">
                        <div class="misi-number">2</div>
                        <div class="misi-text">
                            <h4>Pendidikan & Penelitian</h4>
                            <p>Mendukung dan mengembangkan kegiatan pendidikan kedokteran hewan serta penelitian inovatif di bidang veteriner.</p>
                        </div>
                    </div>
                    <div class="misi-item">
                        <div class="misi-number">3</div>
                        <div class="misi-text">
                            <h4>Pengabdian Masyarakat</h4>
                            <p>Berperan aktif dalam pengabdian kepada masyarakat melalui edukasi kesehatan hewan dan program kesejahteraan hewan.</p>
                        </div>
                    </div>
                    <div class="misi-item">
                        <div class="misi-number">4</div>
                        <div class="misi-text">
                            <h4>Inovasi & Pengembangan</h4>
                            <p>Mengembangkan inovasi teknologi medis veteriner dan sistem manajemen rumah sakit yang efektif dan efisien.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tujuan-box">
                <div class="vm-icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <h3>Tujuan</h3>
                <div class="vm-content">
                    <div class="tujuan-grid">
                        <div class="tujuan-item">
                            <i class="fas fa-star"></i>
                            <p>Memberikan pelayanan kesehatan hewan terbaik dengan standar medis tinggi</p>
                        </div>
                        <div class="tujuan-item">
                            <i class="fas fa-users"></i>
                            <p>Menjadi pusat rujukan kesehatan hewan di Indonesia</p>
                        </div>
                        <div class="tujuan-item">
                            <i class="fas fa-graduation-cap"></i>
                            <p>Menghasilkan lulusan dokter hewan yang kompeten dan profesional</p>
                        </div>
                        <div class="tujuan-item">
                            <i class="fas fa-award"></i>
                            <p>Menciptakan penelitian veteriner yang berdampak dan berkualitas internasional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .visi-misi-container {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .visi-box, .misi-box, .tujuan-box {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .visi-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0073e6, #0056b3);
        }

        .misi-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0073e6, #0056b3);
        }

        .tujuan-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0073e6, #0056b3);
        }

        .visi-box:hover, .misi-box:hover, .tujuan-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .vm-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            transition: transform 0.3s ease;
        }

        .visi-box .vm-icon {
            background: linear-gradient(135deg, #0073e6, #0056b3);
        }

        .misi-box .vm-icon {
            background: linear-gradient(135deg, #0073e6, #0056b3);
        }

        .tujuan-box .vm-icon {
            background: linear-gradient(135deg, #0073e6, #0056b3);
        }

        .visi-box:hover .vm-icon,
        .misi-box:hover .vm-icon,
        .tujuan-box:hover .vm-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .visi-box h3, .misi-box h3, .tujuan-box h3 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            font-weight: 700;
        }

        .vm-content {
            text-align: center;
        }

        .visi-box .vm-content p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--text-dark);
            font-weight: 500;
        }

        .misi-box .vm-content {
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .misi-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .misi-item:hover {
            background: #dbeafe;
            transform: translateX(5px);
        }

        .misi-number {
            min-width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0073e6, #0056b3);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .misi-text h4 {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .misi-text p {
            font-size: 1rem;
            color: var(--text-light);
            line-height: 1.6;
            margin: 0;
        }

        .tujuan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .tujuan-item {
            background: #e0f2fe;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .tujuan-item:hover {
            background: #bae6fd;
            border-color: #0073e6;
            transform: translateY(-5px);
        }

        .tujuan-item i {
            font-size: 2.5rem;
            color: #0073e6;
            margin-bottom: 1rem;
            display: block;
        }

        .tujuan-item p {
            font-size: 0.95rem;
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .visi-box, .misi-box, .tujuan-box {
                padding: 2rem 1.5rem;
            }

            .misi-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .tujuan-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
