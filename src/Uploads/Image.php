<?php
namespace Lab123\Odin\Uploads;

use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App;

class Image
{

    /**
     * Path to upload
     *
     * @var $path
     */
    protected $path;

    /**
     * Image name
     *
     * @var $name
     */
    protected $name;

    /**
     * Original image
     *
     * @var $originalImage
     */
    protected $originalImage;

    /**
     * Formated Image
     *
     * @var $image
     */
    protected $image;

    /**
     * Formated Thumbnaill
     *
     * @var $thumbnaill
     */
    protected $thumbnaill;

    /**
     * Visibility file
     *
     * @var $visibility
     */
    protected $visibility = FilesystemContract::VISIBILITY_PRIVATE;

    /**
     * Properties to format Image
     *
     * @var $properties array
     */
    protected $properties = [
        'format' => 'jpg',
        'quality' => '75',
        'width' => '1000',
        'height' => null
    ];

    /**
     * Properties to format Thumbnaill
     *
     * @var $thumbnaillProperties array
     */
    protected $thumbnaillProperties = [
        'prefix' => 'thumb',
        'width' => '200',
        'height' => null
    ];

    /**
     * Fill data object
     *
     * @return void
     */
    public function __construct(UploadedFile $file = null, $name = '')
    {
        $this->fill(compact('file', 'name'));
    }

    /**
     * Create image in server (upload)
     *
     * @return void
     */
    public function create(UploadedFile $file, $name = '')
    {
        $this->fill(compact('file', 'name'));
        
        if ($this->upload($this->getFullPath(), $this->getPayload($this->image))) {
            return $this->getFullName();
        }
        
        return false;
    }

    /**
     * Create thumbnaill in server (upload)
     *
     * @return void
     */
    public function thumbnaillCreate()
    {
        $this->thumbnaill = $this->format($this->image, $this->thumbnaillProperties);
        
        return $this->upload($this->getFullPathThumbnail(), $this->getPayload($this->thumbnaill));
    }

    /**
     * Upload image to path
     *
     * @return void
     */
    private function upload($path, $payload)
    {
        return Storage::disk('s3')->put($path, $payload, $this->visibility);
    }

    /**
     * Format image (format, resize, etc)
     *
     * @return void
     */
    public function format($image, array $properties)
    {
        if (key_exists('format', $properties)) {
            $image->encode($properties['format'], $properties['quality']);
        }
        
        if (key_exists('width', $properties)) {
            $image->resize($properties['width'], $properties['height'], function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        
        return $image;
    }

    /**
     * Get full name (name and extension)
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->name . '.' . $this->properties['format'];
    }

    /**
     * Get Path
     *
     * @return string
     */
    public function getPath()
    {
        return App::environment() . $this->path;
    }

    /**
     * Get full path file
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->getPath() . $this->getFullName();
    }

    /**
     * Get full path Thumbnail
     *
     * @return string
     */
    public function getFullPathThumbnail()
    {
        return $this->getPath() . $this->getThumbnaillPrefix() . $this->getFullName();
    }

    /**
     * Get prefix of Thumbnail
     *
     * @return string
     */
    public function getThumbnaillPrefix()
    {
        return ($this->thumbnaillProperties['prefix']) ? $this->thumbnaillProperties['prefix'] . '-' : '';
    }

    /**
     * Payload of image
     *
     * @return string
     */
    public function getPayload($image)
    {
        return (string) $image->encode();
    }

    /**
     * Fill object
     *
     * @return void
     */
    protected function fill($array)
    {
        if (is_null($array['file'])) {
            return;
        }
        
        $this->originalImage = ImageManager::make($array['file']);
        $this->image = $this->format($this->originalImage, $this->properties);
        
        $this->name = ($array['name']) ? $array['name'] : uniqid();
    }
}