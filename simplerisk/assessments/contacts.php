<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file

require_once(realpath(__DIR__ . '/../includes/renderutils.php'));
require_once(realpath(__DIR__ . '/../includes/assessments.php'));

render_header_and_sidebar(['blockUI', 'selectize', 'datatables', 'WYSIWYG', 'multiselect', 'tabs:logic', 'CUSTOM:common.js', 'CUSTOM:pages/assessment.js'], ['check_assessments' => true], '');

// Check if assessment extra is enabled
if(assessments_extra())
{
    // Include the assessments extra
    require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));
}
else
{
    header("Location: ../index.php");
    exit(0);
}

// Process actions on contact pages
if(process_assessment_contact()){
    refresh();
}

?>
<div class="row bg-white">
    <div class="col-12">
        <div class="card-body my-2 border">
    <?php 
        if(isset($_GET['action']) && $_GET['action']=="add") { 
    ?>
            <div class="hero-unit bg-white">
                <div class="row">
                    <div class="col-6">
                        <?php display_assessment_contacts_add(); ?>
                    </div>
                </div>
            </div>
    <?php
        } elseif(isset($_GET['action']) && $_GET['action']=="edit" && $_GET['id']) { 
    ?>
            <div class="hero-unit bg-white">
                <div class="row">
                    <div class="col-6">
                        <?php display_assessment_contacts_edit($_GET['id']); ?>
                    </div>
                </div>
            </div>
    <?php
        } else { 
    ?>
            <div class="row">
                <div class="col-6">
                    <input type="text" class="form-control" placeholder="Filter by text" id="filter_by_text">
                </div>
            </div>
            <div data-sr-role='dt-settings' data-sr-target='assessment-contacts-table' class='float-end'>
    <?php
            if(has_permission("assessment_add_contact")) {
    ?>
                <a id="aseessment-contact--add-btn" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Add']); ?></a>
    <?php
            }
    ?>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <?php display_assessment_contacts(); ?>
                </div>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
</div>

<!-- MODAL FOR ADDING A NEW CONTACT -->
<div id="aseessment-contact--add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="aseessment-contact--add" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h4 class="modal-title"><?= $escaper->escapeHtml($lang['AddNewAssessmentContact']); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label>Company:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="company" maxlength="255" size="100" value="" type="text">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['Name']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="name" maxlength="255" size="100" value="" type="text">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['EmailAddress']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="email" maxlength="200" size="100" value="" type="email">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['Phone']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="phone" maxlength="200" size="100" value="" type="text">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['ContactManager']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <?= create_dropdown("enabled_users", NULL, "manager", true, false, true, "", $escaper->escapeHtml($lang['Unassigned']));?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3">
                            <label><?= $escaper->escapeHtml($lang['Details']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <textarea class = "form-control" name='details' class='full-width'></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true"><?= $escaper->escapeHtml($lang['Cancel']); ?></button>
                    <button type="submit" class="btn btn-submit" name="add_contact"><?= $escaper->escapeHtml($lang['Save']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL FOR EDITING A CONTACT -->
<div id="aseessment-contact--edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="aseessment-contact--edit" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="">
                <input type='hidden' name='id' value=''/>
                <div class="modal-header">
                    <h4 class="modal-title"><?= $escaper->escapeHtml($lang['UpdateAssessmentContact']); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label>Company:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="company" maxlength="255" size="100" value="" type="text">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['Name']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="name" maxlength="255" size="100" type="text" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['EmailAddress']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input required class = "form-control" name="email" maxlength="200" size="100" type="email" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['Phone']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <input name="phone" class = "form-control" maxlength="200" value="" size="100" type="text" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['ContactManager']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <?= create_dropdown("enabled_users", null, "manager", true, false, true, "", $escaper->escapeHtml($lang['Unassigned'])) ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3 d-flex align-items-center">
                            <label><?= $escaper->escapeHtml($lang['Details']);?>:</label>
                        </div>
                        <div class = "col-9">
                            <textarea name='details' class='form-control'></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true"><?= $escaper->escapeHtml($lang['Cancel']); ?></button>
                    <button type="submit" class="btn btn-submit" name="update_contact"><?= $escaper->escapeHtml($lang['Update']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL FOR DELETING A CONTACT -->
<div id='aseessment-contact--delete' class='modal fade' aria-labelledby='aseessment-contact--delete' tabindex='-1' aria-hidden='true'>
    <div class='modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered'>
        <div class='modal-content'>
            <form class='' action="" method='post'>
                <input type='hidden' name='contact_id' value=''/>
                <div class='modal-body'>
                    <div class='form-group text-center'>
                        <label for=''><?= $escaper->escapeHtml($lang['AreYouSureYouWantToDeleteThisContact']) ?></label>
                    </div>
                    <div class='text-center control-delete-actions'>
                        <button type="button" class='btn btn-secondary' data-bs-dismiss='modal'><?= $escaper->escapeHtml($lang['Cancel']) ?></button>
                        <button type='submit' class='btn btn-submit' name='delete_contact'><?= $escaper->escapeHtml($lang['Yes']) ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
        
<script>
    
    let assessment_add_contact_permission = <?= has_permission("assessment_add_contact") ?>;
    let assessment_edit_contact_permission = <?= has_permission("assessment_edit_contact") ?>;

    $(function() {

        // Open a modal for adding a contact
        $('body').on("click", "#aseessment-contact--add-btn", function() {

            if (!assessment_add_contact_permission) {
                return toastr.error("<?= $escaper->escapeHtml($lang['NoPermissionForAddAssessmentContacts']) ?>");
            }

            $("#aseessment-contact--add").modal("show");

        });
        
        // Open a modal for editing a contact
        $('body').on("click", ".aseessment-contact--edit-btn", function() {

            if (!assessment_edit_contact_permission) {
                return toastr.error("<?= $escaper->escapeHtml($lang['NoPermissionForThisAction']) ?>");
            }
            
            let contact_id = $(this).data("id");
            $("#aseessment-contact--edit [name=id]").val(contact_id);

            $.ajax({
                url: BASE_URL + '/api/assessment/contacts/edit',
                type: 'POST',
                data: {id: contact_id},
                success : function (result){
                    if(result.status_message){
                        showAlertsFromArray(result.status_message);
                    }

                    $("#aseessment-contact--edit [name='company']").val(result.data.company || '');
                    $("#aseessment-contact--edit [name='name']").val(result.data.name || '');
                    $("#aseessment-contact--edit [name='email']").val(result.data.email || '');
                    $("#aseessment-contact--edit [name='phone']").val(result.data.phone || '');
                    $("#aseessment-contact--edit [name='manager']").val(result.data.manager || '');
                    $("#aseessment-contact--edit [name='details']").val(result.data.details || '');

                    $("#aseessment-contact--edit").modal("show");

                },
                error: function(xhr,status,error){
                    if(xhr.responseJSON && xhr.responseJSON.status_message){
                        showAlertsFromArray(xhr.responseJSON.status_message);
                    }
                }
            });
            
        });
        
        // Open a modal for deleting a contact
        $('body').on("click", ".aseessment-contact--delete-btn", function() {

            $("#aseessment-contact--delete [name=contact_id]").val($(this).data("id"));

            $("#aseessment-contact--delete").modal("show");
            
        });

    });

</script>
<?php
render_footer ();
?>