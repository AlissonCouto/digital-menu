<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class StorageService
{

    public function upload($file, $module)
    {
        // Define o valor default para a variável que contém o nome da imagem 
        $nameFile = null;

        //Storage::disk('public')->delete($module . $request->oldImage);
        // Define um aleatório para o arquivo baseado no timestamps atual
        $name = uniqid(date('HisYmd'));

        // Recupera a extensão do arquivo
        $extension = $file->extension();

        $originalName = explode('.', $file->getClientOriginalName());
        unset($originalName[count($originalName) - 1]);
        $originalName = implode('', $originalName);
        // Define finalmente o nome
        $nameFile = $originalName . "-{$name}.{$extension}";

        // Faz o upload:
        $upload = $file->storeAs($module, $nameFile, 'public');

        $this->cropImage($nameFile, $module, 100, 100);

        if ($upload) {
            return $nameFile;
        } else {
            false;
        }
    } // upload()

    public function cropImage($filename, $module, $width, $height)
    {
        try {
            // Caminho para a imagem original no armazenamento público
            $imgPath = storage_path("app/public/{$module}/" . $filename);

            // Nome do arquivo para a imagem cortada
            $cropfilename = $filename;

            // Caminho público para acessar a imagem cortada via web
            $publicPath = storage_path("app/public/{$module}/crops/" . $cropfilename);

            // criando um gerenciador de imagens com o driver desejado
            $manager = new ImageManager(new Driver());

            // Lendo a imagem no sistema de arquivos
            $image = $manager->read($imgPath);

            $image->resize($width, $height);

            $image->save($publicPath);

            return true;
        } catch (\Exception) {
            return false;
        }
    } // cropImage()
}
