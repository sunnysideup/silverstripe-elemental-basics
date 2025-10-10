<?php

use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

trait BaseElementSummaryTrait
{
    public function getSummary(): string
    {
        $html = '' . $this->inlineEditable() ? ' ' : ' ';
        // foreach ($this->config()->get('has_one') as $name => $type) {
        //     if ($type === 'Image' || $type === SilverStripe\Assets\Image::class) {
        //         $img = $this->getComponent($name);
        //         if ($img && $img->exists()) {
        //             $html .= '<img src="' . $img->CMSThumbnail()->getURL() . '" />';
        //         }
        //     }
        // }
        if (!$html) {
            foreach ($this->config()->get('db') as $name => $type) {
                if (in_array($type, ['Varchar', 'Text', 'HTMLText', 'DBHTMLText', 'DBVarchar', 'DBText'], true)) {
                    $value = $this->dbField($name)->LimitCharacters(40);
                    if ($value) {
                        $html .= '<strong>' . $name . ':</strong> ' . $value . '<br />';
                    }
                }
            }
        }
        return DBHTMLText::create_field('HTMLText', $html);
    }
}
