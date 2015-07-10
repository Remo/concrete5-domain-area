<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="domains"></div>

<script type="text/template" class="template">

    <table class="table table-striped" border="0" cellspacing="1" cellpadding="0">
        <thead>
        <tr>
            <td class="header"><?php echo t('Domain') ?></td>
            <td class="header"><?php echo t('Aliases (optional)') ?></td>
        </tr>
        </thead>
        <tbody>
        <% _.each( rc.domains, function( item, domain ){ %>
        <tr>
            <td><%- domain %>
                <button class="remove-domain" data-domain="<%- domain %>"><i class="fa fa-times"></i></button>
            </td>
            <td>
                <% _.each( item.aliases, function( alias ){ %>
                <%- alias %>
                <button class="remove-alias" data-domain="<%- domain %>" data-alias="<%- alias %>"><i
                        class="fa fa-times"></i></button>
                <br>
                <% }); %>
                <input type="text">
                <button data-domain="<%- domain %>" class="add-alias btn btn-primary">
                    <?= t('Add Alias') ?>
                </button>
            </td>
        </tr>
        <% }); %>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2">
                <input type="text" id="domain">
                <button class="add-domain btn btn-primary">
                    <?= t('Add Domain') ?>
                </button>
            </td>
        </tr>
        </tfoot>
    </table>


</script>
<script type="text/javascript">
    $(document).ready(function () {
        _.templateSettings.variable = "rc";

        var template = _.template(
            $("script.template").html()
        );

        var templateData = {};

        $.get("<?=$this->action('data')?>", function (response) {
            templateData = response;
            render();
        })

        function render() {
            $("#domains").html(template(templateData));
        }

        function save() {
            $.post("<?=$this->action('save')?>", JSON.stringify(templateData), function (response) {
                if (response == "") {
                    ConcreteAlert.notify({
                        'message': <?=json_encode(t('content domains saved'))?>
                    });
                }
                else {
                    ConcreteAlert.notify({
                        'type': 'danger',
                        'message': response
                    });
                }
            });
        }

        $("#domains").on("click", ".add-alias", function (event) {
            event.preventDefault();
            var alias = $(this).siblings("input").val();
            var domain = $(this).data("domain");
            templateData.domains[domain].aliases.push(alias);
            render();
            save();
        });
        $("#domains").on("click", ".add-domain", function (event) {
            event.preventDefault();
            var domain = $(this).siblings("input").val();
            templateData.domains[domain] = {"aliases": []};
            render();
            save();
        });
        $("#domains").on("click", ".remove-alias", function (event) {
            event.preventDefault();
            var domain = $(this).data("domain");
            var alias = $(this).data("alias");

            var pos = templateData.domains[domain].aliases.indexOf(alias);
            templateData.domains[domain].aliases.splice(pos, 1);
            render();
            save();
        });
        $("#domains").on("click", ".remove-domain", function (event) {
            event.preventDefault();
            var domain = $(this).data("domain");
            delete templateData.domains[domain];
            render();
            save();
        });
    });
</script>
