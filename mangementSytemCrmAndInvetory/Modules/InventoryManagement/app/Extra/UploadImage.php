<?php
class UploadImage{
    public static function uploadImage($image, $product){
        $path = $image->store('images', 'public');
        $product->attachments()->create(['path' => $path]);
    }
}
