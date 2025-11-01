<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('sneat') }}/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Login - SIMAKIN</title>

  <meta name="description" content="Sistem Informasi Monitoring Kebersihan Internal" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logopa.png') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css" />
  
  <!-- Helpers -->
  <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>
  <script src="{{ asset('sneat') }}/assets/js/config.js"></script>

  <style>
    .app-brand-logo img {
      max-width: 80px;
      height: auto;
    }
  </style>
</head>

<body>
  <!-- Content -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4">
              <a href="{{ route('home') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img src="{{ asset('img/logopa.png') }}" 
                       alt="Logo SIMAKIN" 
                       onerror="this.style.display='none'" />
                </span>
                <span class="app-brand-text demo text-body fw-bolder">SIMAKIN</span>
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-2">Welcome to SIMAKIN! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start managing cleanliness</p>

            <!-- Alert Messages -->
            @if($errors->any())
              <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="alert-message">
                  @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
              </div>
            @endif

            @if(session('success'))
              <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('success') }}
              </div>
            @endif

            <form id="formAuthentication" class="mb-3" action="{{ route('login.post') }}" method="POST">
              @csrf
              
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="Enter your email"
                       autofocus 
                       required />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                  {{-- <a href="#">
                    <small>Forgot Password?</small>
                  </a> --}}
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" 
                         id="password" 
                         class="form-control @error('password') is-invalid @enderror" 
                         name="password"
                         placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                         aria-describedby="password"
                         required />
                  <span class="input-group-text cursor-pointer">
                    <i class="bx bx-hide"></i>
                  </span>
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </form>

            
          </div>
        </div>
        <!-- /Register Card -->
      </div>
    </div>
  </div>
  <!-- / Content -->

  <!-- Core JS -->
  <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>

  <!-- Main JS -->
  <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

  <!-- Password Toggle -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.querySelector('.form-password-toggle .input-group-text');
      const password = document.querySelector('#password');
      const icon = togglePassword.querySelector('i');

      togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        if (type === 'password') {
          icon.classList.remove('bx-show');
          icon.classList.add('bx-hide');
        } else {
          icon.classList.remove('bx-hide');
          icon.classList.add('bx-show');
        }
      });
    });
  </script>
</body>

</html>