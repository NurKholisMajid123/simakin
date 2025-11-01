<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAKIN - Sistem Informasi Monitoring Kebersihan Internal</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0c582c;
            --secondary-color: #28a745;
            --accent-color: #34b1c2;
        }

        body {
            padding-top: 76px; /* Compensate for fixed navbar */
        }

        .navbar {
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 100px 0;
            min-height: 600px;
            display: flex;
            align-items: center;
        }

        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stats-section {
            background-color: #f8f9fa;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #094a24;
            border-color: #094a24;
        }

        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        footer {
            background-color: #343a40;
            color: white;
        }

        footer a {
            transition: opacity 0.3s ease;
        }

        footer a:hover {
            opacity: 0.8;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <img src="{{ asset('img/logopa.png') }}" alt="Logo PA Malang" onerror="this.style.display='none'">
                SIMAKIN
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Sistem Informasi Monitoring Kebersihan Internal
                    </h1>
                    <p class="lead mb-4">
                        Solusi digital untuk memantau, mengelola, dan meningkatkan standar kebersihan di lingkungan internal di Pengadilan Agama Kabupaten Malang.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
                        </a>
                        <a href="#contact" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-telephone me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <img src="{{ asset('img/man-cleaning.png') }}"
                         alt="SIMAKIN Dashboard"
                         class="img-fluid rounded shadow"
                         style="max-width: 300px; height: auto;"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\'%3E%3Crect width=\'300\' height=\'300\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'Arial\' font-size=\'18\' fill=\'%23999\'%3ESIMAKIN%3C/text%3E%3C/svg%3E'">
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-3">Hubungi Kami</h2>
                    <p class="lead text-muted">
                        Siap untuk meningkatkan standar kebersihan
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card feature-card">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <h5 class="mb-3">Telepon</h5>
                                    <p class="text-muted mb-0">0341 399192</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card feature-card">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <h5 class="mb-3">Email</h5>
                                    <p class="text-muted mb-0">pa.kab.malang@mail.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card feature-card">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <h5 class="mb-3">Alamat</h5>
                                    <p class="text-muted mb-0">Malang, Indonesia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">
                        <i class="bi bi-shield-check me-2"></i>SIMAKIN
                    </h5>
                    <p class="text-light">
                        Sistem Informasi Monitoring Kebersihan Internal - Solusi untuk standar kebersihan yang lebih baik.
                    </p>
                </div>
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <h6 class="text-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home" class="text-light text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#contact" class="text-light text-decoration-none">Kontak</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-light text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="https://www.facebook.com/people/Pengadilan-Agama-Kabupaten-Malang/pfbid02Ms1BmvytT3qnuF2NfFj5cu5e8cMFFiS7D2Pg3FzyTHWUchpemjseqmqr7gui2C3Jl/" 
                           class="text-light fs-4" target="_blank" rel="noopener">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://x.com/pa_kab_malang" 
                           class="text-light fs-4" target="_blank" rel="noopener">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://www.tiktok.com/@pa_kab_malang" 
                           class="text-light fs-4" target="_blank" rel="noopener">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="https://www.instagram.com/pa_kab_malang/" 
                           class="text-light fs-4" target="_blank" rel="noopener">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UC0K5iBfjqd0KMkjcQ-0X3vw/featured" 
                           class="text-light fs-4" target="_blank" rel="noopener">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="text-light mb-0">&copy; 2024 SIMAKIN. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-light text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Smooth Scrolling --}}
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });

        // Close mobile menu when link is clicked
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }
            });
        });
    </script>
</body>
</html>