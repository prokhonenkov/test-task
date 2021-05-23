<?php


namespace app\api\modules\v1\models\forms;


use app\api\modules\v1\models\Link;
use app\components\random\RandomStringGeneratorGenerator;
use yii\base\Model;

/**
 * Class LinkSaveForm
 * @package app\api\modules\v1\models\forms
 */
class LinkSaveForm extends Model implements SaveFormInterface
{
    /**
     * @var Link
     */
    private Link $model;
    /**
     * @var string
     */
    public string $link;

    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string', 'max' => 255],
            [
                ['link'],
                'unique',
                'targetClass' => Link::class,
                'targetAttribute' => 'source',
            ],
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if(!$this->validate()) {
            return false;
        }

        $shortLinkPath = (new RandomStringGeneratorGenerator())
            ->setMinLength(4)
            ->setMaxLength(6)
            ->get();

        $this->model = new Link();
        $this->model->source = $this->link;
        $this->model->hash = $shortLinkPath;

        return $this->model->save(false);
    }

    /**
     * @return Link
     */
    public function getModel(): Link
    {
        return $this->model;
    }
}