<?php 

// Le fichier
$filename = "img/parallax.jpeg";
$max_width = 600;

// Content type
header('Content-Type: image/jpeg');
// Redimensionnement
$image = imagecreatefromjpeg($filename);
$return_image = imagescale($image,$max_width);

// Affichage
imagejpeg($return_image, null, 100);