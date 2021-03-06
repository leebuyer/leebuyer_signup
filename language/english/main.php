<?php
xoops_loadLanguage('main', 'tadtools');
//前、後台所有檔案（除了xoops_version.php）

define('_MD_LEEBUYER_SIGNUP_ID', 'number');
define('_MD_LEEBUYER_SIGNUP_TITLE', 'Event Name');
define('_MD_LEEBUYER_SIGNUP_DETAIL', 'Event Description');
define('_MD_LEEBUYER_SIGNUP_ACTION_DATE', 'Event Date');
define('_MD_LEEBUYER_SIGNUP_NUMBER', 'Number of applicants');
define('_MD_LEEBUYER_SIGNUP_NUMBER_OF_APPLIED', 'Number of reported celebrities');
define('_MD_LEEBUYER_SIGNUP_END_DATE', 'Enrollment Deadline');
define('_MD_LEEBUYER_SIGNUP_END_DATE_COL', 'Enrollment Deadline');
define('_MD_LEEBUYER_SIGNUP_STATUS', 'Enrollment Status');
define('_MD_LEEBUYER_SIGNUP_CANDIDATES_QUOTA', 'Number of available candidates');
define('_MD_LEEBUYER_SIGNUP_SETUP', 'Field Settings');
define('_MD_LEEBUYER_SIGNUP_ENABLE', 'Enabled or not');
define('_MD_LEEBUYER_SIGNUP_UPLOADS', 'Upload Attachments');
define('_MD_LEEBUYER_SIGNUP_APPLY_NOW', 'Sign up now');
define('_MD_LEEBUYER_SIGNUP_CANDIDATE', 'waitlist');
define('_MD_LEEBUYER_SIGNUP_ACCEPT', 'Acceptance');
define('_MD_LEEBUYER_SIGNUP_NOT_ACCEPT', 'Not Admitted');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_NOT_YET', 'Not yet set');
define('_MD_LEEBUYER_SIGNUP_ANNOUNCEMENT_NOT_YET', 'Not yet published');
define('_MD_LEEBUYER_SIGNUP_APPLY_LIST', 'Application List');
define('_MD_LEEBUYER_SIGNUP_APPLY_DATE', 'Enrollment Date');
define('_MD_LEEBUYER_SIGNUP_IDENTITY', 'identity');
define('_MD_LEEBUYER_SIGNUP_STORE_SUCCESS', 'Successful event creation!');
define('_MD_LEEBUYER_SIGNUP_UPDATE_SUCCESS', 'Event was successfully modified!');
define('_MD_LEEBUYER_SIGNUP_DESTROY_SUCCESS', 'Deleted activity successfully!');

define('_MD_LEEBUYER_SIGNUP_DESTROY_ACTION', 'Deleted activity');
define('_MD_LEEBUYER_SIGNUP_EDIT_ACTION', 'Edit Activity');
define('_MD_LEEBUYER_SIGNUP_EXPORT_HTML', 'Export HTML');
define('_MD_LEEBUYER_SIGNUP_APPLY_SUCCESS', 'Successfully added an entry!');
define('_MD_LEEBUYER_SIGNUP_APPLY_UPDATE_SUCCESS', 'Successfully modified enrollment data!');
define('_MD_LEEBUYER_SIGNUP_APPLY_DESTROY_SUCCESS', 'Successfully deleted the enrollment data!');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_SUCCESS', 'Successfully set admission status!');
define('_MD_LEEBUYER_SIGNUP_COPY_SUCCESS', 'Successfully copied the activity!');
define('_MD_LEEBUYER_SIGNUP_IMPORT_SUCCESS', 'Successful import of registration data!');
define('_MD_LEEBUYER_SIGNUP_MY_RECORD', 'My enrollment record');
define('_MD_LEEBUYER_SIGNUP_SIGNIN_TABLE', 'Sign-in table');
define('_MD_LEEBUYER_SIGNUP_SIGNIN', 'signature');
define('_MD_LEEBUYER_SIGNUP_ACTION_SETTING', 'Event Settings');
define('_MD_LEEBUYER_SIGNUP_TITLE', 'Event Title');
define('_MD_LEEBUYER_SIGNUP_KEYIN', 'Please enter');
define('_MD_LEEBUYER_SIGNUP_ACTION_LIST', 'Event List');
define('_MD_LEEBUYER_SIGNUP_IN_PROGRESS', 'Enrolling');
define('_MD_LEEBUYER_SIGNUP_CANT_APPLY', 'Unable to enroll');
define('_MD_LEEBUYER_SIGNUP_ADD_ACTION', 'Add activity');
define('_MD_LEEBUYER_SIGNUP_APPLIED_DATA', 'Enrolled Data');
define('_MD_LEEBUYER_SIGNUP_APPLIED_MAX', 'Maximum number of enrolments');
define('_MD_LEEBUYER_SIGNUP_NAME', 'Name');
define('_MD_LEEBUYER_SIGNUP_CHANGE_TO', 'Change to');
define('_MD_LEEBUYER_SIGNUP_EXPORT_SIGNIN_TABLE', 'Generate signature to table');
define('_MD_LEEBUYER_SIGNUP_EXPORT_APPLY_LIST', 'Export signup list');
define('_MD_LEEBUYER_SIGNUP_IMPORT_APPLY_LIST', 'Import Entry List');
define('_MD_LEEBUYER_SIGNUP_IMPORT', 'Import');
define('_MD_LEEBUYER_SIGNUP_DOWNLOAD', 'download');
define('_MD_LEEBUYER_SIGNUP_IMPORT_FILE', 'Import format file');
define('_MD_LEEBUYER_SIGNUP_APPLY_FORM', 'Enrollment Form');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_STATUS', 'Record Status');
define('_MD_LEEBUYER_SIGNUP_SETUP_SIGNIN_TABLE', 'Sign-in table field setting');
define('_MD_LEEBUYER_SIGNUP_DATA_PREVIEW', 'Enrollment Data Preview');
define('_MD_LEEBUYER_SIGNUP_DESTROY_APPLY', 'Cancel Enrollment');
define('_MD_LEEBUYER_SIGNUP_EDIT_APPLY', 'Modify enrollment information');

