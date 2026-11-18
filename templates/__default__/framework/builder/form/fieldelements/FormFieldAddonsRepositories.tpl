<input type="hidden" name="order_${escape(HTML_ID)}" id="order_${escape(HTML_ID)}" value="{ORDER}" />

<script>
    jQuery(document).ready(function() {
        // Initialize Sortable for each instance
        Sortable.create(document.getElementById('input_fields_${escape(HTML_ID)}'), {
            handle: '.sortable-selector',
            animation: 150,
            onEnd: function() {
                updateOrder('${escape(HTML_ID)}');
            }
        });
    });

    function updateOrder(htmlId) {
        const container = jQuery('#input_fields_' + htmlId);
        const order = [];
        container.find('li').each(function() {
            const idParts = jQuery(this).attr('id').split('_');
            const index = idParts[idParts.length - 1];
            order.push(index);
        });
        jQuery('#order_' + htmlId).val(order.join(','));
    }

    function change_ids(containerId) {
        const liElements = jQuery("#" + containerId + " li");
        liElements.each(function(index) {
            const newId = containerId.replace('input_fields_', '') + '_' + index;
            jQuery(this).attr('id', newId);

            jQuery(this).find('.input-owner')
                .attr('name', 'field_owner_' + newId)
                .attr('id', 'field_owner_' + newId);

            jQuery(this).find('.input-repository')
                .attr('name', 'field_repository_' + newId)
                .attr('id', 'field_repository_' + newId);

            jQuery(this).find('.input-directory')
                .attr('name', 'field_directory_' + newId)
                .attr('id', 'field_directory_' + newId);

            jQuery(this).find('.move-up')
                .attr('id', 'move-up-' + newId)
                .attr('onclick', 'moveItem("' + newId + '", "up", "' + containerId.replace('input_fields_', '') + '"); return false;');

            jQuery(this).find('.move-down')
                .attr('id', 'move-down-' + newId)
                .attr('onclick', 'moveItem("' + newId + '", "down", "' + containerId.replace('input_fields_', '') + '"); return false;');
        });
        updateOrder(containerId.replace('input_fields_', ''));
    }

    function moveItem(id, direction, htmlId) {
        const containerId = 'input_fields_' + htmlId;
        const li = jQuery('#' + id);
        if (direction === 'up') {
            li.insertBefore(li.prev());
        } else if (direction === 'down') {
            li.insertAfter(li.next());
        }
        change_ids(containerId);
    }
</script>

<ul id="input_fields_${escape(HTML_ID)}" class="sortable-block form-field-values">
    # START fieldelements #
    <li id="${escape(HTML_ID)}_{fieldelements.ID}">
        <div class="grouped-inputs flex-wide">
            <span class="sortable-selector grouped-element" aria-label="{@common.move}">&nbsp;</span>
            <input class="input-owner grouped-element" type="text" name="field_owner_${escape(HTML_ID)}_{fieldelements.ID}" id="field_owner_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.OWNER}" placeholder="{@addon.repos.owner}"# IF NOT fieldelements.C_DELETE # readonly# ENDIF # />
            <input class="input-repository grouped-element" type="text" name="field_repository_${escape(HTML_ID)}_{fieldelements.ID}" id="field_repository_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.REPO}" placeholder="{@addon.repos.repository}"# IF NOT fieldelements.C_DELETE # readonly# ENDIF # />
            <input class="input-directory grouped-element" type="text" name="field_directory_${escape(HTML_ID)}_{fieldelements.ID}" id="field_directory_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.DIR}" placeholder="{@addon.sub.directory}"# IF NOT fieldelements.C_DELETE # readonly# ENDIF # />
            # IF fieldelements.C_DELETE #
            <a class="delete-item grouped-element bgc-full error" href="javascript:void(0);" onclick="jQuery(this).closest('li').remove(); updateOrder('${escape(HTML_ID)}');" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" aria-label="{@common.delete}" data-confirmation="delete-element">
                <i class="far fa-trash-alt" aria-hidden="true"></i>
            </a>
            # ENDIF #
            <a href="#" class="move-up grouped-element" aria-label="{@common.move.up}" id="move-up-${escape(HTML_ID)}_{fieldelements.ID}" onclick="moveItem('${escape(HTML_ID)}_{fieldelements.ID}', 'up', '${escape(HTML_ID)}'); return false;">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
            </a>
            <a href="#" class="move-down grouped-element" aria-label="{@common.move.down}" id="move-down-${escape(HTML_ID)}_{fieldelements.ID}" onclick="moveItem('${escape(HTML_ID)}_{fieldelements.ID}', 'down', '${escape(HTML_ID)}'); return false;">
                <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </a>
        </div>
    </li>
    # END fieldelements #
