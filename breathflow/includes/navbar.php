<?php
/**
 * ARC NEBU-PEN  |  Global Navigation Bar
 * ─────────────────────────────────────────────────────────────
 * Canonical location: includes/navbar.php
 * Included by every view template for a unified navigation
 * experience across the entire site.
 *
 * RAD Template Requirements:
 *   • Layout modularisation — all shared chrome in includes/
 *   • FR-01 Role-Based Access Control — admin links are only
 *     rendered when $_SESSION['role'] === 'admin'
 * ─────────────────────────────────────────────────────────────
 */

// ── Safe session initialisation ──────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Derive auth state from session ───────────────────────────
$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']);
$userRole   = $_SESSION['role']     ?? '';
$userName   = $_SESSION['fullname'] ?? '';

$isAdmin    = ($userRole === 'admin');
$isManager  = ($userRole === 'manager');
$isStaff    = ($userRole === 'staff');
$isCustomer = ($userRole === 'customer');

// ── Permissions Matrix ───────────────────────────────────────
$canViewDashboard  = ($isAdmin || $isManager);
$canManageProducts = ($isAdmin || $isStaff);
$canManageSubs     = ($isAdmin);

// Helper for displaying a role badge
$roleBadge = '';
if ($isAdmin)   $roleBadge = '<span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 font-bold uppercase tracking-wider">Admin</span>';
if ($isManager) $roleBadge = '<span class="text-[9px] px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-400 font-bold uppercase tracking-wider">Manager</span>';
if ($isStaff)   $roleBadge = '<span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-500/20 text-purple-400 font-bold uppercase tracking-wider">Staff</span>';


$activePage = $_GET['page'] ?? 'home';

if (!function_exists('navClass')) {
    /**
     * Returns Tailwind classes for a desktop nav link.
     * Active page gets a permanent teal underline + text color.
     * Inactive pages get the underline and color on hover.
     */
    function navClass(string $pageName, string $activePage): string
    {
        $base = 'relative py-1 transition-colors duration-300 '
              . 'after:absolute after:-bottom-1 after:left-0 after:h-[2px] '
              . 'after:bg-teal-400 after:transition-all after:duration-300 '
              . 'hover:text-teal-400';

        if ($pageName === $activePage) {
            return $base . ' text-teal-400 after:w-full';
        }

        return $base . ' text-gray-300 after:w-0 hover:after:w-full';
    }
}

if (!function_exists('mobileNavClass')) {
    function mobileNavClass(string $pageName, string $activePage): string
    {
        $base = 'block px-4 py-2 rounded-lg transition-colors';

        if ($pageName === $activePage) {
            return $base . ' text-teal-400 bg-white/5';
        }

        return $base . ' text-gray-300 hover:text-teal-400 hover:bg-white/5';
    }
}
?>

<!-- ═══════════════════════════════════════════════════════════
     GLOBAL NAVIGATION BAR — includes/navbar.php
     FR-01: Role-Based Access Control applied
