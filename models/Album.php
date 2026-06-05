<?php

require_once __DIR__ . '/../core/Config.php';

class Album
{
    private string $basePath;
    private string $baseUrl;

    public function __construct()
    {
        $this->basePath = realpath(__DIR__ . '/../public/uploads/img_clientes');
        $this->baseUrl = rtrim(BASE_URL, '/') . '/uploads/img_clientes/';

        if (!$this->basePath) {
            throw new Exception('La carpeta de galerías no existe.');
        }
    }

    public function getAll(): array
    {
        return $this->scanAlbums();
    }

    public function getById(string $id): ?array
    {
        $albums = $this->scanAlbums();
        foreach ($albums as $album) {
            if ($album['id'] === $id) {
                return $album;
            }
        }
        return null;
    }

    public function create(string $albumId, string $title, array $uploadedFile): array
    {
        $albumId = trim($albumId);
        $title = trim($title);

        if (empty($albumId) || !preg_match('/^[a-zA-Z0-9_-]+$/', $albumId)) {
            throw new Exception('ID de álbum no válido. Solo letras, números, guiones y guiones bajos.');
        }

        if (empty($title)) {
            throw new Exception('El título del álbum no puede estar vacío.');
        }

        $albumDir = $this->basePath . DIRECTORY_SEPARATOR . $albumId;

        if (file_exists($albumDir)) {
            throw new Exception('Ya existe un álbum con ese identificador.');
        }

        if (!mkdir($albumDir, 0755, true)) {
            throw new Exception('No se pudo crear la carpeta del álbum.');
        }

        $filename = 'cover_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($uploadedFile['name']));
        $destination = $albumDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
            $this->deleteDirectory($albumDir);
            throw new Exception('No se pudo mover la imagen del álbum.');
        }

        $meta = [
            'title' => $title,
            'created_at' => date('Y-m-d H:i:s')
        ];

        file_put_contents($albumDir . DIRECTORY_SEPARATOR . 'album.json', json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return [
            'id' => $albumId,
            'title' => $title,
            'cover' => $this->baseUrl . $albumId . '/' . rawurlencode($filename),
            'photos' => [$this->baseUrl . $albumId . '/' . rawurlencode($filename)]
        ];
    }

    public function delete(string $albumId): bool
    {
        $albumDir = $this->basePath . DIRECTORY_SEPARATOR . $albumId;
        if (!is_dir($albumDir)) {
            return false;
        }
        return $this->deleteDirectory($albumDir);
    }

    private function scanAlbums(): array
    {
        $albums = [];

        $items = scandir($this->basePath);
        if ($items === false) {
            return [];
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $albumDir = $this->basePath . DIRECTORY_SEPARATOR . $item;
            if (!is_dir($albumDir)) {
                continue;
            }

            $metaFile = $albumDir . DIRECTORY_SEPARATOR . 'album.json';
            $title = $item;
            if (is_file($metaFile)) {
                $metaData = json_decode(file_get_contents($metaFile), true);
                if (!empty($metaData['title'])) {
                    $title = $metaData['title'];
                }
            }

            $photos = $this->scanPhotos($albumDir, $item);
            if (empty($photos)) {
                continue;
            }

            $albums[] = [
                'id' => $item,
                'title' => $title,
                'cover' => $photos[0],
                'photos' => $photos
            ];
        }

        return array_values($albums);
    }

    private function scanPhotos(string $albumDir, string $albumId): array
    {
        $photos = [];
        $files = scandir($albumDir);
        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === 'album.json') {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                continue;
            }

            $photos[] = $this->baseUrl . $albumId . '/' . rawurlencode($file);
        }

        sort($photos);
        return $photos;
    }

    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $items = scandir($dir);
        if ($items === false) {
            return false;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        return @rmdir($dir);
    }
}
