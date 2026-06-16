import 'package:flutter/material.dart';
import 'dart:async';

void main() {
  runApp(const ArcApp());
}

class ArcApp extends StatelessWidget {
  const ArcApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'ARC NEBU-PEN',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        fontFamily: 'Inter', // Fits the brand aesthetic
        scaffoldBackgroundColor: const Color(0xFF040D0F), // Dark Ocean base
        colorScheme: const ColorScheme.dark(
          primary: Color(0xFF2ECB80), // Signature Teal
          secondary: Color(0xFF4DD9A8),
          surface: Color(0xFF071419),
          background: Color(0xFF040D0F),
        ),
      ),
      home: const SplashScreen(),
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _fadeAnimation;

  @override
  void initState() {
    super.initState();
    
    // Smooth fade-in entrance
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1500),
    );
    _fadeAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeIn),
    );

    _controller.forward();

    // Route to Login Gate after 3 seconds
    Timer(const Duration(seconds: 3), () {
      Navigator.of(context).pushReplacement(
        PageRouteBuilder(
          pageBuilder: (_, __, ___) => const LoginGateScreen(),
          transitionsBuilder: (_, animation, __, child) {
            return FadeTransition(opacity: animation, child: child);
          },
          transitionDuration: const Duration(milliseconds: 800),
        ),
      );
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        // Signature 4-point Dark Ocean Gradient
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF040D0F),
              Color(0xFF071419),
              Color(0xFF0C2028),
              Color(0xFF163644),
            ],
            stops: [0.0, 0.3, 0.6, 1.0],
          ),
        ),
        child: Center(
          child: FadeTransition(
            opacity: _fadeAnimation,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Minimal Logo Mark matching Web
                Container(
                  width: 80,
                  height: 80,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    gradient: const LinearGradient(
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                      colors: [Color(0xFF2ECB80), Color(0xFF1a9e5c)],
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: const Color(0xFF2ECB80).withOpacity(0.3),
                        blurRadius: 32,
                        offset: const Offset(0, 12),
                      ),
                    ],
                  ),
                  child: const Center(
                    child: Text(
                      'A',
                      style: TextStyle(
                        fontSize: 40,
                        fontWeight: FontWeight.w900,
                        color: Color(0xFF040D0F),
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 32),
                const Text(
                  'ARC',
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 10.0,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  'BREATHE BETTER',
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 4.0,
                    color: Colors.white.withOpacity(0.5),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

// Login Gate Screen
class LoginGateScreen extends StatelessWidget {
  const LoginGateScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        // Signature Dark Ocean Gradient
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF040D0F),
              Color(0xFF071419),
              Color(0xFF0C2028),
              Color(0xFF163644),
            ],
            stops: [0.0, 0.3, 0.6, 1.0],
          ),
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Spacer(),
                // Smaller Logo Mark
                Center(
                  child: Container(
                    width: 60,
                    height: 60,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      gradient: const LinearGradient(
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                        colors: [Color(0xFF2ECB80), Color(0xFF1a9e5c)],
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF2ECB80).withOpacity(0.25),
                          blurRadius: 20,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    child: const Center(
                      child: Text(
                        'A',
                        style: TextStyle(
                          fontSize: 28,
                          fontWeight: FontWeight.w900,
                          color: Color(0xFF040D0F),
                        ),
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 48),
                const Text(
                  'Welcome Back',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Login to manage your device and subscriptions.',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 14,
                    color: Colors.white.withOpacity(0.6),
                  ),
                ),
                const SizedBox(height: 48),
                
                // Form Fields (Styled to match Web Glassmorphism)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.04),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.white.withOpacity(0.1)),
                  ),
                  child: Text(
                    'Email Address',
                    style: TextStyle(color: Colors.white.withOpacity(0.3), fontSize: 16),
                  ),
                ),
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.04),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.white.withOpacity(0.1)),
                  ),
                  child: Text(
                    'Password',
                    style: TextStyle(color: Colors.white.withOpacity(0.3), fontSize: 16),
                  ),
                ),
                const SizedBox(height: 32),
                
                // Teal Login Button
                ElevatedButton(
                  onPressed: () {
                    // Logic to hit PHP backend AuthController
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF2ECB80),
                    foregroundColor: const Color(0xFF040D0F),
                    padding: const EdgeInsets.symmetric(vertical: 18),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                    elevation: 8,
                    shadowColor: const Color(0xFF2ECB80).withOpacity(0.4),
                  ),
                  child: const Text(
                    'Login',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      letterSpacing: 0.5,
                    ),
                  ),
                ),
                
                const SizedBox(height: 24),
                Center(
                  child: RichText(
                    text: TextSpan(
                      text: "Don't have an account? ",
                      style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 12),
                      children: const [
                        TextSpan(
                          text: 'Register here',
                          style: TextStyle(color: Color(0xFF2ECB80), fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                ),
                const Spacer(),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
