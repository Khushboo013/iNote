<?php
include './partials/_connectdb.php';
$wrongPass = false;
$invalid = false;
if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "select * from `iusers` where `email` = '$email'";

    $result = mysqli_query($conn, $sql);
    $findEmail =  mysqli_num_rows($result);

    if ($findEmail) {
        $findPass = mysqli_fetch_assoc($result);
        $dbPass = $findPass['password'];
        $verifyPass = password_verify($password, $dbPass);

        if ($verifyPass) {
            $login = true;
            session_start();
            $_SESSION['loggedin'] = true;
            header('location:  /myProjects/inote.php');
        } else {
            $wrongPass = true;
        }
    } else {
        $invalid = true;
    }
}



?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNote - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <?php
    include './partials/_nav.php';

    if ($wrongPass) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Wrong password!</strong> RE-enter Password
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    if ($invalid) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Invalid Credentials!</strong> RE-enter
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

    ?>
    <div class="position-relative">
        <div class="container w-50">
            <h1 class="text-center my-4">Login to continue in iNote</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="mb-3 ">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="" name="password" required>
                </div>
                <input class="btn btn-primary" type="submit" value="Submit" name="submit">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</body>

</html>