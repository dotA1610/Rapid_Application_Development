import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // Use 10.0.2.2 for Android Emulator testing to point to local XAMPP localhost
  // Replace with actual production URL when deploying
  static const String baseUrl = 'http://10.0.2.2/breathflow/api'; 
  
  /// Authenticates a user and returns the JSON payload.
  Future<Map<String, dynamic>> login(String email, String password) async {
    final url = Uri.parse('$baseUrl/login');
    
    try {
      final response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({
          'email': email,
          'password': password,
        }),
      ).timeout(const Duration(seconds: 10)); // Prevent infinite hangs

      final decodedData = json.decode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && decodedData['success'] == true) {
        return {
          'success': true,
          'data': decodedData['data'], // Assume token/user is nested here
        };
      } else {
        return {
          'success': false,
          'message': decodedData['message'] ?? 'Invalid credentials.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Network error. Please verify your connection or server status.',
      };
    }
  }
}
