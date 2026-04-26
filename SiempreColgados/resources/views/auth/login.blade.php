<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — SiempreColgados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0d1b3e;
            margin: 0;
            overflow: hidden;
        }

        .login-left {
            width: 45%;
            background: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #2563eb 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(245, 158, 11, .08);
        }

        .login-left .elevator-icon {
            font-size: 64px;
            color: #f59e0b;
            margin-bottom: 32px;
            filter: drop-shadow(0 0 20px rgba(245, 158, 11, .4));
        }

        .login-left h2 {
            font-size: 42px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .login-left h2 span {
            color: #f59e0b;
        }

        .login-left p {
            color: rgba(255, 255, 255, .6);
            font-size: 16px;
            max-width: 340px;
            line-height: 1.6;
        }

        .feature-list {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, .75);
            font-size: 14px;
        }

        .feature-item i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
            font-size: 13px;
            flex-shrink: 0;
        }

        .login-right {
            flex: 1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        .login-card h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .login-card .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            padding: 11px 14px;
            font-family: inherit;
            transition: border .15s, box-shadow .15s;
        }

        .form-control:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, .12);
            outline: none;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .input-group-icon {
            position: relative;
        }

        .input-group-icon i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            z-index: 5;
        }

        .input-group-icon .form-control {
            padding-left: 38px;
        }

        .btn-login {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(30, 64, 175, .35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 11px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            font-family: inherit;
        }

        .btn-google:hover {
            border-color: #4285f4;
            background: #f8f9ff;
            color: #0f172a;
            box-shadow: 0 2px 8px rgba(66, 133, 244, .15);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        .form-check-label {
            font-size: 13px;
            color: #64748b;
        }

        .forgot-link {
            font-size: 13px;
            color: #1e40af;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .client-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #64748b;
        }

        .client-link a {
            color: #1e40af;
            font-weight: 600;
            text-decoration: none;
        }

        .client-link a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }

            .login-right {
                background: linear-gradient(135deg, #0d1b3e, #1e40af);
                padding: 24px;
            }

            .login-card {
                background: #f8fafc;
                border-radius: 20px;
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="login-left d-none d-lg-flex">
        <div class="elevator-icon"><i class="fas fa-elevator"></i></div>
        <h2>Gestión de<br><span>Incidencias</span></h2>
        <p>Plataforma digital de mantenimiento de ascensores para SiempreColgados S.L.</p>
        <div class="feature-list">
            <div class="feature-item"><i class="fas fa-wrench"></i>Control total de incidencias y órdenes de trabajo</div>
            <div class="feature-item"><i class="fas fa-users"></i>Gestión de empleados y asignación de operarios</div>
            <div class="feature-item"><i class="fas fa-file-invoice-dollar"></i>Facturación automática y control de cuotas</div>
            <div class="feature-item"><i class="fas fa-globe"></i>Soporte multi-divisa para clientes internacionales</div>
        </div>
    </div>

    <div class="login-right">
        <div class="login-card">
            <h1>Bienvenido</h1>
            <p class="subtitle">Accede con tus credenciales de empleado</p>

            {{-- ✅ Errores de validación --}}
            @if ($errors->any())
            <div class="alert alert-danger mb-3" style="background:#fef2f2;color:#991b1b;border-left:4px solid #ef4444;border-radius:10px;font-size:13px;border:none;">
                <i class="fas fa-triangle-exclamation me-2"></i>{{ $errors->first() }}
            </div>
            @endif

            {{-- ✅ Mensajes de éxito/error de sesión (incluye Google OAuth) --}}
            @if (session('status'))
            <div class="alert alert-success mb-3" style="background:#f0fdf4;color:#166534;border-left:4px solid #22c55e;border-radius:10px;font-size:13px;border:none;">
                {{ session('status') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger mb-3" style="background:#fef2f2;color:#991b1b;border-left:4px solid #ef4444;border-radius:10px;font-size:13px;border:none;">
                <i class="fas fa-triangle-exclamation me-2"></i>{{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success mb-3" style="background:#f0fdf4;color:#166534;border-left:4px solid #22c55e;border-radius:10px;font-size:13px;border:none;">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-group-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="admin@test.com" required autofocus autocomplete="email">
                    </div>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-group-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste la contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-right-to-bracket me-2"></i>Entrar al panel
                </button>
            </form>

            {{-- ✅ Google OAuth - Se muestra solo si la ruta existe --}}
            @if(Route::has('auth.google'))
            <div class="divider">o continúa con</div>
            <a href="{{ route('auth.google') }}" class="btn-google">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:20px;height:20px;">
                Acceder con Google
            </a>
            @endif

            <div class="client-link">
                ¿Eres cliente?
                <a href="{{ route('incidencia.cliente.form') }}">Registrar incidencia sin cuenta →</a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>