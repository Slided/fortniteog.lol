<!DOCTYPE html>
<html>
  <body>
    <?php
      $a=null;
      $b=null;
      exec("./update.sh", $a, $b);

      echo "<p>UPDATING</p>";
      echo "<meta http-equiv=\"refresh\" content=\"0;url=/?file=/\">";
    ?>
  </body>
</html>