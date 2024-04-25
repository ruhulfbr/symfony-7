<?php

namespace App\Entity;

use App\Repository\QueryMakerCSVRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QueryMakerCSVRepository::class)]
class QueryMakerCSV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $file_name = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $file_path = null;

    #[ORM\Column(nullable: true)]
    private ?int $downloaded = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $table_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(?string $file_path): static
    {
        $this->file_path = $file_path;

        return $this;
    }

    public function getDownloaded(): ?int
    {
        return $this->downloaded;
    }

    public function setDownloaded(?int $downloaded): static
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->table_name;
    }

    public function setTableName(?string $table_name): static
    {
        $this->table_name = $table_name;

        return $this;
    }
}
