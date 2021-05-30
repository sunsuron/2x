<?php

function total_datetime_spent($task_datetime_start, $task_datetime_end)
{
    $datetime_start = new DateTime($task_datetime_start);
    $datetime_end = new DateTime($task_datetime_end);
    $interval = $datetime_start->diff($datetime_end);
    $total_datetime_spent = $interval->format('%H:%i:%s');
    return $total_datetime_spent;
}

function fullname($user_id = 0)
{
    $user = load_model('superadmin/user')->user($user_id);
    return $user->row['fullname'];
}

function excerpt($subject = '')
{
    $count = preg_match('/[^\s]+[\s][^\s]+[\s][^\s]+/i', $subject, $matches);
    if ($count) {
        return $matches[0];
    }

    return '';
}

/**
 * https://stackoverflow.com/questions/8830411/regex-to-match-simple-domain
 */
function domain($subject = '')
{
    $pattern = "/(?:[\w-]+\.)*([\w-]{1,63})(?:\.(?:\w{3}|\w{2}))/i";

    if (preg_match($pattern, $subject, $matches)) {
        if (isset($matches[0]) && $matches[0]) {
            return $matches[0];
        }
    }

    return '';
}

function firemail($data)
{
    $transport = new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT, 'tls');

    $transport->setUsername(SMTP_NAME);

    $transport->setPassword(SMTP_PASS);

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message();

    $message->setFrom(SMTP_FROM, SMTP_FROM_NAME);

    $message->setReturnPath(SMTP_FROM);

    $message->setSender(SMTP_FROM, SMTP_FROM_NAME);

    $message->setReplyTo(SMTP_FROM, SMTP_FROM_NAME);

    $body = tpl('email/' . $_SESSION['language'] . '/' . $data['tpl'], $data['replacements']);

    $message->setSubject($data['subject']);

    $message->setTo(array($data['to']));

    $message->setBody($body);

    $mailer->send($message, $failures);

    $mailer->getTransport()->stop();
}

function _mime_content_type($filename)
{
    $result = new finfo();

    if (is_resource($result) === true) {
        return $result->file($filename, FILEINFO_MIME_TYPE);
    }

    return false;
}

function curl_post($url, array $post = null, array $options = array())
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POSTFIELDS => http_build_query($post),
    );

    $ch = curl_init();

    curl_setopt_array($ch, ($options + $defaults));

    if (!$result = curl_exec($ch)) {
        if (curl_errno($ch)) {
            trigger_error(curl_error($ch));
        }
    }

    curl_close($ch);

    return $result;
}

function crop($imgpath, $filename, $where, $thumbnail_width, $thumbnail_height, $flexible = false)
{
    $mimetype = get_mimetype($imgpath);

    $ext = get_extbymime($mimetype);

    list($width_orig, $height_orig) = getimagesize($imgpath);

    if ($ext == "jpg") {
        $myimage = imagecreatefromjpeg($imgpath);
    }

    if ($ext == "png") {
        $myimage = imagecreatefrompng($imgpath);
    }

    $aspect_ratio = $width_orig / $height_orig;

    if ($flexible && $thumbnail_width != 150 && $thumbnail_height != 150) {
        if ($width_orig >= 600) {
            $thumbnail_width = 600;
        } else {
            $thumbnail_width = $width_orig;
        }
    }

    if (!$thumbnail_width) {
        $thumbnail_width = intval($thumbnail_height * $aspect_ratio);
    }

    if (!$thumbnail_height) {
        $thumbnail_height = intval($thumbnail_width / $aspect_ratio);
    }

    if ($thumbnail_width / $thumbnail_height > $aspect_ratio) {
        $new_height = $thumbnail_width / $aspect_ratio;

        $new_width = $thumbnail_width;
    } else {
        $new_width = $thumbnail_height * $aspect_ratio;

        $new_height = $thumbnail_height;
    }

    $x_mid = $new_width / 2; //horizontal middle

    $y_mid = $new_height / 2; //vertical middle

    $process = imagecreatetruecolor(round($new_width), round($new_height));

    imagecopyresampled($process, $myimage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);

    imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

    imagedestroy($process);

    imagedestroy($myimage);

    if ($ext == "jpg") {
        $finalfilename = $filename . '-' . $thumbnail_width . 'x' . $thumbnail_height . '.jpg';

        imagejpeg($thumb, $where . $finalfilename, 100);
    }

    if ($ext == "png") {
        $finalfilename = $filename . '-' . $thumbnail_width . 'x' . $thumbnail_height . '.png';

        imagepng($thumb, $where . $finalfilename, 0);
    }

    return $finalfilename;
}

