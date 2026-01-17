<?php
/**
 * Index Redirect File
 * Upload ke root subdomain sebagai index.php
 * Ini backup jika .htaccess tidak work
 */

// Redirect ke public folder
header("Location: /public/");
exit();
?>