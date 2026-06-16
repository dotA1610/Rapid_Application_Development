import 'package:flutter/material.dart';
import 'package:camera/camera.dart';
import 'dart:ui';

class CameraView extends StatefulWidget {
  const CameraView({super.key});

  @override
  State<CameraView> createState() => _CameraViewState();
}

class _CameraViewState extends State<CameraView> {
  CameraController? _controller;
  List<CameraDescription>? _cameras;
  bool _isReady = false;

  @override
  void initState() {
    super.initState();
    _initializeCamera();
  }

  Future<void> _initializeCamera() async {
    try {
      _cameras = await availableCameras();
      if (_cameras != null && _cameras!.isNotEmpty) {
        // Initialize the first back-facing camera
        _controller = CameraController(
          _cameras![0],
          ResolutionPreset.high,
          enableAudio: false,
        );
        
        await _controller!.initialize();
        if (!mounted) return;
        
        setState(() {
          _isReady = true;
        });
      }
    } catch (e) {
      debugPrint("Camera initialization error: $e");
    }
  }

  @override
  void dispose() {
    _controller?.dispose();
    super.dispose();
  }

  void _takePhoto() async {
    if (_controller == null || !_controller!.value.isInitialized) return;
    
    try {
      // Provide visual feedback for shutter
      // final XFile photo = await _controller!.takePicture();
      // ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Photo captured: ${photo.name}')));
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Processing device scan...'),
          backgroundColor: Color(0xFF2ECB80),
          duration: Duration(seconds: 2),
        )
      );
    } catch (e) {
      debugPrint("Take picture error: $e");
    }
  }

  @override
  Widget build(BuildContext context) {
    if (!_isReady || _controller == null) {
      return const Scaffold(
        backgroundColor: Color(0xFF040D0F),
        body: Center(
          child: CircularProgressIndicator(color: Color(0xFF2ECB80)),
        ),
      );
    }

    return Scaffold(
      backgroundColor: Colors.black,
      body: Stack(
        fit: StackFit.expand,
        children: [
          // 1. Live Camera Preview
          CameraPreview(_controller!),
          
          // 2. Custom Translucent Framing Guide Overlay
          ClipPath(
            clipper: _DeviceFramingClipper(),
            child: BackdropFilter(
              filter: ImageFilter.blur(sigmaX: 5, sigmaY: 5),
              child: Container(
                color: const Color(0xFF040D0F).withOpacity(0.85),
              ),
            ),
          ),
          
          // Custom Frame Border Guide
          Center(
            child: Container(
              width: 120,
              height: 350,
              decoration: BoxDecoration(
                border: Border.all(color: const Color(0xFF2ECB80), width: 2),
                borderRadius: BorderRadius.circular(24),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF2ECB80).withOpacity(0.3),
                    blurRadius: 20,
                    spreadRadius: 2,
                  ),
                ],
              ),
            ),
          ),
          
          // Instructions Text
          const Positioned(
            top: 100,
            left: 0,
            right: 0,
            child: Text(
              'Align your NEBU-PEN within the frame.',
              textAlign: TextAlign.center,
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.w600,
                letterSpacing: 0.5,
              ),
            ),
          ),

          // Close Button
          Positioned(
            top: 50,
            left: 20,
            child: IconButton(
              icon: const Icon(Icons.close, color: Colors.white, size: 30),
              onPressed: () => Navigator.pop(context),
            ),
          ),

          // 3. Shutter Button
          Positioned(
            bottom: 60,
            left: 0,
            right: 0,
            child: Center(
              child: GestureDetector(
                onTap: _takePhoto,
                child: Container(
                  height: 80,
                  width: 80,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.transparent,
                    border: Border.all(color: Colors.white, width: 4),
                  ),
                  child: Center(
                    child: Container(
                      height: 64,
                      width: 64,
                      decoration: const BoxDecoration(
                        shape: BoxShape.circle,
                        color: Color(0xFF2ECB80),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// Custom Clipper to cut out a window for the camera preview
class _DeviceFramingClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    // Defines the entire screen
    final path = Path()..addRect(Rect.fromLTWH(0, 0, size.width, size.height));
    
    // Defines the cutout window for the pen device
    final cutoutWidth = 120.0;
    final cutoutHeight = 350.0;
    final rect = Rect.fromCenter(
      center: Offset(size.width / 2, size.height / 2),
      width: cutoutWidth,
      height: cutoutHeight,
    );
    
    final cutoutPath = Path()..addRRect(RRect.fromRectAndRadius(rect, const Radius.circular(24)));
    
    // Subtract the cutout from the full screen path
    return Path.combine(PathOperation.difference, path, cutoutPath);
  }

  @override
  bool shouldReclip(covariant CustomClipper<Path> oldClipper) => false;
}
