<?php

require_once __DIR__ . '/../model/InitPDO.php';

enum StorageType
{
    case avatar;
    case article;
    case article_inside;

    public function getStoragePath(): string
    {
        return match ($this) {
            self::avatar => ImageManager::STORAGE_AVATAR,
            self::article => ImageManager::STORAGE_ARTICLE,
            self::article_inside => ImageManager::STORAGE_ARTICLE_INSIDE,
        };
    }

    public function getTableImageName(): string
    {
        return match ($this) {
            self::avatar => ImageManager::TABLE_AVATAR,
            self::article => ImageManager::TABLE_ARTICLE,
            self::article_inside => ImageManager::TABLE_ARTICLE_INSIDE,
        };
    }

    public function getTableResourceName(): string
    {
        return match ($this) {
            self::avatar => ImageManager::TABLE_RESOURCE_NAME_AVATAR,
            self::article => ImageManager::TABLE_RESOURCE_NAME_ARTICLE,
            self::article_inside => ImageManager::TABLE_RESOURCE_NAME_ARTICLE_INSIDE,
        };
    }

    public function getFieldImage(): string
    {
        return match ($this) {
            self::avatar => ImageManager::FIELD_IMAGE_AVATAR,
            self::article => ImageManager::FIELD_IMAGE_ARTICLE,
            self::article_inside => ImageManager::FIELD_IMAGE_ARTICLE_INSIDE,
        };
    }

    public function getPublicStoragePath(): string
    {
        return match ($this) {
            self::avatar => ImageManager::PUBLIC_STORAGE_AVATAR,
            self::article => ImageManager::PUBLIC_STORAGE_ARTICLE,
            self::article_inside => ImageManager::PUBLIC_STORAGE_ARTICLE_INSIDE,
        };
    }
}

