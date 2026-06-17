<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account — ARC NEBU-PEN</title>
    <meta name="description" content="Login or register for your ARC NEBU-PEN account." />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        teal:    { 400: '#4DD9A8', 500: '#2ECB80', 600: '#25B870' },
                        ocean:   { 950: '#040D0F', 900: '#071419', 800: '#0C2028', 700: '#112B35', 600: '#163644' },
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .page-bg {
            background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%);
        }
        .auth-card {
            background: rgba(10, 22, 28, 0.82);
            border: 1px solid rgba(46, 203, 128, 0.14);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow:
                0 32px 64px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255,255,255,0.03) inset,
                0 1px 0 rgba(46,203,128,0.08) inset;
        }
        .form-input {
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.10);
            color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.28); }
        .form-input:focus {
            outline: none;
            border-color: #2ECB80;
            background: rgba(46, 203, 128, 0.04);
            box-shadow: 0 0 0 3px rgba(46,203,128,0.15);
        }
        .form-input.is-error {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248,113,113,0.15);
        }
        .btn-teal {
            background: #2ECB80;
            color: #040D0F;
            transition: all 0.25s ease;
        }
        .btn-teal:hover {
            background: #4DD9A8;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(46,203,128,0.35);
        }
        .tab-btn {
            color: #9ca3af;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .tab-btn.active {
            color: #2ECB80;
            border-bottom: 2px solid #2ECB80;
        }
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1 flex items-center justify-center px-4 py-12" id="main-content">
        <div class="auth-card w-full max-w-md rounded-3xl px-8 py-10 sm:px-10">

            <div class="flex flex-col items-center gap-2 mb-8">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-1"
                     style="background: linear-gradient(135deg, #2ECB80 0%, #1a9e5c 100%);
                            box-shadow: 0 8px 20px rgba(46,203,128,0.30);">
                    <span class="text-ocean-950 font-black text-xl">A</span>
                </div>
                <span class="text-gray-400 text-sm font-medium tracking-widest uppercase">@ARC</span>
            </div>

            <?php if (!empty($error)): ?>
            <div role="alert" class="mb-6 flex items-start gap-3 px-4 py-3.5 rounded-xl text-sm"
                 style="background: rgba(248,113,113,0.10); border: 1px solid rgba(248,113,113,0.25);">
                <span class="text-red-300"><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
            <div role="alert" class="mb-6 flex items-start gap-3 px-4 py-3.5 rounded-xl text-sm"
                 style="background: rgba(46,203,128,0.10); border: 1px solid rgba(46,203,128,0.25);">
                <span style="color: #4DD9A8;"><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
            </div>
            <?php unset($_SESSION['flash_success']); endif; ?>

            <!-- Tabs -->
            <div class="flex border-b border-white/10 mb-8">
                <button id="tab-login" class="tab-btn active flex-1 pb-3 text-sm font-semibold tracking-wide uppercase">Sign In</button>
                <button id="tab-register" class="tab-btn flex-1 pb-3 text-sm font-semibold tracking-wide uppercase">Create Account</button>
            </div>

            <!-- Login Form -->
            <div id="content-login" class="tab-content active">
                <form action="index.php?page=login" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>" />
                    
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" name="email" required placeholder="Enter your email" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm" 
                               value="<?= htmlspecialchars(($_GET['page'] ?? '') === 'login' ? ($old['email'] ?? '') : '') ?>" />
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Enter your password" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm" />
                    </div>

                    <button type="submit" class="btn-teal w-full py-3.5 rounded-xl font-semibold text-sm tracking-wide">
                        Sign In
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="content-register" class="tab-content">
                <form action="index.php?page=register" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>" />
                    
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="fullname" required placeholder="John Doe" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm"
                               value="<?= htmlspecialchars(($_GET['page'] ?? '') === 'register' ? ($old['fullname'] ?? '') : '') ?>" />
                        <?php if (isset($errors['fullname'])): ?>
                            <p class="text-xs text-red-400 mt-1"><?= htmlspecialchars($errors['fullname']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" name="email" required placeholder="Enter your email" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm"
                               value="<?= htmlspecialchars(($_GET['page'] ?? '') === 'register' ? ($old['email'] ?? '') : '') ?>" />
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-xs text-red-400 mt-1"><?= htmlspecialchars($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Create a password" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm" />
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-xs text-red-400 mt-1"><?= htmlspecialchars($errors['password']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required placeholder="Confirm your password" 
                               class="form-input w-full rounded-xl px-4 py-3 text-sm" />
                        <?php if (isset($errors['confirm_password'])): ?>
                            <p class="text-xs text-red-400 mt-1"><?= htmlspecialchars($errors['confirm_password']) ?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-teal w-full py-3.5 rounded-xl font-semibold text-sm tracking-wide">
                        Create Account
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');
        const contentLogin = document.getElementById('content-login');
        const contentRegister = document.getElementById('content-register');

        function switchTab(tab) {
            // Update URL cleanly without reloading
            const url = new URL(window.location);
            url.searchParams.set('page', tab);
            window.history.replaceState({}, '', url);

            if (tab === 'login') {
                tabLogin.classList.add('active');
                tabRegister.classList.remove('active');
                contentLogin.classList.add('active');
                contentRegister.classList.remove('active');
            } else {
                tabRegister.classList.add('active');
                tabLogin.classList.remove('active');
                contentRegister.classList.add('active');
                contentLogin.classList.remove('active');
            }
        }

        tabLogin.addEventListener('click', () => switchTab('login'));
        tabRegister.addEventListener('click', () => switchTab('register'));

        // Check URL or errors to set initial tab
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page');
        const hasErrors = <?= !empty($errors) ? 'true' : 'false' ?>;

        if (page === 'register' || (hasErrors && page === 'register')) {
            switchTab('register');
        } else {
            switchTab('login');
        }
    </script>
</body>
</html>
