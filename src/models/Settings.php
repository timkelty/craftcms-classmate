<?php
namespace timkelty\craftcms\classmate\models;

/**
 * @inheritdoc
 */
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
