<?php
/** OpenSSL Functions.

See: {@link http://www.php.net/manual/en/ref.openssl.php}
@package openssl
*/


# FIXME: dummy values
define('X509_PURPOSE_SSL_CLIENT', 1);
define('X509_PURPOSE_SSL_SERVER', 1);
define('X509_PURPOSE_NS_SSL_SERVER', 1);
define('X509_PURPOSE_SMIME_SIGN', 1);
define('X509_PURPOSE_SMIME_ENCRYPT', 1);
define('X509_PURPOSE_CRL_SIGN', 1);
define('X509_PURPOSE_ANY', 1);
define('OPENSSL_ALGO_SHA1', 1);
define('OPENSSL_ALGO_MD5', 1);
define('OPENSSL_ALGO_MD4', 1);
define('OPENSSL_ALGO_MD2', 1);
define('PKCS7_DETACHED', 1);
define('PKCS7_TEXT', 1);
define('PKCS7_NOINTERN', 1);
define('PKCS7_NOVERIFY', 1);
define('PKCS7_NOCHAIN', 1);
define('PKCS7_NOCERTS', 1);
define('PKCS7_NOATTR', 1);
define('PKCS7_BINARY', 1);
define('PKCS7_NOSIGS', 1);
define('OPENSSL_PKCS1_PADDING', 1);
define('OPENSSL_SSLV23_PADDING', 1);
define('OPENSSL_NO_PADDING', 1);
define('OPENSSL_PKCS1_OAEP_PADDING', 1);
define('OPENSSL_CIPHER_RC2_40', 1);
define('OPENSSL_CIPHER_RC2_128', 1);
define('OPENSSL_CIPHER_RC2_64', 1);
define('OPENSSL_CIPHER_DES', 1);
define('OPENSSL_CIPHER_3DES', 1);
define('OPENSSL_KEYTYPE_RSA', 1);
define('OPENSSL_KEYTYPE_DSA', 1);
define('OPENSSL_KEYTYPE_DH', 1);
define('OPENSSL_KEYTYPE_EC', 1);
define('OPENSSL_VERSION_TEXT', "?");
define('OPENSSL_VERSION_NUMBER', 1);

/*. bool .*/ function openssl_x509_export_to_file(/*. mixed .*/ $x509, /*. string .*/ $outfilename /*., args .*/){}
/*. bool .*/ function openssl_x509_export(/*. mixed .*/ $x509, /*. return string .*/ &$out /*., args .*/){}
/*. bool .*/ function openssl_x509_check_private_key(/*. mixed .*/ $cert, /*. mixed .*/ $key){}
/*. array .*/ function openssl_x509_parse(/*. mixed .*/ $x509 /*., args .*/){}
/*. int .*/ function openssl_x509_checkpurpose(/*. mixed .*/ $x509cert, /*. int .*/ $purpose, /*. array .*/ $cainfo /*., args .*/){}
/*. resource .*/ function openssl_x509_read(/*. mixed .*/ $cert){}
/*. void .*/ function openssl_x509_free(/*. resource .*/ $x509){}
/*. bool .*/ function openssl_csr_export_to_file(/*. resource .*/ $csr, /*. string .*/ $outfilename /*., args .*/){}
/*. bool .*/ function openssl_csr_export(/*. resource .*/ $csr, /*. return string .*/ &$out /*., args .*/){}
/*. resource .*/ function openssl_csr_sign(/*. mixed .*/ $csr, /*. mixed .*/ $x509, /*. mixed .*/ $priv_key, /*. int .*/ $days /*., args .*/){}
/*. bool .*/ function openssl_csr_new(/*. array .*/ $dn, /*. return resource .*/ &$privkey /*., args .*/){}
/*. resource .*/ function openssl_pkey_new( /*. args .*/){}
/*. bool .*/ function openssl_pkey_export_to_file(/*. mixed .*/ $key, /*. string .*/ $outfilename /*., args .*/){}
/*. bool .*/ function openssl_pkey_export(/*. mixed .*/ $key, /*. return mixed .*/ &$out /*., args .*/){}
/*. int .*/ function openssl_pkey_get_public(/*. mixed .*/ $cert){}
/*. void .*/ function openssl_pkey_free(/*. int .*/ $key){}
/*. int .*/ function openssl_pkey_get_private(/*. string .*/ $key /*., args .*/){}
/*. bool .*/ function openssl_pkcs7_verify(/*. string .*/ $filename, /*. int .*/ $flags /*., args .*/){}
/*. bool .*/ function openssl_pkcs7_encrypt(/*. string .*/ $infile, /*. string .*/ $outfile, /*. mixed .*/ $recipcerts, /*. array .*/ $headers /*., args .*/){}
/*. bool .*/ function openssl_pkcs7_sign(/*. string .*/ $infile, /*. string .*/ $outfile, /*. mixed .*/ $signcert, /*. mixed .*/ $signkey, /*. array .*/ $headers /*., args .*/){}
/*. bool .*/ function openssl_pkcs7_decrypt(/*. string .*/ $infilename, /*. string .*/ $outfilename, /*. mixed .*/ $recipcert /*., args .*/){}
/*. bool .*/ function openssl_private_encrypt(/*. string .*/ $data, /*. string .*/ $crypted, /*. mixed .*/ $key /*., args .*/){}
/*. bool .*/ function openssl_private_decrypt(/*. string .*/ $data, /*. string .*/ $decrypted, /*. mixed .*/ $key /*., args .*/){}
/*. bool .*/ function openssl_public_encrypt(/*. string .*/ $data, /*. string .*/ $crypted, /*. mixed .*/ $key /*., args .*/){}
/*. bool .*/ function openssl_public_decrypt(/*. string .*/ $data, /*. string .*/ $crypted, /*. resource .*/ $key /*., args .*/){}
/*. mixed .*/ function openssl_error_string(){}
/*. bool .*/ function openssl_sign(/*. string .*/ $data, /*. return string .*/ &$signature, /*. mixed .*/ $key){}
/*. int .*/ function openssl_verify(/*. string .*/ $data, /*. string .*/ $signature, /*. mixed .*/ $key){}
/*. int .*/ function openssl_seal(/*. string .*/ $data, /*. return string .*/ &$sealdata, /*. array .*/ &$ekeys, /*. array .*/ $pubkeys){}
/*. bool .*/ function openssl_open(/*. string .*/ $data, /*. return string .*/ &$opendata, /*. string .*/ $ekey, /*. mixed .*/ $privkey){}
/*. resource .*/ function openssl_pkey_get_details(/*. resource .*/ $key){}
/*. mixed .*/ function openssl_csr_get_subject(/*. mixed .*/ $csr){}
/*. mixed .*/ function openssl_csr_get_public_key(/*. mixed .*/ $csr){}
