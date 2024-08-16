<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Render the header and sidebar
require_once(realpath(__DIR__ . '/../includes/renderutils.php'));
render_header_and_sidebar(['tabs:logic', 'multiselect'], ['check_im_configure' => true]);

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/permissions.php'));

// If the Incident Management Extra is enabled
if (incident_management_extra())
{       
    // Load the Incident Management Extra
    require_once(realpath(__DIR__ . '/../extras/incident_management/index.php'));

    process_incident_management();
}
else
{
	// Redirect them to the activation page
	header("Location: ../admin/incidentmanagement.php");
}

?>
<?php
// If the Incident Management Extra is enabled
if (incident_management_extra()){
    // Include the incident management javascript file
    echo "<script src='../extras/incident_management/js/incident_management.js?" . current_version("app") . "' defer></script>";
    // Include the incident management css file
    echo "<link rel='stylesheet' href='../extras/incident_management/css/incident_management.css?" . current_version("app") . "'>";
}
?>
<div class="row bg-white">
    <div class="col-12">
        <div id="appetite-tab-content">
            <div class="status-tabs">
                <div class="tab-content">
                    <!-- Display the Configuration -->
                    <?php
                    // If a menu was provided
                    if (isset($_GET['menu']))
                    {
                        // Display the page for the menu
                        switch ($_GET['menu'])
                        {
                            // Display the settings page
                            case "settings":
                                display_incident_management_configure_settings();
                                break;
                            // Display the add and remove values page
                            case "add_remove_values":
                                display_incident_management_configure_add_remove_values();
                                break;
                            // Display the playbooks page
                            case "playbooks":
                                display_incident_management_configure_playbooks();
                                break;
                            // Display the settings page by default
                            default:
                                display_incident_management_configure_settings();
                                break;
                        }
                    }
                    // If no menu was provided
                    else
                    {
                        // Display the settings by default
                        display_incident_management_configure_settings();
                    }
                    ?>
                </div>
            </div>
        </div>
       
    </div>
</div>
<?php
    // Render the footer of the page. Please don't put code after this part.
    render_footer();
?>