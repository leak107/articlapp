<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ImageabeInterface
{
    public function images(): MorphMany;
}