function get_mimetype($filepath)
{
    $output = null;

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $output = finfo_file($finfo, $filepath);

        finfo_close($finfo);

        return strtolower(trim($output));
    } else if (strtoupper(PHP_OS) === 'LINUX') {
        ob_start();

        system("file -bi " . escapeshellarg($filepath));

        $output = ob_get_clean();

        $output = explode("; ", $output);

        if (is_array($output)) {
            $output = $output[0];
        }

        return strtolower(trim($output));
    } else {
        $output = mime_content_type($filepath);

        return $output;
    }
}

function get_extbymime($mimetype)
{
    $x = explode('/', $mimetype);

    $ext = $x[1];

    if ($ext == 'jpeg' || $ext == 'jpg') {
        $ext = 'jpg';
    }

    if ($ext == 'msword') {
        $ext = 'doc';
    }

    if ($ext == 'vnd.openxmlformats-officedocument.wordprocessingml.document') {
        $ext = 'docx';
    }

    return $ext;
}

function upload_max_filesize()
{
    $value = ini_get('upload_max_filesize');

    if (is_numeric($value)) {
        return $value;
    } else {
        $value_length = strlen($value);

        $qty = substr($value, 0, $value_length - 1);

        $unit = strtolower(substr($value, $value_length - 1));

        switch ($unit) {
            case 'k':
                $qty *= 1024;
                break;
            case 'm':
                $qty *= 1048576;
                break;
            case 'g':
                $qty *= 1073741824;
                break;
        }

        return $qty;
    }
}

function goodimage($minwidth = 300, $minheight = false)
{
    $goodmime = array('image/jpg', 'image/jpeg', 'image/png');

    if (isset($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
        if (!$_FILES['image']['size']) {
            $_SESSION['error'][] = sprintf(lang('err_size_zero'), lang('image'));
        } else {
            if ($_FILES['image']['size'] > upload_max_filesize()) {
                $_SESSION['error'][] = sprintf(lang('err_img_size'), lang('image'));
            } else {
                if ($_FILES['image']['error']) {
                    $_SESSION['error'][] = sprintf(lang('err_img_error'), lang('image'));
                } else {
                    $mimetype = get_mimetype($_FILES['image']['tmp_name']);

                    if (!$mimetype || !in_array($mimetype, $goodmime)) {
                        $_SESSION['error'][] = sprintf(lang('err_img_mime'), lang('image'));
                    } else {
                        list($width, $height) = getimagesize($_FILES['image']['tmp_name']);

                        if (!$width || ($width < $minwidth)) {
                            $_SESSION['error'][] = sprintf(lang('err_img_width'), lang('image'), $minwidth);
                        }

                        if ($minheight) {
                            if (!$height || ($height < $minheight)) {
                                $_SESSION['error'][] = sprintf(lang('err_img_height'), lang('image'), $minheight);
                            }
                        }

                        $image = null;

                        $ext = get_extbymime($mimetype);

                        if ($ext == 'jpg') {
                            $image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                        } else if ($ext == 'png') {
                            $image = imagecreatefrompng($_FILES['image']['tmp_name']);
                        }

                        if (!$image) {
                            $_SESSION['error'][] = sprintf(lang('err_img_invalid'), lang('image'));
                        }
                    }
                }
            }
        }
    } else {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('image'));
    }
}

function is_logged()
{
    if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']) {
        return true;
    }

    return false;
}

function acl_update($force = false)
{
    global $acl, $aclaccess;

    $user_id = $_SESSION['user']['user_id'];

    if ($force) {
        unset($_SESSION['acl'], $_SESSION['aclaccess']);

        $res = load_model('acl')->get($user_id);

        if ($res->num_rows) {
            $_SESSION['acl'] = $_SESSION['aclaccess'] = array();

            foreach ($res->rows as $a) {
                $_SESSION['acl'][$a['acl_id']] = $a['acl_id'];

                $role = array_flip($acl);

                $role = $role[$a['acl_id']];

                $_SESSION['aclaccess'] = array_merge($_SESSION['aclaccess'], $aclaccess[$role]);
            }

            $_SESSION['user']['is_superadmin'] = $res->row['is_superadmin'];
        }
    } else {
        if (!isset($_SESSION['acl']) || empty($_SESSION['acl'])) {
            $res = load_model('acl')->get($user_id);

            if ($res->num_rows) {
                $_SESSION['acl'] = $_SESSION['aclaccess'] = array();

                foreach ($res->rows as $a) {
                    $_SESSION['acl'][$a['acl_id']] = $a['acl_id'];

                    $role = array_flip($acl);

                    $role = $role[$a['acl_id']];

                    $_SESSION['aclaccess'] = array_merge($_SESSION['aclaccess'], $aclaccess[$role]);
                }

                $_SESSION['user']['is_superadmin'] = $res->row['is_superadmin'];
            }
        }
    }
}

