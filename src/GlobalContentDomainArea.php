<?php

namespace Concrete\Package\DomainArea\Src;

use Loader,
    Page,
    Area;

class GlobalContentDomainArea extends ContentDomainArea
{   
    /**
     * @inheritdoc
     */
    public function __construct($arHandle)
    {
        parent::__construct($arHandle, true);
    }  
}
