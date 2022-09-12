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

        $backButton = "";       // Logic
        $counter = ""; 
        if (isset($_GET["path"])) {
            $backButton = explode('/', rtrim($_SERVER['QUERY_STRING'], '/'));
            array_pop($backButton);
            $counter = count($backButton) == 0
                            ? $backButton = './'
                            : $backButton = '?' . implode('/', $backButton) . '/';
           
        }

        print('<div class="mb-3 mt-4" style="display: flex-end; aligne-items: center;">
        <button class="mb-4 mx-2 btn btn-warning mt-3 ">
        <a href="index.php?action=logout" style="text-decoration: none; color: white;">
        <i class="fa-solid fa-right-from-bracket"></i>Logout</a></button></div>'); // Logout button

        if(!$counter == 0){             // Back button 
            print('<button class="mb-4 mx-2 btn btn-primary " style="float: left;">
            <a style="text-decoration: none; color: white;" href= "' . $backButton . '">
            <i class="fa-solid fa-circle-arrow-left"></i> 
            Back</a>
            </button>');
        }else {
            print('<button class="mb-4 mx-2 btn btn-light " style="float: left;">
            <a style="text-decoration: none; color: grey;">
            <i class="fa-solid fa-circle-arrow-left"></i> 
            Back</a>
            </button>');
        }

        print('
        <table class="table table-striped table-active" style="width:80%; margin:auto; border-radius: 10%; border: 1px solid black;">
        <th style="width: 50%; text-align: center; border: 1px solid black;">Name</th>
        <th style="width: 10%; text-align: center; border: 1px solid black;">Type</th>
        <th style="width: 10%; text-align: center; border: 1px solid black;">Delete</th> 
        <th style="width: 10%; text-align: center; border: 1px solid black;">Download</th>
    ');
    


    foreach ($docs as $value) {
        if ($value != ".." and $value != "." and $value != ".git") {
            print('<tr>');
            print('<td style="border: 1px solid black;">' . (is_dir($path . $value)
                ? '<i class="fa-solid fa-folder-open" style="font-size: 20px; color:  #0073e6; "></i> <a style=" text-decoration: none; color:  #0073e6 " href="' . (isset($_GET['path'])
                    ? $_SERVER["REQUEST_URI"] . $value . '/'
                    : $_SERVER['REQUEST_URI'] . '?path=' . $value . '/') . '">' . $value . '</a>'
                : $value)                    
                . '</td>');
               
            print('<td style="border: 1px solid black; text-align: center; ">' . (is_dir($path . $value) ? "Folder" : "File") . '</td>');


            if (is_dir($path . $value)) { 
                print('<td style="border: 1px solid black;"></td>');
                print('<td style="border: 1px solid black;"></td>');
            } else if (is_file($path . $value)) {
                

                print('<td style="border: 1px solid black;">' .
                    '<form style= "display: flex; justify-content: center" action="" method="post">
                        <button class="delete btn btn-xs btn-danger" type ="submit" name="delete" value ='  . $value . ' style="color: white;">
                        <i class="fa-regular fa-trash-can"></i> 
                        Delete</button>
                        </form>
                       </td>'); 



                print('<td style="border: 1px solid black;">');
                print('<form style= "display: flex; justify-content: center" action="" method="POST">');
                print('<button type="submit" name="download" value="' . $value . '" class="btn" style=" color: white; background: #2884bd;">
                       <i class="fa-solid fa-download"></i> 
                       Download</button>
                       </form>'); 
                print('</td>');
            print("</tr>");  
            }
        }
    }
    print('</table>'); 
    print("<div style='display: flex; justify-content: space-between;'>"); 
                print('<form class="mb-4" action="" method="POST" style=" text-align: end;">
                <input name="createfolder" type="text" class="p-2 mb-4 mt-3  rounded" placeholder="Folder name" style="height: 48px; background-color: white; border: 2px solid gray;">
                <button type="submit" class="btn btn-success mb-4 mt-3" style="color: white;">
                <i class="fa-solid fa-folder-plus"></i> 
                Create Folder</button> 
                </form>');  

                
                print('<form class="mb-4" action="" method="POST" enctype="multipart/form-data" style="margin-left:10px; text-align: end;">
                <input type = "file" name = "image" value = "" class="btn w-40 p-2 mb-4 mt-3 rounded" style="border: 2px solid grey; background-color: white"/>
                <button type = "submit" class="btn btn-success mb-4 mt-3" style="color: white;"/>
                <i class="fa-solid fa-upload"></i> 
                Upload file</button>
                </form>');  
        print('</div>');


    }
    ?>


</body>

</html>