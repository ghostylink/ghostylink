var expect = require('chai').expect;
var Encryptor = require('../../webroot/js/libs/encryptor');

describe('Encryptor', function() {
   
  describe("#constructor()", function(){
      
  });
  describe('#encrypt()', function() {            
    it('should exist', function(){
       var encryptor = new Encryptor();
       expect(encryptor.encrypt).to.be.a('function');
    });
    it('should return the encrypted message', function() {
       var encryptor = new Encryptor();
       var encrypted = encryptor.encrypt("toBeEncrypt");
       expect(encrypted).to.exist;
       expect(encrypted).to.be.a("Object");
       expect(encrypted.key).to.exist;       
       expect(encrypted.content).to.not.equal("toBeEncrypted");
    });    
  });
  describe('#decrypt', function(){      
      it('should exist', function(){
          var encryptor = new Encryptor();
          expect(encryptor.decrypt).to.be.a('function');
      });

      it('should descript', function(){
        var content = "U2FsdGVkX1+4znozXx98B68QDP1LKBNX8CEK7ELYW5U=";
        var key = "w9iFJl0xm7dsHCLetHVH6g==";
        var encryptedContent = {content:content, key:key};
        var encryptor = new Encryptor();
        expect(encryptor.decrypt).to.exist;        
        expect(encryptor.decrypt(encryptedContent)).to.be.equal("toBeEncrypt")        
      });
  });
});



