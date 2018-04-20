<?php
include("../classes/db.php");
include("../classes/Login.php");
$userid = Login::isLoggedIn();
if(isset($_POST['uploadFile'])){
   move_uploaded_file($_FILES['file']['tmp_name'], "uploads/".$_FILES['file']['name']);
   DB::query("INSERT INTO video_uploads VALUES ('', :userid, :path, :title)", array(":userid"=>$userid, ":path"=>"uploads/".$_FILES['file']['name'], ":title"=>$_POST['title']));
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
    <style type="text/css">
      @import url('https://fonts.googleapis.com/css?family=ABeeZee|Questrial|Ropa+Sans');
    </style>
    <title>Me-tube</title>
  </head>
  <body>
    <section class="hero is-primary is-fullheight">
        <div class="hero-head">
          <nav class="navbar">
            <div class="container">
              <div class="navbar-brand">
                <h1 class="title" style="font-family: 'ABeeZee', sans-serif;">Metube</h1>
                <span class="navbar-burger burger" data-target="navbarMenuHeroA">
                <span></span>
                <span></span>
                <span></span>
                </span>
              </div>
               <div id="navbarMenuHeroA" class="navbar-menu">
                  <div class="navbar-end">
                      <label class="label">Search</label>
                      <input type="text" class="input is-half" name="Query">
                      <input type="submit" class="button" name="search">
                  </div>
               </div>   
            </div>
          </nav>  
        <form action="index.php" method="post" enctype="multipart/form-data">
          <label class="label">Video title here</label>
          <input type="text" class="w3-input" name="title"><br>
          <label for="file" class="label">Select:</label>
          <input type="file" name="file" class="w3-input w3-button w3-round w3-indigo w3-hover-blue" id="file">
          <br>
          <input type="submit" class="w3-button w3-round w3-indigo w3-hover-blue" name="uploadFile" value="Upload">
        </form>
      </div>
    </div>
    <br>
    <div class="w3-container" style="background-color: #1a42e0;">
      <h1>Latest Uploads:</h1>
    </div>
    <hr>
    <?php
     $uploads = DB::query("SELECT * FROM video_uploads");
     foreach($uploads as $something){
      $uploader_name = DB::query("SELECT username FROM users WHERE id=:userid", array(":userid"=>$something['user_id']))[0]['username'];
      echo "<span style='background-color:#365ae7;color:white;padding:10px;'><a href='".$something['path']."'>".$something['title']."</a>"." by-" . $uploader_name . "</span><hr>";
     }    
    ?>
     </section>
  </body>
</html>
