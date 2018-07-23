<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    //Je crée une propriété qui va stocker le chemin vers le dossier d'Upload
    private $targetDirectory;

    public function __construct($directory)
    {
        //Lors de l'instanciation, on remplit la propriété avec le chemin vers
        //le dossier d'upload
        $this->targetDirectory = $directory;
    }

    public function upload(UploadedFile $file, $oldFileName = null){
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        //Transfert du fichier
        $file->move($this->targetDirectory, $fileName);
        //supprimer l'éventuelle ancienne image
        //si on m'a fourni un nom de fichier et que ce fichier existe bien
        if($oldFileName and file_exists($this->targetDirectory . '/' . $oldFileName)){
            unlink($this->targetDirectory . '/' . $oldFileName);
        }

        return $fileName;
    }
}