<?php>
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.io.OutputStreamWriter;
import java.io.IOException;

public class ApiExample {
    private static final String API_KEY = "12345";
    private static final String SERVER_KEY = "67890";
    private static final String ENDPOINT_URL = "https://api.example.com/endpoint";
    private static final String USER_ID = "0707000123";

    public static void main(String[] args) throws IOException, NoSuchAlgorithmException {
        String apiKeyHash = hashString(API_KEY, "SHA-256");

        Map<String, String> params = new HashMap<>();
        params.put("userId", USER_ID);
        params.put("signature", signData(USER_ID.getBytes(StandardCharsets.UTF_8), SERVER_KEY.getBytes(StandardCharsets.UTF_8)));

        URL url = new URL(ENDPOINT_URL);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("POST");
        conn.setDoOutput(true);
        conn.setRequestProperty("api_key", apiKeyHash);

        OutputStreamWriter writer = new OutputStreamWriter(conn.getOutputStream());
        writer.write(getParamsString(params));
        writer.flush();

        BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));
        StringBuilder responseBuilder = new StringBuilder();
        String line;
        while ((line = reader.readLine()) != null) {
            responseBuilder.append(line);
        }
        String response = responseBuilder.toString();

        // Handle the server response...
    }

    private static String hashString(String data, String algorithm) throws NoSuchAlgorithmException {
        MessageDigest digest = MessageDigest.getInstance(algorithm);
        byte[] hashedBytes = digest.digest(data.getBytes(StandardCharsets.UTF_8));
        return convertByteArrayToHexString(hashedBytes);
    }

    private static String signData(byte[] data, byte[] key) throws NoSuchAlgorithmException {
        String algorithm = "HmacSHA256";
        Mac mac = Mac.getInstance(algorithm);
        SecretKeySpec secretKey = new SecretKeySpec(key, algorithm);
        mac.init(secretKey);
        byte[] signatureBytes = mac.doFinal(data);
        return convertByteArrayToHexString(signatureBytes);
    }

    private static String convertByteArrayToHexString(byte[] array) {
        StringBuilder builder = new StringBuilder();
        for (byte b : array) {
            builder.append(String.format("%02x", b));
        }
        return builder.toString();
    }

    private static String getParamsString(Map<String, String> params) {
        StringBuilder builder = new StringBuilder();
        for (String key : params.keySet()) {
            builder.append(key).append("=").append(params.get(key)).append("&");
        }
        String result = builder.toString();
        return result.length() > 0 ? result.substring(0, result.length() - 1) : result;
    }
}
<?>