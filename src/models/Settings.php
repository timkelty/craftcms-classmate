<?php
namespace timkelty\craftcms\classmate\models;

class Settings extends \craft\base\Model
{
    public string $filePath = '@config/classmate.json';

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['filePath', 'required'],
        ];
    }
}
