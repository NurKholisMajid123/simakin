<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('sneat') }}/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title', 'SIMAKIN - Sistem Manajemen Kebersihan')</title>

  <meta name="description" content="" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logopa.png') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Helpers -->
  <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>

  <!-- Config -->
  <script src="{{ asset('sneat') }}/assets/js/config.js"></script>

  @stack('styles')
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      @include('components.sidebar')
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        @include('components.navbar')
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Page Title -->
            <h4 class="fw-bold py-3 mb-4">
              @yield('page-title', 'Dashboard')
            </h4>

            <!-- Alert Messages -->
            @include('components.alert')

            <!-- Content -->
            @yield('content')

          </div>
          <!-- / Content -->

          <!-- Footer -->
          @include('components.footer')
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  @include('components.js')

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    $(document).ready(function () {
      $('.data-table').DataTable({
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
      });
    });
  </script>

  @stack('scripts')
</body>

</html>