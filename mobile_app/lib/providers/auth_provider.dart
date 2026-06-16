import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  bool _isLoading = false;
  String? _token;

  bool get isLoading => _isLoading;
  bool get isAuthenticated => _token != null;

  /// Check local secure storage on app boot to see if the user has a saved session
  Future<void> checkAuthStatus() async {
    _token = await _storage.read(key: 'auth_token');
    notifyListeners();
  }

  /// Attempts login via API, saves token on success, and updates UI state
  Future<Map<String, dynamic>> login(String email, String password) async {
    // 1. Set loading state to true and rebuild UI
    _isLoading = true;
    notifyListeners();

    // 2. Perform API network call
    final response = await _apiService.login(email, password);

    // 3. Clear loading state
    _isLoading = false;

    // 4. Handle success scenario
    if (response['success'] == true) {
      final String? receivedToken = response['data']?['token'];
      
      if (receivedToken != null) {
        _token = receivedToken;
        
        // Persist token to Keychain (iOS) / Keystore (Android)
        await _storage.write(key: 'auth_token', value: _token);
        
        notifyListeners();
        return {'success': true};
      }
    }
    
    // 5. Update UI for failure and return error message
    notifyListeners();
    return {
      'success': false, 
      'message': response['message'] ?? 'An unknown error occurred.'
    };
  }

  /// Clears token from state and secure storage, triggering a route back to Login Gate
  Future<void> logout() async {
    _token = null;
    await _storage.delete(key: 'auth_token');
    notifyListeners();
  }
}
