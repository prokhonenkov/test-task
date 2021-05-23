<?php


namespace app\components\random;


use yii\base\Theme;

class RandomStringGeneratorGenerator implements RandomStringGeneratorInterface
{
    private int $minLength = 10;
    private int $maxLength = 20;
    private string $useLetters;
    private  string $userNumbers;

    private $chars = '';

    public function __construct(bool $useLetters = true, bool $userNumbers = true)
    {
        $this->useLetters = $useLetters;
        $this->userNumbers = $userNumbers;

        if($this->useLetters) {
            $this->chars .= 'abcdefghijklmnopqrstuvwxyz';
        }

        if($this->userNumbers) {
            $this->chars .= '0123456789';
        }
    }

    public function setMinLength(int $count): self
    {
        $this->minLength = $count;
        return $this;
    }

    public function setMaxLength(int $count): self
    {
        $this->maxLength = $count;
        return $this;
    }

    public function get(): string
    {
        $stringLength = mt_rand($this->minLength, $this->maxLength);

        $result = '';
        for ($i = 0; $i < $stringLength; $i++) {
            $result .= $this->chars[mt_rand(0, mb_strlen($this->chars) - 1)];
        }

        return $result;
    }
}