// class\Tad_signup_data.php
define('_MD_LEEBUYER_SIGNUP_NOADM_CANNOT_USE', 'Non-administrator, you do not have permission to use this function');
define('_MD_LEEBUYER_SIGNUP_CANNOT_BE_MODIFIED', 'No data is available for checking no report, no data can be modified');
define('_MD_LEEBUYER_SIGNUP_END', 'Application is closed, no further application or modification is possible');
define('_MD_LEEBUYER_SIGNUP_CLOSED', 'The application is closed, no more applications or changes can be made');
define('_MD_LEEBUYER_SIGNUP_FULL', 'Enrollment is full, no further enrollment is possible');
define('_MD_LEEBUYER_SIGNUP_CANT_WATCH', 'There is no registration data, can\'t watch');
define('_MD_LEEBUYER_SIGNUP_NO_TITLE', 'No title');
define('_MD_LEEBUYER_SIGNUP_NO_CONTENT', 'No content');
define('_MD_LEEBUYER_SIGNUP_UNABLE_TO_SEND', 'No number, unable to send notification letter');
define('_MD_LEEBUYER_SIGNUP_DESTROY_TITLE', '"%s" cancellation of enrollment notification');
define('_MD_LEEBUYER_SIGNUP_DESTROY_HEAD', '<p>Your registration for the "%s}" event at %s was cancelled at %s by %s. </p>');
define('_MD_LEEBUYER_SIGNUP_DESTROY_FOOT', 'To re-enroll, please link to ');
define('_MD_LEEBUYER_SIGNUP_STORE_TITLE', '"%s" enrollment completion notice');
define('_MD_LEEBUYER_SIGNUP_STORE_HEAD', '<p>Your registration for the "%s" event at %s was completed at %s by %s. </p>');
define('_MD_LEEBUYER_SIGNUP_FOOT', 'For full details, please link to ');
define('_MD_LEEBUYER_SIGNUP_UPDATE_TITLE', 'Notification of "%s" modification of enrollment data');
define('_MD_LEEBUYER_SIGNUP_UPDATE_HEAD', '<p>Your registration for the "%s" event at %s was modified at %s by %s as follows:</p>');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_TITLE', 'Notification of "%s" Enrollment Status');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_HEAD1', '<p>Your application for the "%s" event in %s has been reviewed, <h2 style="color:blue">Congratulations on your acceptance! </h2>Your registration details are as follows:</p>');
define('_MD_LEEBUYER_SIGNUP_ACCEPT_HEAD0', '<p>Your application for the "%s" event in %s was reviewed, and we regret to inform you that due to limited space, <span style="color:red;">you were not accepted. </span>Your registration information is as follows:</p>');
define('_MD_LEEBUYER_SIGNUP_FAILED_TO_SEND', 'Notification letter sent failed!');
define('_MD_LEEBUYER_SIGNUP_UNABLE_TO_OPEN', 'Unable to open');

//*** Translated with www.DeepL.com/Translator (free version) ***
