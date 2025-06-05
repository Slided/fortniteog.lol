<!DOCTYPE html>
<html>
  <body>
    <?php
      if(isset($_POST["submit"])) {
        $fi = new FilesystemIterator("output", FilesystemIterator::SKIP_DOTS);
        $output = "output/" . iterator_count($fi) . ".lua";

        $args = "";
        if ($_POST["minify"]) {
          $args = $args . " --minify";
        };
        if ($_POST["nosolve"]) {
          $args = $args . " --nosolve";
        };
        
        $a=null;
        $b=null;
        exec('./luau-beautifier ' . $_FILES["myfile"]["tmp_name"] . $args . " > " . $output, $a, $b);

        echo "<meta http-equiv=\"refresh\" content=\"0;url=/?file=" . $output . "\">";
      };
    ?>
  </body>
</html>