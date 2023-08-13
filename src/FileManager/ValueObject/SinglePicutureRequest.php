<?php

declare(strict_types=1);

namespace App\FileManager\ValueObject;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SinglePicutureRequest
{
    /**
     * @SerializedName("fullPath")
     */
    private string $fullPath;

    public function __construct(string $fullPath)
    {
        $this->fullPath = $fullPath;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }
}