function tpl($template, $data = [], $echo = false, $scripts = [], $links = [])
{
    global $start, $acl, $aclaccess, $sql_count;

    extract($data);

    ob_start();

    require 'app/view/' . TEMPLATE . '/' . $template;

    $output = ob_get_contents();

    ob_end_clean();

    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

function route($route)
{
    global $mysqli, $start, $acl, $aclaccess, $sql_count, $onair;

    $parts = explode('/', $route);

    /**
     * admin?
     */

    if (in_array('admin', $parts)) {
        require_once 'app/controller/admin/routine.php';
    }

    if (in_array('superadmin', $parts)) {
        require_once 'app/controller/superadmin/routine.php';
    }

    if (in_array('profile', $parts)) {
        require_once 'app/controller/profile/routine.php';
    }

    /**
     * virtual page
     * - DB checking NOT performed
     */

    $is_vpage = false;

    if (is_vpage($route)) {
        $is_vpage = true;
    }

    $faclaccess = array_flip($_SESSION['aclaccess']);

    /**
     * website maintenance checking
     */

    if (defined('MAINTENANCE') && MAINTENANCE) {
        require_once 'app/controller/maintenance.php';
    }

    /**
     * acl checking
     */

    elseif (!isset($faclaccess[$route]) && !$is_vpage) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

        require_once 'app/controller/permission.php';
    }

    /**
     * acl passed
     * - NOT virtual page
     * - basically, normal file
     */

    elseif (!$is_vpage) {
        $path = $file = '';

        foreach ($parts as $part) {
            $path .= $part;

            if (is_dir('app/controller/' . $path)) {
                $path .= '/';

                array_shift($parts);

                continue;
            }

            if (is_file('app/controller/' . rtrim($path, '/') . '.php')) {
                $file = 'app/controller/' . rtrim($path, '/') . '.php';

                require $file;

                array_shift($parts);

                break;
            }
        }

        if (!$file) {
            $file = 'app/controller/' . rtrim($path, '/') . '/home.php';

            if (is_file($file)) {
                require $file;
            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

                require 'app/controller/404.php';
            }
        }
    }

    /**
     * acl passed
     * - is virtual page
     */

    elseif ($is_vpage) {
        $parts = explode('/', $route);

        $path = $file = '';

        foreach ($parts as $part) {
            $file = 'app/controller/' . $part . '.php';

            if (is_file($file)) {
                require $file;

                break;
            } else {
                $file = 'app/controller/' . $part . '/home.php';

                if (is_file($file)) {
                    require $file;

                    break;
                }
            }
        }
    }
}

function is_vpage($route)
{
    if (isset($route) && $route && preg_match('/^(page)\/[a-z0-9-\/]+$/', $route, $matches)) {
        return true;
    }

    return false;
}

function basicseourl($str)
{
    $str = mb_strtolower($str);

    $str = preg_replace('/\s+/', '-', $str);

    $str = preg_replace('/[^a-z0-9-]/', '', $str);

    return $str;
}

function lang($key)
{
    global $_;

    $key = preg_replace('/[^a-z_1-9]/i', '', $key);

    return isset($_[$key]) ? $_[$key] : $key;
}

function rip_tags($string)
{
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $string);
}

function clean()
{
    if (isset($_POST) && $_POST) {
        foreach ($_POST as $key => $val) {
            if (!is_array($val)) {
                $_POST[$key] = trim($_POST[$key]);
                $_POST[$key] = stripslashes($_POST[$key]);
                $_POST[$key] = html_entity_decode($_POST[$key]);
                $_POST[$key] = rawurldecode($_POST[$key]);
                $_POST[$key] = rip_tags($_POST[$key]);
                $_POST[$key] = strip_tags($_POST[$key]);
            }
        }
    }

    if (isset($_GET) && $_GET) {
        $_GET = array_map('trim', $_GET);
        $_GET = array_map('stripslashes', $_GET);
        $_GET = array_map('rawurldecode', $_GET);
        $_GET = array_map('html_entity_decode', $_GET);
        $_GET = array_map('rip_tags', $_GET);
        $_GET = array_map('strip_tags', $_GET);
    }
}

/*
 * Generate a secure hash for php < 5.5 for a given password. The cost is passed
 * to the blowfish algorithm. Check the PHP manual page for crypt to
 * find more information about this setting.
 * http://php.net/manual/en/function.crypt.php
 */

