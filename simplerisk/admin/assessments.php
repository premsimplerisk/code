<?php
    /* This Source Code Form is subject to the terms of the Mozilla Public
     * License, v. 2.0. If a copy of the MPL was not distributed with this
     * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/functions.php'));
require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../includes/display.php'));
require_once(realpath(__DIR__ . '/../includes/alerts.php'));
require_once(realpath(__DIR__ . '/../vendor/autoload.php'));

// Add various security headers
add_security_headers();

// Add the session
$permissions = array(
        "check_access" => true,
        "check_admin" => true,
);
add_session_check($permissions);

// Include the CSRF Magic library
include_csrf_magic();

// Include the SimpleRisk language file
// Ignoring detections related to language files
// @phan-suppress-next-line SecurityCheck-PathTraversal
require_once(language_file());

    // If the extra directory exists
    if (is_dir(realpath(__DIR__ . '/../extras/assessments')))
    {
        // Include the Assessment Extra
        require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));

        // If the user wants to activate the extra
        if (isset($_POST['activate']))
        {
            // Enable the Assessments Extra
            enable_assessments_extra();
        }

        // If the user wants to deactivate the extra
        if (isset($_POST['deactivate']))
        {
            // Disable the Assessments Extra
            disable_assessments_extra();
        }

        // If the user updated the configuration
        if (isset($_POST['submit']))
        {
            // Update the assessment configuration
            update_assessment_config();
            set_alert(true, "good", $escaper->escapeHtml($lang['AssessmentSettingsUpdatedSuccessfully']));
        }
    }

/*********************
 * FUNCTION: DISPLAY *
 *********************/
function display()
{
    global $lang;
    global $escaper;

    // If the extra directory exists
    if (is_dir(realpath(__DIR__ . '/../extras/assessments')))
    {
        // But the extra is not activated
        if (!assessments_extra())
        {
            // If the extra is not restricted based on the install type
            if (!restricted_extra("riskassessment"))
            {
                echo "<form name=\"activate\" method=\"post\" action=\"\">\n";
                echo "<input type=\"submit\" value=\"" . $escaper->escapeHtml($lang['Activate']) . "\" name=\"activate\" /><br />\n";
                echo "</form>\n";
            }
            // The extra is restricted
            else echo $escaper->escapeHtml($lang['YouNeedToUpgradeYourSimpleRiskSubscription']);
        }
        // Once it has been activated
        else
        {
            // Include the Assessments Extra
            require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));

            display_assessments();
        }
    }
    // Otherwise, the Extra does not exist
    else
    {
        echo "<a href=\"https://www.simplerisk.com/extras\" target=\"_blank\">Purchase the Extra</a>\n";
    }
}

?>

<!doctype html>
<html>

  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=10,9,7,8">
<?php
        // Use these jQuery scripts
        $scripts = [
                'jquery.min.js',
        ];

        // Include the jquery javascript source
        display_jquery_javascript($scripts);

	display_bootstrap_javascript();
?>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    
    <link rel="stylesheet" href="../css/bootstrap.css?<?php echo current_version("app"); ?>">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css?<?php echo current_version("app"); ?>">

    <link rel="stylesheet" href="../css/divshot-util.css?<?php echo current_version("app"); ?>">
    <link rel="stylesheet" href="../css/divshot-canvas.css?<?php echo current_version("app"); ?>">
    <link rel="stylesheet" href="../css/display.css?<?php echo current_version("app"); ?>">

    <link rel="stylesheet" href="../vendor/components/font-awesome/css/fontawesome.min.css?<?php echo current_version("app"); ?>">
    <link rel="stylesheet" href="../css/theme.css?<?php echo current_version("app"); ?>">
    <link rel="stylesheet" href="../css/side-navigation.css?<?php echo current_version("app"); ?>">
    
    <?php if (assessments_extra()) { // Only need these if the extra is enabled ?>
        <script src="../vendor/tinymce/tinymce/tinymce.min.js?<?php echo current_version("app"); ?>"></script>
        <script src="../extras/assessments/js/editor.js?<?php echo current_version("app"); ?>"></script>
        <link rel="stylesheet" href="../css/WYSIWYG/editor.css?<?php echo current_version("app"); ?>">
	<?php }?>
    
    <?php
        setup_favicon("..");
        setup_alert_requirements("..");
    ?>
      <script>
          $(document).ready(function(){
              $('#confirm_deactivate').click(function()
              {
                  var modal = $('#extra--deactivate');
                  $(modal).modal('show');
              });
          })
      </script>
  </head>

  <body>

<?php
    display_license_check();
    view_top_menu("Configure");

    // Get any alert messages
    get_alert();
?>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <?php view_configure_menu("Extras"); ?>
        </div>
        <div class="span9">
          <div class="row-fluid">
            <div class="span12">
              <div class="hero-unit">
                <h4>Assessments Extra</h4>
                <?php display(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
        <?php prevent_form_double_submit_script(); ?>
    </script>    
  </body>

</html>
