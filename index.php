<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/d9c5ee75e5.js" crossorigin="anonymous"></script>
    <title>List of folders/files</title>
    <style>

    </style>
</head>

<body>
    
    <?php
            $msg = '';
        if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {       // LOGIN logic
            if ($_POST['username'] == 'Patestuojam' && $_POST['password'] == '1234') {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_POST['username'];
                print('<a href= "./" action= "">');
                
            
            } else {
                $msg = 'Wrong username or password';
            }
        }
    ?>

   
    <div class="text-center mt-5 w-50  m-auto " style="border-radius: 1rem;">       <!--HTML Login Form -->
    
        <h1 class="mb-4" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                ? print("style = 'display: none'")
                                : print("style = 'display: block'") ?>>
                                <span class="" style="font-size: 35px; color: #0073e6; margin-left:10px; padding: 20px;">
                                FILE SYSTEM BROWSER</span>
                                Sign in
        </h1>
        <h4><?php echo $msg; ?></h4>
        <div>
            <form action="" method="POST" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                                ? print("style = \"display: none\"")
                                                : print("style = \"display: block\"") ?>>
                
                <input class="mb-4 " type="text" name="username" placeholder="username = Patestuojam" required autofocus></br>
                <input class="mb-4 " type="password" name="password" placeholder="password = 1234" required></br>
                <button class="btn btn-primary" style=" color: white;" type="submit" name="login" formaction="./">Login</button>
            </form>
        </div>
    </div>
    
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) 
    {
        $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
        $docs = scandir($path);
        print("Puiku!!!!!!!!");

    }
    ?>


</body>

</html>