function generate_hash($password, $cost = 12)
{
    /* To generate the salt, first generate enough random bytes. Because
     * base64 returns one character for each 6 bits, the we should generate
     * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
     * 22 base64 characters
     */

    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);

    /* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
     * replace any '+' in the base64 string with '.'. We don't have to do
     * anything about the '=', as this only occurs when the b64 string is
     * padded, which is always after the first 22 characters.
     */

    $salt = str_replace("+", ".", $salt);

    /* Next, create a string that will be passed to crypt, containing all
     * of the settings, separated by dollar signs
     */

    $param = '$' . implode('$', array(
        "2y", // select the most secure version of blowfish (>=PHP 5.3.7)
        str_pad($cost, 2, "0", STR_PAD_LEFT), // add the cost in two digits
        $salt, // add the salt
    ));

    // now do the actual hashing

    return crypt($password, $param);
}

/**
 * generate hash for php > 5.5
 *
 * @param string $raw_data
 * @param string $hashed_data
 * @return boolean|string
 */

function hasher($raw_data, $hashed_data = false)
{
    if ($hashed_data) {
        if (password_verify($raw_data, $hashed_data)) {
            return true;
        } else {
            return false;
        }
    } else {
        return password_hash($raw_data, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}

function X($user_id) // use with cautions

{
    $sql = "SELECT

		u.user_id,
		u.email,
		u.is_superadmin,
		u.ip,
        ua.usertitle,
        ua.fullname

	FROM user u
	LEFT JOIN user_attr ua ON (u.user_id = ua.user_id)
	WHERE 1=1

			AND u.is_active = 1
			AND u.user_id = %d

	LIMIT 1";

    $sql = sprintf($sql, (int) $user_id);

    $res = db_query($sql);

    if (!$res->num_rows) {
        logout();

        redirect(u('/'));
    }

    $_SESSION['user'] = $res->row;

    $_SESSION['token'] = md5(hasher($res->row['user_id'] . random_bytes(16)));

    acl_update(true);

    update_runtime_config();

    return true;
}

function logout()
{
    session_regenerate_id();

    $_SESSION = [];
}

function redirect($url, $status = 302)
{
    header('Status: ' . $status);

    header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));

    exit();
}

function update_runtime_config()
{
    $_SESSION['config'] = load_model('config')->configs()->rows;
}


function login_email($email, $password)
{
    global $wpdb;

    $sql = "SELECT

		u.user_id,
		u.email,
		u.password,
		u.is_superadmin,
        ua.mobile_no

	FROM user u
	LEFT JOIN user_attr ua ON (ua.user_id = u.user_id)
	WHERE 1=1

			AND u.is_active = 1
			AND u.email = '%s'

	LIMIT 1";

    $sql = sprintf($sql, db_escape($email));

    $res = db_query($sql);

    if (!$res->num_rows) {
        return false;
    }

    if (password_verify($password, $res->row['password'])) {
        unset($res->row['password']);

        $_SESSION['user'] = $res->row;

        $_SESSION['token'] = md5(hasher($res->row['user_id'] . random_bytes(16)));

        acl_update(true);

        update_runtime_config();

        return true;
    }

    return false;
}

function pagination($page, $total, $url, $limit = PAGE_LIMIT, $num_links = 3)
{
    $page = ($page < 1) ? 1 : $page;

    $num_pages = ceil($total / $limit);

    $html = '<ul class="pagination">';

    if ($page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', 1, $url) . '" title="' . lang('first') . '">&laquo;</a></li>';
        $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $page - 1, $url) . '" title="' . lang('prev') . '">&larr;</a></li>';
    }

    if ($num_pages > 1) {
        if ($num_pages <= $num_links) {
            $start = 1;

            $end = $num_pages;
        } else {
            $start = $page - floor($num_links / 2);

            $end = $page + floor($num_links / 2);

            if ($start < 1) {
                $end += abs($start) + 1;

                $start = 1;
            }

            if ($end > $num_pages) {
                $start -= ($end - $num_pages);

                $end = $num_pages;
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($page == $i) {
                $html .= '<li class="page-item active"><a class="page-link" href="#"><span>' . $i . '</span></a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $i, $url) . '">' . $i . '</a></li>';
            }
        }
    }

    if ($page < $num_pages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $page + 1, $url) . '" title="' . lang('next') . '">&rarr;</a></li>';
        $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $num_pages, $url) . '" title="' . lang('last') . '">&raquo;</a>';
    }

    $html .= '</ul>';

    if ($num_pages > 1) {
        return $html;
    } else {
        return '';
    }
}

function makefilename($user_id = 0)
{
    $randomfilename = md5($user_id . microtime() . random_bytes(16));

    return $randomfilename;
}
