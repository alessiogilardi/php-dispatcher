<?php
/**
 * Function used to open a mysqli connection
 * 
 * @param string $serverName
 * @param string $username
 * @param string $password
 * @param string $database
 *
 * @return mysqli $connection
 */
function openDatabaseConnection($serverName = '', $username = '', $password = '', $database = '') {
  if (!isset($serverName))
    $serverName = MYSQL_SERVER;
  if (!isset($username))
    $username = MYSQL_USER;
  if (!isset($password))
    $password = MYSQL_PASSWORD;
  if (!isset($database))
    $database = MYSQL_DB;

  $connection = new mysqli($serverName, $username, $password, $database);
  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
    return NULL;
  }
  return $connection;
}

/**
 * Function used to close a mysqli connection
 * 
 * @param mysqli $connection The DB connection
 *
 * @return void
 */
function closeDatabaseConnection($connection) {
  $connection->close();
}

/**
 * Function used to update database rows
 * 
 * @param string  $table           Table name of DB
 * @param array   $fields          Fields of DB to update
 * @param array   $values          New values
 * @param array   $valuesType      Data type of new values
 * @param string  $whereField      Field of where condition
 * @param string  $whereValue      Value of where condition
 * @param string  $whereValueType  Value type of where params
 * 
 * @return void
 */
# TODO: gestire aggiornamento di più righe
function update($table, $fields, $values, $valuesType, $whereField, $whereValue, $whereValueType) {
  /*$input = array_combine($fields, array_fill( 0, count($values), '?'));
  $str = implode(', ', array_map(
      function ($v, $k) {
        if (is_int($v) || is_float($v) || $v == '?') {
          return sprintf("%s=%s", $k, $v);
        }
        return sprintf("%s='%s'", $k, $v);
      },
      $input,
      array_keys($input)
  ));*/

  $str = joinToString($fields, array_fill( 0, count($values), '?'), '=', ',');

  $conn = openDatabaseConnection();
  # TODO: testare se funziona con implode
  # $paramType = implode('', $valuesType).$whereValueType;
  $paramType = implode('', $valuesType).$whereValueType;
  /*
  $paramType = '';
  foreach ($valuesType as $type)
    $paramType .= $type;
  $paramType .= $whereValueType;
  */
  $aParams = array();
  $aParams[] = &$paramType;

  foreach ($values as $v)
    $v = $conn->real_escape_string($v);

  foreach ($values as &$v)
    $aParams[] = &$v;

  $whereValue = $conn->real_escape_string($whereValue);
  $aParams[] = &$whereValue;

  $query = "UPDATE $table SET $str WHERE $whereField=?";
  $stmt = $conn->prepare($query);
  if($stmt === false) {
    trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
  }
  call_user_func_array(array($stmt, 'bind_param'), $aParams);
  $stmt->execute();
  closeDatabaseConnection($conn);
}

/**
* Function used for projection of DB rows
* 
* @param string $table
* @param array  $fields
* @param string $whereField
* @param string $whereValue
* @param string whereValueType
*
* @return array
*/
function select($table, $fields = null, $whereField = '', $whereValue = '', $whereValueType = '') {
  $fields = isset($fields) ? implode(',', $fields) : '*';
  $query = "SELECT $fields FROM $table WHERE $whereField =?";
  $conn = openDatabaseConnection();
  $whereValue = $conn->real_escape_string($whereValue);
  $stmt = $conn->prepare($query);
  $stmt->bind_param($whereValueType, $whereValue);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows >= 1) {
    $result = $result->fetch_assoc();
    closeDatabaseConnection($conn);
    return $result;
  }
  closeDatabaseConnection($conn);
  return null;
}

/**
 * Function used to insert new rows into DB
 *
 * @param string $table     The name of the DB table
 * @param array $fields     Fields of the table insert
 * @param array $values     Values to insert into the new rows
 * @param array $valuesType The type of values to insert
 *
 * @return void
 */
# TODO: $values deve essere un array multidimensionale, in questo modo pusso inserire più righe con una sola chiamata e sfruttando la struttura della query già generata
function insert($table, $fields, $values, $valuesType) {
 
  /*
  $fields = "(". implode(",", $fields) .")";
  $placeHolder = "(". implode(",", array_fill( 0, count($values), '?')) .")";
  $query = "INSERT INTO $table $fields VALUES $placeHolder";
  */
  $conn        = openDatabaseConnection();
  $fields      = implode(',', $fields);
  $placeHolder = implode(',', array_fill( 0, count($values), '?'));
  $query       = 'INSERT INTO $table ('.$fields.') VALUES ('.$placeHolder.')';
  

/*
  $paramType = '';
  foreach ($valuesType as $type)
    $paramType .= $type;
  $paramType .= $whereValueType;
*/
  $paramType = implode('', $valuesType).$whereValueType;
  $aParams   = array();
  $aParams[] = &$paramType;

  foreach ($values as $v)
    $v = $conn->real_escape_string($v);

  foreach ($values as &$v)
    $aParams[] = &$v;

  //$whereValue = $conn->real_escape_string($whereValue);
  //$aParams[] = &$whereValue;
  $stmt = $conn->prepare($query);
  call_user_func_array(array($stmt, 'bind_param'), $aParams);
  //$stmt->bind_param($whereValueType, $whereValue);

  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

}

