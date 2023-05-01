<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
#[UniqueConstraint(name: 'languageCodeIdx', columns: ['code'])]
#[ORM\Index(columns: ['code'], name: 'codeIdx')]
#[ORM\HasLifecycleCallbacks]
class Language extends BaseEntity
{
    #[ORM\Column(length: 100, nullable: false)]
    private string $name;

    #[ORM\Column(length: 10, nullable: false)]
    private string $code;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Language
     */
    public function setName(string $name): Language
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Language
     */
    public function setCode(string $code): Language
    {
        $this->code = $code;
        return $this;
    }
}