</ul>

<a href="javascript:void(0);" onclick="addField('${escape(HTML_ID)}', {FIELDS_NUMBER}); return false;" id="add-${escape(HTML_ID)}" class="add-more-values" aria-label="{@common.add}">
    <i class="far fa-lg fa-plus-square" aria-hidden="true"></i>
</a>

<script>
    function addField(htmlId, fieldNb) {
        const newFieldId = htmlId + '_' + fieldNb;
        const container = jQuery('#input_fields_' + htmlId);

        jQuery('<li/>', {
            id: newFieldId
        }).appendTo(container);

        jQuery('<div/>', {
            class: 'grouped-inputs flex-wide'
        }).appendTo('#' + newFieldId);

        jQuery('<span/>', {
            class: 'sortable-selector grouped-element',
            'aria-label': ${escapejs(@common.move)}
        }).html('&nbsp;').appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<input/>', {
            class: 'input-owner grouped-element',
            type: 'text',
            id: 'field_owner_' + newFieldId,
            name: 'field_owner_' + newFieldId,
            placeholder: ${escapejs(@addon.repos.owner)}
        }).appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<input/>', {
            class: 'input-repository grouped-element',
            type: 'text',
            id: 'field_repository_' + newFieldId,
            name: 'field_repository_' + newFieldId,
            placeholder: ${escapejs(@addon.repos.repository)}
        }).appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<input/>', {
            class: 'input-directory grouped-element',
            type: 'text',
            id: 'field_directory_' + newFieldId,
            name: 'field_directory_' + newFieldId,
            placeholder: ${escapejs(@addon.sub.directory)}
        }).appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<a/>', {
            class: 'delete-item grouped-element bgc-full error',
            href: 'javascript:void(0);',
            onclick: 'jQuery(this).closest("li").remove(); updateOrder("' + htmlId + '");',
            id: 'delete_' + newFieldId,
            'aria-label': ${escapejs(@common.delete)}
        }).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<a/>', {
            class: 'move-up grouped-element',
            href: '#',
            id: 'move-up-' + newFieldId,
            'aria-label': ${escapejs(@common.move.up)},
            onclick: 'moveItem("' + newFieldId + '", "up", "' + htmlId + '"); return false;'
        }).html('<i class="fa fa-arrow-up" aria-hidden="true"></i>').appendTo('#' + newFieldId + ' .grouped-inputs');

        jQuery('<a/>', {
            class: 'move-down grouped-element',
            href: '#',
            id: 'move-down-' + newFieldId,
            'aria-label': ${escapejs(@common.move.down)},
            onclick: 'moveItem("' + newFieldId + '", "down", "' + htmlId + '"); return false;'
        }).html('<i class="fa fa-arrow-down" aria-hidden="true"></i>').appendTo('#' + newFieldId + ' .grouped-inputs');

        updateOrder(htmlId);
        jQuery('#add-' + htmlId).attr('onclick', 'addField("' + htmlId + '", ' + (fieldNb + 1) + '); return false;');
    }
</script>