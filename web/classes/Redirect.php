<?php

class Redirect
{
    public static function to($location = NULL)
    {
        if ($location) {
            header("Location: " . $location);
            exit;
        }
    }
}

?>
