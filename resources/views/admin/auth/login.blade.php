<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin | Ungu Spa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-shell">
        <aside class="admin-login-visual" aria-hidden="true">
            <div class="admin-login-glow admin-login-glow-one"></div>
            <div class="admin-login-glow admin-login-glow-two"></div>
            <div class="admin-login-visual-content">
                <span class="admin-login-eyebrow">Ungu Spa Content Management</span>
                <h2>Kelola konten.<br><em>Perkuat visibilitas.</em></h2>
                <p>Satu ruang khusus untuk mengelola artikel, metadata SEO, dan performa konten Ungu Spa.</p>
                <div class="admin-login-features">
                    <span>Artikel</span><span>SEO</span><span>Media</span>
                </div>
            </div>
        </aside>

        <section class="admin-login-card">
            <div class="admin-login-form-wrap">
                <a class="admin-login-brand" href="{{ route('home') }}" aria-label="Kembali ke Ungu Spa">
                    <img src="{{ asset('assets/ganbar/logo-ungu-spa-transparent.png') }}" alt="Logo Ungu Spa">
                    <span><strong>Ungu</strong><small>Spa</small></span>
                </a>

                <div class="admin-login-heading">
                    <p>Selamat datang kembali</p>
                    <h1>Login Admin</h1>
                    <span>Masukkan akun admin untuk melanjutkan ke dashboard.</span>
                </div>

                <form class="admin-login-form" method="POST" action="{{ route('admin.login.store') }}">
                    @csrf
                    <label>
                        <span>Email</span>
                        <div class="admin-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Zm8 7 8-5H4l8 5Zm0 2.3L4 9.3V17h16V9.3l-8 5Z"/></svg>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@unguspa.com" required autofocus autocomplete="email">
                        </div>
                    </label>

                    <label>
                        <span>Password</span>
                        <div class="admin-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v9h14v-9a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V6Zm3 9.7V17h-2v-1.3a2 2 0 1 1 2 0Z"/></svg>
                            <input id="admin-password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                            <button class="admin-password-toggle" type="button" aria-label="Tampilkan password" data-password-toggle>
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5c5.5 0 9.5 5.2 9.5 7s-4 7-9.5 7S2.5 13.8 2.5 12 6.5 5 12 5Zm0 2c-4 0-7 3.6-7.5 5 .5 1.4 3.5 5 7.5 5s7-3.6 7.5-5C19 10.6 16 7 12 7Zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6Z"/></svg>
                            </button>
                        </div>
                    </label>

                    <label class="admin-remember"><input type="checkbox" name="remember" value="1"><span>Ingat saya di perangkat ini</span></label>
                    @error('email')<p class="admin-login-error">{{ $message }}</p>@enderror
                    <button class="admin-login-submit" type="submit"><span>Masuk ke Dashboard</span><b aria-hidden="true">&rarr;</b></button>
                </form>

                <p class="admin-login-footer">Area aman khusus administrator Ungu Spa.</p>
            </div>
        </section>
    </main>

    <script>
        document.querySelector('[data-password-toggle]')?.addEventListener('click', (event) => {
            const input = document.querySelector('#admin-password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            event.currentTarget.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
</body>
</html>
