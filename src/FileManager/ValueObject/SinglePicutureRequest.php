<?php

declare(strict_types=1);

namespace App\FileManager\ValueObject;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SinglePicutureRequest
{
    /**
     * @SerializedName("value")
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
