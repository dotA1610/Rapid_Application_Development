<?php
$activePage = $_GET['page'] ?? 'home';

if (!function_exists('navClass')) {
    function navClass($pageName, $activePage) {
        $baseClass = 'relative transition-colors duration-300 after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-teal-400 after:transition-all after:duration-300 hover:text-teal-400';
        if ($pageName === $activePage) {
            $baseClass .= ' text-teal-400 after:w-full';
        } else {
            $baseClass .= ' text-gray-300 after:w-0 hover:after:w-full';
        }
        return $baseClass;
    }
}

if (!function_exists('mobileNavClass')) {
    function mobileNavClass($pageName, $activePage) {
        $baseClass = 'block px-4 py-2 rounded-lg transition-colors';
        if ($pageName === $activePage) {
            $baseClass .= ' text-teal-400 bg-white/5';
        } else {
            $baseClass .= ' hover:text-teal-400 hover:bg-white/5';
        }
        return $baseClass;
    }
}
?>
    <!-- ═══════════════════════════════════════════════════════
         NAVIGATION BAR
    ═══════════════════════════════════════════════════════ -->
    <nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 px-6 lg:px-12 py-4"
         style="background: rgba(4,13,15,0.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(46,203,128,0.08);">
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

            <!-- Desktop Nav Links -->
            <ul class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-300">
                <li><a href="index.php?page=home"        class="<?= navClass('home', $activePage) ?>">Home</a></li>
                <li><a href="index.php?page=science"     class="<?= navClass('science', $activePage) ?>">Science</a></li>
                <li><a href="index.php?page=device"      class="<?= navClass('device', $activePage) ?>">Device</a></li>
                <li><a href="index.php?page=profiles"    class="<?= navClass('profiles', $activePage) ?>">Sensory Profiles</a></li>
                <li><a href="index.php?page=starter_kit" class="<?= navClass('starter_kit', $activePage) ?>">Starter Kit</a></li>
                <li><a href="index.php?page=bundle_builder" class="<?= navClass('bundle_builder', $activePage) ?>">Build Your Bundle</a></li>
                <li><a href="index.php?page=subscription" class="<?= navClass('subscription', $activePage) ?>">Subscription</a></li>
            </ul>

            <!-- Nav Actions -->
            <div class="flex items-center gap-3">
                <!-- Theme toggle placeholder -->
                <button id="btn-theme-toggle"
                        class="text-gray-400 hover:text-teal-400 transition-colors duration-200 p-2 rounded-full hover:bg-white/5"
                        aria-label="Toggle theme">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>

                <!-- Account icon -->
                <a href="index.php?page=login" id="nav-account"
                   class="text-gray-400 hover:text-teal-400 transition-colors duration-200 p-2 rounded-full hover:bg-white/5 <?= ($activePage === 'login' || $activePage === 'dashboard') ? 'text-teal-400' : '' ?>"
                   aria-label="Account">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>

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

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-white/10">
            <ul class="flex flex-col gap-1 pt-4 text-sm font-medium text-gray-300">
                <li><a href="index.php?page=home"         class="<?= mobileNavClass('home', $activePage) ?>">Home</a></li>
                <li><a href="index.php?page=science"      class="<?= mobileNavClass('science', $activePage) ?>">Science</a></li>
                <li><a href="index.php?page=device"       class="<?= mobileNavClass('device', $activePage) ?>">Device</a></li>
                <li><a href="index.php?page=profiles"     class="<?= mobileNavClass('profiles', $activePage) ?>">Sensory Profiles</a></li>
                <li><a href="index.php?page=starter_kit"  class="<?= mobileNavClass('starter_kit', $activePage) ?>">Starter Kit</a></li>
                <li><a href="index.php?page=bundle_builder" class="<?= mobileNavClass('bundle_builder', $activePage) ?>">Build Your Bundle</a></li>
                <li><a href="index.php?page=subscription" class="<?= mobileNavClass('subscription', $activePage) ?>">Subscription</a></li>
                <li class="pt-2 border-t border-white/10 mt-2">
                    <a href="index.php?page=login" class="<?= mobileNavClass('login', $activePage) ?> text-teal-400 font-semibold">Login</a>
                </li>
            </ul>
        </div>
    </nav>
