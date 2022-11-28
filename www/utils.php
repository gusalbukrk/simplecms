<?php

// PHP `headers` function is used to redirect but won't work after HTML output
// https://stackoverflow.com/a/8028987
function redirect($url)
{
  echo "<script> location.replace(\"$url\"); </script>";
}
