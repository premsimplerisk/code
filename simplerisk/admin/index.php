<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Render the header and sidebar
require_once(realpath(__DIR__ . '/../includes/renderutils.php'));
render_header_and_sidebar(['blockUI', 'tabs:logic'], ['check_admin' => true]);

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/mail.php'));

// If the General tab was submitted
if (isset($_POST['update_general_settings']))
{
    // Set the error to false
    $error = false;

    // Update the 'Automatically verify new assets' setting
    $auto_verify_new_assets = (isset($_POST['auto_verify_new_assets'])) ? 1 : 0;
    $current_auto_verify_new_assets = get_setting("auto_verify_new_assets");
    if ($auto_verify_new_assets != $current_auto_verify_new_assets)
    {
        update_setting("auto_verify_new_assets", $auto_verify_new_assets);
    }

    // Update the 'Document Exception update resets its approval' setting
    $exception_update_resets_approval = (isset($_POST['exception_update_resets_approval'])) ? 1 : 0;
    if ($exception_update_resets_approval != get_setting("exception_update_resets_approval"))
    {
        update_setting("exception_update_resets_approval", $exception_update_resets_approval);
    }

    // Update the 'Require a Risk Mapping for all risks' setting
    $risk_mapping_required = (isset($_POST['risk_mapping_required'])) ? 1 : 0;
    if ($risk_mapping_required != get_setting("risk_mapping_required"))
    {
        update_setting("risk_mapping_required", $risk_mapping_required);
    }

    // Update the alert timeout
    $alert_timeout = $_POST['alert_timeout'];
    if ($alert_timeout != get_setting("alert_timeout")) {
        // Update the base url
        update_setting("alert_timeout", $alert_timeout);
    }

    // Update the setting to show all risks for plan projects
    $plan_projects_show_all = (isset($_POST['plan_projects_show_all'])) ? 1 : 0;
    $current_plan_projects_show_all = get_setting("plan_projects_show_all");
    if ($plan_projects_show_all != $current_plan_projects_show_all)
    {
        update_setting("plan_projects_show_all", $plan_projects_show_all);
    }

    // Update the default language setting
    $default_language = get_name_by_value("languages", (int)$_POST['languages']);
    $current_default_language = get_setting("default_language");
    if ($default_language != $current_default_language)
    {
        update_setting("default_language", $default_language);
    }

    // Update the default timezone setting
    $default_timezone = $_POST['default_timezone'];
    $current_default_timezone = get_setting("default_timezone");
    if ($default_timezone != $current_default_timezone)
    {
        update_setting("default_timezone", $default_timezone);
    }

    // Update the default date format setting
    $default_date_format = $_POST['default_date_format'];
    $current_default_date_format = get_setting("default_date_format");
    if ($default_date_format != $current_default_date_format)
    {
        update_setting("default_date_format", $default_date_format);
    }

    // Update the default risk score setting
    $default_risk_score = (float)$_POST['default_risk_score'];
    $current_default_risk_score = get_setting("default_risk_score");
    if ($default_risk_score != $current_default_risk_score)
    {
        // If the default risk score is a numeric value between 0 and 10
        if (is_numeric($default_risk_score) && ($default_risk_score >= 0) && ($default_risk_score <= 10))
        {
            update_setting("default_risk_score", $default_risk_score);
        }
    }

    // Update the default risk score setting
    $maximum_risk_subject_length = (float)$_POST['maximum_risk_subject_length'];
    $current_maximum_risk_subject_length = get_setting("maximum_risk_subject_length");
    if ($maximum_risk_subject_length != $current_maximum_risk_subject_length) {
        // If the maximum_risk_subject_length is a numeric value between 0 and 1000
        if (is_numeric($maximum_risk_subject_length) && ($maximum_risk_subject_length > 0) && ($maximum_risk_subject_length <= 1000)) {
            update_setting("maximum_risk_subject_length", $maximum_risk_subject_length);
        }
    }

    // Update the default closed audit status setting
    $default_closed_audit_status = (int)$_POST['closed_audit_status'];
    $current_default_closed_audit_status = get_setting("closed_audit_status");
    if ($default_closed_audit_status != $current_default_closed_audit_status)
    {
        // If the default closed audit status is empty
        if (empty($default_closed_audit_status))
        {
            set_alert(true, "bad", $escaper->escapeHtml($lang['ClosedAuditStatusIsRequired']));
            $error = true;
        }
        else
        {
            update_setting("closed_audit_status", $default_closed_audit_status);
        }
    }

    // Update the default initiated audit status setting
    $default_initiated_audit_status = (int)$_POST['initiated_audit_status'];
    $current_default_initiated_audit_status = get_setting("initiated_audit_status");
    if ($default_initiated_audit_status != $current_default_initiated_audit_status)
    {
        update_setting("initiated_audit_status", $default_initiated_audit_status);
    }

    // Update the default currency setting
    $default_currency = $_POST['default_currency'];
    $current_default_currency = get_setting("currency");
    if ($default_currency != $current_default_currency)
    {
        // If the default currency is not empty
        if ($default_currency != "")
        {
            // If the default currency value is less than or equal to six characters long
            if (strlen($default_currency) <= 6)
            {
                // Update the currency
                update_setting("currency", $default_currency);
            }
        }
    }

    // Update the default asset valuation setting
    $default_asset_valuation = (int)$_POST['default_asset_valuation'];
    $current_default_asset_valuation = get_setting("default_asset_valuation");
    if ($default_asset_valuation != $current_default_asset_valuation)
    {
        // If the default asset valuation is numeric
        if (is_numeric($default_asset_valuation))
        {
            // If the default asset valuation is between 1 and 10
            if ($default_asset_valuation >= 1 && $default_asset_valuation <= 10)
            {
                // Update the default asset valuation
                update_setting("default_asset_valuation", $default_asset_valuation);
            }
        }
    }

    // Update the default user role setting
    $default_user_role = (int)$_POST['default_user_role'];
    $current_default_user_role = get_default_role_id();
    if ($default_user_role != $current_default_user_role) {
        // Update the default user role
        set_default_role($default_user_role);
    }

    // Update the default current maturity setting
    $default_current_maturity = (int)$_POST['default_current_maturity'];
    $current_default_current_maturity = get_setting("default_current_maturity");
    if ($default_current_maturity != $current_default_current_maturity)
    {
        // Update the default current maturity
        update_setting("default_current_maturity", $default_current_maturity);
    }

    // Update the default desired maturity setting
    $default_desired_maturity = (int)$_POST['default_desired_maturity'];
    $current_default_desired_maturity = get_setting("default_desired_maturity");
    if ($default_desired_maturity != $current_default_desired_maturity)
    {
        // Update the default desired maturity
        update_setting("default_desired_maturity", $default_desired_maturity);
    }

    // Update the next review date setting
    $next_review_date_uses = $_POST['next_review_date_uses'];
    $current_next_review_date_uses = get_setting("next_review_date_uses");
    if ($next_review_date_uses != $current_next_review_date_uses)
    {
        // Update the default user role
        update_setting("next_review_date_uses", $next_review_date_uses);
    }

    // Update the base url
    $simplerisk_base_url = $_POST['simplerisk_base_url'];
    $current_simplerisk_base_url = get_setting("simplerisk_base_url");
    if ($simplerisk_base_url != $current_simplerisk_base_url)
    {
        // If the base url is not empty
        if ($simplerisk_base_url != "" && is_valid_base_url($simplerisk_base_url))
        {
            // Update the base url
            update_setting("simplerisk_base_url", $simplerisk_base_url);

            $_SESSION['base_url'] = $simplerisk_base_url;
        } else {
            set_alert(true, "bad", $escaper->escapeHtml($lang['InvalidSimpleriskBaseUrl']));
            $error = true;
        }
    }

    // Update the Risk Appetite
    $risk_appetite = (float)$_POST['risk_appetite'];
    if ($risk_appetite != get_setting("risk_appetite") && $risk_appetite != "")
    {
        // Update the Risk Appetite
        update_setting("risk_appetite", $risk_appetite);
    }

    // If all setting values were saved successfully
    if (!$error)
    {
        // Display an alert
        set_alert(true, "good", "The settings were updated successfully.");
    }
}

