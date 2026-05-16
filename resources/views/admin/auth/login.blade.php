<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تسجيل الدخول — Sys</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:       #0c3c2c;
            --accent-2:     #1a7a55;
            --accent-glow:  rgba(12,60,44,.60);
            --dark:         #061009;
            --dark-2:       #0d1f16;
            --dark-3:       #122b1e;
            --text:         #f1f5f9;
            --text-muted:   #94a3b8;
            --border:       rgba(255,255,255,.08);
            --input-bg:     rgba(255,255,255,.05);
            --input-border: rgba(255,255,255,.12);
            --trans:        .22s cubic-bezier(.4,0,.2,1);
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--dark);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Layout ── */
        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
        }
        @media (max-width: 900px) {
            .login-page { grid-template-columns: 1fr; }
            .login-hero  { display: none; }
        }

        /* ── Hero panel (left) ── */
        .login-hero {
            position: relative;
            background: linear-gradient(145deg, #040d08 0%, #071e15 50%, #040d08 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            overflow: hidden;
        }

        /* Animated blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .40;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 420px; height: 420px; background: #0c3c2c; top: -120px; left: -80px; animation-delay: 0s; }
        .blob-2 { width: 320px; height: 320px; background: #1a7a55; bottom: -80px; right: -60px; animation-delay: 3s; }
        .blob-3 { width: 200px; height: 200px; background: #064e3b; top: 45%; left: 30%; animation-delay: 1.5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.06); }
        }

        /* Grid dots */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Logo image */
        .hero-logo {
            margin: 0 auto 32px;
        }
        .hero-logo img {
            height: 90px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            filter: drop-shadow(0 8px 24px rgba(12,60,44,.8)) brightness(1.05);
        }

        .hero-title {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #fff 30%, #6ee7b7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto 40px;
        }
        .hero-pills {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-pill {
            display: flex; align-items: center; gap: 7px;
            padding: 8px 16px;
            border-radius: 99px;
            background: rgba(255,255,255,.06);
            border: 1px solid var(--border);
            font-size: 13px; color: var(--text-muted);
            backdrop-filter: blur(8px);
        }
        .hero-pill i { color: #6ee7b7; font-size: 13px; }

        /* ── Form panel (right) ── */
        .login-panel {
            background: var(--dark-2);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
            border-right: 1px solid var(--border);
            position: relative;
        }

        /* Top gradient stripe */
        .login-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2), #34d399);
        }

        .login-form-wrap {
            width: 100%;
            max-width: 360px;
        }

        /* Mobile logo */
        .mobile-logo {
            display: none;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
        }
        @media (max-width: 900px) { .mobile-logo { display: flex; } }
        .mobile-logo img {
            height: 52px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
        }

        .form-heading { margin-bottom: 32px; }
        .form-heading h1 {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.5px;
            margin-bottom: 6px;
        }
        .form-heading p {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Validation errors */
        .error-box {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px;
            background: rgba(239,68,68,.10);
            border: 1px solid rgba(239,68,68,.25);
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #fca5a5;
        }
        .error-box i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }

        /* Field */
        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: .2px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            top: 50%; right: 14px;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
            transition: color var(--trans);
        }
        .field-wrap:focus-within .field-icon { color: #6ee7b7; }

        .field input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color var(--trans), background var(--trans), box-shadow var(--trans);
        }
        .field input::placeholder { color: #475569; }
        .field input:focus {
            border-color: var(--accent-2);
            background: rgba(26,122,85,.10);
            box-shadow: 0 0 0 3px rgba(26,122,85,.20);
        }
        .field input.is-error { border-color: rgba(239,68,68,.6); }
        .field-error {
            font-size: 12px;
            color: #f87171;
            margin-top: 6px;
            display: flex; align-items: center; gap: 5px;
        }

        /* Password toggle */
        .toggle-password {
            position: absolute;
            top: 50%; left: 14px;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0; font-size: 14px;
            transition: color var(--trans);
        }
        .toggle-password:hover { color: var(--text); }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            letter-spacing: .2px;
            box-shadow: 0 4px 20px var(--accent-glow);
            transition: all var(--trans);
            position: relative;
            overflow: hidden;
            margin-top: 28px;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12), transparent);
            opacity: 0;
            transition: opacity var(--trans);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26,122,85,.60);
        }
        .btn-login:hover::after { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        .btn-login .btn-content {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            position: relative; z-index: 1;
        }
        .btn-login .btn-arrow { transition: transform var(--trans); }
        .btn-login:hover .btn-arrow { transform: translateX(-4px); }

        /* Footer */
        .login-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: #334155;
        }
        .login-footer strong { color: #475569; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>
</head>

<body>
<div class="login-page">

    <!-- ── Hero Panel ── -->
    <div class="login-hero">
        <div class="hero-grid"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="hero-content">
            <div class="hero-logo">
                <img src="{{ asset('assets/admin/logo.png') }}" alt="Logo">
            </div>
            <h1 class="hero-title">منصة إدارة<br>الأكاديمية</h1>
            <p class="hero-subtitle">
                نظام متكامل لإدارة اللاعبين والاشتراكات
                ومتابعة محتوى السوشال ميديا بكل سهولة واحترافية.
            </p>
            <div class="hero-pills">
                <span class="hero-pill"><i class="fas fa-running"></i> إدارة اللاعبين</span>
                <span class="hero-pill"><i class="fas fa-file-invoice-dollar"></i> الاشتراكات</span>
                <span class="hero-pill"><i class="fas fa-photo-film"></i> السوشال ميديا</span>
            </div>
        </div>
    </div>

    <!-- ── Form Panel ── -->
    <div class="login-panel">
        <div class="login-form-wrap">

            <!-- Mobile logo (shown on small screens when hero is hidden) -->
            <div class="mobile-logo">
                <img src="{{ asset('assets/admin/logo.png') }}" alt="Logo">
            </div>

            <div class="form-heading">
                <h1>مرحباً بك 👋</h1>
                <p>سجّل دخولك للوصول إلى لوحة التحكم</p>
            </div>

            {{-- Global errors --}}
            @if($errors->any())
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                <div>اسم المستخدم أو كلمة المرور غير صحيحة. حاول مجدداً.</div>
            </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" id="loginForm">
                @csrf

                {{-- Username --}}
                <div class="field">
                    <label for="username">اسم المستخدم</label>
                    <div class="field-wrap">
                        <i class="fas fa-user field-icon"></i>
                        <input type="text"
                               id="username"
                               name="username"
                               placeholder="أدخل اسم المستخدم"
                               value="{{ old('username') }}"
                               autocomplete="username"
                               class="{{ $errors->has('username') ? 'is-error' : '' }}"
                               autofocus>
                    </div>
                    @error('username')
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">كلمة المرور</label>
                    <div class="field-wrap">
                        <i class="fas fa-lock field-icon"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="أدخل كلمة المرور"
                               autocomplete="current-password"
                               class="{{ $errors->has('password') ? 'is-error' : '' }}">
                        <button type="button" class="toggle-password" id="togglePwd" tabindex="-1" title="إظهار/إخفاء">
                            <i class="fas fa-eye" id="togglePwdIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" id="submitBtn">
                    <span class="btn-content">
                        <span>تسجيل الدخول</span>
                        <i class="fas fa-arrow-left btn-arrow"></i>
                    </span>
                </button>

            </form>

            <div class="login-footer">
                &copy; {{ date('Y') }} <strong>Sys Academy</strong>. جميع الحقوق محفوظة.
            </div>

        </div>
    </div>

</div>

<script>
    // Password visibility toggle
    const toggleBtn  = document.getElementById('togglePwd');
    const pwdInput   = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePwdIcon');

    toggleBtn.addEventListener('click', () => {
        const isHidden = pwdInput.type === 'password';
        pwdInput.type  = isHidden ? 'text' : 'password';
        toggleIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-content"><i class="fas fa-circle-notch fa-spin"></i><span>جاري الدخول...</span></span>';
    });
</script>

</body>
</html>
