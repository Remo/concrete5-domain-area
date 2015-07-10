<?php

namespace Concrete\Package\DomainArea\Controller\SinglePage\Dashboard\System\Basics;

use \Concrete\Core\Page\Controller\DashboardPageController,
    \Concrete\Core\Http\ResponseAssetGroup,
    Database,
    Loader;

class ContentDomains extends DashboardPageController
{

    public $helpers = array('form', 'concrete/ui');

    public function view()
    {
        $r = ResponseAssetGroup::get();
        $r->requireAsset('javascript', 'underscore');
    }

    public function data()
    {
        $db = Database::connection();
        $return = new \stdClass();
        $return->domains = new \stdClass();
        $domains = $db->GetCol('SELECT domain FROM DomainAreaDomains ORDER BY domain');
        foreach ($domains as $domain) {
            $domainObject = $return->domains->$domain = new \stdClass();
            $domainObject->aliases = $db->getCol('SELECT alias FROM DomainAreaDomainAliases WHERE domain = ? ORDER BY alias', [$domain]);
        }

        header('Content-type: application/json');
        echo json_encode($return);
        die();
    }

    public function save()
    {
        $input = file_get_contents("php://input");
        $object = json_decode($input);
        $domains = $object->domains;

        $db = Database::connection();

        if (empty($domains)) {
            $db->Execute('DELETE FROM DomainAreaDomains');
        } else {
            $existingDomains = [];
            foreach ($domains as $domain => $item) {
                $db->Execute('REPLACE INTO DomainAreaDomains (domain) VALUES (?)', $domain);
                $existingDomains[] = '\'' . $domain . '\'';

                if (empty($item->aliases)) {
                    $db->Execute('DELETE FROM DomainAreaDomainAliases WHERE domain = ?', [$domain]);
                } else {

                    $existingAliases = [];
                    foreach ($item->aliases as $alias) {

                        $db->Execute('REPLACE INTO DomainAreaDomainAliases (domain, alias) VALUES (?, ?)', [$domain, $alias]);
                        $existingAliases[] = '\'' . $alias . '\'';
                    }
                    $db->Execute('DELETE FROM DomainAreaDomainAliases WHERE domain = ? AND alias NOT IN (' . join(',', $existingAliases) . ')', [$domain]);

                }
            }
            $db->Execute('DELETE FROM DomainAreaDomains WHERE domain NOT IN (' . join(',', $existingDomains) . ')');
        }

        die();
    }

}
