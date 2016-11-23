var CryptoJS = require('crypto-js');

class Encryptor {  
  constructor() {
    ;
  }
  
  /**
   * 
   * @param {Object} encryptedContent object with encrypted key and encrypted content
   * @returns {String} The decrypted string
   */
  decrypt(encryptedContent) {
      let secretKey = encryptedContent.key;      
      let content = encryptedContent.content;      
      let bytes = CryptoJS.AES.decrypt(content, secretKey);      
      return bytes.toString(CryptoJS.enc.Utf8);        
  }

  /**
   * 
   * @param {string} toEncrypt text to encrypt
   * @returns {EncryptedContent} The encrypted information
   */
  encrypt(toEncrypt) {
      let secretKey = CryptoJS.lib.WordArray.random(16).toString(CryptoJS.enc.Base64);        
      let ciphertext = CryptoJS.AES.encrypt(toEncrypt, secretKey.toString());
      return {content:ciphertext.toString(), key: secretKey};
  }
};

module.exports = Encryptor;


