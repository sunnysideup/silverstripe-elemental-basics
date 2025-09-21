<?php

namespace Sunnysideup\ElementalBasics\Extensions;

use Fromholdio\ColorPalette\Fields\ColorPaletteField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBBackgroundColour;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBFontColour;

/**
 * Class \Sunnysideup\ElementalBasics\Extensions\BaseElementExtension
 *
 * @property \DNADesign\Elemental\Models\BaseElement|\App\Website\Extension\BaseElementExtension $owner
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
        'InvertTopMargin' => 'Boolean',
        'InvertBottomMargin' => 'Boolean',
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
        if ($owner->hasMethod('getCustomTopMarginValues')) {
            $topMarginValues = $owner->getCustomTopMarginValues($topMarginValues);
        }
        if ($owner->hasMethod('getCustomTopPaddingValues')) {
            $topPaddingValues = $owner->getCustomTopPaddingValues($topPaddingValues);
        }
        if ($owner->hasMethod('getCustomBottomPaddingValues')) {
            $bottomPaddingValues = $owner->getCustomBottomPaddingValues($bottomPaddingValues);
        }
        if ($owner->hasMethod('getCustomBottomMarginValues')) {
            $bottomMarginValues = $owner->getCustomBottomMarginValues($bottomMarginValues);
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
                    CheckboxField::create(
                        'InvertTopMargin',
                        'Invert Top Margin - overlap with previous block'
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
                    CheckboxField::create(
                        'InvertBottomMargin',
                        'Invert Bottom Margin - overlap with next block'
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

    public function updateStyleVariant($styleVariant)
    {
        $owner = $this->getOwner();
        if ($owner->ElementBackgroundColour) {
            $styleVariant .= ' bg-' . str_replace('#', '', $owner->ElementBackgroundColour);
        }
        if ($owner->ElementTextColour) {
            $styleVariant .= ' text-' . str_replace('#', '', $owner->ElementTextColour);
        }
        if ($owner->TopPadding && $owner->TopPadding !== 'none') {
            $styleVariant .= ' pt-' . $owner->TopPadding;
        }
        if ($owner->BottomPadding && $owner->BottomPadding !== 'none') {
            $styleVariant .= ' pb-' . $owner->BottomPadding;
        }
        if ($owner->TopMargin && $owner->TopMargin !== 'none') {
            $styleVariant .= ' mt-' . $owner->TopMargin;
            if ($owner->InvertTopMargin) {
                $styleVariant .= ' mt-invert';
            }
        }
        if ($owner->BottomMargin && $owner->BottomMargin !== 'none') {
            $styleVariant .= ' mb-' . $owner->BottomMargin;
            if ($owner->InvertBottomMargin) {
                $styleVariant .= ' mb-invert';
            }
        }
        return trim($styleVariant);
    }
}
