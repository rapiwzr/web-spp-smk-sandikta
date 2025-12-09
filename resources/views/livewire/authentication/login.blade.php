<div>
    {{-- Custom CSS untuk halaman ini --}}
    <style>
        .auth-bg {
            /* --- BAGIAN INI YANG DIUBAH --- */
            /* Pastikan kamu sudah menyimpan foto sekolah di folder: public/img/gedung-sekolah.jpg */
            /* Atau ganti asset(...) di bawah dengan link URL gambar langsung */
            
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("{{ asset('img/smk sandikta profile.jpg') }}");
            
            /* Jika gambarnya dari internet (URL), pakai baris ini (hapus tanda komentarnya): */
            /* background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://smk.sandikta.sch.id/wp-content/uploads/2019/07/bg-slider.jpg'); */

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95); /* Putih sedikit transparan */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #0061f2, #6900f2);
        }

        .logo-container img {
            height: 100px;
            width: auto;
            margin-bottom: 1rem;
            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1));
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0, 97, 242, 0.15);
            border-color: #0061f2;
        }
        
        .btn-primary {
            background: #0061f2;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #004bbd;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 97, 242, 0.3);
        }
    </style>

    <div class="auth-bg">
        <div class="login-card animate__animated animate__fadeInUp">
            
            {{-- Logo Section --}}
            <div class="text-center logo-container">
              {{-- Tombol Kembali --}}
              <div class="mb-3 text-start">
                  <a href="{{ route('welcome') }}" class="text-decoration-none text-muted small">
                      <i class="bi bi-arrow-left"></i> Kembali ke Homepage
                  </a>
              </div>


                {{-- Logo Sekolah --}}
                <img src="{{ asset('img/logo-SMK-sandikta-PNG.png') }}" alt="Logo SMK Sandikta">
                <h4 class="font-weight-bold text-dark mb-1">SMK SANDIKTA</h4>
                <p class="text-muted small mb-4">Sistem Informasi Akademik</p>
            </div>

            {{-- Alerts Section --}}
            @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show text-sm" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @error('email')
            <div class="alert alert-danger alert-dismissible fade show text-sm" role="alert">
                <i class="bi bi-x-circle me-2"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @enderror

            @error('password')
            <div class="alert alert-danger alert-dismissible fade show text-sm" role="alert">
                <i class="bi bi-x-circle me-2"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @enderror

            {{-- Form Section --}}
            <form wire:submit.prevent="authenticate">
                
                {{-- Email Input --}}
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="email" wire:model.blur="email"
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Alamat Email"
                        autofocus>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>

                {{-- Password Input --}}
                <div class="form-group mb-3 position-relative has-icon-left">
                    <input type="{{ $input_type }}" wire:model.blur="password"
                        class="form-control pe-5 @error('password') is-invalid @enderror"
                        placeholder="Kata Sandi" />
                    
                    {{-- Toggle Password Visibility --}}
                    <span wire:click="togglePasswordVisibility" title="{{ $input_title }}"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted" 
                        style="cursor: pointer; z-index: 10;">
                        <i class="{{ $icon }}"></i>
                    </span>

                    <div class="form-control-icon">
                        <i class="bi bi-lock"></i>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="form-check d-flex align-items-center mb-4">
                    <input class="form-check-input me-2" wire:model="remember_me" type="checkbox" id="rememberMe">
                    <label class="form-check-label text-gray-600 small" for="rememberMe">
                        Ingat Saya
                    </label>
                </div>

                {{-- Submit Button --}}
                <button class="btn btn-primary w-100 shadow-sm" type="submit">
                    Masuk Dashboard
                </button>
            </form>
            
            {{-- Footer Copyright --}}
            <div class="text-center mt-4">
                <p class="text-muted small" style="font-size: 0.7rem;">
                    &copy; {{ date('Y') }} Yayasan Pendidikan Kita<br>SMK Sandikta
                </p>
            </div>
        </div>
    </div>
</div>