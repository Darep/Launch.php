<?php
/*!
 * Common view functions
 */


/*!
 * Print the site header. To be used with each page/view
 *
 * A function like this is much more efficient than including "header.php" and "footer.php" individually
 *
 * @param $title Page title
 */
function site_header($title = '') {
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <title><?php if (!empty($title)) echo $title, ' &ndash; ' ?>Launch.php</title>

    <link rel="stylesheet" href="<?php echo SITE_BASE ?>content/css/style.css" type="text/css" media="screen" />
</head>
<body>

<header>
    <h1>Launch.php</h1>
</header>

<div id="wrap">

<?php
}


/*!
 * Print the site footer. To be used with each page/view
 */
function site_footer() {
?>

</div>

<footer>
    Footer
</footer>

</body>
</html>

<?php
}


function include_css() {

}
