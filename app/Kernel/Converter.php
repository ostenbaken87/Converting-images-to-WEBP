<?php
$inputFile = $_FILES['inputFile']['tmp_name'];
$inputFileName = $_FILES['inputFile']['name'];
$ext = pathinfo($inputFileName, PATHINFO_EXTENSION);
$fileName = uniqid() . "." . $ext;

$outputDir = "../Storage/webp/";
if (!is_dir($outputDir)) {
    try {
        mkdir($outputDir, 0777, true);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
$outputFile = $outputDir . $fileName;

if (!isset($_FILES['inputFile']) || !is_uploaded_file($inputFile)) {
    echo "No file uploaded";
    die();
}


$fileType = exif_imagetype($inputFile);

switch ($fileType) {
    case IMAGETYPE_GIF:
        $image = imagecreatefromgif($inputFile);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        break;
    case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($inputFile);
        break;
    case IMAGETYPE_PNG:
        $image = imagecreatefrompng($inputFile);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        break;
    case IMAGETYPE_WEBP:
        rename($inputFile, $outputFile);
        return;
    default:
        return;
}

try {
    if(imagejpeg($image, $outputFile, 80)) {
    echo "success";
    }else {
        echo "failed";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    imagedestroy($image);
}