// Check if a new file type was submitted
if (isset($_POST['add_file_type']))
{
    $name = $_POST['new_file_type'];
    $extension = $_POST['file_type_ext'];

    // Insert a new file type (250 chars) with extension (10 chars)
    $success = add_file_type($name, $extension);

    // If the add was successful
    if ($success)
    {
        // Display an alert
        set_alert(true, "good", "A new upload file type was added successfully.");
    }
}

// Check if a file type was deleted
if (isset($_POST['delete_file_type']))
{
    $value = (int)$_POST['file_types'];

    // Verify value is an integer
    if (is_int($value))
    {
        delete_value("file_types", $value);

        // Display an alert
        set_alert(true, "good", "An existing upload file type was removed successfully.");
    }
}

// Check if a file type extension was deleted
if (isset($_POST['delete_file_extension']))
{
    $value = (int)$_POST['file_type_extensions'];

    // Verify value is an integer
    if (is_int($value))
    {
        delete_value("file_type_extensions", $value);

        // Display an alert
        set_alert(true, "good", "An existing upload file extension was removed successfully.");
    }
}

// Check if the maximum file upload size was updated
if (isset($_POST['update_max_upload_size']))
{
    // Verify value is a numeric value
    if (is_numeric($_POST['size']))
    {
        update_setting('max_upload_size', $_POST['size']);

        // Get the currently set max upload size for SimpleRisk
        $simplerisk_max_upload_size = get_setting('max_upload_size');

        // If the max upload size for SimpleRisk is bigger than the PHP max upload size
        if ($simplerisk_max_upload_size > php_max_allowed_values())
        {
            // Display an alert
            set_alert(true, "bad", $escaper->escapeHtml($lang['WarnPHPUploadSize']));
        }
        // If the max upload size for SimpleRisk is bigger than the MySQL max_allowed_packet
        else if ($simplerisk_max_upload_size > mysql_max_allowed_values())
        {
            // Display an alert
            set_alert(true, "bad", $escaper->escapeHtml($lang['WarnMySQLUploadSize']));
        }
        else
        {
            // Display an alert
            set_alert(true, "good", "The maximum upload file size was updated successfully.");
        }
    }
    else
    {
        // Display an alert
        set_alert(true, "bad", "The maximum upload file size needs to be an integer value.");
    }
}

// Check if the mail settings were submitted
if (isset($_POST['submit_mail']))
{
    // Get the posted values
    $transport = $_POST['transport'];
    $from_email = $_POST['from_email'];
    $from_name = $_POST['from_name'];
    $replyto_email = $_POST['replyto_email'];
    $replyto_name = $_POST['replyto_name'];
    $prepend = $_POST['prepend'];
    $host = $_POST['host'];
    $smtpautotls = (isset($_POST['smtpautotls'])) ? "true" : "false";
    $smtpauth = (isset($_POST['smtpauth'])) ? "true" : "false";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $encryption = $_POST['encryption'];
    $port = $_POST['port'];

    // Update the mail settings
    update_mail_settings($transport, $from_email, $from_name, $replyto_email, $replyto_name, $host, $smtpautotls, $smtpauth, $username, $password, $encryption, $port, $prepend);

    // Display an alert
    set_alert(true, "good", "Mail settings were updated successfully.");
}

// Check if the mail test was submitted
if (isset($_POST['test_mail_configuration']))
{
    // Set up the test email
    $name = "SimpleRisk Test";
    $email = $_POST['email'];
    $subject = "SimpleRisk Test Email";
    $full_message = "This is a test email from SimpleRisk.";
    $now = time();
    // Set limit the frequency of test mail to 5 minutes.
    if((isset($_SESSION['test_mail_sent']) && $now - intval($_SESSION['test_mail_sent']) > 300) || !isset($_SESSION['test_mail_sent'])) {
        // Send the e-mail
        send_email($name, $email, $subject, $full_message);

        $_SESSION['test_mail_sent'] = time();

        // Display an alert
        set_alert(true, "good", "A test email has been sent using the current settings.");
    } else {
        set_alert(true, "bad", $escaper->escapeHtml($lang['LimitedTestmailMessage']));
    }
}

// If the Backups tab was submitted
if (isset($_POST['submit_backup']) || isset($_POST['submit_and_backup_now'])) {

    // Set the error to false
    $error = false;

    // Get the submitted backup_auto value
    $backup_auto = (isset($_POST['backup_auto']) ? "true" : "false");

    // If the backup_auto value has changed
    if ($backup_auto != get_setting("backup_auto")) {
        // Update the backup_auto setting
        update_setting("backup_auto", $backup_auto);
    }

    // Get the submitted backup_path value
    $backup_path = $_POST['backup_path'];

    // Remove any trailing slashes from the backup path
    $backup_path = rtrim($backup_path, "/");

    // If the backup_path value has changed
    if ($backup_path != get_setting("backup_path")) {
        // Get the actual path to the document root and backup directory
        $root_path = str_replace('/', '\\', realpath(__DIR__ . '/../'));
        $dir_path = str_replace('/', '\\', $backup_path);

        // If the backup file is not in the web root
        if (strpos($dir_path, $root_path) === false && $dir_path != "") {
            // Update the backup_path setting
            update_setting("backup_path", $backup_path);
        } else {
            // We have an error
            $error = true;
            set_alert(true, "bad", $escaper->escapeHtml($lang['ForSecurityReasonsBackupOutsideWebRoot']));
        }
    }

    // Get the submitted backup_schedule value
    $backup_schedule = $_POST['backup_schedule'];

    // If the backup_schedule value has changed
    if ($backup_schedule != get_setting("backup_schedule")) {
        // If the backup schedule is hourly, daily, weekly or monthly
        if ($backup_schedule == "hourly" || $backup_schedule == "daily" || $backup_schedule == "weekly" || $backup_schedule == "monthly") {
            // Update the backup_schedule setting
            update_setting("backup_schedule", $backup_schedule);
        }
    }

    // Get the posted backup_remove value
    $backup_remove = (int)$_POST['backup_remove'];

    // If the backup_remove value has changed
    if ($backup_remove != get_setting("backup_remove")) {
        // If the backup_remove value is an integer value
        if (is_int($backup_remove)) {
            // Update the backup_remove setting
            update_setting("backup_remove", $backup_remove);
        }
    }

    // If we don't have an error
    if (!$error) {
        // Display an alert
        set_alert(true, "good", "The settings were updated successfully.");
        
        $message = _lang('BackupSettingsUpdated', ['user_name' => $_SESSION['name']], false); 
        write_log(0, $_SESSION['uid'], $message, 'backup');
        
        // If we should also do a backup
        if (isset($_POST['submit_and_backup_now'])) {

            $message = _lang('BackupInitiatedByUser', ['user_name' => $_SESSION['name']], false);
            write_debug_log($message);
            write_log(0, $_SESSION['uid'], $message, 'backup');

            // Increasing the time for timeout
            set_time_limit(600);

            require_once(realpath(__DIR__ . '/../cron/cron_backup.php'));
            do_backup(true);
        }
    }
}
    
