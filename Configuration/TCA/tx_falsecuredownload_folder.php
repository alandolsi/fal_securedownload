<?php

use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') or die();

$tca = [
    'ctrl' => [
        'title' => 'LLL:EXT:fal_securedownload/Resources/Private/Language/locallang_db.xlf:tx_falsecuredownload_folder',
        'label' => 'folder',
        'tstamp' => 'tstamp',
        'hideTable' => true,
        'rootLevel' => true,
        'default_sortby' => 'ORDER BY folder ASC',
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
        ],
        'iconfile' => 'EXT:fal_securedownload/Resources/Public/Icons/folder.png',
    ],
    'types' => [
        '0' => ['showitem' => 'fe_groups,--palette--;;filePalette'],
    ],
    'palettes' => [
        // File palette, hidden but needs to be included all the time
        'filePalette' => [
            'showitem' => 'storage, folder, folder_hash',
            'isHiddenPalette' => true,
        ],
    ],
    'columns' => [
        'storage' => [
            'exclude' => false,
            'label' => 'LLL:EXT:fal_securedownload/Resources/Private/Language/locallang_db.xlf:storage',
            'config' => [
                'type' => 'group',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 1,
                'allowed' => 'sys_file_storage',
            ],
        ],
        'folder' => [
            'exclude' => false,
            'label' => 'LLL:EXT:fal_securedownload/Resources/Private/Language/locallang_db.xlf:folder',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'folder_hash' => [
            'exclude' => false,
            'label' => 'LLL:EXT:fal_securedownload/Resources/Private/Language/locallang_db.xlf:folder',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'fe_groups' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 20,
                'maxitems' => 40,
                'items' => [
                    [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        'value' => -2,
                    ],
                    [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        'value' => '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
    ],
];

$typo3Version = new Typo3Version();
if ($typo3Version->getMajorVersion() === 11) {
    foreach ($tca['columns']['fe_groups']['config']['items'] as &$item) {
        $item = array_values($item);
    }
}

return $tca;
