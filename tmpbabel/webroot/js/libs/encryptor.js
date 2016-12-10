'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CryptoJS = require('crypto-js');

var Encryptor = function () {
    function Encryptor() {
        _classCallCheck(this, Encryptor);

        ;
    }

    /**
     * 
     * @param {Object} encryptedContent object with encrypted key and encrypted content
     * @returns {String} The decrypted string
     */


    _createClass(Encryptor, [{
        key: 'decrypt',
        value: function decrypt(encryptedContent) {
            var secretKey = encryptedContent.key;
            var content = encryptedContent.content;
            var bytes = CryptoJS.AES.decrypt(content, secretKey);
            return bytes.toString(CryptoJS.enc.Utf8);
        }

        /**
         * 
         * @param {string} toEncrypt text to encrypt
         * @returns {EncryptedContent} The encrypted information
         */

    }, {
        key: 'encrypt',
        value: function encrypt(toEncrypt) {
            var secretKey = CryptoJS.lib.WordArray.random(16).toString(CryptoJS.enc.Base64);
            var ciphertext = CryptoJS.AES.encrypt(toEncrypt, secretKey.toString());
            return { content: ciphertext.toString(), key: secretKey };
        }
    }]);

    return Encryptor;
}();

;

module.exports = Encryptor;