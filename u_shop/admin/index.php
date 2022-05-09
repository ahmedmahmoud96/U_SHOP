<?php
session_name('adminLogin');
session_start();
$NoNavbar = "";
$pageTitle = "Log In";
$conn = mysqli_connect("localhost", "root", "", "u_shop");
if (isset($_SESSION['Admin_email'])) {
  //  header("location:dashboard.php");
}
//include "init.php";

// Check If User Coming From HTTP Post

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $shapassword=sha1($password);

    $check_admin = "SELECT * FROM admin where email = '$email' and password='$shapassword'";
    $res = mysqli_query($conn, $check_admin);
    if (mysqli_num_rows($res)) {
        $row = mysqli_fetch_array($res);
        $_SESSION['Admin_email'] = $email;
        $_SESSION['ID'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['password'] = $row['password'];
       // header("location:dashboard.php");
       echo "انت  ادمن ";
        exit();
    } else {
        echo "<div class='alert alert-danger'> ألبريد ألألكتروني أو كلمة السر غير صحيحة  </div>";
    }
}

?>
<div class="container" dir="ltr">
    <div class="login">
        <h3 class="text-center">Admin Login </h3>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" role="form" enctype="multipart/form-data">
            <div class="form-group">
                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
            </div>
            <div class="d-grid gap-2">
                <input type="submit" name="admin-submit" id="login-submit" tabindex="4" class="btn btn-primary btn-lg" value="Log In">
            </div>
        </form>
    </div>
</div>

<?php
//include $tpl . "footer.php";
?>