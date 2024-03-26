
<?php include "nav.php"?>
<?php include "conn.php"?>
<?php
if (isset($_POST['regis'])){
    $Username = strtolower(stripslashes($_POST['Username']));
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);
	$Password2 = mysqli_real_escape_string($conn, $_POST['Password2']);
    $Email = htmlspecialchars($_POST['Email']);
    $Nama_User = htmlspecialchars($_POST['Nama_User']);
    $Alamat = htmlspecialchars($_POST['Alamat']);

    //cek username
    $result = mysqli_query($conn, "SELECT Username FROM user WHERE Username = '$Username'");
    if(mysqli_fetch_assoc($result)){
        echo "
        <script>
            alert('Username sudah terdaftar, silahkan ganti!!');
            document.location.href='register.php';
        ";
        return false;
    }

    // cek password
    if($Password !== $Password2){
        echo "
        <script>
            alert('Konfirmasi Password Salah');
            document.location.href='register.php';
        </script>";
        
        return false;
    }

    //enkirpsi password
    $Password = password_hash ($Password, PASSWORD_DEFAULT);

    //simpan data ke database
    mysqli_query($conn, "INSERT INTO user VALUES ('', '$Username', '$Password', '$Email', '$Nama_User', '$Alamat')");
    if (mysqli_affected_rows($conn)){
        echo"
        <script>
        alert('Akun Berhasil Di Buat Silahkan Login!! :)');
        document.location.href='register.php';
        </script>";
    } else{
        echo mysqli_error($conn);
    }
}
?>
<body id="page-top">
    <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-2 d-none d-lg-block "></div>
                <div class="col-lg-8">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Registrasi Akun</h1>
                        </div>
                        <form class="user" method="post" action="login.php">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="username"
                                placeholder="Username" name="username" required>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                        id="Password" placeholder="Password" name="password" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                    id="RepeatPassword" placeholder="Ulang Password" name="password2" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email"
                                    placeholder="Email" name="email" required>
                            </div>
                            <div class="form-group">
                                <input type="Nama_User" class="form-control form-control-user" id="Nama_User"
                                    placeholder="Nama User" name="Nama_User" required>
                            </div>
                            <div class="form-group">
                                <input type="alamat" class="form-control form-control-user" id="alamat"
                                    placeholder="Alamat" name="alamat" required>
                            </div>
                            <button type="submit" name="regis" class="btn btn-info btn-user btn-block">Buat Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var Username = document.getElementById("Username").value;
        var Password = document.getElementById("Password").value;
        var UlangPassword = document.getElementById("Ulang Password").value;
        var Email = document.getElementById("Email").value;
        var Nama_User = document.getElementById("Nama_User").value;
        var Alamat = document.getElementById("Alamat").value;

        if (Username === "" ||  Password === "" || UlangPassword === "" ||Email === "" || Nama_User === ""  Alamat === "") {
            alert("Please fill in all fields");
            return false;
        }
        return true;
    }
</script>

    <?php include "footer.php"?>
   