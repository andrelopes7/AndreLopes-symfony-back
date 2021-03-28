<?php 

namespace App\Service;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
  
  private $recipeFolder;
 

    public function __construct($recipeFolder)
    {
        $this->recipeFolder = $recipeFolder;
        
    }

   /**
     * Et la j'ai la meme fonctionnalité dédiée à un cas particulier
     *
     * @param UploadedFile|null $picture on autorise le null si jamais aucune picture n'a été fournie
     * @return string|null
     */
    function movePicture(?UploadedFile $picture, string $targetFolder, $prefix = ''): ?string
    {
        $newFilename = null;

        if ($picture !== null) {
            // on a décidé d'appeler notre fichier
            $newFilename = $prefix . uniqid() . '.' . $picture->guessExtension();

            // Move the file to the directory where brochures are stored
            $picture->move(
                $targetFolder,
                $newFilename
            );
        }
        return $newFilename;
    }

    function moveRecipePicture(?UploadedFile $picture, Recipe $recipe)
    {
        $pictureName = $this->movePicture($picture, $this->recipeFolder, 'recipe-');
        if ($pictureName !== null) {
            $recipe->setPicture($pictureName);
        }
        return $recipe;
    }

}