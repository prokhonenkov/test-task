<?php


namespace app\components\random;


interface RandomStringGeneratorInterface
{
    public function setMinLength(int $count): self;
    public function setMaxLength(int $count): self;
    public function get(): string;
}