<?php

require_once __DIR__ . '/../model/InitPDO.php';

enum StorageType
{
    case avatar;
    case article;

    public function getStoragePath(): string
    {
        return match ($this) {
            self::avatar => ImageManager::$storageAvatar,
            self::article => ImageManager::$storageArticle,
        };
    }

    public function getTableName(): string
    {
        return match ($this) {
            self::avatar => ImageManager::$tableAvatar,
            self::article => ImageManager::$tableArticle,
        };
    }

    public function getPublicStoragePath(): string
    {
        return match ($this) {
            self::avatar => ImageManager::$publicStorageAvatar,
            self::article => ImageManager::$publicStorageArticle,
        };
    }
}

class ImageManager
{
    private InitPdo $pdo;

    private static $allowedMimeTypes = [
        'image/jpeg',
        'image/png'
    ];

    public static $storageAvatar = '/../../public/avatars/';
    public static $storageArticle = '/../../public/articles/';

    public static $publicStorageAvatar = '/avatars/';
    public static $publicStorageArticle = '/articles/';

    public static $tableAvatar = 'images_avatar';
    public static $tableArticle = 'images';

    public function __construct(InitPdo $pdo)
    {
        if (!$pdo)
            throw new Exception("Erreur lors de la connexion");
        $this->pdo = $pdo;
    }

    public function getImageAvatarFromId($id)
    {
        return $this->getImageFromIdAndTable($id, 'images_avatar');
    }

    public function getImageArticleFromId($id)
    {
        return $this->getImageFromIdAndTable($id, 'images');
    }

    private function getImageFromIdAndTable($id, $table)
    {
        $sql = "SELECT image_filepath, alt FROM $table WHERE id = :id";

        $resReq = $this->pdo->executeQuery($sql, ['id' => $id]);

        error_log("ResReq: " . print_r($resReq, true));

        if (count($resReq) > 0) {
            return [
                "filepath" => $resReq[0]['image_filepath'],
                "alt" => $resReq[0]['alt']
            ];
        } else {
            return [
                'filepath' => "images/default.png",
                'alt' => "Image par défaut"
            ];
        }
    }

    /**
     * Upload une image dans le dossier approprié (c'est storageType qui le détermine)
     * @param StorageType $storageType Le type de stockage (avatar ou article)
     */
    public function uploadImage(StorageType $storageType): array
    {
        // ON vérifie que le fichier a bien été uploadé et qu'il n'y a pas d'erreur
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            // Vérification de type de fichier s'il est présent dans la liste
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);
            $ext = strtolower($mimeType);
            if (!in_array($ext, ImageManager::$allowedMimeTypes)) {
                throw new Exception("Fichier non pris en charge", 1);
            }

            // Récupération du nom de fichier temporaire
            $tmp = $_FILES['image']['tmp_name'];
            // Récupèration de l'extension du fichier
            $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Nommage aléatoire
            $randomName = bin2hex(random_bytes(16)) . '.' . $extention;
            // création de chemin du fichier pour la copie.
            $destination = __DIR__ . $storageType->getStoragePath() . $randomName;

            // On vérifie que le déplacement s'est bien passé
            if (move_uploaded_file($tmp, $destination)) {

                $basename = basename($destination);
                $tableName = $storageType->getTableName();

                /*
                 * Deux requêtes sql en une:
                 *  * Insère l'image dans la table appropriée
                 *  * Met à jour l'id_avatar de l'utilisateur connecté
                 */
                $sql = "
                        INSERT INTO $tableName (image_filepath, alt)
                        VALUES (:image_filepath, :alt);
                        
                        UPDATE users
                        SET id_avatar = LAST_INSERT_ID()
                        WHERE id = :id;
                    ";

                // On exécute la requête
                PDO->executeQuery($sql, [
                    ':image_filepath' => $basename,
                    ':alt' => "Image de profil de " . htmlspecialchars($_SESSION['user-username']),
                    ':id' => $_SESSION['user-id']
                ]);

                // On retourne le message de succès et le chemin de l'image.
                return [
                    'message' => "Upload de l'image fait !",
                    'filename' => $storageType->getPublicStoragePath() . $basename,
                ];
            } else {
                // Si le déplacement a échoué, on lance une exception
                // TODO message a modifier.
                throw new Exception("Fichier manquant", 2);
            }
        } else {
            // Si le fichier n'a pas été uploadé ou qu'il y a une erreur, on lance une exception
            throw new Exception("Accès refusé", 3);
        }
    }
}