// If the Security tab was submitted
if (isset($_POST['update_security_settings']))
{
    // Set the error to false
    $error = false;

    // Update the session activity timeout setting
    $session_activity_timeout = (int)$_POST['session_activity_timeout'];

    // If the session_activity_timeout value is at least 5 minutes
    if ($session_activity_timeout >= 300)
    {
        $current_session_activity_timeout = get_setting("session_activity_timeout");
        if ($session_activity_timeout != $current_session_activity_timeout)
        {
            update_setting("session_activity_timeout", $session_activity_timeout);
        }
    }
    else
    {
        $error = true;
        set_alert(true, "bad", "We do not recommend setting a session activity timeout less than 300 seconds.");
    }

    // Update the session absolute timeout setting
    $session_absolute_timeout = (int)$_POST['session_absolute_timeout'];

    // If the session_absolute_timeout value is less than the session_activity_timeout
    if ($session_absolute_timeout > get_setting("session_activity_timeout"))
    {
        $current_session_absolute_timeout = get_setting("session_absolute_timeout");
        if ($session_absolute_timeout != $current_session_absolute_timeout)
        {
            update_setting("session_absolute_timeout", $session_absolute_timeout);
        }
    }
    else
    {
        $error = true;
        set_alert(true, "bad", "The session absolute timeout should be more than the session activity timeout.");
    }

    // Update the content security policy
    $content_security_policy = isset($_POST['content_security_policy']) ? 1 : 0;
    $current_content_security_policy = get_setting("content_security_policy");
    if ($content_security_policy != $current_content_security_policy)
    {
        update_setting("content_security_policy", $content_security_policy);
    }

    // Update the SSL certificate check for the SimpleRisk API
    $ssl_certificate_check = isset($_POST['ssl_certificate_check_simplerisk']) ? 1 : 0;
    $current_ssl_certificate_check = get_setting("ssl_certificate_check_simplerisk");
    if ($ssl_certificate_check != $current_ssl_certificate_check)
    {
        update_setting("ssl_certificate_check_simplerisk", $ssl_certificate_check);
    }

    // Update the SSL certificate check for external websites
    $ssl_certificate_check = isset($_POST['ssl_certificate_check_external']) ? 1 : 0;
    $current_ssl_certificate_check = get_setting("ssl_certificate_check_external");
    if ($ssl_certificate_check != $current_ssl_certificate_check)
    {
        update_setting("ssl_certificate_check_external", $ssl_certificate_check);
    }

    // Update the proxy settings
    $proxy_web_requests = isset($_POST['proxy_web_requests']) ? 1 : 0;
    $current_proxy_web_requests = get_setting("proxy_web_requests");
    if ($proxy_web_requests != $current_proxy_web_requests)
    {
        update_setting("proxy_web_requests", $proxy_web_requests);
    }

    // If proxy web requests is enabled
    if ($proxy_web_requests)
    {
        // Get the new proxy values
        $proxy_authenticated = isset($_POST['proxy_authenticated']) ? 1 : 0;
        $proxy_verify_ssl_certificate = isset($_POST['proxy_verify_ssl_certificate']) ? 1 : 0;
        $proxy_host = isset($_POST['proxy_host']) ? $_POST['proxy_host'] : "";
        $proxy_port = isset($_POST['proxy_port']) ? $_POST['proxy_port'] : "";
        $proxy_user = isset($_POST['proxy_user']) ? $_POST['proxy_user'] : "";
        $proxy_pass = isset($_POST['proxy_pass']) ? $_POST['proxy_pass'] : "";

        // Get the current proxy values
        $current_proxy_authenticated = get_setting("proxy_authenticated");
        $current_proxy_verify_ssl_certificate = get_setting("proxy_verify_ssl_certificate");
        $current_proxy_host = get_setting("proxy_host");
        $current_proxy_port = get_setting("proxy_port");
        $current_proxy_user = get_setting("proxy_user");
        $current_proxy_pass = get_setting("proxy_pass");

        // Update the proxy settings
        if ($proxy_authenticated != $current_proxy_authenticated)
        {
            update_setting("proxy_authenticated", $proxy_authenticated);
        }

        if ($proxy_verify_ssl_certificate != $current_proxy_verify_ssl_certificate)
        {
            update_setting("proxy_verify_ssl_certificate", $proxy_verify_ssl_certificate);
        }

        if ($proxy_host != $current_proxy_host)
        {
            // If this is a valid IP or domain name
            if (filter_var($proxy_host, FILTER_VALIDATE_IP) || filter_var($proxy_host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME))
            {
                update_setting("proxy_host", $proxy_host);
            }
        }

        if ($proxy_port != $current_proxy_port)
        {
            // Set the minimum and maximum port range
            $options = array("options" => array("min_range"=>0, "max_range"=>65535));

            // If this is a valid integer value
            if (filter_var($proxy_port, FILTER_VALIDATE_INT, $options))
            {
                update_setting("proxy_port", $proxy_port);
            }
        }

        if ($proxy_user != $current_proxy_user)
        {
            update_setting("proxy_user", $proxy_user);
        }

        // If the proxy password has been changed
        if ($proxy_pass != "XXXXXXXXXX" && $proxy_pass != $current_proxy_pass)
        {
            update_setting("proxy_pass", $proxy_pass);
        }
    }

    // If all setting values were saved successfully
    if (!$error)
    {
        // Display an alert
        set_alert(true, "good", "The settings were updated successfully.");
    }
}

