<?php

namespace Sunnysideup\ElementalBasics\Extensions;

use Fromholdio\ColorPalette\Fields\ColorPaletteField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBBackgroundColour;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBFontColour;

/**
 * Class \Sunnysideup\ElementalBasics\Extensions\BaseElementExtension
 *
 * @property \DNADesign\Elemental\Models\BaseElement|\App\Extensions\BaseElementExtension $owner
 */
class BaseElementExtension extends Extension
{
    private static $db = [
        'ElementBackgroundColour' => 'BackgroundColour',
        'ElementTextColour' => 'FontColour',
        'TopPadding' => 'Enum("none, small, medium, large, xlarge", "medium")',
        'BottomPadding' => 'Enum("none, small, medium, large, xlarge", "medium")',
        'TopMargin' => 'Enum("none, small, medium, large, xlarge", "medium")',
        'BottomMargin' => 'Enum("none, small, medium, large, xlarge", "medium")',
    ];

    private static $defaults = [
        'ElementBackgroundColour' => '#ffffff',
        'ElementTextColour' => '#000000',
    ];

    // Allow archiving elements even on pages that cannot be deleted (e.g., home page, pages with children)
    public function canDelete($member = null)
    {
        return $this->owner->canEdit($member);
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('TopPadding');
        $fields->removeByName('BottomPadding');
        $fields->removeByName('TopMargin');
        $fields->removeByName('BottomMargin');

        $fields->addFieldsToTab(
            'Root.Settings',
            [
                ColorPaletteField::create(
                    'ElementBackgroundColour',
                    'Background Colour',
                    DBBackgroundColour::get_colours_for_dropdown()
                ),
                ColorPaletteField::create(
                    'ElementTextColour',
                    'Text Colour',
                    DBFontColour::get_colours_for_dropdown()
                ),
                FieldGroup::create(
                    'Spacing',
                    DropdownField::create(
                        'TopMargin',
                        'Top Margin',
                        singleton($this->owner->ClassName)->dbObject('TopMargin')->enumValues()
                    ),
                    DropdownField::create(
                        'TopPadding',
                        'Top Padding',
                        singleton($this->owner->ClassName)->dbObject('TopPadding')->enumValues()
                    ),
                    DropdownField::create(
                        'BottomPadding',
                        'Bottom Padding',
                        singleton($this->owner->ClassName)->dbObject('BottomPadding')->enumValues()
                    ),
                    DropdownField::create(
                        'BottomMargin',
                        'Bottom Margin',
                        singleton($this->owner->ClassName)->dbObject('BottomMargin')->enumValues()
                    )
                )
            ]
        );
    }
}