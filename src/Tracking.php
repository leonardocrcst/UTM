<?php

namespace App;

use App\Adapters\Interfaces\AdapterInterface;
use App\Exceptions\AdapterLoadError;
use App\Exceptions\AdapterNotFound;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\InvalidDataException;
use Throwable;
use App\Types\TranslatorType;

class Tracking
{
    /**
     * @throws FileNotFoundException|AdapterNotFound
     * @throws AdapterLoadError
     * @throws InvalidDataException
     */
    public static function sheet(string $filename, TranslatorType $translator): ?array
    {
        $track = new Tracking();
        if (file_exists($filename)) {
            $fileType = $track->getFileType($filename);
            $adapter = $track->getAdapter($fileType);
            if ($adapter) {
                $content = $adapter->translate($filename);
                if (is_array($content)) {
                    return $track->compute($content, $translator);
                }
                throw new InvalidDataException();
            }
            throw new AdapterNotFound();
        }
        throw new FileNotFoundException();
    }

    private function getFileType(string $filename)
    {
        return mime_content_type($filename);
    }

    /**
     * @throws AdapterLoadError
     */
    private function getAdapter(string $fileType): ?AdapterInterface
    {
        foreach (glob(getcwd() . '/src/Adapters/*.php') as $item) {
            $name = "App\\Adapters\\" . $this->getAdapterName($item);
            try {
                /** @var AdapterInterface $adapter */
                $adapter = new $name();
                if ($adapter instanceof AdapterInterface && $adapter->accept($fileType)) {
                    return $adapter;
                }
            } catch (Throwable $e) {
                throw new AdapterLoadError($name);
            }
        }
        return null;
    }

    private function getAdapterName(string $filename): string
    {
        $exp = explode(DIRECTORY_SEPARATOR, $filename);
        return str_replace(".php", "", $exp[count($exp) - 1]);
    }

    private function compute(array $data, TranslatorType $translator):?array
    {
        $indexes = $this->getColumnsIndex($data[0], $translator);
        $lines = [];
        foreach (array_slice($data, 1, count($data)) as $line => $values) {
            foreach ($indexes as $key => $index) {
                $lines[$line][$key] = $values[$index];
            }
        }
        return $lines;
    }

    private function getColumnsIndex(array $line, TranslatorType $translator): array
    {
        $indexes = [];
        foreach (get_object_vars($translator) as $property => $value) {
            $indexes[$property] = array_search($value, $line);
        }
        return $indexes;
    }

    /**
     * @throws AdapterLoadError
     * @throws InvalidDataException
     * @throws FileNotFoundException
     * @throws AdapterNotFound
     */
    public static function sheets(array $list): array
    {
        $content = [];
        /**
         * @var string $file
         * @var TranslatorType $translator
         */
        foreach ($list as $file => $translator) {
            $content = self::merge($content, self::sheet($file, $translator));
        }
        return $content;
    }

    public static function merge(array $previous, array $next): array
    {
        return array_merge($previous, $next);
    }
}
