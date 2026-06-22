import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // Use a configurable string placeholder.
  // 10.0.2.2 is used for Android emulator to access the host machine's localhost.
  // Change to 'localhost' or an IP address based on your environment.
  static const String baseUrl = 'http://10.0.2.2/DCS/breathflow/api.php';

  static Future<List<dynamic>> fetchProducts() async {
    try {
      final uri = Uri.parse('$baseUrl?action=get_products');
      final response = await http.get(uri);

      if (response.statusCode == 200) {
        final List<dynamic> products = json.decode(response.body);
        return products;
      } else {
        throw Exception('Failed to load products: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error while fetching products: $e');
    }
  }
}
