<?php

namespace Concrete\Package\DomainArea\Controller\SinglePage\Dashboard\System\Basics;

use \Concrete\Core\Page\Controller\DashboardPageController,
    Loader;

class ContentDomains extends DashboardPageController
{

    public $helpers = array('form', 'concrete/ui');

    public function view()
    {
        $db = Loader::db();
        $domains = $db->getAll('SELECT domain FROM DomainAreaDomains ORDER BY domain');
        $this->set('domains', $domains);
    }

    public function add()
    {
        if ($this->token->validate('add')) {
            $db = Loader::db();
            $domain = $this->post('domain');
            $db->Execute('INSERT INTO DomainAreaDomains (domain) VALUES (?)', array($domain));
            $this->redirect('/dashboard/system/basics/content_domains', 'domain_added');
        } else {
            $this->set('error', array($this->token->getErrorMessage()));
            $this->view();
        }
    }

    public function delete($domain)
    {
        if ($this->token->validate('delete')) {
            $db = Loader::db();
            $db->Execute('DELETE FROM DomainAreaDomains WHERE domain = ?', array(base64_decode($domain)));
            $this->redirect('/dashboard/system/basics/content_domains', 'domain_deleted');
        } else {
            $this->set('error', array($this->token->getErrorMessage()));
            $this->view();
        }
    }

    public function domain_added()
    {
        $this->set('message', t('Your domain has been added'));
        $this->view();
    }

    public function domain_deleted()
    {
        $this->set('message', t('Your domain has been deleted'));
        $this->view();
    }

}
