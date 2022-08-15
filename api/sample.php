<?php

function encrypt($key, $payload)
{
    //$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	$iv = "77215989@jotangi";
    $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 1, $iv);
    //return base64_encode($encrypted . '::' . $iv);
	return base64_encode($encrypted);
}

function decrypt($key, $garble)
{
    //list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
	$iv = "77215989@jotangi";
	$encrypted_data = base64_decode($garble);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 1, $iv);
}

$key = "AwBHMEUCIQCi7omUvYLm0b2LobtEeRAY";
//$c15 = encrypt($key, $c15);
//$decrypt = decrypt($key, $c15);

?>