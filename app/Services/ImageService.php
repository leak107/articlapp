<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Contracts\ImageableInterface;
use App\Models\Image;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{

    public function add(ImageableInterface $imageable, UploadedFile $image, User $user): Image
    {
        /** @var string $fileName **/
        $fileName = $image->getClientOriginalName();

        /** @var string $mimeType **/
        $mimeType = $image->getClientMimeType();

        /** @var int $fize **/
        $size = $image->getSize();

        $path = $this->upload($image);

        $image = new Image();

        $image->imageable_type = $this->getImageableType($imageable);
        $image->imageable_id = $imageable->id;
        $image->filename = $fileName;
        $image->full_path = $path;
        $image->mime_type = $mimeType;
        $image->size = $size;
        $image->created_by_id = $user->id;
        $image->save();

        return $image;
    }

    /**
    * @param Image $image
    *
    * @return bool
    */
    public function delete(Image $image): bool
    {
        return $image->delete();
    }

    /**
    * @param ImageableInterface $imageable
    *
    * @return string
    */
    protected function getImageableType(ImageableInterface $imageable): string
    {
        if ($imageable instanceof Post) return 'post';

        if ($imageable instanceof Product) return 'product';
    }

    /**
    * @param UploadedFile $image
    *
    * @throws Exception
    *
    * @return string
    */
    protected function upload(UploadedFile $image): string
    {
        try {
            $extension = $image->getClientOriginalExtension();

            $hashedFileName = Str::random(10) . '.' . $extension;

            Storage::disk('images')->put($hashedFileName, file_get_contents($image));

            return $hashedFileName;
        } catch (Exception $e) {
            logger($e->getMessage());

            throw new Exception('Storage API Unavailable', 503);
        }
    }
}
