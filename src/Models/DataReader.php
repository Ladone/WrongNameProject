<?php

declare(strict_types=1);

namespace App\Models;

class DataReader
{
    public static function readData(string $path): mixed
    {
        $file = fopen($path,"r");
        try {
            while($line = fgets($file))
                yield json_decode(trim($line));
        } finally {
            fclose($file);
        }
    }
}
