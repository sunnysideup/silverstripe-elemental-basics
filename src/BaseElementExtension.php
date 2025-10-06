<?php

declare(strict_types=1);

namespace Sunnysideup\ElementalBasics\Extensions;

use Dom\Element;
use Fromholdio\ColorPalette\Fields\ColorPaletteField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBBackgroundColour;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBFontColour;

/**
 * Class \Sunnysideup\ElementalBasics\Extensions\BaseElementExtension
 *
 * @property \DNADesign\Elemental\Models\BaseElement|\App\Website\Extension\BaseElementExtension $owner
 */
class BaseElementExtension extends Extension
{

    private static $margin_and_padding_options = [
        // 'none',
        // 'small',
        // 'medium',
        // 'large',
        // 'xlarge',
    ];

    private static $element_width_options = [
        // 'full-width',
        // 'normal-width',
        // 'text-width'
    ];

    private static $db = [
        'ElementBackgroundColour' => 'BackgroundColour',
        'ElementTextColour' => 'FontColour',
        'TopMargin' => 'Varchar(30)',
        'InvertTopMargin' => 'Boolean',
        'TopPadding' => 'Varchar(30)',
        'ElementWidth' => 'Varchar(30)',
        'BottomPadding' => 'Varchar(30)',
        'BottomMargin' => 'Varchar(30)',
        'InvertBottomMargin' => 'Boolean',
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
        $labels = $owner->fieldLabels();

        $fields->insertAfter(
            'Main',
            Tab::create(
                'Colours',
                'Colours',
            )
        );
        $backgroundColours = DBBackgroundColour::get_colours_for_dropdown();
        $textColours = DBFontColour::get_colours_for_dropdown();
        if ($owner->hasMethod('getCustomBackgroundColours')) {
            $backgroundColours = $owner->getCustomBackgroundColours($backgroundColours);
        }
        if ($owner->hasMethod('getCustomTextColours')) {
            $textColours = $owner->getCustomTextColours($textColours);
        }
        $fieldsToAdd = [];
        $colours = [
            'ElementBackgroundColour' => $backgroundColours,
            'ElementTextColour' => $textColours,
        ];
        foreach ($colours as $fieldName => $values) {
            if (! empty($values)) {
                $label = $labels[$fieldName] ?? $fieldName;
                $fieldsToAdd[] = ColorPaletteField::create(
                    $fieldName,
                    $label,
                    $values
                )->setEmptyString('-- select colour --');
            } else {
                $fields->removeByName($fieldName);
            }
        }

        if (!empty($fieldsToAdd)) {
            $fields->addFieldsToTab(
                'Root.Colours',
                $fieldsToAdd
            );
        }

        $fields->insertAfter(
            'Main',
            Tab::create(
                'Spacing',
                'Spacing',
            )
        );

        $options = $owner->config()->get('margin_and_padding_options');
        $marginAndPaddingOptions = array_combine(
            $options,
            $options
        );
        $topMarginValues = $marginAndPaddingOptions;
        $topPaddingValues = $marginAndPaddingOptions;
        $bottomPaddingValues = $marginAndPaddingOptions;
        $bottomMarginValues = $marginAndPaddingOptions;

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
        $options = $owner->config()->get('element_width_options');
        $elementWidthValues = array_combine(
            $options,
            $options
        );

        if ($owner->hasMethod('getCustomElementWidthValues')) {
            $elementWidthValues = $owner->getCustomElementWidthValues($elementWidthValues);
        }
        $settings = [
            'TopMargin' => $topMarginValues,
            'TopPadding' => $topPaddingValues,
            'ElementWidth' => $elementWidthValues,
            'BottomPadding' => $bottomPaddingValues,
            'BottomMargin' => $bottomMarginValues,
        ];
        $fieldsToAdd = [];
        foreach ($settings as $fieldName => $values) {
            if (!empty($values)) {
                $label = $labels[$fieldName] ?? $fieldName;
                $fieldsToAdd[] = DropdownField::create(
                    $fieldName,
                    $label,
                    $values
                );
                if ($fieldName === 'TopMargin' || $fieldName === 'BottomMargin') {
                    $fieldsToAdd[] = CheckboxField::create(
                        'Invert' . $fieldName,
                        $labels['Invert' . $fieldName] ?? 'Invert ' . $label . ' - overlap with ' . ($fieldName === 'TopMargin' ? 'previous' : 'next') . ' block?'
                    );
                }
            } else {
                $fields->removeByName($fieldName);
                if ($fieldName === 'TopMargin' || $fieldName === 'BottomMargin') {
                    $fields->removeByName('Invert' . $fieldName);
                }
            }
        }
        if (! empty($fieldsToAdd)) {

            $fields->addFieldsToTab(
                'Root.Spacing',
                $fieldsToAdd
            );
        }
    }

    public function updateStyleVariant(?string &$styleVariant = null): string
    {
        $styleVariant = (string) $styleVariant;
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
        if ($owner->ElementWidth && $owner->ElementWidth !== 'none') {
            $styleVariant .= ' ew-' . $owner->ElementWidth;
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

    public function getAnchorTitle()
    {
        $owner = $this->getOwner();
        if ($owner->config()->enable_title_in_template) {
            return $owner->getField('Title');
        }
        return 'page-section-' . ($owner->getField('Title') ?: '#' . $owner->ID);
    }
}
