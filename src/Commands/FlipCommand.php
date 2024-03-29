<?php

namespace Platina\Image\Commands;

use ImagickException;
use Platina\Image\Contracts\ImageInterface;
use Platina\Image\Image;

/**
 * Команда отражения изображения по горизонтали или вертикали.
 */
class FlipCommand extends Image implements ImageInterface
{
    /**
     * Обрабатывает отражение изображения по горизонтали или вертикали.
     *
     * @param ImageInterface $image Объект изображения, который нужно отразить.
     * @param string $mode Режим отражения: 'h' для горизонтального, 'v' для вертикального.
     *
     * @return ImageInterface Возвращает объект изображения после отражения.
     * @throws ImagickException Исключение, если произошла ошибка Imagick при кодировании изображения.
     */
    public function execute(ImageInterface $image, string $mode = 'h'): ImageInterface
    {
        // Получаем ресурс изображения
        $imagick = $image->getImageResource();

        // Отражаем изображение в зависимости от режима
        if ($mode === 'h') {
            $imagick->flopImage();
        } elseif ($mode === 'v') {
            $imagick->flipImage();
        }

        // Обновляем ресурс изображения в текущем объекте $image
        $image->setImageResource($imagick);

        return $image;
    }
}
