<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<form method="post" class="ccm-dashboard-content-form" action="<?php echo $view->action('add') ?>">
    <?php $this->controller->token->output('add') ?>

    <table class="table table-striped" border="0" cellspacing="1" cellpadding="0">
        <thead>
            <tr>
                <td class="header"><?php echo t('Domain') ?></td>
                <td class="header">&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($domains as $domain) { ?>
                <tr>
                    <td><?php echo $domain['domain']; ?></td>
                    <td width="60">
                        <?php echo $concrete_ui->button(t('Delete'), $view->url('/dashboard/system/basics/content_domains', 'delete', $domain['domain']) . '?' . $this->controller->token->getParameter('delete'), 'left', 'btn-xs'); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td><input type="text" name="domain" class="form-control"></td>
                <td>
                    <?php echo $concrete_ui->submit(t('Add'), 'left', 'btn-xs'); ?>                    
                </td>
            </tr>
        </tfoot>
    </table>
</form>