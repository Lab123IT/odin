<?php
namespace Lab123\Odin\Libs\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile as UploadedFile;
use Storage;

abstract class File
{

    protected $file;

    protected $name;

    protected $path = '/';

    protected $extension = [];

    public function __construct(UploadedFile $file, $name)
    {
        $this->file = $file;
        $this->name = $name . '.' . $this->file->getClientOriginalExtension();
    }

    public function put()
    {
        return Storage::put($this->path . '/' . $this->name, $this->getContents());
    }

    public function getContents()
    {
        return file_get_contents($this->file->getRealPath());
    }

    public function getName()
    {
        return $this->name;
    }
}