import 'package:flutter/material.dart';
// Note: Requires adding Provider to pubspec if reading AuthProvider state dynamically
// import 'package:provider/provider.dart'; 
// import '../providers/auth_provider.dart';
import 'camera_view.dart';

class DashboardView extends StatelessWidget {
  const DashboardView({super.key});

  @override
  Widget build(BuildContext context) {
    // In a real app with Provider:
    // final auth = Provider.of<AuthProvider>(context);
    
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
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              
              // Custom Header
              Padding(
                padding: const EdgeInsets.fromLTRB(24, 24, 24, 16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Welcome back,',
                          style: TextStyle(
                            fontSize: 14,
                            color: Colors.white.withOpacity(0.6),
                            fontWeight: FontWeight.w600,
                            letterSpacing: 1.2,
                          ),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Aiman', // Will be dynamic via auth.user.name later
                          style: TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.w800,
                            color: Colors.white,
                          ),
                        ),
                      ],
                    ),
                    // Profile Avatar Placeholder
                    Container(
                      width: 48,
                      height: 48,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(color: const Color(0xFF2ECB80), width: 2),
                        image: const DecorationImage(
                          image: NetworkImage('https://ui-avatars.com/api/?name=Aiman&background=2ECB80&color=040D0F'),
                          fit: BoxFit.cover,
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              // Welcome Glassmorphism Card
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8.0),
                child: Container(
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.04),
                    borderRadius: BorderRadius.circular(24),
                    border: Border.all(color: Colors.white.withOpacity(0.1)),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.3),
                        blurRadius: 32,
                        offset: const Offset(0, 16),
                      ),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Core Club Subscription',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Your next 3-flavor bundle ships on May 20, 2026.',
                        style: TextStyle(
                          fontSize: 14,
                          color: Colors.white.withOpacity(0.6),
                          height: 1.5,
                        ),
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: const Color(0xFF2ECB80).withOpacity(0.15),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: const Color(0xFF2ECB80).withOpacity(0.3)),
                            ),
                            child: const Text(
                              'Active',
                              style: TextStyle(
                                color: Color(0xFF4DD9A8),
                                fontSize: 12,
                                fontWeight: FontWeight.w700,
                                letterSpacing: 0.5,
                              ),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Text(
                            'Manage Bundle ➔',
                            style: TextStyle(
                              color: const Color(0xFF2ECB80),
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 32),
              
              // Profiles Section
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0),
                child: Text(
                  'Sensory Profiles',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 1.0,
                    color: Colors.white.withOpacity(0.8),
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Grid List
              Expanded(
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20.0),
                  child: GridView.count(
                    crossAxisCount: 2,
                    mainAxisSpacing: 16,
                    crossAxisSpacing: 16,
                    childAspectRatio: 0.9,
                    physics: const BouncingScrollPhysics(),
                    children: [
                      _buildProfileCard('Mint', 'Cooling & Refreshing', Colors.greenAccent),
                      _buildProfileCard('Berry', 'Smooth & Soothing', Colors.pinkAccent),
                      _buildProfileCard('Citrus', 'Bright & Uplifting', Colors.orangeAccent),
                      _buildProfileCard('Lavender', 'Calming & Relaxing', Colors.indigoAccent),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
      
      // Floating Action Button for Camera
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const CameraView()),
          );
        },
        backgroundColor: const Color(0xFF2ECB80),
        foregroundColor: const Color(0xFF040D0F),
        elevation: 8,
        tooltip: 'Analyze Device',
        child: const Icon(Icons.document_scanner_rounded, size: 28),
      ),
    );
  }

  Widget _buildProfileCard(String title, String subtitle, Color accentColor) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.02),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.white.withOpacity(0.05)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Expanded(
            flex: 3,
            child: Container(
              decoration: BoxDecoration(
                color: accentColor.withOpacity(0.1),
                borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
              ),
              child: Center(
                child: Icon(Icons.air, color: accentColor.withOpacity(0.5), size: 40),
              ),
            ),
          ),
          Expanded(
            flex: 2,
            child: Padding(
              padding: const EdgeInsets.all(12.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    title,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    subtitle,
                    style: TextStyle(
                      color: Colors.white.withOpacity(0.5),
                      fontSize: 10,
                      fontWeight: FontWeight.w500,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