═══════════════════════════════════════════════════════════ -->
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 px-6 lg:px-12 py-4"
     style="background: rgba(4,13,15,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid rgba(46,203,128,0.08);">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <!-- Logo -->
        <a href="index.php?page=home" id="nav-logo" class="flex items-center gap-2.5 group" aria-label="ARC Home">
            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center
                        group-hover:bg-teal-400 transition-colors duration-200">
                <span class="text-ocean-950 font-black text-xs tracking-wider">A</span>
            </div>
            <span class="font-bold text-lg tracking-wide text-white group-hover:text-teal-400 transition-colors duration-200">
                ARC
            </span>
        </a>

        <!-- ── Desktop Nav Links ──────────────────────────────── -->
        <ul class="hidden md:flex items-center gap-7 text-sm font-medium">

            <!-- Public links — visible to everyone -->
            <li><a href="index.php?page=home"           class="<?= navClass('home', $activePage) ?>">Home</a></li>
            <li><a href="index.php?page=science"        class="<?= navClass('science', $activePage) ?>">Science</a></li>
            <li><a href="index.php?page=device"         class="<?= navClass('device', $activePage) ?>">Device</a></li>
            <li><a href="index.php?page=profiles"       class="<?= navClass('profiles', $activePage) ?>">Sensory Profiles</a></li>
            <li><a href="index.php?page=starter_kit"    class="<?= navClass('starter_kit', $activePage) ?>">Starter Kit</a></li>
            <li><a href="index.php?page=bundle_builder" class="<?= navClass('bundle_builder', $activePage) ?>">Build Your Bundle</a></li>
            <li><a href="index.php?page=subscription"   class="<?= navClass('subscription', $activePage) ?>">Subscription</a></li>

            <?php if ($canViewDashboard || $canManageProducts || $canManageSubs): ?>
            <!-- ── Back-office links (FR-01 RBAC) ──────────────── -->
            <li class="border-l border-white/10 pl-6 ml-2 text-gray-500 text-xs tracking-widest uppercase font-bold">
                Internal
            </li>

            <?php if ($canViewDashboard): ?>
            <li>
                <a href="index.php?page=admin/dashboard" class="<?= navClass('admin/dashboard', $activePage) ?>">
                    Dashboard
                </a>
            </li>
            <?php endif; ?>

            <?php if ($canManageProducts): ?>
            <li>
                <a href="index.php?page=admin/products" class="<?= navClass('admin/products', $activePage) ?>">
                    Manage Products
                </a>
            </li>
            <?php endif; ?>

            <?php if ($canManageSubs): ?>
            <li>
                <a href="index.php?page=admin/subscriptions" class="<?= navClass('admin/subscriptions', $activePage) ?>">
                    Manage Subs
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

        </ul>

        <!-- ── Nav Action Icons ───────────────────────────────── -->
        <div class="flex items-center gap-3">

            <?php if ($isLoggedIn): ?>

                <!-- Logged-in user greeting + dashboard -->
                <a href="index.php?page=dashboard" id="nav-dashboard"
                   class="hidden sm:flex items-center gap-2 text-xs font-medium px-3 py-1.5 rounded-full transition-all duration-200
                          <?= ($activePage === 'dashboard') ? 'text-teal-400 bg-teal-400/10' : 'text-gray-400 hover:text-teal-400 hover:bg-white/5' ?>"
                   aria-label="Dashboard">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <?= htmlspecialchars(explode(' ', $userName)[0]) ?>
                    <?= $roleBadge ?>
                </a>

                <!-- Logout -->
                <a href="index.php?page=logout" id="nav-logout"
                   class="text-gray-400 hover:text-red-400 transition-colors duration-200 p-2 rounded-full hover:bg-red-400/10"
                   aria-label="Logout" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </a>

            <?php else: ?>

                <!-- Guest: Login icon -->
                <a href="index.php?page=login" id="nav-account"
                   class="text-gray-400 hover:text-teal-400 transition-colors duration-200 p-2 rounded-full hover:bg-white/5 <?= ($activePage === 'login' || $activePage === 'register') ? 'text-teal-400' : '' ?>"
                   aria-label="Login">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>

            <?php endif; ?>

            <!-- Cart icon -->
            <a href="index.php?page=bundle_builder" id="nav-cart"
               class="text-gray-400 hover:text-teal-400 transition-colors duration-200 p-2 rounded-full hover:bg-white/5 <?= ($activePage === 'bundle_builder') ? 'text-teal-400' : '' ?>"
               aria-label="Cart">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
            </a>

            <!-- Mobile hamburger -->
            <button id="btn-mobile-menu"
                    class="md:hidden text-gray-400 hover:text-white p-2"
                    aria-label="Open navigation menu"
                    aria-expanded="false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- ── Mobile Menu (hidden by default) ────────────────────── -->
    <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-white/10">
        <ul class="flex flex-col gap-1 pt-4 text-sm font-medium text-gray-300">

            <!-- Public links -->
            <li><a href="index.php?page=home"           class="<?= mobileNavClass('home', $activePage) ?>">Home</a></li>
            <li><a href="index.php?page=science"        class="<?= mobileNavClass('science', $activePage) ?>">Science</a></li>
            <li><a href="index.php?page=device"         class="<?= mobileNavClass('device', $activePage) ?>">Device</a></li>
            <li><a href="index.php?page=profiles"       class="<?= mobileNavClass('profiles', $activePage) ?>">Sensory Profiles</a></li>
            <li><a href="index.php?page=starter_kit"    class="<?= mobileNavClass('starter_kit', $activePage) ?>">Starter Kit</a></li>
            <li><a href="index.php?page=bundle_builder" class="<?= mobileNavClass('bundle_builder', $activePage) ?>">Build Your Bundle</a></li>
            <li><a href="index.php?page=subscription"   class="<?= mobileNavClass('subscription', $activePage) ?>">Subscription</a></li>

            <?php if ($canViewDashboard || $canManageProducts || $canManageSubs): ?>
            <!-- Back-office links (FR-01 RBAC) -->
            <li class="pt-2 border-t border-white/10 mt-2">
                <span class="block px-4 py-1 text-[10px] font-bold tracking-widest text-gray-500 uppercase">Internal</span>
            </li>
            <?php if ($canViewDashboard): ?>
            <li><a href="index.php?page=admin/dashboard"     class="<?= mobileNavClass('admin/dashboard', $activePage) ?>">⚙ Dashboard</a></li>
            <?php endif; ?>
            <?php if ($canManageProducts): ?>
            <li><a href="index.php?page=admin/products"      class="<?= mobileNavClass('admin/products', $activePage) ?>">📦 Manage Products</a></li>
            <?php endif; ?>
            <?php if ($canManageSubs): ?>
            <li><a href="index.php?page=admin/subscriptions" class="<?= mobileNavClass('admin/subscriptions', $activePage) ?>">🔄 Manage Subscriptions</a></li>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Auth section -->
            <li class="pt-2 border-t border-white/10 mt-2">
                <?php if ($isLoggedIn): ?>
                    <div class="px-4 py-2 flex items-center justify-between">
                        <span class="text-gray-400 text-xs">
                            <?= htmlspecialchars($userName) ?>
                            <?= $roleBadge ?>
                        </span>
                    </div>
                    <a href="index.php?page=dashboard" class="<?= mobileNavClass('dashboard', $activePage) ?> font-semibold">My Dashboard</a>
                    <a href="index.php?page=logout" class="block px-4 py-2 rounded-lg text-red-400 hover:bg-red-400/10 transition-colors">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="<?= mobileNavClass('login', $activePage) ?> text-teal-400 font-semibold">Login / Register</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>