class ImageManager
{
    private InitPdo $pdo;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png'
    ];

    private const ALLOWED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png'
    ];

    public const STORAGE_AVATAR = '/../../public/uploads/avatars/';
    public const STORAGE_ARTICLE = '/../../public/uploads/articles/';
    public const STORAGE_ARTICLE_INSIDE = '/../../public/uploads/articles_inside/';

    public const PUBLIC_STORAGE_AVATAR = '/uploads/avatars/';
    public const PUBLIC_STORAGE_ARTICLE = '/uploads/articles/';
    public const PUBLIC_STORAGE_ARTICLE_INSIDE = '/uploads/articles_inside/';

    public const TABLE_RESOURCE_NAME_AVATAR = 'users';
    public const TABLE_RESOURCE_NAME_ARTICLE = 'articles';
    public const TABLE_RESOURCE_NAME_ARTICLE_INSIDE = 'images_insides';

    public const FIELD_IMAGE_AVATAR = 'id_avatar';
    public const FIELD_IMAGE_ARTICLE = 'id_image_pres';
    public const FIELD_IMAGE_ARTICLE_INSIDE = 'id'; // TODO inutilisé

    public const TABLE_AVATAR = 'images_avatar';
    public const TABLE_ARTICLE = 'images';
    public const TABLE_ARTICLE_INSIDE = 'images_insides';

    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 Mo max

    public function __construct(InitPdo $pdo)
    {
        if (!$pdo)
            throw new Exception("Erreur lors de la connexion");
        $this->pdo = $pdo;
    }

    public function getImageAvatarFromId($id)
    {
        return $this->getImageFromIdAndTable($id, self::TABLE_AVATAR);
    }

    public function getImageArticleFromId($id)
    {
        return $this->getImageFromIdAndTable($id, self::TABLE_ARTICLE);
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
    public function uploadImage(StorageType $storageType, int $id): array
    {
        // ON vérifie que le fichier a bien été uploadé et qu'il n'y a pas d'erreur
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            // Récupération du nom de fichier temporaire
            $tmp = $_FILES['image']['tmp_name'];
            // Récupèration de l'extension du fichier
            $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Nommage aléatoire
            $randomName = bin2hex(random_bytes(16)) . '.' . $extention;
            // création de chemin du fichier pour la copie.
            $destination = __DIR__ . $storageType->getStoragePath() . $randomName;

            // vérification du fichier image d'entrée
            $this->verifInputFile($extention, $tmp);

            // On vérifie que le déplacement s'est bien passé
            if (move_uploaded_file($tmp, $destination)) {

                $basename = basename($destination);
                $tableImagesName = $storageType->getTableImageName();
                $tableRessourceName = $storageType->getTableResourceName();
                $fieldImage = $storageType->getFieldImage();

                $sql = "
                            INSERT INTO $tableImagesName (image_filepath, alt)
                            VALUES (:image_filepath, :alt);
                            
                            UPDATE $tableRessourceName
                            SET $fieldImage = LAST_INSERT_ID()
                            WHERE id = :id;
                        ";

                // On exécute la requête
                PDO->executeQuery($sql, [
                    ':image_filepath' => $basename,
                    ':alt' => "Image de profil de " . htmlspecialchars($_SESSION['user-username']),
                    ':id' => $id
                ]);

                // On retourne le message de succès et le chemin de l'image.
                return [
                    'message' => "Upload de l'image fait !",
                    'filename' => $storageType->getPublicStoragePath() . $basename,
                ];
            } else {
                // Si le déplacement a échoué, on lance une exception
                throw new Exception("Fichier manquant", 2);
            }
        } else {
            // Si le fichier n'a pas été uploadé ou qu'il y a une erreur, on lance une exception
            throw new Exception("Accès refusé", 3);
        }
    }

    public function uploadImageForArticle($articleId, $userId)
    {
        error_log("COUCOU");

        $storageType = StorageType::article_inside;

        // ON vérifie que le fichier a bien été uploadé et qu'il n'y a pas d'erreur
        error_log($_FILES['image']['error']);
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            error_log("Fichier image trouvé");

            // Récupération du nom de fichier temporaire
            $tmp = $_FILES['image']['tmp_name'];
            // Récupèration de l'extension du fichier
            $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Nommage aléatoire
            $randomName = bin2hex(random_bytes(16)) . '.' . $extention;
            // création de chemin du fichier pour la copie.
            $destination = __DIR__ . $storageType->getStoragePath() . $randomName;

            // vérification du fichier image d'entrée
            $this->verifInputFile($extention, $tmp);

            // On vérifie que le déplacement s'est bien passé
            if (move_uploaded_file($tmp, $destination)) {

                error_log("Déplacement du fichier réussi");

                $basename = basename($destination);
                $tableImagesName = $storageType->getTableImageName();
                $tableRessourceName = $storageType->getTableResourceName();
                $fieldImage = $storageType->getFieldImage();

                $sql = "
                            INSERT INTO $tableImagesName (image_filepath, alt, uploaded_by, article_id)
                            VALUES (:image_filepath, :alt, :uploaded_by, :article_id);
                        ";

                // On exécute la requête
                PDO->executeQuery($sql, [
                    ':image_filepath' => $basename,
                    ':alt' => "Image de profil de " . htmlspecialchars($_SESSION['user-username']), // Pas forcement besoin ici c'est EditorJS qui gère ça.
                    ':uploaded_by' => $userId,
                    ':article_id' => $articleId
                ]);

                // On retourne le message de succès et le chemin de l'image.
                return [
                    'message' => "Upload de l'image fait !",
                    'filename' => $storageType->getPublicStoragePath() . $basename,
                ];
            } else {
                // Si le déplacement a échoué, on lance une exception
                throw new Exception("Fichier manquant", 2);
            }
        } else {
            // Si le fichier n'a pas été uploadé ou qu'il y a une erreur, on lance une exception
            throw new Exception("Accès refusé", 3);
        }
    }

    private function verifInputFile($extention, $tmp)
    {
        // Vérification de type de fichier s'il est présent dans la liste
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['image']['tmp_name']);
        $ext = strtolower($mimeType);
        if (!in_array($ext, self::ALLOWED_MIME_TYPES)) {
            throw new Exception("Fichier non pris en charge", 1);
        }

        // On vérifie l'extension du fichier est correcte
        if (!in_array($extention, self::ALLOWED_EXTENSIONS)) {
            throw new Exception("Extension de fichier non prise en charge", 1);
        }

        // Vérification que le contenu est bien une image
        if (@getimagesize($tmp) === false) {
            throw new Exception("Le fichier n'est pas une image", 1);
        }

        // On vérifie la taille du fichier
        if ($_FILES['image']['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("Le fichier est trop volumineux", 1);
        }
    }
}