// If the Debug tab was submitted
if (isset($_POST['update_debug_settings']))
{
    // Set the error to false
    $error = false;

    // Update the debug logging
    $debug_logging = isset($_POST['debug_logging']) ? 1 : 0;
    $current_debug_logging = get_setting("debug_logging");
    if ($debug_logging != $current_debug_logging)
    {
        update_setting("debug_logging", $debug_logging);
    }

    // If all setting values were saved successfully
    if (!$error)
    {
        // Display an alert
        set_alert(true, "good", "The settings were updated successfully.");
    }
}

// Get the max upload size setting
$simplerisk_max_upload_size = get_setting('max_upload_size');

?>
<div class="row">
    <div class="col-12">
        <div class="mt-2">
            <nav class="nav nav-tabs">
                <a class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                    <?= $escaper->escapeHtml($lang['General']); ?> 
                </a>
                <a class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file" type="button" role="tab" aria-controls="file" aria-selected="false">
                    <?= $escaper->escapeHtml($lang['FileUpload']); ?>
                </a>
                <a class="nav-link" id="mail-tab" data-bs-toggle="tab" data-bs-target="#mail" type="button" role="tab" aria-controls="mail" aria-selected="false">
                    <?= $escaper->escapeHtml($lang['Mail']); ?>
                </a>
                <a class="nav-link" id="backups-tab" data-bs-toggle="tab" data-bs-target="#backups" type="button" role="tab" aria-controls="backups" aria-selected="false">
                    <?= $escaper->escapeHtml($lang['Backups']); ?>
                </a>
                <a class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                    <?= $escaper->escapeHtml($lang['Security']); ?>
                </a>
                <a class="nav-link" id="debug-tab" data-bs-toggle="tab" data-bs-target="#debug" type="button" role="tab" aria-controls="debug" aria-selected="false">
                    <?= $escaper->escapeHtml($lang['Debugging']); ?>
                </a>
            </nav>
        </div>
        <div class="tab-content cust-tab-content" id="myTabContent" >
            <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form name="general_settings" method="post" action="">
                    <div class="card-body my-2 border">
                        <h4 class="page-title"><?= $escaper->escapeHtml($lang['UserInterface']);?></h4>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="form-check mr-sm-2">
                                        <input <?php if($escaper->escapeHtml(get_setting('plan_projects_show_all')) == 1){ echo "checked"; } ?> name="plan_projects_show_all" class="form-check-input" size="2" value="90" id="plan_projects_show_all" type="checkbox">
                                        <label class="form-check-label mb-0" for="plan_projects_show_all"  >
                                        &nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['ShowAllRisksForPlanProjects']); ?>
                                        </label>
                                </div>
                                <div class="form-check mr-sm-2">
                                    <input <?php if($escaper->escapeHtml(get_setting('auto_verify_new_assets')) == 1){ echo "checked"; } ?> name="auto_verify_new_assets" class="form-check-input" size="2" value="90" id="auto_verify_new_assets" type="checkbox">
                                    <label class="form-check-label mb-0" for="auto_verify_new_assets"  >
                                        &nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['AutomaticallyVerifyNewAssets']); ?>
                                    </label>
                                </div>
                                <div class="form-check mr-sm-2">
                                    <input <?php if($escaper->escapeHtml(get_setting('exception_update_resets_approval')) == 1){ echo "checked"; } ?> name="exception_update_resets_approval" class="form-check-input" size="2" value="90" id="exception_update_resets_approval" type="checkbox" >
                                    <label class="form-check-label mb-0" for="exception_update_resets_approval">
                                        &nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['ExceptionUpdateResetsApproval']); ?>
                                    </label>
                                </div>
                                <div class="form-check mr-sm-2">
                                    <input <?php if($escaper->escapeHtml(get_setting('risk_mapping_required')) == 1){ echo "checked"; } ?> name="risk_mapping_required" class="form-check-input" size="2" value="90" id="risk_mapping_required" type="checkbox">
                                    <label class="form-check-label mb-0"for="risk_mapping_required">&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['RequireRiskMappingForAllRisks']); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label><?php echo $escaper->escapeHtml($lang['AlertTimeout']); ?>:</label>
                                <select class="form-select" id="alert_timeout" name="alert_timeout">
                                    <?php
                                        // Create the list of possible timeouts
                                    $possible_timeouts = array(
                                        "5"     => _lang('TimeoutXSeconds', array('timeout' => '5')),
                                        "10"    => _lang('TimeoutXSeconds', array('timeout' => '10')),
                                        "15"    => _lang('TimeoutXSeconds', array('timeout' => '15')),
                                        "30"    => _lang('TimeoutXSeconds', array('timeout' => '30')),
                                        "60"    => _lang('TimeoutXSeconds', array('timeout' => '60')),
                                        "0"     => $lang['StayUntilClicked'],
                                    );
                                    // Get the current value
                                    $alert_timeout = get_setting("alert_timeout", "5");
                                    // For each possible timeout
                                    foreach($possible_timeouts as $key => $value)
                                    {
                                        echo "<option value=\"" . $key . "\"" . ($key == $alert_timeout ? " selected" : "") . ">" . $escaper->escapeHtml($value) . "</option>\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body my-2 border">
                        <h4 class="page-title"><?= $escaper->escapeHtml($lang['DefaultValues']);?></h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultLanguage']); ?>:</label>
                                    <?php create_dropdown("languages", get_value_by_name("languages", $escaper->escapeHtml(get_setting("default_language"))), null, false); ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultTimezone']); ?>:</label>
                                    <select class="form-select" id="default_timezone" name="default_timezone">
                                        <?php
                                        // Get the list of timezones
                                        $timezones = timezone_list();
                                        // Get the defeault timezone
                                        $default_timezone = $escaper->escapeHtml(get_setting("default_timezone"));
                                        // For each timezone
                                        foreach($timezones as $key => $value)
                                        {
                                            echo "<option value=\"" . $key . "\"" . ($key == $default_timezone ? " selected" : "") . ">" . $value . "</option>\n";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultDateFormat']); ?>:</label>
                                    <?php
                                        // Get the defeault date format
                                        $default_date_format = $escaper->escapeHtml(get_setting("default_date_format"));
                                        create_dropdown("date_formats", $default_date_format, "default_date_format", false);
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultRiskScore']); ?>:</label>
                                    <input value="<?php echo $escaper->escapeHtml(get_setting('default_risk_score')); ?>" name="default_risk_score" id="default_risk_score" type="number" min="0" step="0.1" max="10" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['MaximumRiskSubjectLength']); ?>:</label>
                                    <input value="<?php echo $escaper->escapeHtml(get_setting('maximum_risk_subject_length')); ?>" name="maximum_risk_subject_length" id="maximum_risk_subject_length" type="number" min="1" step="1" max="1000" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultInitiatedAuditStatus']); ?>:</label>
                                    <?php create_dropdown("test_status", $escaper->escapeHtml(get_setting("initiated_audit_status")), "initiated_audit_status", true, false, false, "", "--", 0); ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultClosedAuditStatus']); ?>:</label>
                                    <?php create_dropdown("test_status", $escaper->escapeHtml(get_setting("closed_audit_status")), "closed_audit_status", false, false, false, "required"); ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultCurrencySymbol']); ?>:</label>
                                    <input type="text" name="default_currency" maxlength="3" value="<?php echo $escaper->escapeHtml(get_setting("currency")); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultAssetValuation']); ?>:</label>
                                    <?php
                                        // Get the default asset valuation
                                        $default = get_default_asset_valuation();
                                        // Create the asset valuation dropdown
                                        create_asset_valuation_dropdown("default_asset_valuation", $default);
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultUserRole']); ?>:</label>
                                    <?php
                                        // Create role dropdown
                                        create_dropdown("role", $escaper->escapeHtml(get_default_role_id()), "default_user_role");
                                    ?>  
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultCurrentMaturity']); ?>:</label>
                                    <?php
                                        // Create default current maturity dropdown
                                        create_dropdown("control_maturity", $escaper->escapeHtml(get_setting("default_current_maturity")), "default_current_maturity", false);
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['DefaultDesiredMaturity']); ?>:</label>
                                    <?php
                                        // Create default desired maturity dropdown
                                        create_dropdown("control_maturity", $escaper->escapeHtml(get_setting("default_desired_maturity")), "default_desired_maturity", false);
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['NextReviewDateUses']); ?>:</label>
                                    <select name="next_review_date_uses" class="form-select">
                                        <option value="InherentRisk" <?php echo $escaper->escapeHtml(get_setting("next_review_date_uses")) == "InherentRisk" ? "selected" : ""; ?> ><?php echo $escaper->escapeHtml($lang['InherentRisk']); ?></option>
                                        <option value="ResidualRisk" <?php echo $escaper->escapeHtml(get_setting("next_review_date_uses")) == "ResidualRisk" ? "selected" : ""; ?>><?php echo $escaper->escapeHtml($lang['ResidualRisk']); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['SimpleriskBaseUrl']); ?>:</label>
                                    <input type="text" name="simplerisk_base_url" value="<?php echo $escaper->escapeHtml(get_setting("simplerisk_base_url")); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <?php $risk_appetite = (float)get_setting("risk_appetite", 0);?>
                                    <div class="slider-progress-values">
                                        <label><?php echo $escaper->escapeHtml($lang['RiskAppetite']); ?>:</label>
                                        <span id="risk_appetite_display" style="border:0; font-weight:bold;"><?php echo $escaper->escapeHtml($risk_appetite); ?></span>
                                        <span id="risk_appetite_color" class="risk-color" style="background-color:#ff0000"></span>
                                    </div>
                                    <input type="hidden" id="risk_appetite" name="risk_appetite" value="<?php echo $escaper->escapeHtml($risk_appetite); ?>">
                                    <?php
                                        $risk_levels = get_risk_levels();
    
                                        if ((int)$risk_levels[0]['value'] > 0) {
                                            array_unshift($risk_levels, array('value' => 0.0, 'name' => 'Insignificant', 'color' => 'white', 'display_name' => $lang['Insignificant']));
                                        }
    
                                        $ranges = [];
                                        $number_of_levels = count($risk_levels);
                                        foreach($risk_levels as $key => $level) {
                                            $next_key = ($key + 1 < $number_of_levels) ? $key + 1 : null;
                                            $ranges[] = array('display_name' => $level['display_name'],
                                                                'color' => $level['color'],
                                                                'range' => [(int)$level['value'], $next_key ? $risk_levels[$next_key]['value'] - 0.1 : 9999]);
                                        }
    
                                        foreach($ranges as $key => $range) {
                                            if ($key == 0)
                                                $slider_bg_grad = "{$range['color']} " . ($range['range'][1] * 10) . "%";
                                            elseif ($key == count($ranges) - 1) {
                                                $slider_bg_grad .= ", {$range['color']} " . ($ranges[$key - 1]['range'][1] * 10) . "%, {$range['color']} 100%";
                                            } else {
                                                $slider_bg_grad .= ", {$range['color']} " . ($ranges[$key - 1]['range'][1] * 10) . "%, {$range['color']} " . ($range['range'][1] * 10) . "%";
                                            }
                                        }
                                    ?>
                                    <div id="slider" style="margin-top: 10px; background-image: linear-gradient(90deg, <?php echo $slider_bg_grad; ?>); background-size: 100% 100%;"></div>
                                        
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div>
                                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_general_settings" class="btn btn-submit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="file" role="tabpanel" aria-labelledby="file-tab">
                <form name="filetypes" method="post" action="">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body my-2 border">
                                <h4 class="page-title"><?= $escaper->escapeHtml($lang['AllowedFileTypes']);?></h4>
                                <div class="row" style="align-items:flex-end">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label><?php echo $escaper->escapeHtml($lang['AddNewFileTypeOf']); ?>:</label>
                                            <input name="new_file_type" type="text" maxlength="250" size="10" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label><?php echo $escaper->escapeHtml($lang['WithExtension']); ?>:</label>
                                           <input name="file_type_ext" type="text" maxlength="10" size="10" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                           <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Add']); ?>" name="add_file_type" class="btn btn-submit form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="align-items:flex-end">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label><?php echo $escaper->escapeHtml($lang['DeleteCurrentFileTypeOf']); ?>:</label>
                                            <?php create_dropdown("file_types"); ?>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                           <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Delete']); ?>" name="delete_file_type" class="btn btn-dark form-control" style="margin-top: 30px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="align-items:flex-end">
                                    <div class="col-8">
                                        <div class="">
                                            <label><?php echo $escaper->escapeHtml($lang['DeleteCurrentExtensionOf']); ?>:</label>
                                            <?php create_dropdown("file_type_extensions"); ?>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                    <div class="col-2">
                                        <div class="">
                                           <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Delete']); ?>" name="delete_file_extension" class="btn btn-dark form-control" style="margin-top: 30px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <div class="card-body my-2 border flex-grow-1">
                                <h4 class="page-title"><?= $escaper->escapeHtml($lang['MaximumUploadFileSize']);?></h4>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                           <input name="size" type="number" maxlength="50" size="20" value="<?php echo $escaper->escapeHtml(get_setting('max_upload_size')); ?>" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                           <?php echo $escaper->escapeHtml($lang['Bytes']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <?php
                                        // If the max upload size for SimpleRisk is bigger than the PHP max upload size
                                        if($simplerisk_max_upload_size > php_max_allowed_values()){
                                            echo "<p style=\"color: red;\">" . $escaper->escapeHtml($lang['WarnPHPUploadSize']) . '<br /></p>';
                                        }
                                        // If the max upload size for SimpleRisk is bigger than the MySQL max upload size
                                        if ($simplerisk_max_upload_size > mysql_max_allowed_values())
                                        {
                                            echo "<p style=\"color: red;\">" . $escaper->escapeHtml($lang['WarnMySQLUploadSize']) . '<br /></p>';
                                        }
                                        ?>
                                        <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_max_upload_size" class="btn btn-submit"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="mail" role="tabpanel" aria-labelledby="mail-tab">
                <form name="mail_settings" method="post" action="">
                    <?php
                        // Get the mail settings
                        $mail = get_mail_settings();
                        $transport = $mail['phpmailer_transport'];
                        $from_email = $mail['phpmailer_from_email'];
                        $from_name = $mail['phpmailer_from_name'];
                        $replyto_email = $mail['phpmailer_replyto_email'];
                        $replyto_name = $mail['phpmailer_replyto_name'];
                        $prepend = $mail['phpmailer_prepend'];
                        $host = $mail['phpmailer_host'];
                        $smtpautotls = $mail['phpmailer_smtpautotls'];
                        $smtpauth = $mail['phpmailer_smtpauth'];
                        $username = $mail['phpmailer_username'];
                        $password = $mail['phpmailer_password'];
                        $encryption = $mail['phpmailer_smtpsecure'];
                        $port = $mail['phpmailer_port'];
                    ?>
                    <div class="card-body my-2 border">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['TransportAgent']); ?>:</label>
                                    <select name="transport" id="transport" onchange="javascript: dropdown_transport()" class="form-select">
                                      <option value="sendmail"<?php echo ($transport=="sendmail") ? " selected" : ""; ?>>sendmail</option>
                                      <option value="smtp"<?php echo ($transport=="smtp") ? " selected" : ""; ?>>smtp</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Prepend']); ?>:</label>
                                    <input type="text" name="prepend" value="<?php echo $escaper->escapeHTML($prepend); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['FromName']); ?>:</label>
                                    <input type="text" name="from_name" value="<?php echo $escaper->escapeHTML($from_name); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['ReplyToName']); ?>:</label>
                                    <input type="text" name="replyto_name" value="<?php echo $escaper->escapeHTML($replyto_name); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['FromEmail']); ?>:</label>
                                    <input type="email" name="from_email" value="<?php echo $escaper->escapeHTML($from_email); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['ReplyToEmail']); ?>:</label>
                                    <input type="email" name="replyto_email" value="<?php echo $escaper->escapeHTML($replyto_email); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Host']); ?>:</label>
                                    <input type="text" name="host" value="<?php echo $escaper->escapeHTML($host); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Port']); ?>:</label>
                                    <input type="number" name="port" value="<?php echo $escaper->escapeHTML($port); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                            <div class="col-6">
                                <div class="form-check mr-sm-2 form-group">
                                    <input  type="checkbox" name="smtpautotls" id="smtpautotls" <?php echo ($smtpautotls == "true") ? "checked=\"yes\" " : ""?> class="form-check-input"/>
                                   <label class="form-check-label mb-0"><?php echo $escaper->escapeHtml($lang['EnableTLSEncryptionAutomaticallyIfAServerSupportsIt']); ?></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check mr-sm-2 form-group">
                                    <input  type="checkbox" name="smtpauth" id="smtpauth" onchange="javascript: checkbox_smtpauth()" <?php echo ($smtpauth == "true") ? "checked=\"yes\" " : ""?> class="form-check-input"/>
                                   <label class="form-check-label mb-0"><?php echo $escaper->escapeHTML($lang['SMTPAuthentication']); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row smtpauth"<?php echo ($transport=="sendmail" || $smtpauth=="false") ? " style=\"display: none;\"" : "" ?>>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Username']); ?>:</label>
                                    <input type="text" name="username" value="<?php echo $escaper->escapeHTML($username); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Password']); ?>:</label>
                                    <input type="password" name="password" value="" placeholder="Change Current Value" class="form-control"/>
                                </div>
                            </div>
                        </div>
                         <div class="row smtpauth"<?php echo ($transport=="sendmail" || $smtpauth=="false") ? " style=\"display: none;\"" : "" ?>>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><?php echo $escaper->escapeHtml($lang['Encryption']); ?>:</label>
                                    <select name="encryption" id="encryption" class="form-select">
                                      <option value="none"<?php echo ($encryption=="none") ? " selected" : ""; ?>>None</option>
                                      <option value="tls"<?php echo ($encryption=="tls") ? " selected" : ""; ?>>TLS</option>
                                      <option value="ssl"<?php echo ($encryption=="ssl") ? " selected" : ""; ?>>SSL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                 <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Submit']); ?>" name="submit_mail" class="btn btn-submit"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-body my-2 border">
                        <u><strong><?php echo $escaper->escapeHtml($lang['TestMailSettings']); ?></strong></u></td>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" name="email" size="50" placeholder="<?php echo $escaper->escapeHtml($lang['EmailAddress']); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="">
                                   <button type="submit" name="test_mail_configuration" class="btn btn-submit"><?php echo $escaper->escapeHtml($lang['Send']); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
             <!----- backups tab --------->
            <div class="tab-pane" id="backups" role="tabpanel" aria-labelledby="backups-tab">
                <?php
                    // Get the backup settings
                    $backup_auto = get_setting('backup_auto');
                    $backup_path= get_setting('backup_path');
                    $phpExecutablePath = getPHPExecutableFromPath();
                ?>
                <div class="card-body my-2 border">
                    <h4 class="page-title"><?= $escaper->escapeHtml($lang['Instructions']); ?></h4>
                    <div class="row">
                        <div class="col-12">
                            <?= $escaper->escapeHtml($lang['PlaceTheFollowingInYourCrontabToRunAutomatically']); ?>:
                            <br>
                            * * * * * <?php echo $escaper->escapeHtml($phpExecutablePath ? $phpExecutablePath : $lang['PathToPhpExecutable']); ?> <?php echo (strncasecmp(PHP_OS, 'WIN', 3) == 0 ? "" : "-f") ?> <?php echo realpath(__DIR__ . '/../cron/cron.php'); ?> > /dev/null 2>&1
                        </div>
                    </div>
                </div>
                <form name="backups_settings" method="post" action="" class="block-on-submit">
                    <div class="card-body my-2 border">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check mr-sm-2">
                                    <input type="checkbox" name="backup_auto" id="backup_auto" <?php echo ($backup_auto == "true") ? "checked=\"yes\" " : ""?> class="form-check-input" >
                                    <label class="form-check-label mb-0" for="backup_auto">
                                        &nbsp;&nbsp;<?php echo $escaper->escapeHTML($lang['AutomaticallyBackupThisSimpleRiskInstance']); ?>
                                    </label>
                                    <p style="color: red;"><?= $escaper->escapeHtml($lang['ForSecurityReasonsBackupOutsideWebRoot']); ?></p>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label><?= $escaper->escapeHtml($lang['BackupLocation']); ?>:</label>
                                            <input type="text" name="backup_path" value="<?= $escaper->escapeHtml($backup_path); ?>" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label><?= $escaper->escapeHTML($lang['BackupSchedule']); ?>:&nbsp;&nbsp;</label>
                                            <select name="backup_schedule" id="backup_schedule" onchange="javascript: dropdown_transport()" class="form-select">
                                              <option value="hourly"<?= (get_setting('backup_schedule') == "hourly") ? " selected" : ""; ?>><?= $escaper->escapeHTML($lang['Hourly']); ?></option>
                                              <option value="daily"<?= (get_setting('backup_schedule') == "daily") ? " selected" : ""; ?>><?= $escaper->escapeHTML($lang['Daily']); ?></option>
                                              <option value="weekly"<?= (get_setting('backup_schedule') == "weekly") ? " selected" : ""; ?>><?= $escaper->escapeHTML($lang['Weekly']); ?></option>
                                              <option value="monthly"<?= (get_setting('backup_schedule') == "monthly") ? " selected" : ""; ?>><?= $escaper->escapeHTML($lang['Monthly']); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="align-items:flex-end">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label><?= $escaper->escapeHTML($lang['RemoveBackupsAfter']); ?>&nbsp;</label>
                                            <input value="<?= $escaper->escapeHTML(get_setting('backup_remove')); ?>" name="backup_remove" id="backup_remove" type="number"min="1" max="365" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                        &nbsp;<?= $escaper->escapeHTML($lang['days']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="">
                                           <button type="submit" name="submit_backup" class="btn btn-dark"><?php echo $escaper->escapeHtml($lang['Save']); ?></button>
                                           <button type="submit" name="submit_and_backup_now" class="btn btn-submit"><?php echo $escaper->escapeHtml($lang['SaveAndBackupNow']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body my-2 border">
                    <?php
                    // Open the database connection
                    $db = db_open();
                    // Get the list of backups ordered by timestamp
                    $stmt = $db->prepare("SELECT * FROM `backups` ORDER BY `timestamp` DESC;");
                    $stmt->execute();
                    $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // Close the database connection
                    db_close($db); ?>
                    <h4 class="page-title mb-1"><?php echo $escaper->escapeHtml($lang['Backups']); ?></h4>
                    <p style="color: red;"><?php echo $escaper->escapeHtml($lang['PrivateTmpMessage']); ?></p>
                    <table class="table border m-b-0">
                       <thead>
                            <tr>
                                <td>
                                    <u><?php echo $escaper->escapeHtml($lang['BackupDate']); ?></u>
                                </td>
                                <td width="20px">&nbsp;</td>
                                <?php
                                // If this is not a hosted customer
                                if (get_setting('hosting_tier') == false){
                                    echo "<td><u>" . $escaper->escapeHtml($lang['ApplicationBackup']) . "</u></td>\n";
                                    echo "<td width=\"20px\">&nbsp;</td>\n";
                                }else{
                                    echo "<td width=\"0px\">&nbsp;</td>\n";
                                    echo "<td width=\"0px\">&nbsp;</td>\n";
                                }
                                ?>
                                <td>
                                    <u><?php echo $escaper->escapeHtml($lang['DatabaseBackup']); ?></u>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // For each backup
                            foreach ($backups as $backup){
                                // Display the backup information
                                echo "<tr>\n";
                                echo "  <td>" . $escaper->escapeHtml($backup['timestamp']) . "</td>\n";
                                echo "  <td>&nbsp;</td>\n";
                                // If this is not a hosted customer
                                if (get_setting('hosting_tier') == false){
                                    // Display the Download link for the application backup
                                    echo "<td><a target=\"_blank\" href=\"download_backup.php?type=app&id=" . $escaper->escapeHtml($backup['random_id']) . "\">" . $escaper->escapeHtml($lang['Download']) . "</a></td>\n";
                                    echo "  <td>&nbsp;</td>\n";
                                }else{
                                    // If this is a hosted customer
                                  // Do not display a Download link for the application backup
                                    echo "<td width=\"0px\">&nbsp;</td>\n";
                                    echo "<td width=\"0px\">&nbsp;</td>\n";
                                }
    
                                echo "<td><a target=\"_blank\" href=\"download_backup.php?type=db&id=" . $escaper->escapeHtml($backup['random_id']) . "\">" . $escaper->escapeHtml($lang['Download']) . "</a></td>\n";
                                echo "</tr>\n";
                            }?>
                        </tbody>
                    </table> 
                </div>
            </div>
            <!----- security tab --------->
            <div class="tab-pane" id="security" role="tabpanel" aria-labelledby="security-tab">
                <form name="security_settings" method="post" action="">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body my-2 border">
                                <h4 class="page-title">
                                    <?php echo $escaper->escapeHtml($lang['UserSessions']); ?>
                                </h4>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label><?php echo $escaper->escapeHtml($lang['SessionActivityTimeout']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</label>
                                            <input name="session_activity_timeout" id="session_activity_timeout" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_activity_timeout")); ?>" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label><?php echo $escaper->escapeHtml($lang['SessionAbsoluteTimeout']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</label>
                                            <input name="session_absolute_timeout" id="session_absolute_timeout" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_absolute_timeout")); ?>" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <div class="card-body my-2 border flex-grow-1">
                                <h4 class="page-title"><?php echo $escaper->escapeHtml($lang['Security']); ?></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check mr-sm-4">
                                            <input <?php if($escaper->escapeHtml(get_setting('content_security_policy')) == 1){ echo "checked"; } ?> name="content_security_policy" size="2" value="90" id="content_security_policy" type="checkbox"  class="form-check-input">
                                            <label  for="content_security_policy" class="form-check-label mb-0" >&nbsp;&nbsp; 
                                               <?php echo $escaper->escapeHtml($lang['EnableCSP']); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row mt-2">
                                    <div class="col-12"><font color='red'><?php echo $escaper->escapeHtml($lang['SSLSecurityCheckWarning']); ?></font></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check mr-sm-4">
                                            <input <?php if($escaper->escapeHtml(get_setting('ssl_certificate_check_simplerisk')) == 1){ echo "checked"; } ?> name="ssl_certificate_check_simplerisk"  class="form-check-input" size="2" value="90" id="ssl_certificate_check_simplerisk" type="checkbox">  <label for="ssl_certificate_check_simplerisk" class="form-check-label mb-0"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableSSLCertificateCheckSimpleRisk']); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check mr-sm-4">
                                           <input <?php if($escaper->escapeHtml(get_setting('ssl_certificate_check_external')) == 1){ echo "checked"; } ?> name="ssl_certificate_check_external" class="form-check-input" size="2" value="90" id="ssl_certificate_check_external" type="checkbox">  <label for="ssl_certificate_check_external"  class="form-check-label mb-0">&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableSSLCertificateCheckExternal']); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-2 border">
                        <h4 class="page-title"><?php echo $escaper->escapeHtml($lang['Proxy']); ?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mr-sm-4">
                                    <input <?php if($escaper->escapeHtml(get_setting('proxy_web_requests')) == 1){ echo "checked"; } ?> name="proxy_web_requests" id="proxy_web_requests_checkbox" type="checkbox" onclick="update_proxy()"  class="form-check-input">
                                    <label  for="proxy_web_requests_checkbox" class="form-check-label mb-0" >&nbsp;&nbsp; 
                                       <?php echo $escaper->escapeHtml($lang['ProxyWebRequests']); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" id="proxy_verify_ssl_certificate_row" <?php echo (get_setting('proxy_web_requests') != 1 ? " style='display: none;'" : "");     ?>>
                            <div class="col-md-6">
                                <div class="form-check mr-sm-4">
                                    <input <?php if($escaper->escapeHtml(get_setting('proxy_verify_ssl_certificate')) == 1){ echo "checked"; } ?> name="proxy_verify_ssl_certificate" id="proxy_verify_ssl_certificate_checkbox" type="checkbox" onclick="update_proxy()" class="form-check-input">
                                     <label for="proxy_verify_ssl_certificate_checkbox" class="form-check-label mb-0" >&nbsp;&nbsp; 
                                        <?php echo $escaper->escapeHtml($lang['VerifySSLCertificate']); ?>
                                     </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="proxy_host_row" <?php echo (get_setting('proxy_web_requests') != 1 ? " style='display: none;'" : ""); ?>>
                                <div class="form-group">
                                    <label>
                                        <?php echo $escaper->escapeHtml($lang['ProxyHostname']); ?>:
                                    </label>
                                    <input name="proxy_host" id="proxy_host" type="text" value="<?php echo $escaper->escapeHtml(get_setting("proxy_host")); ?>"class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-6" id="proxy_port_row" <?php echo (get_setting('proxy_web_requests') != 1 ? " style='display: none;'" : ""); ?>>
                                <div class="form-group">
                                    <label>
                                        <?php echo $escaper->escapeHtml($lang['ProxyPort']); ?>:
                                    </label>
                                    <input name="proxy_port" id="proxy_port" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("proxy_port")); ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" id="proxy_authenticated_row" <?php echo (get_setting('proxy_web_requests') != 1 ? " style='display: none;'" : "");  ?>>
                            <div class="col-md-6">
                                <div class="form-check mr-sm-4">
                                    <input <?php if($escaper->escapeHtml(get_setting('proxy_authenticated')) == 1){ echo "checked"; } ?> name="proxy_authenticated" id="proxy_authenticated_checkbox" type="checkbox" onclick="update_proxy()"  class="form-check-input">
                                    <label  for="proxy_authenticated_checkbox" class="form-check-label mb-0" >&nbsp;&nbsp; 
                                       <?php echo $escaper->escapeHtml($lang['AuthenticatedProxy']); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"  id="proxy_user_row" <?php echo (get_setting('proxy_web_requests') != 1 || get_setting('proxy_authenticated') != 1 ? " style='display: none;'" : ""); ?>>
                                <div class="form-group">
                                    <label>
                                        <?php echo $escaper->escapeHtml($lang['ProxyUsername']); ?>:
                                    </label>
                                    <input  name="proxy_user" id="proxy_user" type="text" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("proxy_user")); ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-6" id="proxy_pass_row" <?php echo (get_setting('proxy_web_requests') != 1 || get_setting('proxy_authenticated') != 1 ? " style='display: none;'" : ""); ?>>
                                <div class="form-group">
                                    <label>
                                       <?php echo $escaper->escapeHtml($lang['ProxyPassword']); ?>:
                                    </label>
                                    <input  name="proxy_pass" id="proxy_pass" type="password" size="20px" value="XXXXXXXXXX" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body mb-2 border">
                                <button type="submit" name="update_security_settings" class="btn btn-submit"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="debug" role="tabpanel" aria-labelledby="debug-tab">
                <form name="debug_settings" method="post" action="">
                    <div class="card-body my-2 border">
                        <h4 class="page-title"><?= $escaper->escapeHtml($lang['Debugging']); ?></h4>
                        <div class="row">
                            <div class="col-6 form-group">
                                <div class="form-check mr-sm-4">
                                    <input <?php if($escaper->escapeHtml(get_setting('debug_logging')) == 1){ echo "checked"; } ?> name="debug_logging" id="debug_logging" type="checkbox"  class="form-check-input" size="2" value="90">
                                    <label class="form-check-label mb-0"  for="debug_logging">&nbsp;&nbsp; <?= $escaper->escapeHtml($lang['EnableDebugLogging']); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <p><strong>Note: </strong>We no longer configure a debug log file location and all debug logs will go into the error log with a "[SIMPLERISK:DEBUG]" value appended to the message.</p>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" name="update_debug_settings" class="btn btn-submit"><?= $escaper->escapeHtml($lang['Update']); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    <?php echo "var ranges = " . json_encode($ranges) . ";"; ?>
    function handleValueInRange(value) {
        var index, len;
        for (index = 0, len = ranges.length; index < len; ++index) {
            if (ranges[index]['range'][0] <= value && ranges[index]['range'][1] >= value) {
                $("#risk_appetite_display").text(ranges[index]['display_name'] + " (" + value + ")");
                $("#risk_appetite").val(value);
                $("#risk_appetite_color").css('background-color', ranges[index]['color']);

                return;
            }
        }
    }

    $(document).ready(function() {
        $("#slider").slider({
            value:<?php echo $risk_appetite * 10; ?>,
            min: 0,
            max: 100,
            step: 1,
            create: function() {
                handleValueInRange($("#slider").slider("value") / 10);
            },
            slide: function(event, ui) {
                handleValueInRange(ui.value / 10);
            }
        });
    });

    function update_proxy()
    {
        var proxy_web_requests_checkbox = document.getElementById("proxy_web_requests_checkbox");
        var proxy_verify_ssl_certificate_checkbox = document.getElementById("proxy_verify_ssl_certificate_checkbox");
        var proxy_verify_ssl_certificate_row = document.getElementById("proxy_verify_ssl_certificate_row");
        var proxy_authenticated_row = document.getElementById("proxy_authenticated_row");
        var proxy_authenticated_checkbox = document.getElementById("proxy_authenticated_checkbox");
        var proxy_host_row = document.getElementById("proxy_host_row");
        var proxy_port_row = document.getElementById("proxy_port_row");
        var proxy_user_row = document.getElementById("proxy_user_row");
        var proxy_pass_row = document.getElementById("proxy_pass_row");

        if (proxy_web_requests_checkbox.checked == true)
        {
            proxy_verify_ssl_certificate_row.style.display = "";
            proxy_host_row.style.display = "";
            proxy_port_row.style.display = "";
            proxy_authenticated_row.style.display = "";

            if (proxy_authenticated_checkbox.checked == true)
            {
                proxy_user_row.style.display = "";
                proxy_pass_row.style.display = "";
            }
            else
            {
                proxy_user_row.style.display = "none";
                proxy_pass_row.style.display = "none";
            }
        }
        else
        {
            proxy_verify_ssl_certificate_row.style.display = "none";
            proxy_host_row.style.display = "none";
            proxy_port_row.style.display = "none";
            proxy_authenticated_row.style.display = "none";
            proxy_user_row.style.display = "none";
            proxy_pass_row.style.display = "none";
        }
    }
</script>
<?php
    // Render the footer of the page. Please don't put code after this part.
    render_footer();
?>