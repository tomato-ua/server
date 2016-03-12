<?php


date_default_timezone_set("Europe/Kiev");


if (isset($argv['1'])) {
    $my_id = $argv['1']; // 'bKSsDQ8HHFs';
} else {
    exit;
}


function parseImages()
{

    $files = scandir(__DIR__ . '/out');

    sort($files);

    $progres = 0;
    $before = 0;

    $offset = 0;

    foreach ($files as $file) {

        if (strpos('.', $file) === 0 || strpos($file, 'img') === false || $file == 'cli.php') {
            continue;
        }

        $outFile = __DIR__ . "/cropped-output.png";
        $image = new Imagick(__DIR__ . "/out/" . $file);
        $image->cropImage(90, 26, 50, 405);
        $image->writeImage($outFile);

        $threshold = 50;
        exec('convert cropped-output.png -resize 300 -threshold ' . $threshold . '% 2.tif');

        `tesseract 2.tif ./result -pcm 7 1>/dev/null 2>&1 `;
        $result = trim(file_get_contents(__DIR__ . '/result.txt'));
        $offset++;
        if (strlen($result) == 5) {
            $time = substr_replace($result, ':', 2, 1);
            list($hh, $mm) = explode(':', $time);

            if ($before < $mm) {
                $progres++;
            } else {
                $progres = 0;
            }

            if ($progres > 4) {

                $date = new \DateTime();
                $date->setTime($hh, $mm);

                $date->modify('-' . $offset . ' min');

                file_put_contents(__DIR__ . '/videoresult.txt', json_encode(['time' => $date->format(DateTime::ATOM), 'offset' => $offset]));

                return;
            }
        }
    }
}


// сергвй антоненко - рада

function downloadFile($url, $path)
{
    $newfname = $path;
    $file = fopen($url, 'rb');
    if ($file) {
        $newf = fopen($newfname, 'wb');
        if ($newf) {

            $length = 60 * 1024;

            while ($length) {
                fwrite($newf, fread($file, 1024 * 1), 1024 * 8);
                $length--;

            }

        }
    }
    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
    }
}

function curlGet($URL)
{
    global $config; // get global $config to know if $config['multipleIPs'] is true
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    /* if you want to force to ipv6, uncomment the following line */
    //curl_setopt( $ch , CURLOPT_IPRESOLVE , 'CURLOPT_IPRESOLVE_V6');
    $tmp = curl_exec($ch);
    curl_close($ch);

    return $tmp;
}

/*
 * function to use cUrl to get the headers of the file
 */
function get_location($url)
{
    global $config;
    $my_ch = curl_init();
    curl_setopt($my_ch, CURLOPT_URL, $url);
    curl_setopt($my_ch, CURLOPT_HEADER, true);
    curl_setopt($my_ch, CURLOPT_NOBODY, true);
    curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
    $r = curl_exec($my_ch);
    foreach (explode("\n", $r) as $header) {
        if (strpos($header, 'Location: ') === 0) {
            return trim(substr($header, 10));
        }
    }

    return '';
}

function get_size($url)
{
    global $config;
    $my_ch = curl_init();
    curl_setopt($my_ch, CURLOPT_URL, $url);
    curl_setopt($my_ch, CURLOPT_HEADER, true);
    curl_setopt($my_ch, CURLOPT_NOBODY, true);
    curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
    $r = curl_exec($my_ch);
    foreach (explode("\n", $r) as $header) {
        if (strpos($header, 'Content-Length:') === 0) {
            return trim(substr($header, 16));
        }
    }

    return '';
}


$cleanedtitle = '';
$my_video_info = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id . '&asv=3&el=detailpage&hl=en_US'; //video details fix *1
$my_video_info = curlGet($my_video_info);
$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

parse_str($my_video_info);
if ($status == 'fail') {
    echo '<p>Error in video ID</p>';
    exit();
}

if (file_exists(__DIR__ . '/video.mp4')) {
    unlink(__DIR__ . '/video.mp4');
}


if (isset($url_encoded_fmt_stream_map)) {
    /* Now get the url_encoded_fmt_stream_map, and explode on comma */
    $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
} else {
    echo '<p>No encoded format stream found.</p>';
    echo '<p>Here is what we got from YouTube:</p>';
    echo $my_video_info;
}
if (count($my_formats_array) == 0) {
    echo '<p>No format stream map found - was the video id correct?</p>';
    exit;
}

/* create an array of available download formats */
$avail_formats[] = '';
$i = 0;
$ipbits = $ip = $itag = $sig = $quality = '';
$expire = time();

foreach ($my_formats_array as $format) {
    parse_str($format);
    $avail_formats[$i]['itag'] = $itag;
    $avail_formats[$i]['quality'] = $quality;
    $type = explode(';', $type);
    $avail_formats[$i]['type'] = $type[0];
    $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
    parse_str(urldecode($url));
    $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
    $avail_formats[$i]['ipbits'] = $ipbits;
    $avail_formats[$i]['ip'] = $ip;
    $i++;
}

/* now that we have the array, print the options */
for ($i = 0; $i < count($avail_formats); $i++) {

    $directlink = explode('.googlevideo.com/', $avail_formats[$i]['url']);
    $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '';

    if ($avail_formats[$i]['type'] == 'video/mp4') {
        downloadFile($directlink, __DIR__ . '/video.mp4');

        $command = "ffmpeg -i " . __DIR__ . "/video.mp4  -s 720x480   -vf fps=1/60 " . __DIR__ . "/out/img%03d.jpg";
        `$command`;

        parseImages();
    }
}

if (file_exists(__DIR__ . '/video.mp4')) {
    unlink(__DIR__ . '/video.mp4');
}


