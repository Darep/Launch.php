<?php
/*
 * ui.php
 * Helper functions for views
 * site_header(), site_footer(), etc.
 *
 * (c) 2011 Antti-Jussi Kovalainen (www.ajk.im)
 */


/**
 * Print the HTML 
 *
 * @param $title Page title
 */
function site_header($title = '')
{ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <title><?php if (!empty($title)) echo $title, ' &ndash; ' ?></title>
   
    <link rel="stylesheet" href="<?php echo SITE_BASE ?>content/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo SITE_BASE ?>content/css/enhance.css" type="text/css" media="screen" />
</head>
<body>

<div id="wrap">


<?php
}


function site_footer($show_text = true)
{ ?>


</body>
</html>

    <?php
    
}

