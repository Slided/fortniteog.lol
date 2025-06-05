<!DOCTYPE html>
<html>
  <head>
    <?php
      if (array_key_exists("file", $_GET)) {
        echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $_GET["file"] . "\">";
      } else {
        // $output=null;
        // $status=null;
        // exec("curl https://raw.githubusercontent.com/TechHog8984/luau_beautifier/main/version.txt", $output, $status);
  
        // $version_file = fopen("cached_version.txt", "r");
        // $version = fread($version_file, filesize("cached_version.txt"));
        // fclose($version_file);
  
        // if ($output[0] != $version) {
        //   echo "<p>UPDATING</p>";
        //   echo "<meta http-equiv=\"refresh\" content=\"0;url=update.php\">";
        // };
      };
    ?>

    <style>
      body {
        background: #2b2a33
      }
      h1 {
        color: #c2c0ba
      }
      a {
        color: #c2c0ba
      }
      form {
        color: #c2c0ba
      }
    </style>
  </head>

  <body>
    <h1>Luau Beautifier</h1>
    <a href = https://github.com/TechHog8984/luau_beautifier>https://github.com/TechHog8984/luau_beautifier</a>
    <br>
    <a>Please note that the beautified output you receive is public and may be accessed by others.</a>
    </br>
    
    <br>
    <form action="/beautify.php" method="POST" enctype="multipart/form-data">
      <label for="myfile">Select a file:</label>
      <input type="file" id="myfile" name="myfile">
      <br>
      <input type="checkbox" id="minify" name="minify">
      <label for="minify">Minify</label>
      <br>
      <input type="checkbox" id="nosolve" name="nosolve">
      <label for="nosolve">Don't solve expressions</label>
      <br>
      <input type="submit" name="submit" value="go">
    </form> 
  </body>
</html>