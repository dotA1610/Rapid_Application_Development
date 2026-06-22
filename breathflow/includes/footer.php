<?php
/**
 * ARC NEBU-PEN  |  Global Footer
 * ─────────────────────────────────────────────────────────────
 * Canonical location: includes/footer.php
 * Included globally by the front controller.
 * ─────────────────────────────────────────────────────────────
 */
?>
    <!-- ═══════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════ -->
    <footer class="border-t border-white/5"
            style="background: linear-gradient(180deg, #071419 0%, #040D0F 100%);">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                <!-- Brand column -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-teal-500 flex items-center justify-center">
                            <span class="text-ocean-950 font-black text-xs">A</span>
                        </div>
                        <span class="font-bold text-base text-white">ARC</span>
                    </div>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        Breathe Better. Feel Better.<br/>
                        &copy; <?= date('Y') ?> ARC NEBU-PEN. All rights reserved.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white text-xs font-semibold uppercase tracking-widest mb-4">Quick Links</h3>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li><a href="index.php?page=home"     class="hover:text-teal-400 transition-colors">Home</a></li>
                        <li><a href="index.php?page=science"  class="hover:text-teal-400 transition-colors">Science</a></li>
                        <li><a href="index.php?page=device"   class="hover:text-teal-400 transition-colors">Device</a></li>
                        <li><a href="index.php?page=profiles" class="hover:text-teal-400 transition-colors">Sensory Profiles</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-white text-xs font-semibold uppercase tracking-widest mb-4">Support</h3>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li><a href="#" class="hover:text-teal-400 transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Shipping</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Returns</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Follow Us -->
                <div>
                    <h3 class="text-white text-xs font-semibold uppercase tracking-widest mb-4">Follow Us</h3>
                    <div class="flex items-center gap-3">
                        <!-- Facebook -->
                        <a href="#" id="footer-facebook"
                           class="w-8 h-8 rounded-full flex items-center justify-center
                                  bg-white/5 hover:bg-teal-500/20 hover:text-teal-400
                                  text-gray-400 transition-all duration-200"
                           aria-label="Facebook">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="#" id="footer-instagram"
                           class="w-8 h-8 rounded-full flex items-center justify-center
                                  bg-white/5 hover:bg-teal-500/20 hover:text-teal-400
                                  text-gray-400 transition-all duration-200"
                           aria-label="Instagram">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/>
                                <circle cx="12" cy="12" r="4" stroke-width="2"/>
                                <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                            </svg>
                        </a>
                        <!-- TikTok -->
                        <a href="#" id="footer-tiktok"
                           class="w-8 h-8 rounded-full flex items-center justify-center
                                  bg-white/5 hover:bg-teal-500/20 hover:text-teal-400
                                  text-gray-400 transition-all duration-200"
                           aria-label="TikTok">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.07-.1z"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════════════════
         JAVASCRIPT
    ═══════════════════════════════════════════════════════ -->
    <script>
        // ── Mobile menu toggle ─────────────────────────────────
        const btnMenu   = document.getElementById('btn-mobile-menu');
        const mobileNav = document.getElementById('mobile-menu');

        if (btnMenu && mobileNav) {
            btnMenu.addEventListener('click', () => {
                const isOpen = mobileNav.classList.toggle('hidden');
                btnMenu.setAttribute('aria-expanded', String(!isOpen));
            });
        }

        // ── Navbar scroll behaviour ────────────────────────────
        // Slightly increase opacity/blur on scroll for depth effect.
        const nav = document.getElementById('main-nav');

        if (nav) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 40) {
                    nav.style.background = 'rgba(4,13,15,0.96)';
                    nav.style.borderBottomColor = 'rgba(46,203,128,0.15)';
                } else {
                    nav.style.background = 'rgba(4,13,15,0.85)';
                    nav.style.borderBottomColor = 'rgba(46,203,128,0.08)';
                }
            }, { passive: true });
        }
    </script>

</body>
</html>
