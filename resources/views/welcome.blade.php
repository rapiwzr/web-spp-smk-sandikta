<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandikta School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* 1. Navbar Transparan */
        .navbar { transition: 0.3s; padding: 20px 0; }
        .navbar-brand { font-weight: 700; font-size: 24px; color: white !important; }
        .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; margin-left: 20px; }
        .nav-link:hover { color: white !important; }

        /* 2. Hero Section (Gambar Besar) */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://www.kupasmerdeka.com/wp-content/uploads/2025/02/IMG-20250214-WA0017.jpg');
            background-size: cover;
            background-position: center;
            height: 90vh; /* Tinggi hampir selayar penuh */
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }

        /* 3. Floating Cards (Kartu Melayang) */
        .floating-container {
            margin-top: -100px; /* Ini kuncinya: naik ke atas menimpa gambar */
            position: relative;
            z-index: 10;
        }
        .info-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s;
        }
        .info-card:hover { transform: translateY(-10px); }
        .icon-box { font-size: 40px; color: #0d6efd; margin-bottom: 15px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg position-absolute w-100 z-3">
        <div class="container">
            <a class="navbar-brand" href="#">
    <img src="{{ asset('img/logo-SMK-sandikta-PNG.png') }}" alt="Logo SMK Sandikta" height="50">
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item ms-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-light text-primary px-4 py-2 rounded-pill fw-bold">Login</a>
                            @endauth
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="text-uppercase tracking-wider mb-2 d-block text-warning fw-bold"></span>
                    <h1 class="display-3 fw-bold mb-4">SMK Sandikta</h1>
                    <p class="lead mb-5 text-white-50">Reach Your Vocational Skill With Us</p>
                    <a href="#" class="btn btn-primary btn-lg px-5 py-3 rounded-1 fw-bold">Daftar Disini</a>
                </div>
            </div>
        </div>
    </section>

    <section class="floating-container mb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3"><i class="bi bi-mortarboard"></i></div>
                            <div>
                                <h4 class="fw-bold">Masa Depan</h4>
                                <p class="text-muted small">Temukan masa depan cerahmu di SMK SANDIKTA, tempat yang memadukan pembelajaran berkualitas dengan pengembangan keterampilan praktis yang sesuai dengan tuntutan industri modern</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3"><i class="bi bi-buildings"></i></div>
                            <div>
                                <h4 class="fw-bold">Kualitas</h4>
                                <p class="text-muted small">Bergabunglah dengan komunitas kami di SMK SANDIKTA dan rasakan atmosfer belajar yang inspiratif, didukung oleh fasilitas modern dan pengajar yang berpengalaman.

</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3"><i class="bi bi-book"></i></div>
                            <div>
                                <h4 class="fw-bold">Industri</h4>
                                <p class="text-muted small">Dengan kurikulum yang terintegrasi dan fokus pada pembangunan karier, SMK SANDIKTA memberikan kesempatan bagi setiap siswa untuk mencapai potensi maksimal mereka dan siap bersaing di dunia kerja global.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('img/smk sandikta profile.jpg') }}" class="img-fluid rounded shadow" alt="Students">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h5 class="text-primary fw-bold"></h5>
                    <h2 class="fw-bold mb-4">Tentang SMK Sandikta</h2>
                    <p class="text-muted">SMK SANDIKTA adalah institusi pendidikan yang berdedikasi untuk memberikan pengalaman pembelajaran yang inspiratif dan relevan bagi para siswa. Dengan fokus pada pengembangan keterampilan praktis dan pemahaman yang mendalam dalam berbagai bidang keahlian, SMK SANDIKTA memberikan fondasi yang kuat bagi siswa untuk mencapai potensi maksimal mereka. Melalui kurikulum yang terintegrasi dan fasilitas modern, SMK SANDIKTA tidak hanya mempersiapkan siswa untuk sukses akademis, tetapi juga untuk meraih kesuksesan dalam karier dan kehidupan sehari-hari. Dengan staf pengajar yang berpengalaman dan dukungan komunitas yang kuat, SMK SANDIKTA menjadi tempat di mana impian masa depan menjadi nyata.</p>
                    
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-5 text-center">
        <div class="container">
            <small>&copy; 2025 Yayasan Pendidikan Kita SMK Sandikta</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>