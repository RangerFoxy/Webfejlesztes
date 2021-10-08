<?php
  //DB Connection
  $conn = new mysqli('localhost', 'root', '');

  if (!$conn) {
      die('Connection failed: ' . mysqli_connect_error());
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  $username_helper = false;
  $password_helper = false;

  //SQL query
  $query_color = 'select titkos from web.tabla where username like"' . $username . '";';

  //Decryption method
  function decrypt($in_data): string {
      $out_data = $in_data;
      $len = strlen($in_data);
      $offsets = [5, -14, 31, -9, 3];
      $j = 0;
      for ($i = 0; $i < $len; $i++) {
          $c = $in_data[$i];
          $out_data[$i] = chr((ord($c) - $offsets[$j++ % 5]) % 255);
      }
      return $out_data;
  }

  //Write decrypted passwords to a file
  /*
  $file = fopen('password.txt', 'r');
  $decoded = fopen('decoded.txt', 'w');
  while(!feof($file)) {
    $line = trim(fgets($file), "\n");
    $parts = explode('*', decrypt($line));
    fwrite($decoded, $parts[0] . ' ' . $parts[1] . "\n");
  }
  fclose($decoded);
  */
  
  //Validation
      $file = fopen('password.txt', 'r');
      while(!feof($file)) {
        $line = trim(fgets($file), "\n");
        $parts = explode('*', decrypt($line));

        if ($parts[0] === $username) {
          $username_helper = true;
          if ($parts[1] === $password) {
            $password_helper = true;
          } else {
            echo '<body style="text-align: center; font-size: 4rem; font-weight: bold">Hibás jelszó!</body>';
            echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "http://www.police.hu"; }, 3000);</script>';
          }
        }
      }
  
  fclose($file);

  //Username error
  if (!$username_helper) {
    echo '<script type="text/javascript">alert("Hibás felhasználónév!"); location.href="index.html";</script>';
  }

  //Color picking/Password error
  if($password_helper) {
    $result = $conn->query($query_color);
    $color = mysqli_fetch_array($result);
    if ($color[0] == 'piros') {
      echo '<body style="background-color: red"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } elseif ($color[0] == 'zold') {
        echo '<body style="background-color: green"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } elseif ($color[0] == 'sarga') {
        echo '<body style="background-color: yellow"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } elseif ($color[0] == 'kek') {
        echo '<body style="background-color: blue"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } elseif ($color[0] == 'fekete') {
        echo '<body style="background-color: black"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } else {
        echo '<body style="background-color: white"><img src="foka.gif" style="display: block; margin-left: auto; margin-right: auto;"></body>';
    } 
  } 

  $conn->close();

?>