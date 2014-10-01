<?php

namespace Concrete\Package\DomainArea;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType,
    \Concrete\Core\Page\Single as SinglePage;

class Controller extends \Concrete\Core\Package\Package
{

    protected $pkgHandle = 'domain_area';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.9';

    public function getPackageName()
    {
        return t('Domain Area');
    }

    public function getPackageDescription()
    {
        return t('Adds a custom area class you can use in your theme to manage domain specific content.');
    }

    public function install()
    {
        $pkg = parent::install();

        $sp = SinglePage::add('/dashboard/system/basics/content_domains', $pkg);
        $sp->update(
                [
                    'cName' => t('Content Domains'),
                    'cDescription' => t('Manage content domains with domain specifiy content')
                ]
        );
    }

    public function upgrade()
    {
        parent::upgrade();
    }

}
