<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ImageableInterface
{
    public function images(): MorphMany;
}
