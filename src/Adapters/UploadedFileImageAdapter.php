<?php

namespace Platina\Image\Adapters;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Imagick;
use ImagickException;
use ImagickPixel;
use Platina\Image\Contracts\ImageInterface;
use Platina\Image\Exception\NotReadableException;

/**
 * Адаптер для создания объекта изображения на основе данных, предоставленных UploadedFile
 *
 * Class UploadedFileImageAdapter
 */
class UploadedFileImageAdapter extends AbstractImageAdapter
{
    protected UploadedFile $file;

    /**
     * Конструктор класса.
     *
     * @param UploadedFile $file UploadedFile, представляющий загруженный файл
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * Метод для создания объекта изображения на основе данных UploadedFile.
     *
     * @return ImageInterface Объект изображения.
     *
     * @throws NotReadableException|FileNotFoundException Исключение, если изображение не может быть прочитано или
     *     обработано.
     */
    public function createImageFromData(): ImageInterface
    {
        try {
            // Создание нового экземпляра объекта Imagick
            $imagick = new Imagick();

            // Устанавливаем фон изображения как прозрачный
            $imagick->setBackgroundColor(new ImagickPixel('transparent'));
            // Установка разрешения (DPI)
            $imagick->setResolution(600, 600);
            // Чтение изображения из загруженного файла
            $imagick->readImageBlob($this->file->get());

            // Получение расширения файла из UploadedFile
            $extension = $this->file->getClientOriginalExtension();
            // Получение имени файла из UploadedFile
            $originalName = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);

            return $this->loadImageFromResource($imagick, [
                    'basename' => basename($this->file->path()),
                    'dirname' =>  "",
                    'filename' =>  $originalName,
                    'extension' =>  $extension,
                ]);
        } catch (ImagickException $e) {
            // Обрабатываем исключения Imagick, если возникли проблемы при обработке изображения
            throw new NotReadableException("Ошибка обработки изображения: " . $e->getMessage());
        }
    }
}
