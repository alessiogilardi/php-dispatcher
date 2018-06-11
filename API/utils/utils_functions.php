<?


function generateRandomToken($length = 128) {
     $token = '';
     $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $codeAlphabet.= 'abcdefghijklmnopqrstuvwxyz';
     $codeAlphabet.= '0123456789';
     $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

// TODO: INVECE DI UNSARE UN SEPARATOR POTREI DEFIIRE DEI TIPI E IN BASE AL TIPO UNA LUNGHEZZA PER IL TOKEN
// TODO: resta il problema di mettere più dati assieme
function verifyHashSign($signed, &$unsigned = '', $signature = '') {
  $val = substr($signed, 0, strlen($signed)-64);
  $mac = substr($signed, strlen($signed)-64, strlen($signed)-1);
  if (hash_equals(hash_hmac('sha256', $val, $signature.SECRET_KEY), $mac)) {
    $unsigned = $val;
    return true;
  }
  $unsigned = '';
  return false;
}

function hashSign($value, $signature = '') {
  //$mac = hash_hmac('sha256', $value, $secretKey);
  return $value.hash_hmac('sha256', $value, $signature.SECRET_KEY);
}

function destroyCookie($name, $path = '/') {
  if (isset($_COOKIE[$name])) {
    unset($_COOKIE[$name]);
    setcookie($name, '', time() - 3600, $path);
  }
}

function joinToString($array1, $array2, $joinChar, $separator = ',') {
  if (count($array1) != count($array2)) {
    return false;
  }
  $str = '';
  $N = count($array1);
  for ($i = 0; $i < $N; $i++)
    $str.= $array1[$i].$joinChar.$array2[$i].$separator;
  return trimLastChar($str);
}

function trimLastChar($string) {
  return substr($string, 0, -1);
}

function getDirectoryName() {
  return basename(__DIR__);
}

?>
