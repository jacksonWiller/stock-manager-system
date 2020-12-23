<?php


namespace App\Services\Image;


class ImageService
{

    public function resize($file, $maxWidth = null, $maxHeight = null)
    {
        $maxsize = 550;

        // create new Imagick object
        $image = new \Imagick($file);
        /*if ($maxWidth) {
            $image->thumbnailImage($maxWidth, 0, true, true);
        } else {
            $image->thumbnailImage(0, $maxHeight, true, true);
        }*/
        // $image->stripImage();
        $fileinfo = pathinfo($file);
        $newFile = $fileinfo['dirname'] . '/' . uniqid() . '.jpg';
        // $image->writeImage($newFile);
        // $image->destroy();

        // return $newFile;

        // Resizes to whichever is larger, width or height
        if ($maxWidth) {
            // Resize image using the lanczos resampling algorithm based on width
            $image->resizeImage($maxWidth, 0, \Imagick::FILTER_LANCZOS, 1);
        } else {
            // Resize image using the lanczos resampling algorithm based on height
            $image->resizeImage(0, $maxHeight, \Imagick::FILTER_LANCZOS, 1);
        }

        // Set to use jpeg compression
        $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
        // Set compression level (1 lowest quality, 100 highest quality)
        $image->setImageCompressionQuality(75);
        // Strip out unneeded meta data
        $image->stripImage();
        // Writes resultant image to output directory
        $this->autoRotateImage($image);
        $image->writeImage($newFile);
        // Destroys Imagick object, freeing allocated resources in the process
        $image->destroy();
        return $newFile;
    }

    public function isImage($filepath)
    {
        $finfo = \finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $info = \finfo_file($finfo, $filepath);
        \finfo_close($finfo);
        return strpos($info, 'image/');
    }

    public function getImageSize($filepath)
    {
        $image = new \Imagick($filepath);
        $width = $image->getImageWidth();
        $height = $image->getImageHeight();
        return [$width, $height];
    }

    protected function autoRotateImage(\Imagick $image)
    {
        $orientation = $image->getImageOrientation();

        switch ($orientation) {
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case \Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
    }
}