package com.mustangpay.api.utils.mustangpay;

import lombok.extern.slf4j.Slf4j;
import org.apache.commons.codec.binary.Base64;

import javax.crypto.Cipher;
import javax.crypto.KeyGenerator;
import javax.crypto.SecretKey;
import javax.crypto.spec.GCMParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;

@Slf4j
public class AESUtil {

    private AESUtil() {
        throw new IllegalStateException("Utility class");
    }

    private static final String KEY_ALGORITHM = "AES";
    // Default encryption algorithm
    private static final String DEFAULT_CIPHER_ALGORITHM = "AES/GCM/PKCS5Padding";

    /**
     * AES Encryption operation
     *
     * @param content The content to be encrypted
     * @param password The encryption password
     */
    public static String encrypt(String content, String password) {
        try {
            Cipher cipher = Cipher.getInstance(DEFAULT_CIPHER_ALGORITHM);
            cipher.init(Cipher.ENCRYPT_MODE, getSecretKey(password));
            byte[] iv = cipher.getIV();
            assert iv.length == 12;
            byte[] encryptData = cipher.doFinal(content.getBytes());
            assert encryptData.length == content.getBytes().length + 16;
            byte[] message = new byte[12 + content.getBytes().length + 16];
            System.arraycopy(iv, 0, message, 0, 12);
            System.arraycopy(encryptData, 0, message, 12, encryptData.length);
            return Base64.encodeBase64String(message);
        } catch (Exception e) {
            log.info("AESUtil#encrypt throw exception ,password:{},content:{}",password,content,e);
        }
        return null;
    }

    /**
     * AES Decryption Operation
     *
     * @param base64Content
     * @param password
     * @return
     */
    public static String decrypt(String base64Content, String password) {
        try {
            byte[] content = Base64.decodeBase64(base64Content);
            if (content.length < 12 + 16) {
                throw new IllegalArgumentException();
            }
            GCMParameterSpec params = new GCMParameterSpec(128, content, 0, 12);

            Cipher cipher = Cipher.getInstance(DEFAULT_CIPHER_ALGORITHM);
            cipher.init(Cipher.DECRYPT_MODE, getSecretKey(password), params);
            byte[] decryptData = cipher.doFinal(content, 12, content.length - 12);
            return new String(decryptData);
        } catch (Exception e) {
            log.info("AESUtil#decrypt throw exception,password:{},content:{}",password,base64Content,e);
        }
        return null;
    }

    /**
     *generate key
     *
     * @return
     * @throws NoSuchAlgorithmException
     */
    private static SecretKeySpec getSecretKey(String encryptPass) throws NoSuchAlgorithmException {
        KeyGenerator kg = KeyGenerator.getInstance(KEY_ALGORITHM);

        SecureRandom random=SecureRandom.getInstance("SHA1PRNG");
        random.setSeed(encryptPass.getBytes());
        // Initialize the key generator, AES requires key lengths of 128 bits, 192 bits, or 256 bits.
        kg.init(128, random);
        SecretKey secretKey = kg.generateKey();
        // Convert to an AES-specific key.
        return new SecretKeySpec(secretKey.getEncoded(), KEY_ALGORITHM);
    }
}