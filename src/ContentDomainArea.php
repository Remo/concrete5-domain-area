<?php

namespace Concrete\Package\DomainArea\Src;

use Loader,
    Page,
    Area;

class ContentDomainArea
{

    private $areas = [];
    private $arHandle;
    private $global;

    /**
     * Creates areas depending on the current domain and the state of the page.
     * If we're in the edit mode, areas for all domains are shown, no matter what.
     * 
     * In case we're not in the edit mode, only those areas are shown which match
     * the current domain.
     * 
     * @param string $arHandle
     * @param boolean $global
     */
    public function __construct($arHandle, $global = false)
    {
        $this->arHandle = $arHandle;
        $this->global = $global;

        $c = Page::getCurrentPage();
        
        $areaClassName = $global ? 'GlobalArea' : 'Area';

        // get domains
        // @TODO move this into a separate model
        $db = Loader::db();

        // show all areas while we're in edit mode
        if ($c->isEditMode()) {
            $domains = $db->getAll('SELECT domain FROM DomainAreaDomains ORDER BY domain');

            foreach ($domains as $domain) {
                $this->areas[] = new $areaClassName($this->arHandle . ' (' . $domain['domain'] . ')');
            }
        } else {
            $httpHost = $_SERVER['HTTP_HOST'];
            if (preg_match("/[^\.\/]+\.[^\.\/]+$/", $httpHost, $matches)) {
                $currentDomain = $matches[0];
                $domains = $db->getAll('SELECT domain FROM DomainAreaDomains WHERE domain = ?', array($currentDomain));

                foreach ($domains as $domain) {
                    $this->areas[] = new $areaClassName($this->arHandle . ' (' . $domain['domain'] . ')');
                }
            }
        }
    }

    /**
     * Returns the list of all areas, for those who want to work with areas in
     * their code.
     * 
     * @return array
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * Display areas
     * 
     * @param Page $c
     * @param array $alternateBlockArray
     */
    public function display($c = null, $alternateBlockArray = null)
    {
        if (is_array($this->areas)) {
            foreach ($this->areas as $area) {
                if ($this->global) {
                    $area->display();
                }
                else {
                    $area->display($c, $alternateBlockArray);
                }
            }
        }
    }

    /**
     * Forward certain calls to each area
     * 
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $supportedMethods = [
            'enableGridContainer',
            'disableControls',
            'setBlockLimit',
            'setAreaGridMaximumColumns',
            'setBlockWrapperStart',
            'setBlockWrapperEnd',
            'setCustomTemplate',
            'forceControlsToDisplay'
        ];

        if (in_array($name, $supportedMethods)) {
            if (is_array($this->areas)) {
                foreach ($this->areas as $area) {
                    call_user_func_array(array($area, $name), $arguments);
                }
                return;
            }
        }

        throw new \Exception(sprintf('%s does not support the method %s', get_class(), $name));
    }

}
