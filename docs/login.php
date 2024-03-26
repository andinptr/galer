<<?php
 session_start();
include 'conn.php';

// Check if user is already logged in using session
if (isset($_SESSION["login"])) {
  header("Location: index.php");
  exit;
}

// Check if cookie exists and try to log in with cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
  $id = $_COOKIE['id'];
  $key = $_COOKIE['key'];

  $stmt = mysqli_prepare($conn, "SELECT Username FROM user WHERE Id_User = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);

  if ($row && $key === hash('sha256', $row['Username'])) {
    $_SESSION['login'] = true;
    header("Location: index.php");
    exit;
  }
}

// Attempt to log in with submitted form
if (isset($_POST['Login'])) {
  $Username = htmlspecialchars($_POST["Username"]);
  $Password = htmlspecialchars($_POST["Password"]);

  $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE Username = ?");
  mysqli_stmt_bind_param($stmt, "s", $Username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (Password_verify($Password, $row['Password'])) {
      $_SESSION['nama'] = $row['nama'];
      $_SESSION['Id_User'] = $row['Id_User'];
      $_SESSION['Username'] = $Username;
     
      $_SESSION['login'] = true;

      if (isset($_POST['remember'])) {
        $secure_key = hash('sha256', $row['Username']);
        setcookie('id', $row['Id_User'], time() + 60, "/", null, true, true);
        setcookie('key', $secure_key, time() + 60, "/", null, true, true);
      }

      header("Location: index.php");
      exit;
    } else {
      echo "
      <script>
          alert('Password Salah!');
          window.location.href = 'login.php';
      </script>
      ";
    }
  } else {
    echo "
    <script>
        alert('Username Tidak Ditemukan!');
        window.location.href = 'login.php';
    </script>
    ";
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgb(238,174,202);
            background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
            
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: pink;
            color: purple;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form method="post"> <center><br><br>
      <img src="https://upload.wikimedia.org/wikipedia/commons/3/31/Logo-smkterataiputihglobal.png" alt="" srcset="">
      </center><br>
      <input type="text" name="username" placeholder="Username" alt="username" required="required"><br>
        <input type="password" name="password" placeholder="Password" alt="password" required="required"><br><br>
        <input type="submit" name="Login" value="Login" alt="submit">
        
    </form>
</body>
</html>
