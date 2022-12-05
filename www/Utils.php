<?php

class Utils
{
  public static function get_password()
  {
    $path = "/run/secrets/password"; // docker secrets location

    $f = fopen($path, "r");
    $pw = preg_replace("/\s+$/", "", fread($f, filesize($path))); // remove trailing space if any
    fclose($f);

    return $pw;
  }

  public static function redirect($url)
  {
    echo "<script> location.replace(\"$url\"); </script>";
  }
}
