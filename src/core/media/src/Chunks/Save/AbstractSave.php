<?php

namespace TVHung\Media\Chunks\Save;

use TVHung\Media\Chunks\Handler\AbstractHandler;
use Illuminate\Http\UploadedFile;

abstract class AbstractSave
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var AbstractHandler
     */
    private $handler;

    /**
     * AbstractUpload constructor.
     *
     * @param UploadedFile $file the uploaded file (chunk file)
     * @param AbstractHandler $handler the handler that detected the correct save method
     */
    public function __construct(UploadedFile $file, AbstractHandler $handler)
    {
        $this->file = $file;
        $this->handler = $handler;
    }

    /**
     * Checks if the file upload is finished.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->isValid();
    }

    /**
     * Checks if the upload is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->file->isValid();
    }

    /**
     * Returns the error message.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->file->getErrorMessage();
    }

    /**
     * Passes all the function into the file.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getFile(), $name], $arguments);
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    /**
     * @return AbstractHandler
     */
    public function handler(): AbstractHandler
    {
        return $this->handler;
    }
}
