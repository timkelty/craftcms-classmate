<?php
namespace timkelty\craftcms\classmate\models;

class Settings extends \craft\base\Model
{
    public string $filePath = '@config/classmate.json';

    public function rules(): array
    {
        return [
            ['filePath', 'required'],
        ];
    }
}