# Insert che gestisce l'inserimento di più righe e ritorna un intero invece di un bool
function insert2($table, $fields, $values, $valuesType) {
  $fields      = implode(',', $fields);
  $placeHolder = implode(',', array_fill( 0, count($values), '?'));
  $paramType   = implode('', $valuesType).$whereValueType;
  $aParams     = array();
  $aParams[]   = &$paramType;
  $conn        = openDatabaseConnection();
  $query       = 'INSERT INTO $table ('.$fields.') VALUES ('.$placeHolder.')';
  $stmt        = $conn->prepare($query);

  foreach ($values as $row) {
    foreach ($row as $v)
      $v = $conn->real_escape_string($v);

    foreach ($row as &$v)
      $aParams[] = &$v;
  
    call_user_func_array(array($stmt, 'bind_param'), $aParams);

    $stmt->execute();
    if ($stmt->affected_rows <= 0) {
      $stmt->close();
      closeDatabaseConnection($connection);
      return INSERT_FAIL;
    }
  }
  #$result = $stmt->get_result();
  $lastInterted = $connection->insert_id;
  $stmt->close();
  return $lastInterted;
}

/**
 * Function used the check the user credential on login
 * 
 * @param string $username   The username of the user
 * @param string $password   The password of the user
 * @param array  $dataResult The strcture used to store the query result
 *
 * @return bool
 */
function checkUserCredential($username, $password, &$dataResult = NULL) {
  $connection = openDatabaseConnection();
  $username   = $connection->real_escape_string(trim($username));
	$password   = $connection->real_escape_string(trim($password)); 
	$query      = "SELECT * FROM " . DB_ACCOUNTS_TB_NAME .
                  " WHERE " . DB_ACCOUNTS_USERNAME . "=?" .
                  " AND " . DB_ACCOUNTS_ACTIVE . "=1";

  $stmt = $connection->prepare($query);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($result->num_rows == 1) {
    $data = $result->fetch_assoc();
    if (isset($dataResult)) # POSSIBILE ERRORE QUI
      $dataResult = $data;
    if (password_verify($password, $data[DB_ACCOUNTS_PASSWORD])) {
      closeDatabaseConnection($connection);
      return true;
    }
  }
  closeDatabaseConnection($connection);
  $dataResult = NULL;
  return false;
}

/**
 * Function used to log users in
 *
 * @param string $username
 * @param string $password
 * @param bool   $keepLoggedIn
 * 
 * @return array $user
 */
function loginUser($username, $password, $keepLoggedIn) {
  $user = NULL;
  if (checkUserCredential($username, $password, $user)) {
    setLoginSession($user[DB_ACCOUNTS_USER_ID], $keepLoggedIn);
  }
  return $user;
}

# TODO: 
function buildRegisterNewUserQuery() {
  return "INSERT INTO " . DB_ACCOUNTS_TB_NAME .
    " (" . implode(",", array(DB_ACCOUNTS_USERNAME, DB_ACCOUNTS_PASSWORD, DB_ACCOUNTS_EMAIL, DB_ACCOUNTS_ACTIVATION_TOKEN)) .")" .
    " VALUES (?,?,?,?)";
}

/**
 * Function used to register a new user
 *
 * @param string $username The username of the new user
 * @param string $password The password of the new user
 * @param string $email    The email of the new user
 *
 * @return int             The id of the just inserted user or an error
 */
function registerNewUser($username, $password, $email) {

  $connection      = openDatabaseConnection();
  $username        = $connection->real_escape_string($username);
	$password        = $connection->real_escape_string($password);
  $email           = $connection->real_escape_string($email);
  $password        = password_hash($password, PASSWORD_DEFAULT);
  $activationToken = generateRandomToken();
  //$mac = hash_hmac('sha256', $activationToken, SECRET_KEY);


  //$query = buildRegisterNewUserQuery();

  $stmt = $connection->prepare(buildRegisterNewUserQuery());
  $stmt->bind_param('ssss', $username, $password, $email, $activationToken);
  $stmt->execute();
  if ($stmt->affected_rows<=0) {
    $stmt->close();
    closeDatabaseConnection($connection);
    return REGISTER_FAIL;
  }
  $lastUserId = $connection->insert_id;
  $stmt->close();
  closeDatabaseConnection($connection);
  // href = "/mysite/register/?activate=yes&uid=$lastUserId&token=hashSign($activationToken)"
  return $lastUserId;
}

