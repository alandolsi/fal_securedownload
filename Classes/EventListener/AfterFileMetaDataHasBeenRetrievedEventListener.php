<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace BeechIt\FalSecuredownload\EventListener;

use BeechIt\FalSecuredownload\Aspects\PublicUrlAspect;
use BeechIt\FalSecuredownload\Security\CheckPermissions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\ArrayUtility;

final class AfterFileMetaDataHasBeenRetrievedEventListener
    {

    protected CheckPermissions $checkPermissionsService;
    protected PublicUrlAspect $publicUrlAspect;

    public function __construct()
        {
        $this->checkPermissionsService = GeneralUtility::makeInstance( CheckPermissions::class);
        $this->publicUrlAspect         = GeneralUtility::makeInstance( PublicUrlAspect::class);
        }
    public function __invoke(\ApacheSolrForTypo3\Solrfal\Event\Indexing\AfterFileMetaDataHasBeenRetrievedEvent $event): void
        {
        $metadata = $event->getMetaData();
        $item     = $event->getFileIndexQueueItem();
        if ($item->getFile() instanceof File && !$item->getFile()->getStorage()->isPublic()) {
            $resourcePermissions = $this->checkPermissionsService->getPermissions( $item->getFile() );
            // If there are already permissions set, refine these with actual file permissions
            if ($metadata['fe_groups']) {
                $metadata['fe_groups'] = implode(
                    ',',
                    ArrayUtility::keepItemsInArray( explode( ',', $resourcePermissions ), $metadata['fe_groups'] )
                );
                }
            else {
                $metadata['fe_groups'] = $resourcePermissions;
                }
            }

        // Re-generate public url
        $this->publicUrlAspect->setEnabled( false );
        $metadata['public_url'] = $item->getFile()->getPublicUrl();
        $this->publicUrlAspect->setEnabled( true );

        $event->overrideMetaData( $metadata );
        }
    }