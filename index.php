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

<body style="background-color: #e6f2ff ;">
    <container>
    <div class="container">
    <?php
        session_start(); // START
        
        if (isset($_GET['action']) and $_GET['action'] == 'logout') {    // LOGOUT logic
            session_destroy();
            session_start();
        }
        
        $msg = '';
        if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {       // LOGIN logic
            if ($_POST['username'] == 'Patestuojam' && $_POST['password'] == '1234') {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_POST['username'];
                print('<a href= "./" action= "">');
                header("refresh: 0");
            
            } else {
                $msg = 'Wrong username or password!';
            }
        }
    ?>

   
    <div class="text-center mt-5 w-50  m-auto " style="border-radius: 1rem;">       <!--HTML Login Form -->
    
        <h1 class="mb-4" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                ? print("style = 'display: none'")
                                : print("style = 'display: block'") ?>>
                                <span class="" style="font-size: 35px; color: #0073e6; margin-left:10px; padding: 20px;">
                                <img src="image2.png" alt="Fine">FILE SYSTEM BROWSER</span>
                                Sign in
        </h1>
        <div>
            <form action="" method="POST" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                                ? print("style = \"display: none\"")
                                                : print("style = \"display: block\"") ?>>
                <h4 style="color: red"><?php echo $msg; ?></h4>
                <input class="mb-4 form-control form-control-md" type="text" name="username" placeholder="username = Patestuojam" required autofocus></br>
                <input class="mb-4 form-control form-control-md" type="password" name="password" placeholder="password = 1234" required></br>
                <button class="btn btn-lg btn-block btn-primary" style=" color: white; width: 250px;" type="submit" name="login" formaction="./">Login</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) 
    {
        $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
        $docs = scandir($path);

        // CREATE NEW FOLDER Conditional Statement
        //------------------------------------------
        $success = "";
        $errors = "";
        if (isset($_POST['createfolder'])) {
            $folder_name = $_POST['createfolder'];
            if (isset($_GET['path'])) {
                $path_n = $_GET['path'];
                $path = './' . $path_n;
            }
            if (!file_exists($path . $folder_name)) {
                @mkdir($path . $folder_name, 0777, true);
                header("refresh: 1");
            } else if (isset($_POST['createfolder']) && file_exists("./" . $_POST['createfolder'])) {
                $errors = 'Folder ' . $_POST['createfolder'] . ' already exists';
            }
        }



        
        if (isset($_FILES['image'])) { // Upload file logic
            $errors = "";
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext_n = (explode('.', $_FILES['image']['name']));
            $file_ext = strtolower(end($file_ext_n));
            $extensions = array("jpeg", "jpg", "png", "");

            if ($_FILES['image']['size'] == 0 ){
                $errors = "No file was selected for upload!";
            }           
            if (in_array($file_ext, $extensions) === false) {
                $errors = "Extension not allowed, please choose a JPEG or PNG file.";
            }
            if ($file_size > 2097152) {
                $errors = 'File size must be less than 2 MB';
            }
            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, "./" . $path . $file_name);
                $success = "Success uploaded file!";
                header("refresh: 1");                
            }
        }
        
        // DOWNLOAD FILE Conditional Statement
        //------------------------------------------
        if (isset($_POST['download'])) {
            $file = './' . $_GET['path'] . "/" . $_POST['download'];
            $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));

            ob_clean();
            ob_start();
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileToDownloadEscaped));
            ob_end_flush();
            readfile($fileToDownloadEscaped);
            exit;
        }


        $deleteSuccess = "";            //Delete logic
        $deleteError = '';
        if (isset($_POST['delete']) && $_POST['delete'] !== 'index.php' &&
            $_POST['delete'] !== 'README.md' && $_POST['delete'] !== 'image2.png') {
            $file = './' . $path . $_POST['delete'];
            $file1 = str_replace("&nbsp;", " ", $ki = htmlentities($file, 0, 'utf-8'));
            var_dump($ki);
            if (is_file($file1)) {                
                if (file_exists($file1)) {
                    unlink($file1);
                    header("refresh: 1");
                    $deleteSuccess = 'File Deleted Successfuly!';
                    }
                }
            }
        if (isset($_POST['delete']) && ($_POST['delete'] === 'index.php' || $_POST['delete'] === 'README.md' || $_POST['delete'] == 'image2.png')) {
            $deleteError = 'This file can not be deleted!';
        }

        

        $backButton = "";       //Go Back Logic
        $counter = ""; 
        if (isset($_GET["path"])) {
            $backButton = explode('/', rtrim($_SERVER['QUERY_STRING'], '/'));
            array_pop($backButton);
            $counter = count($backButton) == 0
                            ? $backButton = './'
                            : $backButton = '?' . implode('/', $backButton) . '/';
           
        }
       
        // Header, Logout, Create folder, Upload folder
        
        print("<div style='margin-top: -20px; display: flex; align-items: center;
               justify-content: space-between ; display: space-  margin-left:10px;'>");               
                print('<div><span class="" style="font-size: 35px; color: #0073e6; margin-left:10px; padding: 20px;">  
                       <img src="image2.png" alt="Fine">FILE SYSTEM BROWSER</span></div>');  //  FSB   
                print('<div class="mb-3 mt-4" style="display: flex-end; aligne-items: center;">
                       <button class="mb-4 mx-2 btn btn-warning mt-3 ">
                       <a href="index.php?action=logout" style="text-decoration: none; color: white;">
                       <i class="fa-solid fa-right-from-bracket"></i>Logout</a></button></div>'); // Logout button
        print("</div>");



        print("<div style='display: flex; justify-content: space-between;'>"); 
                print('<form class="mb-4" action="" method="POST" style=" text-align: end;">
                <input name="createfolder" type="text" class="p-2 mb-4 mt-3  rounded" placeholder="Folder name" style="height: 48px; background-color: white; border: 2px solid gray;">
                <button type="submit" class="btn btn-success mb-4 mt-3" style="color: white;">
                <i class="fa-solid fa-folder-plus"></i> 
                Create Folder</button> 
                </form>');  //Create folder

                
                print('<form class="mb-4" action="" method="POST" enctype="multipart/form-data" style="margin-left:10px; text-align: end;">
                <input type = "file" name = "image" value = "" class="btn w-40 p-2 mb-4 mt-3 rounded" style="border: 2px solid grey; background-color: white"/>
                <button type = "submit" class="btn btn-success mb-4 mt-3" style="color: white;"/>
                <i class="fa-solid fa-upload"></i> 
                Upload file</button>
                </form>');  //Upload file
        print('</div>');

        
        print("<div style=' display: flex; justify-content: space-between;  margin-left:10px;'>"); // Errors Display Logic and View
            print('<h6 class="mx-2 mt-4" style="color: #0073e6;" >Directory: ' . str_replace('?path=/', "", $_SERVER["REQUEST_URI"]) . '</h6>');
            if(!$deleteSuccess == "" || !$errors == "" || !$success == "" || !$deleteError == "" ){
                print('<h6><div class="alert alert-warning alert-dismissible fade show" role="alert" >
                      ' . $deleteSuccess . $errors . $success . $deleteError . '
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                       </div></h6>');
                }
        print('</div>');
       

            if(!$counter == 0){             // Back button show logic
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
            print("<br>");
        print("</div>");
        print("</div>");
        print("<br>");
        print("<br>");

        

        //Table Start      
        print('
            <table class="table table-striped table-active" style="width:80%; margin:auto; border-radius: 10%; border: 1px solid black;">
            <th style="width: 50%; text-align: center; border: 1px solid black;">Name</th>
            <th style="width: 10%; text-align: center; border: 1px solid black;">Type</th>
            <th style="width: 10%; text-align: center; border: 1px solid black;">Delete</th> 
            <th style="width: 10%; text-align: center; border: 1px solid black;">Download</th>
        '); // All Table Headers


        foreach ($docs as $value) { //Printing Files and Folders list
            if ($value != ".." and $value != "."  and $value != ".git" and $value != "Pictures") {
                print('<tr>');
                print('<td style="border: 1px solid black;">' . (is_dir($path . $value)
                    ? '<i class="fa-solid fa-folder-open" style="font-size: 20px; color:  #0073e6; "></i>
                    <a style=" text-decoration: none; color:  #0073e6 " href="' . (isset($_GET['path'])
                        ? $_SERVER["REQUEST_URI"] . $value . '/'
                        : $_SERVER['REQUEST_URI'] . '?path=' . $value . '/') . '">' . $value . '</a>'
                    : $value)                    
                    . '</td>');
                    // Show File or Folder
                print('<td style="border: 1px solid black; text-align: center; ">' . (is_dir($path . $value) ? "Folder" : "File") . '</td>');


                if (is_dir($path . $value)) {   // Delete and Download buttons show logic start
                    print('<td style="border: 1px solid black;"></td>');
                    print('<td style="border: 1px solid black;"></td>');
                } else if (is_file($path . $value)) {
                    

                    print('<td style="border: 1px solid black;">' .
                        '<form style= "display: flex; justify-content: center" action="" method="post">
                            <button class="delete btn btn-xs btn-danger" type ="submit" name="delete" 
                            value ='  . str_replace(' ', '&nbsp;', $value) . ' style="color: white;">
                            <i class="fa-regular fa-trash-can"></i> 
                            Delete</button>
                            </form>
                           </td>');  // Delete button



                    print('<td style="border: 1px solid black;">');
                    print('<form style= "display: flex; justify-content: center" action="" method="POST">');
                    print('<button type="submit" name="download" value="' . $value . '" class="btn" style=" color: white; background: #2884bd;">
                           <i class="fa-solid fa-download"></i> 
                           Download</button>
                           </form>');  // Downolad button
                    print('</td>');
                print("</tr>");  // Delete and Download buttons show logic end
                }
            }
        }
        print('</table>'); //Table end
    }
    ?>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script src="https://kit.fontawesome.com/d9c5ee75e5.js" crossorigin="anonymous"></script>
</div>

    
    </container>
</body>

</html>