function registerNewUser2($username, $password, $email) {
  $connection      = openDatabaseConnection();
  $username        = $connection->real_escape_string($username);
  $password        = $connection->real_escape_string($password);
  $email           = $connection->real_escape_string($email);
  $password        = password_hash($password, PASSWORD_DEFAULT);
  $activationToken = generateRandomToken();

  $lastUserId = insert2(DB_ACCOUNTS_TB_NAME, array(DB_ACCOUNTS_USERNAME, DB_ACCOUNTS_PASSWORD, DB_ACCOUNTS_EMAIL, DB_ACCOUNTS_ACTIVATION_TOKEN),
      array($username, $password, $email),
      array('s','s','s','s'));

  if ($lastUserId == INSERT_FAIL)
    return REGISTER_FAIL;

  return $lastUserId;
}

/**
 * Function used to update the password of a user
 * 
 * @param int $userId
 * @param string $newPassword
 * 
 * @return void
 */
# TODO: è necessario richiedere qui la vecchia password? oppure è già dato per fatto?
function updatePassword($userId, $newPassword) {
  $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  update(DB_ACCOUNTS_TB_NAME, array(DB_ACCOUNTS_PASSWORD), array($newPassword), array('s'), DB_ACCOUNTS_USER_ID, $userId, 'i');
}

function activateUser($userId, $signedToken) {
  $token = '';
  if (verifyHashSign($signedToken, $token)) {
    $storedToken = fetchActivationTokenById($userId);
    if (isset($storedToken) && hash_equals($storedToken, $token)) {
      update(DB_ACCOUNTS_TB_NAME, array(DB_ACCOUNTS_ACTIVE, DB_ACCOUNTS_ACTIVATION_TOKEN), array(1, 'NULL'), array('i', 's'),
        DB_ACCOUNTS_USER_ID, $userId, 'i');
    }
  }
}

function fetchActivationTokenById($userId) {
  $token = select(DB_ACCOUNTS_TB_NAME, array(DB_ACCOUNTS_ACTIVATION_TOKEN), DB_ACCOUNTS_USER_ID, $userId);
  return $token[DB_ACCOUNTS_ACTIVATION_TOKEN];
}

function setKeepLogged($userId) {
  $token = generateRandomToken(128);
  storeKeepLoggedToken($userId, $token);
  $cookie = $userId . ':' . $token;
  //$mac = hash_hmac('sha256', $cookie, SECRET_KEY);
  //$cookie .= ':' . $mac;
  setcookie(COOKIE_KEEP_LOGGED, hashSign($cookie), time() + (86400 * 30),'/');
}

function validateKeepLoggedToken() {
  $cookie = isset($_COOKIE[COOKIE_KEEP_LOGGED]) ? $_COOKIE[COOKIE_KEEP_LOGGED] : '';
  if ($cookie) {
    $val = '';
    if (verifyHashSign($cookie, $val)) {
      list($userId, $token) = explode(':', $val);
      /*
      list ($userId, $token, $mac) = explode(':', $cookie);
      if (!hash_equals(hash_hmac('sha256', $userId . ':' . $token, SECRET_KEY),$mac))
        return false;
      */
      $storedToken = fetchKeepLoggedTokenById($userId);
      if (hash_equals($storedToken, $token)) {
        setLoginSession($userId, true);
        return true;
      }
    }
  }
  return false;
}

function storeKeepLoggedToken($userId, $token) {
  update(DB_ACCOUNTS_TB_NAME,
    array(DB_ACCOUNTS_KEEP_LOGGED_TOKEN),
    array($token),
    array('s'),
    DB_ACCOUNTS_USER_ID,
    $userId,
    'i');
}

function destroyKeepLogged() {
  destroyCookie(COOKIE_KEEP_LOGGED);
}

function setLoginSession($userId, $keepLogged) {
  if (session_status() == PHP_SESSION_NONE)
		session_start();
  $_SESSION[SESSION_USER_ID] = $userId;
  if ($keepLogged) {
    setKeepLogged($userId);
  }
}

// TODO: definire meglio quali info estrarre

function fetchUserById($userId) {
  return select(DB_ACCOUNTS_TB_NAME, null, DB_ACCOUNTS_USER_ID, $userId, 'i');
}

function fetchKeepLoggedTokenById($userId) {
  $token = select(DB_ACCOUNTS_TB_NAME, array(DB_ACCOUNTS_KEEP_LOGGED_TOKEN), DB_ACCOUNTS_USER_ID, $userId, 'i');
  return $token[DB_ACCOUNTS_KEEP_LOGGED_TOKEN];
}

function fetchUserByUsername($username) {
  return select(DB_ACCOUNTS_TB_NAME, null, DB_ACCOUNTS_USERNAME, $username, 's');
}

function checkAvailableUsername() {
  if (count(fetchUserByUsername($username)) > 0)
    return false;
  return true;
}
?>