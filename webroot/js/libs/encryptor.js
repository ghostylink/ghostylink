var CryptoJS = require('crypto-js');
 
function Encryptor () {
    ;
}

/**
 * 
 * @param {Object} encryptedContent object with encrypted key and encrypted content
 * @returns {String} The decrypted string
 */
Encryptor.prototype.decrypt = function(encryptedContent) {
    var secretKey = encryptedContent.key;      
    var content = encryptedContent.content;      
    var bytes = CryptoJS.AES.decrypt(content, secretKey);      
    return bytes.toString(CryptoJS.enc.Utf8);
};
 
/**
 * 
 * @param {string} toEncrypt text to encrypt
 * @returns {EncryptedContent} The encrypted information
 */
Encryptor.prototype.encrypt = function(toEncrypt) {
    var secretKey = CryptoJS.lib.WordArray.random(16).toString(CryptoJS.enc.Base64);        
    var ciphertext = CryptoJS.AES.encrypt(toEncrypt, secretKey.toString());
    return {content:ciphertext.toString(), key: secretKey};
};

module.exports = Encryptor