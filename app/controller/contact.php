<?php

$user = [
	'usertitle'	=> '',
	'fullname'	=> '',
	'mobile_no'	=> '',
	'email'		=> ''
];

if (is_logged()) $user = load_model('superadmin/user')->user($_SESSION['user']['user_id'])->row;

function goodpost()
{
	clean();

	$_SESSION['error'] = [];

	if (!isset($_POST['subject']) || !$_POST['subject'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('subject'));
	}

	if (!isset($_POST['message']) || !$_POST['message'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('message'));
	}
	else
	{
		if (mb_strlen($_POST['message']) > MAX_LEN_MSG)
		{
			$_SESSION['error'][] = sprintf(lang('err_length'),lang('message'), MAX_LEN_MSG);
		}
	}
	
	if (!isset($_POST['usertitle']) || !$_POST['usertitle'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('usertitle'));
	}

	if (!isset($_POST['fullname']) || !$_POST['fullname'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('fullname'));
	}
	else
	{
		if (mb_strlen($_POST['fullname']) > MAX_LEN)
		{
			$_SESSION['error'][] = sprintf(lang('err_length'),lang('fullname'), MAX_LEN);
		}
	}

	if (!isset($_POST['mobile_no']) || !$_POST['mobile_no'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('mobile_no'));
	}
	else
	{
		if (!preg_match('/^01[0-9]{7,}$/', $_POST['mobile_no']))
		{
			$_SESSION['error'][] = sprintf(lang('err_format'),lang('mobile_no'));
		}
	}

	if (!isset($_POST['email']) || !$_POST['email'])
	{
		$_SESSION['error'][] = sprintf(lang('err_required'),lang('email'));
	}
	else
	{
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['error'][] = sprintf(lang('err_format'),lang('email'));
		}
		else
		{
			if (mb_strlen($_POST['email']) > MAX_LEN)
			{
				$_SESSION['error'][] = sprintf(lang('err_length'),lang('email'), MAX_LEN);
			}
		}
	}
	
	if (!isset($_POST['g-recaptcha-response']) || !$_POST['g-recaptcha-response'])
	{
	    $_SESSION['error'][] = sprintf(lang('err_required'),lang('recaptcha'));
	}
	else
	{
	    $json = curl_post(CAPTCHA_URL,['secret' => SECRET_KEY, 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']]);
	    
	    if (!$json)
	    {
	        $_SESSION['error'][] = sprintf(lang('err_system'),lang('recaptcha'));
	    }
	    else
	    {
	        $response = (array)json_decode($json);
	        
	        if (isset($response['error-codes']) && $response['error-codes'])
	        {
	            foreach ($response['error-codes'] as $k => $err)
	            {
	                $_SESSION['error'][] = $err;
	            }
	        }
	        else
	        {
	            if (isset($response['success']) && !$response['success'])
	            {
	                $_SESSION['error'][] = lang('err_recaptcha_failed');
	            }
	        }
	    }
	}

	if ($_SESSION['error']) return false;
	
	return true;
}

$posted = [];

if (isset($_POST['enquiry']))
{
	if (goodpost())
	{
		mysqli_autocommit($mysqli, false);

		try
		{
			$user_id = 0;

			if (is_logged())
			{
				$user_id = $_SESSION['user']['user_id'];
			}

			$time = time();

			/***
			 * enquiry
			 */

			$enquiry = [
				'user_id'		=> $user_id,
				'subject'		=> $_POST['subject'],
				'message'		=> $_POST['message'],
				'usertitle'		=> $_POST['usertitle'],
				'fullname'		=> $_POST['fullname'],
				'mobile_no'		=> $_POST['mobile_no'],
				'email'			=> $_POST['email'],
				'status'		=> 1,
				'created_at'	=> date('Y-m-d H:i:s', $time),
				'updated_at'	=> date('Y-m-d H:i:s', $time)
			];

			db_insert('enquiry', $enquiry);

			/***
			 * send email to recipient
			 */

			$recipient_subject = ($_POST['subject'] == 'Other') ? lang('es_enquiry_recipient') : $_POST['subject'];
				
			$replacements = [
				'message'	=> $_POST['message'],
				'fullname'  => mb_strtoupper($_POST['fullname']),
				'mobile_no'	=> $_POST['mobile_no'],
				'email'		=> $_POST['email']
			];

			$emaildata = [
				'tpl'          => 'enquiry-recipient.tpl',
				'to'           => $_POST['email'],
				'subject'      => $recipient_subject,
				'replacements' => $replacements
			];

			firemail($emaildata);

			/**
			 * send email to Olumis
			 */

			$olumis_subject = ($_POST['subject'] == 'Other') ? lang('es_enquiry_sender') : $_POST['subject'];

			$replacements = [
				'sender_fullname'	=> mb_strtoupper(SMTP_FROM_NAME),
				'message'			=> $_POST['message'],
				'fullname'  		=> mb_strtoupper($_POST['fullname']),
				'mobile_no'			=> $_POST['mobile_no'],
				'email'				=> $_POST['email']
			];

			$emaildata = [
				'tpl'          => 'enquiry-sender.tpl',
				'to'           => SMTP_FROM,
				'subject'      => $olumis_subject,
				'replacements' => $replacements
			];
			
			firemail($emaildata);

			/**
			 * commit DB
			 */
			
			mysqli_commit($mysqli);
			
			/***
			 * done
			 */

			$_SESSION['success'][] = lang('succ_enquiry');
		}

		catch(Exception $e)
		{
			mysqli_rollback($mysqli);

			$_SESSION['error'][] = $e->getMessage();
		}
	}

	$posted = $_POST;
}

if (isset($_POST['get_subject_msg']))
{
	$html = '';

	$fullname = (isset($_SESSION['user']['fullname'])) ? $_SESSION['user']['fullname'] : '';

	clean();

	if ($_POST['subject'] == 'Application: Marketer/Promoter')
	{
		$html = tpl('_ct_marketer.tpl', ['fullname' => $fullname], true);
	}

	if ($_POST['subject'] == 'Application: Programmer')
	{
		$html = tpl('_ct_programmer.tpl', ['fullname' => $fullname], true);
	}

	exit($html);
}

/**
 * subjects
 */

 $subjects = load_model('lists')->get('subject')->rows;

/**
 * user titles
 */

$usertitles = load_model('lists')->get('usertitle')->rows;

if (is_logged()) $config = $_SESSION['config'];

/**
 * active page
 */

if (strpos($_GET['route'], '/') !== false)
{
	list($root,$active) = explode('/', $_GET['route']);
}

/**
 * breadcrumbs
 */

$breadcrumbs[] = [
	'text' 		=> lang('home'),
	'href'		=> u('/'),
	'is_active'	=> false
];

$breadcrumbs[] = [
	'text' 		=> lang('contact'),
	'href'		=> '',
	'is_active'	=> true
];

$js = [u(TEMPLATE.'/js/wazap-contact.js')];

$data = [

	'header'		=> tpl('header.tpl', ['title' => lang('contact'), 'root' => 'contact', 'active' => 'contact']),
	'footer'		=> tpl('footer.tpl', [], false, $js),
	'breadcrumbs'	=> $breadcrumbs,
	'posted'		=> $posted,
	'user'			=> $user,
	'subjects'		=> $subjects,
	'usertitles'	=> $usertitles
];

tpl('contact.tpl', $data, true);
