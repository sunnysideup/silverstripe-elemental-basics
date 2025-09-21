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
        'TopPadding' => 'Enum("none, small, medium, large, xlarge", "none")',
        'BottomPadding' => 'Enum("none, small, medium, large, xlarge", "none")',
        'TopMargin' => 'Enum("none, small, medium, large, xlarge", "none")',
        'BottomMargin' => 'Enum("none, small, medium, large, xlarge", "none")',
    ];

    private static $defaults = [
        'ElementBackgroundColour' => '#ffffff',
        'ElementTextColour' => '#000000',
    ];

    /**
     * Small hack!
     * Allow archiving elements even on pages that cannot be deleted (e.g., home page, pages with children)
     *
     * @param $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        $owner = $this->getOwner();
        return $owner->canEdit($member);
    }

    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->getOwner();
        $fields->removeByName('TopPadding');
        $fields->removeByName('BottomPadding');
        $fields->removeByName('TopMargin');
        $fields->removeByName('BottomMargin');
        $topMarginValues = $owner->dbObject('TopMargin')->enumValues();
        $topPaddingValues = $owner->dbObject('TopPadding')->enumValues();
        $bottomPaddingValues = $owner->dbObject('BottomPadding')->enumValues();
        $bottomMarginValues = $owner->dbObject('BottomMargin')->enumValues();
        if ($owner->hasMethod('CustomTopMarginValues')) {
            $topMarginValues = $owner->CustomTopMarginValues();
        }
        if ($owner->hasMethod('CustomTopPaddingValues')) {
            $topPaddingValues = $owner->CustomTopPaddingValues();
        }
        if ($owner->hasMethod('CustomBottomPaddingValues')) {
            $bottomPaddingValues = $owner->CustomBottomPaddingValues();
        }
        if ($owner->hasMethod('CustomBottomMarginValues')) {
            $bottomMarginValues = $owner->CustomBottomMarginValues();
        }
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
                        $topMarginValues
                    ),
                    DropdownField::create(
                        'TopPadding',
                        'Top Padding',
                        $topPaddingValues
                    ),
                    DropdownField::create(
                        'BottomPadding',
                        'Bottom Padding',
                        $bottomPaddingValues
                    ),
                    DropdownField::create(
                        'BottomMargin',
                        'Bottom Margin',
                        $bottomMarginValues
                    )
                )
            ]
        );
    }
}
