<?php

require_once __DIR__ . '/../model/InitPDO.php';

class ImageManager
{
    private InitPdo $pdo;

    public function __construct(InitPdo $pdo)
    {
        if(!$pdo)
            throw new Exception("Erreur lors de la connexion");
        $this->pdo = $pdo;
    }

    public function getImageFromId($id)
    {
        $sql = 'SELECT image_filepath, alt FROM images_avatar WHERE id = :id';

        $resReq = $this->pdo->executeQuery($sql, ['id' => $id]);

        if(count($resReq) > 0){
            return [
                "filepath" => $resReq[0]['image_filepath'],
                "alt" => $resReq[0]['alt']
            ];
        } else{
            return [
                'filepath' => "images/default.png",
                'alt' => "Image par défaut"
            ];
        }
    }
}